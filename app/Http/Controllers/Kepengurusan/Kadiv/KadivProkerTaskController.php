<?php

namespace App\Http\Controllers\Kepengurusan\Kadiv;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kepengurusan\Period;
use App\Models\Kepengurusan\WorkProgram;
use App\Models\ProgressReport\WorkTask;

class KadivProkerTaskController extends Controller
{
    /**
     * Menyimpan tugas baru untuk proker
     */
    public function store(Request $request, WorkProgram $proker)
    {
        $user = auth()->user();
        $activePeriod = Period::where('is_active', true)->first();

        if (!$activePeriod) {
            return back()->with('error', 'Tidak ada periode aktif.');
        }

        $membership = $user->memberships()
            ->whereHas('division', fn($q) => $q->where('period_id', $activePeriod->id))
            ->first();

        // Ensure the proker belongs to the Kadiv's division
        if (!$membership || $proker->division_id !== $membership->division_id) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'assigned_to' => 'required|exists:users,id',
        ]);

        $validated['work_program_id'] = $proker->id;
        $validated['division_id'] = $proker->division_id;
        $validated['assigned_by'] = $user->id;
        $validated['status'] = 'todo';

        WorkTask::create($validated);

        return back()->with('success', 'Tugas berhasil ditambahkan.');
    }

    /**
     * Memperbarui tugas
     */
    public function update(Request $request, WorkProgram $proker, WorkTask $task)
    {
        $user = auth()->user();
        $activePeriod = Period::where('is_active', true)->first();

        if (!$activePeriod) {
            return back()->with('error', 'Tidak ada periode aktif.');
        }

        $membership = $user->memberships()
            ->whereHas('division', fn($q) => $q->where('period_id', $activePeriod->id))
            ->first();

        if (!$membership || $proker->division_id !== $membership->division_id || $task->work_program_id !== $proker->id) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'assigned_to' => 'required|exists:users,id',
        ]);

        $task->update($validated);

        return back()->with('success', 'Tugas berhasil diperbarui.');
    }

    /**
     * Menghapus tugas
     */
    public function destroy(WorkProgram $proker, WorkTask $task)
    {
        $user = auth()->user();
        $activePeriod = Period::where('is_active', true)->first();

        if (!$activePeriod) {
            return back()->with('error', 'Tidak ada periode aktif.');
        }

        $membership = $user->memberships()
            ->whereHas('division', fn($q) => $q->where('period_id', $activePeriod->id))
            ->first();

        if (!$membership || $proker->division_id !== $membership->division_id || $task->work_program_id !== $proker->id) {
            abort(403, 'Akses ditolak.');
        }

        $task->delete();

        return back()->with('success', 'Tugas berhasil dihapus.');
    }
}
