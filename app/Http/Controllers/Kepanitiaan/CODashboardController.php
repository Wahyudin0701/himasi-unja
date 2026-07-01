<?php

namespace App\Http\Controllers\Kepanitiaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kepanitiaan\Event;
use App\Models\Kepanitiaan\EventDivision;
use App\Models\ProgressReport\WorkTask;
use App\Models\ProgressReport\DivisionSprint;

class CODashboardController extends Controller
{
    /**
     * Dashboard utama Koordinator Divisi (CO).
     * Menampilkan statistik divisi yang dikoordinasikan.
     */
    public function index(\App\Models\Kepanitiaan\Event $event, \App\Models\Kepanitiaan\EventDivision $division)
    {
        $user = auth()->user();
        
        $assignment = $user->eventCommittees()
            ->where('event_id', $event->id)
            ->where('event_division_id', $division->id)
            ->whereHas('role', fn($q) => $q->where('slug', 'co-divisi'))
            ->with(['event', 'division', 'role'])
            ->first();

        if (!$assignment) {
            abort(403, 'Anda bukan Koordinator di divisi ini.');
        }

        $divisionsData = [];
        $divisionMembers = $assignment->event->committees()
            ->where('event_division_id', $division->id)
            ->whereHas('role', fn($q) => $q->where('slug', 'anggota'))
            ->with('user')
            ->get();

        $tasks = WorkTask::where('event_id', $event->id)
            ->where('event_division_id', $division->id)
            ->with(['assignee', 'reports'])
            ->orderBy('sprint_number', 'asc')
            ->orderBy('due_date', 'asc')
            ->get();

        $groupedTasks = $tasks->groupBy('sprint_number');

        $sprints = DivisionSprint::where('event_division_id', $division->id)
            ->orderBy('sprint_number', 'asc')
            ->get();

        $divisionsData[] = [
            'assignment' => $assignment,
            'members' => $divisionMembers,
            'groupedTasks' => $groupedTasks,
            'sprints' => $sprints,
            'totalTasks' => $tasks->count(),
            'completedTasks' => $tasks->where('status', 'completed')->count(),
        ];

        $ketupelCommittee = $event->committees()
            ->whereHas('role', fn($q) => $q->where('slug', 'ketua-pelaksana'))
            ->with('user')
            ->first();

        return view('kepanitiaan.co.dashboard', compact('user', 'divisionsData', 'ketupelCommittee'));
    }

    /**
     * Menampilkan halaman pengaturan sprint.
     */
    public function manageSprints(\App\Models\Kepanitiaan\Event $event, \App\Models\Kepanitiaan\EventDivision $division)
    {
        $user = auth()->user();
        
        $assignment = $user->eventCommittees()
            ->where('event_id', $event->id)
            ->where('event_division_id', $division->id)
            ->whereHas('role', fn($q) => $q->where('slug', 'co-divisi'))
            ->with(['event', 'division'])
            ->first();

        if (!$assignment) {
            abort(403, 'Anda bukan Koordinator di divisi ini.');
        }

        $divisionsData = [];
        $sprints = DivisionSprint::where('event_division_id', $division->id)
            ->orderBy('sprint_number', 'asc')
            ->get();

        $divisionsData[] = [
            'assignment' => $assignment,
            'sprints' => $sprints,
        ];

        return view('kepanitiaan.co.sprints', compact('divisionsData', 'event', 'division'));
    }

