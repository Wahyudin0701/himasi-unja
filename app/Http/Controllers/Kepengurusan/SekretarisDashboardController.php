<?php

namespace App\Http\Controllers\Kepengurusan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Kepengurusan\Period;
use App\Models\Kepengurusan\Division;
use App\Models\Kepengurusan\Member;

class SekretarisDashboardController extends Controller
{
    public function index()
    {
        $activePeriod = Period::where('is_active', true)->first();

        if (!$activePeriod) {
            return view('kepengurusan.sekretaris.dashboard', [
                'totalDivisions' => 0,
                'totalMembers' => 0,
                'activePeriod' => null
            ]);
        }

        $totalDivisions = Division::where('period_id', $activePeriod->id)->count();
        $totalMembers = Member::whereHas('division', function ($query) use ($activePeriod) {
            $query->where('period_id', $activePeriod->id);
        })->count();
        
        $divisions = Division::where('period_id', $activePeriod->id)
            ->withCount('members')
            ->get();

        return view('kepengurusan.sekretaris.dashboard', compact('activePeriod', 'totalDivisions', 'totalMembers', 'divisions'));
    }
}
