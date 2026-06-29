<?php

namespace App\Http\Controllers\Kepengurusan\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProgressReport\WorkTask;
use App\Models\ProgressReport\ProgressReport;

class AnggotaProkerController extends Controller
{
    /**
     * Tampilkan Kanban Proker untuk Anggota Divisi.
     */
    public function index()
    {
        $user = auth()->user();

        // Ambil semua tugas yang di-assign ke user dari proker non-event
        // yang sedang aktif
        $tasks = WorkTask::where('assigned_to', $user->id)
            ->whereHas('workProgram', function($q) {
                $q->where('type', 'non-event')
                  ->whereIn('status', ['planning', 'ongoing']);
            })
            ->with(['workProgram', 'assigner', 'reports'])
            ->orderBy('due_date')
            ->get();

        // Statistik
        $totalTasks = $tasks->count();
        $completedTasks = $tasks->where('status', 'completed')->count();
        $inProgressTasks = $tasks->whereIn('status', ['waiting', 'revisi', 'todo'])->count();
        
        return view('kepengurusan.anggota.proker.kanban', compact(
            'user', 'tasks', 'totalTasks', 'completedTasks', 'inProgressTasks'
        ));
    }

    /**
     * Tampilkan detail tugas.
     */
    public function show(WorkTask $task)
    {
        $user = auth()->user();

        if ($task->assigned_to !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        $task->load(['workProgram', 'assigner', 'reports']);
        $latestReport = $task->reports()->latest()->first();

        return view('kepengurusan.anggota.proker.task-detail', compact('user', 'task', 'latestReport'));
    }

    /**
     * Ajukan penyelesaian tugas (ke status 'waiting' / Review).
     */
    public function submitReview(Request $request, WorkTask $task)
    {
        $user = auth()->user();
        
        if ($task->assigned_to !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

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

        return redirect()->route('kepengurusan.anggota.proker.kanban')->with('success', 'Progres tugas berhasil diajukan dan menunggu review Kadiv.');
    }
}
