<?php

namespace App\Http\Controllers\Kepanitiaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProgressReport\WorkTask;
use App\Models\ProgressReport\ProgressReport;
use Illuminate\Support\Facades\Storage;

class AnggotaDashboardController extends Controller
{
    /**
     * Dashboard utama Anggota Panitia.
     * Menampilkan tabel progress mingguan / sprint.
     */
    public function index()
    {
        $user = auth()->user();

        // Ambil semua tugas yang di-assign ke user di event aktif
        $tasks = WorkTask::where('assigned_to', $user->id)
            ->whereHas('event', fn($q) => $q->whereIn('status', ['planning', 'preparation', 'ongoing']))
            ->with(['event', 'eventDivision', 'assigner', 'reports'])
            ->orderBy('sprint_number')
            ->orderBy('due_date')
            ->get();

        // Grup berdasarkan sprint
        $sprintGroups = $tasks->groupBy('sprint_number');
        
        // Statistik
        $totalTasks = $tasks->count();
        $completedTasks = $tasks->where('status', 'completed')->count();
        $inProgressTasks = $tasks->whereIn('status', ['waiting', 'revisi'])->count();
        $todoTasks = $tasks->where('status', 'todo')->count();

        return view('kepanitiaan.anggota.dashboard', compact(
            'user', 'tasks', 'sprintGroups', 'totalTasks', 'completedTasks', 'inProgressTasks', 'todoTasks'
        ));
    }

    /**
     * Menampilkan detail tugas anggota.
     */
    public function show(WorkTask $task)
    {
        $user = auth()->user();

        // Pastikan user adalah assignee
        if ($task->assigned_to !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        $task->load(['event', 'eventDivision', 'assigner', 'reports']);

        $latestReport = $task->reports()->latest()->first();

        return view('kepanitiaan.anggota.tasks_detail', compact('user', 'task', 'latestReport'));
    }

    /**
     * Update status tugas (todo/revisi → waiting)
     */
    public function updateTaskStatus(Request $request, WorkTask $task)
    {
        $user = auth()->user();
        
        // Pastikan user adalah assignee
        if ($task->assigned_to !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        // Anggota hanya bisa mengajukan progres (mengubah ke waiting)
        $request->validate([
            'status' => 'required|in:waiting',
            'catatan' => 'required|string',
            'file_names.*' => 'nullable|string|max:255',
            'files.*' => 'nullable|file|max:10240',
            'link_names.*' => 'nullable|string|max:255',
            'links.*' => 'nullable|url|max:255',
        ]);

        if (!in_array($task->status, ['todo', 'revisi'])) {
            return back()->with('error', 'Status tugas tidak valid untuk diajukan.');
        }

        $attachments = [
            'files' => [],
            'links' => []
        ];

        if ($request->hasFile('files')) {
            $fileNames = $request->input('file_names', []);
            foreach ($request->file('files') as $index => $file) {
                $attachments['files'][] = [
                    'name' => !empty($fileNames[$index]) ? $fileNames[$index] : 'Lampiran ' . (count($attachments['files']) + 1),
                    'path' => $file->store('progress_reports', 'public')
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

        ProgressReport::create([
            'work_task_id' => $task->id,
            'user_id' => $user->id,
            'content' => $request->input('catatan'),
            'attachments' => (empty($attachments['files']) && empty($attachments['links'])) ? null : $attachments,
            'status_update' => 'in_progress'
        ]);

        $task->status = 'waiting';
        $task->save();

        return redirect()->route('kepanitiaan.anggota.dashboard')->with('success', 'Progres tugas berhasil diajukan dan menunggu review CO.');
    }
}