    /**
     * Menyimpan pengaturan sprint (Tambah 1 Sprint).
     */
    public function storeSprint(Request $request)
    {
        $validated = $request->validate([
            'event_division_id' => 'required|exists:event_divisions,id',
            'sprint_number' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $user = auth()->user();
        $isCO = $user->eventCommittees()
            ->where('event_division_id', $validated['event_division_id'])
            ->whereHas('role', fn($q) => $q->where('slug', 'co-divisi'))
            ->exists();

        if (!$isCO) {
            abort(403, 'Akses ditolak.');
        }

        // Cek apakah sprint number sudah ada untuk menghindari duplikat
        $exists = DivisionSprint::where('event_division_id', $validated['event_division_id'])
                                ->where('sprint_number', $validated['sprint_number'])
                                ->exists();

        if ($exists) {
            return back()->with('error', 'Sprint ' . $validated['sprint_number'] . ' sudah ada untuk divisi ini.');
        }

        DivisionSprint::create($validated);

        return back()->with('success', 'Sprint berhasil ditambahkan!');
    }

    /**
     * Memperbarui sprint.
     */
    public function updateSprint(Request $request, DivisionSprint $sprint)
    {
        $validated = $request->validate([
            'sprint_number' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $user = auth()->user();
        $isCO = $user->eventCommittees()
            ->where('event_division_id', $sprint->event_division_id)
            ->whereHas('role', fn($q) => $q->where('slug', 'co-divisi'))
            ->exists();

        if (!$isCO) {
            abort(403, 'Akses ditolak.');
        }

        // Cek apakah ada duplikat sprint number jika mengubah sprint_number
        if ($sprint->sprint_number != $validated['sprint_number']) {
            $exists = DivisionSprint::where('event_division_id', $sprint->event_division_id)
                                    ->where('sprint_number', $validated['sprint_number'])
                                    ->exists();
            
            if ($exists) {
                return back()->with('error', 'Sprint ' . $validated['sprint_number'] . ' sudah ada untuk divisi ini.');
            }
        }

        // Cek apakah ada tugas yang tenggat waktunya di luar rentang sprint baru
        $tasksInSprint = \App\Models\ProgressReport\WorkTask::where('event_division_id', $sprint->event_division_id)
                                ->where('sprint_number', $sprint->sprint_number)
                                ->get();

        $invalidTasks = [];
        foreach ($tasksInSprint as $task) {
            if ($task->due_date) {
                $dueDate = $task->due_date->format('Y-m-d');
                if ($dueDate < $validated['start_date'] || $dueDate > $validated['end_date']) {
                    $invalidTasks[] = $task->title;
                }
            }
        }

        if (count($invalidTasks) > 0) {
            return back()->with('error', 'Gagal memperbarui sprint. Tugas berikut memiliki tenggat di luar rentang baru: ' . implode(', ', $invalidTasks) . '. Sesuaikan tanggal tugas-tugas tersebut terlebih dahulu.');
        }

        $oldSprintNumber = $sprint->sprint_number;
        $sprint->update($validated);

        // Sinkronisasi data sprint ke tabel tugas
        \App\Models\ProgressReport\WorkTask::where('event_division_id', $sprint->event_division_id)
            ->where('sprint_number', $oldSprintNumber)
            ->update([
                'sprint_number' => $validated['sprint_number'],
                'sprint_start_date' => $validated['start_date'],
                'sprint_end_date' => $validated['end_date'],
            ]);

        return back()->with('success', 'Sprint berhasil diperbarui!');
    }

    /**
     * Menghapus sprint.
     */
    public function destroySprint(DivisionSprint $sprint)
    {
        $user = auth()->user();
        $isCO = $user->eventCommittees()
            ->where('event_division_id', $sprint->event_division_id)
            ->whereHas('role', fn($q) => $q->where('slug', 'co-divisi'))
            ->exists();

        if (!$isCO) {
            abort(403, 'Akses ditolak.');
        }

        $hasTasks = WorkTask::where('event_division_id', $sprint->event_division_id)
            ->where('sprint_number', $sprint->sprint_number)
            ->exists();

        if ($hasTasks) {
            return back()->with('error', 'Sprint tidak dapat dihapus karena masih ada tugas yang terkait pada sprint ini.');
        }

        $sprint->delete();

        return back()->with('success', 'Sprint berhasil dihapus!');
    }

    /**
     * Menampilkan detail tugas.
     */
    public function showTask(WorkTask $task)
    {
        $user = auth()->user();

        // Validasi: Pastikan user login adalah CO dari event_division tersebut
        $isCO = $user->eventCommittees()
            ->where('event_id', $task->event_id)
            ->where('event_division_id', $task->event_division_id)
            ->whereHas('role', fn($q) => $q->where('slug', 'co-divisi'))
            ->exists();

        if (!$isCO) {
            abort(403, 'Akses ditolak. Anda bukan Koordinator di divisi ini.');
        }

        $task->load(['event', 'eventDivision', 'assignee', 'reports' => function($q) {
            $q->latest()->first(); // Ambil report terakhir
        }]);

        // Jika report berelasi banyak, kita ambil first saja untuk ditampilkan
        $latestReport = $task->reports->first();

        return view('kepanitiaan.co.tasks.tasks_detail', compact('task', 'latestReport'));
    }

    /**
     * Menampilkan halaman tambah tugas.
     */
    public function createTask(\App\Models\Kepanitiaan\Event $event, \App\Models\Kepanitiaan\EventDivision $division)
    {
        $user = auth()->user();

        // Validasi: Pastikan user login adalah CO dari event_division tersebut
        $isCO = $user->eventCommittees()
            ->where('event_id', $event->id)
            ->where('event_division_id', $division->id)
            ->whereHas('role', fn($q) => $q->where('slug', 'co-divisi'))
            ->exists();

        if (!$isCO) {
            abort(403, 'Akses ditolak. Anda bukan Koordinator di divisi ini.');
        }

        $members = $event->committees()
            ->where('event_division_id', $division->id)
            ->whereHas('role', fn($q) => $q->where('slug', 'anggota'))
            ->with('user')
            ->get();

        $sprints = DivisionSprint::where('event_division_id', $division->id)
            ->orderBy('sprint_number', 'asc')
            ->get();

        return view('kepanitiaan.co.tasks.tasks_create', compact('event', 'division', 'members', 'sprints'));
    }

    /**
     * Menyimpan tugas baru untuk anggota divisi.
     */
    public function storeTask(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'event_division_id' => 'required|exists:event_divisions,id',
            'assigned_to' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_names.*' => 'nullable|string|max:255',
            'files.*' => 'nullable|file|max:10240',
            'link_names.*' => 'nullable|string|max:255',
            'links.*' => 'nullable|url|max:255',
            'sprint_number' => 'required|integer|min:1',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
        ]);

        $user = auth()->user();

        // Validasi: Pastikan user login adalah CO dari event_division tersebut
        $isCO = $user->eventCommittees()
            ->where('event_id', $validated['event_id'])
            ->where('event_division_id', $validated['event_division_id'])
            ->whereHas('role', fn($q) => $q->where('slug', 'co-divisi'))
            ->exists();

        if (!$isCO) {
            abort(403, 'Akses ditolak. Anda bukan Koordinator di divisi ini.');
        }

        $sprint = DivisionSprint::where('event_division_id', $validated['event_division_id'])
            ->where('sprint_number', $validated['sprint_number'])
            ->first();

        $validated['sprint_start_date'] = $sprint ? $sprint->start_date : now();
        $validated['sprint_end_date'] = $sprint ? $sprint->end_date : now()->addDays(7);

        $validated['assigned_by'] = $user->id;
        $validated['status'] = 'todo';

        $attachments = [
            'files' => [],
            'links' => []
        ];

        if ($request->hasFile('files')) {
            $fileNames = $request->input('file_names', []);
            foreach ($request->file('files') as $index => $file) {
                $attachments['files'][] = [
                    'name' => !empty($fileNames[$index]) ? $fileNames[$index] : 'Lampiran ' . (count($attachments['files']) + 1),
                    'path' => $file->store('task_files', 'public')
                ];
            }
        }

        if ($request->filled('links')) {
            $linkNames = $request->input('link_names', []);
            foreach ($request->input('links') as $index => $link) {
                if (!empty($link)) {
                    $attachments['links'][] = [
                        'name' => !empty($linkNames[$index]) ? $linkNames[$index] : 'Tautan ' . (count($attachments['links']) + 1),
                        'url' => $link
                    ];
                }
            }
        }

        if (!empty($attachments['files']) || !empty($attachments['links'])) {
            $validated['attachments'] = $attachments;
        }

        WorkTask::create($validated);

        return redirect()->route('kepanitiaan.co.dashboard', ['event' => $validated['event_id'], 'division' => $validated['event_division_id']])->with('success', 'Tugas berhasil ditambahkan!');
    }

    public function editTask(WorkTask $task)
    {
        $user = auth()->user();

        // Validasi: Pastikan user login adalah CO dari event_division task ini
        $isCO = $user->eventCommittees()
            ->where('event_division_id', $task->event_division_id)
            ->whereHas('role', fn($q) => $q->where('slug', 'co-divisi'))
            ->exists();

        if (!$isCO) {
            abort(403, 'Akses ditolak. Anda bukan Koordinator di divisi ini.');
        }

        $event = $task->event;
        $division = $task->eventDivision;

        $members = $event->committees()
            ->where('event_division_id', $division->id)
            ->whereHas('role', fn($q) => $q->where('slug', 'anggota'))
            ->with('user')
            ->get();

        $sprints = DivisionSprint::where('event_division_id', $division->id)
            ->orderBy('sprint_number', 'asc')
            ->get();

        return view('kepanitiaan.co.tasks.tasks_edit', compact('task', 'event', 'division', 'members', 'sprints'));
    }

    /**
     * Memperbarui tugas.
     */
    public function updateTask(Request $request, WorkTask $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_names.*' => 'nullable|string|max:255',
            'files.*' => 'nullable|file|max:10240',
            'link_names.*' => 'nullable|string|max:255',
            'links.*' => 'nullable|url|max:255',
            'existing_files' => 'nullable|array',
            'existing_file_names' => 'nullable|array',
            'existing_links' => 'nullable|array',
            'existing_link_names' => 'nullable|array',
            'assigned_to' => 'required|exists:users,id',
            'sprint_number' => 'required|integer|min:1',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:todo,waiting,revisi,completed',
        ]);

        $user = auth()->user();

        // Validasi: Pastikan user login adalah CO yang membuat tugas ini atau CO di divisinya
        $isCO = $user->eventCommittees()
            ->where('event_id', $task->event_id)
            ->where('event_division_id', $task->event_division_id)
            ->whereHas('role', fn($q) => $q->where('slug', 'co-divisi'))
            ->exists();

        if (!$isCO) {
            abort(403, 'Akses ditolak. Anda tidak berhak mengedit tugas ini.');
        }

        $sprint = DivisionSprint::where('event_division_id', $task->event_division_id)
            ->where('sprint_number', $validated['sprint_number'])
            ->first();

        if ($sprint) {
            $validated['sprint_start_date'] = $sprint->start_date;
            $validated['sprint_end_date'] = $sprint->end_date;
        }

        if ($validated['status'] === 'completed' && $task->status !== 'completed') {
            $validated['completed_at'] = now();
        } elseif ($validated['status'] !== 'completed') {
            $validated['completed_at'] = null;
        }

        // Pertahankan file & link lama yang tidak dihapus
        $attachments = [
            'files' => [],
            'links' => []
        ];

        $existingFiles = $request->input('existing_files', []);
        $existingFileNames = $request->input('existing_file_names', []);
        foreach ($existingFiles as $index => $path) {
            $attachments['files'][] = [
                'name' => !empty($existingFileNames[$index]) ? $existingFileNames[$index] : 'Lampiran ' . ($index + 1),
                'path' => $path
            ];
        }

        $existingLinks = $request->input('existing_links', []);
        $existingLinkNames = $request->input('existing_link_names', []);
        foreach ($existingLinks as $index => $url) {
            $attachments['links'][] = [
                'name' => !empty($existingLinkNames[$index]) ? $existingLinkNames[$index] : 'Tautan ' . ($index + 1),
                'url' => $url
            ];
        }

        // Hapus file lama yang tidak ada di list existing_files
        $oldAttachments = $task->attachments ?? ['files' => [], 'links' => []];
        $oldFiles = $oldAttachments['files'] ?? [];
        $keptFilePaths = array_column($attachments['files'], 'path');
        foreach ($oldFiles as $oldFile) {
            // Support both old format (string path) and new format (object)
            $oldPath = is_array($oldFile) ? ($oldFile['path'] ?? null) : $oldFile;
            if ($oldPath && !in_array($oldPath, $keptFilePaths) && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
            }
        }

        if ($request->hasFile('files')) {
            $fileNames = $request->input('file_names', []);
            foreach ($request->file('files') as $index => $file) {
                $attachments['files'][] = [
                    'name' => !empty($fileNames[$index]) ? $fileNames[$index] : 'Lampiran ' . (count($attachments['files']) + 1),
                    'path' => $file->store('task_files', 'public')
                ];
            }
        }

        if ($request->filled('links')) {
            $linkNames = $request->input('link_names', []);
            foreach ($request->input('links') as $index => $link) {
                if (!empty($link)) {
                    $attachments['links'][] = [
                        'name' => !empty($linkNames[$index]) ? $linkNames[$index] : 'Tautan ' . (count($attachments['links']) + 1),
                        'url' => $link
                    ];
                }
            }
        }

        // Simpan hanya jika ada isinya
        if (!empty($attachments['files']) || !empty($attachments['links'])) {
            $validated['attachments'] = $attachments;
        } else {
            $validated['attachments'] = null;
        }

        $task->update($validated);

        return redirect()->route('kepanitiaan.co.dashboard', ['event' => $task->event_id, 'division' => $task->event_division_id])->with('success', 'Tugas berhasil diperbarui!');
    }

