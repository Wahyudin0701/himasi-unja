<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kepengurusan\Period;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $activePeriod = Period::where('is_active', true)->first();
        
        // Data default kosong
        $prokers = collect();
        $members = collect();
        $tasks = collect();
        $totalProker = 0;
        $activeProker = 0;
        $totalBudget = 0;

        // Cek global_role untuk menentukan dashboard kepengurusan
        $globalRole = $user->global_role ?? 'anggota';

        if (in_array($globalRole, ['super_admin', 'kahim'])) {
            return view('dashboard.executive', compact(
                'user', 'prokers', 'totalProker', 'activeProker', 'totalBudget'
            ));
        }

        if ($globalRole === 'kadiv') {
            return view('dashboard.kadiv', compact('user', 'prokers', 'members'));
        }

        // Fallback: cek dari membership di periode aktif
        if ($activePeriod) {
            $membership = $user->memberships()
                ->whereHas('division', fn($q) => $q->where('period_id', $activePeriod->id))
                ->with('orgPosition')
                ->first();

            if ($membership && $membership->orgPosition) {
                $level = $membership->orgPosition->level;
                
                // Level 1-4: BPH (Kahim, Wakahim, Sekretaris, Bendahara)
                if ($level <= 4) {
                    return view('dashboard.executive', compact(
                        'user', 'prokers', 'totalProker', 'activeProker', 'totalBudget'
                    ));
                }

                // Level 5: Kadiv
                if ($level === 5) {
                    return view('dashboard.kadiv', compact('user', 'prokers', 'members'));
                }
            }
        }

        // Default: dashboard anggota biasa
        return view('dashboard.anggota', compact('user', 'tasks'));
    }
}
