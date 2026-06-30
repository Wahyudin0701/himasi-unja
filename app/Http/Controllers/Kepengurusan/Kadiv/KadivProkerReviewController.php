<?php

namespace App\Http\Controllers\Kepengurusan\Kadiv;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kepengurusan\ProkerLog;
use App\Models\Kepengurusan\Period;
use App\Models\Kepengurusan\WorkProgram;

class KadivProkerReviewController extends Controller
{
    /**
     * Menampilkan daftar jurnal proker non-event yang menunggu review
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

        // Ambil log-log non-event dalam divisi ini yang berstatus pending (menunggu review)
        $logs = ProkerLog::whereHas('workProgram', function($q) use ($divisionId) {
                $q->where('type', 'non_event')
                  ->where('division_id', $divisionId);
            })
            ->where('status', 'pending')
            ->with(['workProgram', 'author'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('kepengurusan.kadiv.proker.review', compact('user', 'logs'));
    }

    /**
     * Mereviu jurnal progres (Approve / Revise)
     */
    public function reviewLog(Request $request, ProkerLog $log)
    {
        $user = auth()->user();

        // Validasi divisi
        $activePeriod = Period::where('is_active', true)->first();
        $membership = $user->memberships()
            ->whereHas('division', fn($q) => $q->where('period_id', $activePeriod->id))
            ->first();

        if (!$membership || $log->workProgram->division_id !== $membership->division_id) {
            abort(403, 'Anda tidak berhak mereviu laporan ini.');
        }

        if ($log->status !== 'pending') {
            return back()->with('error', 'Status laporan tidak valid untuk direviu.');
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,revise',
            'feedback' => 'nullable|string|max:500'
        ]);

        $action = $validated['action'];
        $log->feedback = $validated['feedback'];

        if ($action === 'approve') {
            $log->status = 'approved';
            $log->save();

            // Update work program progress
            $proker = $log->workProgram;
            $proker->progress_percentage = $log->progress_update;
            
            // Jika progres 100%, otomatis completed
            if ($proker->progress_percentage == 100) {
                $proker->status = 'completed';
            } elseif ($proker->progress_percentage > 0 && $proker->status == 'planning') {
                $proker->status = 'ongoing';
            }
            
            $proker->save();

            return back()->with('success', 'Laporan Progres berhasil disetujui.');
        } else {
            $log->status = 'revised';
            $log->save();

            return back()->with('success', 'Laporan Progres dikembalikan untuk revisi.');
        }
    }
}
