<?php

namespace App\Http\Controllers\Kepengurusan\Kadiv;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProgressReport\WorkTask;
use App\Models\Kepengurusan\Period;
use App\Models\ProgressReport\ProgressReport;

class KadivProkerReviewController extends Controller
{
    /**
     * Menampilkan daftar tugas proker non-event yang menunggu review
     */
    public function index()
    {
        $user = auth()->user();
        $activePeriod = Period::where('is_active', true)->first();

        if (!$activePeriod) {
            abort(404, 'Tidak ada periode aktif.');
        }

        $membership = $user->memberships()
            ->whereHas('division', fn($q) => $q->where('period_id', $activePeriod->id))
            ->first();

        if (!$membership) {
            abort(403, 'Anda tidak terdaftar dalam divisi di periode aktif.');
        }

        $divisionId = $membership->division_id;

        // Ambil tugas-tugas non-event dalam divisi ini yang berstatus waiting (menunggu review)
        $tasks = WorkTask::whereHas('workProgram', function($q) use ($divisionId) {
                $q->where('type', 'non-event')
                  ->where('division_id', $divisionId);
            })
            ->where('status', 'waiting')
            ->with(['workProgram', 'assignee', 'reports'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('kepengurusan.kadiv.proker.review', compact('user', 'tasks'));
    }

    /**
     * Approve tugas yang direview
     */
    public function approve(Request $request, WorkTask $task)
    {
        $user = auth()->user();

        // Validasi divisi
        $activePeriod = Period::where('is_active', true)->first();
        $membership = $user->memberships()
            ->whereHas('division', fn($q) => $q->where('period_id', $activePeriod->id))
            ->first();

        if (!$membership || $task->workProgram->division_id !== $membership->division_id) {
            abort(403, 'Anda tidak berhak mereview tugas ini.');
        }

        if ($task->status !== 'waiting') {
            return back()->with('error', 'Status tugas tidak valid untuk di-approve.');
        }

        $task->status = 'completed';
        $task->completed_at = now();
        $task->save();

        return back()->with('success', 'Tugas berhasil di-approve (Selesai).');
    }

    /**
     * Menolak/merevisi tugas
     */
    public function revise(Request $request, WorkTask $task)
    {
        $user = auth()->user();

        // Validasi divisi
        $activePeriod = Period::where('is_active', true)->first();
        $membership = $user->memberships()
            ->whereHas('division', fn($q) => $q->where('period_id', $activePeriod->id))
            ->first();

        if (!$membership || $task->workProgram->division_id !== $membership->division_id) {
            abort(403, 'Anda tidak berhak mereview tugas ini.');
        }

        if ($task->status !== 'waiting') {
            return back()->with('error', 'Status tugas tidak valid untuk direvisi.');
        }

        $request->validate([
            'revision_note' => 'required|string|max:500'
        ]);

        $task->status = 'revisi';
        $task->revision_note = $request->input('revision_note');
        $task->save();

        return back()->with('success', 'Tugas dikembalikan untuk direvisi oleh anggota.');
    }
}
