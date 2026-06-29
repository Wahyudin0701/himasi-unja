<?php

namespace App\Http\Controllers\Kepengurusan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kepengurusan\Period;
use App\Models\Kepengurusan\Division;

class AnggotaDashboardController extends Controller
{
    /**
     * Dashboard khusus untuk Anggota Divisi
     */
    public function index()
    {
        $user = auth()->user();
        $activePeriod = Period::where('is_active', true)->first();

        if (!$activePeriod) {
            abort(404, 'Tidak ada periode aktif.');
        }

        // Ambil data keanggotaan user di divisi
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
        
        // Total anggaran proker
        $totalBudget = $prokers->sum('budget_plan');

        // ===== CEK KEPANITIAAN AKTIF =====
        // Ambil semua event aktif di mana user ini menjadi panitia
        $activeCommitteeRoles = $user->eventCommittees()
            ->with(['event', 'role'])
            ->whereHas('event', fn($q) => $q->whereIn('status', ['planning', 'preparation', 'ongoing']))
            ->get()
            ->groupBy('event_id');

        return view('kepengurusan.anggota.dashboard', compact(
            'user', 'division', 'members', 'prokers', 
            'totalMembers', 'totalProkers', 'activeProkers', 'totalBudget',
            'activeCommitteeRoles'
        ));
    }
}
