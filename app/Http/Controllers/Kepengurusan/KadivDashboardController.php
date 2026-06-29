<?php

namespace App\Http\Controllers\Kepengurusan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kepengurusan\Period;
use App\Models\Kepengurusan\Division;

class KadivDashboardController extends Controller
{
    /**
     * Dashboard khusus untuk Ketua Divisi (Kadiv)
     */
    public function index()
    {
        $user = auth()->user();
        $activePeriod = Period::where('is_active', true)->first();

        if (!$activePeriod) {
            abort(404, 'Tidak ada periode aktif.');
        }

        // Ambil data keanggotaan user di divisi (sebagai Kadiv)
        $membership = $user->memberships()
            ->whereHas('division', fn($q) => $q->where('period_id', $activePeriod->id))
            ->with(['division.members.user', 'division.members.orgPosition', 'division.workPrograms'])
            ->first();

        if (!$membership) {
            abort(403, 'Anda tidak terdaftar dalam divisi di periode aktif.');
        }

        $division = $membership->division;
        $members = $division->members->sortBy(fn($m) => $m->orgPosition->level ?? 99);
        $prokers = $division->workPrograms;

        // Hitung statistik
        $totalMembers = $members->count();
        $totalProkers = $prokers->count();
        $activeProkers = $prokers->whereIn('status', ['planning', 'ongoing'])->count();
        
        // Total anggaran proker (jika ada properti budget_plan di work_programs)
        $totalBudget = $prokers->sum('budget_plan');

        // ===== CEK KEPANITIAAN AKTIF =====
        // Ambil semua event aktif di mana user ini menjadi panitia
        $activeCommitteeRoles = $user->eventCommittees()
            ->with(['event', 'role'])
            ->whereHas('event', fn($q) => $q->whereIn('status', ['planning', 'preparation', 'ongoing']))
            ->get()
            ->groupBy('event_id');

        return view('kepengurusan.kadiv.dashboard', compact(
            'user', 'division', 'members', 'prokers', 
            'totalMembers', 'totalProkers', 'activeProkers', 'totalBudget',
            'activeCommitteeRoles'
        ));
    }

    /**
     * Halaman Progres Divisi untuk memantau progres divisi oleh Kadiv
     */
    public function progresDivisi()
    {
        $user = auth()->user();
        $activePeriod = Period::where('is_active', true)->first();
        
        if (!$activePeriod) {
            abort(404, 'Tidak ada periode aktif.');
        }

        $membership = $user->memberships()
            ->whereHas('division', fn($q) => $q->where('period_id', $activePeriod->id))
            ->with(['division.members.user', 'division.workPrograms'])
            ->first();

        if (!$membership) {
            abort(403, 'Anda tidak terdaftar dalam divisi di periode aktif.');
        }

        $division = $membership->division;

        $members = $division->members()->whereHas('user', function($q) {
            $q->where('global_role', 'anggota');
        })->with(['user', 'orgPosition'])->get();

        foreach ($members as $member) {
            $member->tasks = \App\Models\ProgressReport\WorkTask::whereHas('workProgram', function($q) use ($division) {
                    $q->where('type', 'non-event')
                      ->where('division_id', $division->id);
                })
                ->where('assigned_to', $member->user_id)
                ->orderBy('due_date', 'asc')
                ->get();
        }

        return view('kepengurusan.kadiv.progres-divisi', compact('division', 'members'));
    }
}
