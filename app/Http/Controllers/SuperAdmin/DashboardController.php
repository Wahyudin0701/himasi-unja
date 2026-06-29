<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kepengurusan\Period;
use App\Models\Kepengurusan\Division;

class DashboardController extends Controller
{
    public function index()
    {
        $activePeriod = Period::where('is_active', true)->first();
        $totalPeriods = Period::count();
        $totalDivisions = $activePeriod ? Division::where('period_id', $activePeriod->id)->count() : 0;
        
        return view('super_admin.dashboard', compact('activePeriod', 'totalPeriods', 'totalDivisions'));
    }
}