    /**
     * CO me-review progres dari anggota (Accept atau Revisi).
     */
    public function reviewTask(Request $request, WorkTask $task)
    {
        $validated = $request->validate([
            'status' => 'required|in:completed,revisi',
            'revision_note' => 'nullable|string',
        ]);

        $user = auth()->user();

        // Validasi: Pastikan user login adalah CO dari event_division tersebut
        $isCO = $user->eventCommittees()
            ->where('event_id', $task->event_id)
            ->where('event_division_id', $task->event_division_id)
            ->whereHas('role', fn($q) => $q->where('slug', 'co-divisi'))
            ->exists();

        if (!$isCO) {
            abort(403, 'Akses ditolak. Anda bukan Koordinator di divisi ini.');
        }

        // Hanya tugas dengan status waiting yang bisa di-review
        if ($task->status !== 'waiting') {
            return back()->with('error', 'Tugas ini belum diajukan progresnya oleh anggota.');
        }

        $task->status = $validated['status'];
        if ($validated['status'] === 'completed') {
            $task->completed_at = now();
            $task->revision_note = null; // bersihkan note jika di-accept
        } else {
            $task->completed_at = null;
            $task->revision_note = $validated['revision_note'] ?? null;
        }

        $task->save();

        if ($validated['status'] === 'completed') {
            return redirect()->route('kepanitiaan.co.dashboard', ['event' => $task->event_id, 'division' => $task->event_division_id])->with('success', 'Progres tugas berhasil diterima (Completed).');
        } else {
            return back()->with('success', 'Progres tugas berhasil dikembalikan (Revisi).');
        }
    }

    /**
     * Menghapus tugas.
     */
    public function destroyTask(WorkTask $task)
    {
        $user = auth()->user();

        // Validasi: Pastikan user login adalah CO dari event_division tersebut
        $isCO = $user->eventCommittees()
            ->where('event_id', $task->event_id)
            ->where('event_division_id', $task->event_division_id)
            ->whereHas('role', fn($q) => $q->where('slug', 'co-divisi'))
            ->exists();

        if (!$isCO) {
            abort(403, 'Akses ditolak. Anda bukan Koordinator di divisi ini.');
        }

        $task->delete();

        return back()->with('success', 'Tugas berhasil dihapus!');
    }
}
