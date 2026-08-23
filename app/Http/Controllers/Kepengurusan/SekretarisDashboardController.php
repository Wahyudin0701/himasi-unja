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

        $totalSuratMasuk = \App\Models\Kepengurusan\OrganizationLetter::where('type', 'masuk')->count();
        $totalSuratKeluar = \App\Models\Kepengurusan\OrganizationLetter::where('type', 'keluar')->count();
        $totalTemplat = \App\Models\Kepengurusan\DocumentTemplate::count();
        
        $upcomingMeetings = \App\Models\Kepengurusan\Meeting::whereDate('date', '>=', now()->toDateString())
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->take(4)
            ->get();

        return view('kepengurusan.sekretaris.dashboard', compact(
            'activePeriod', 
            'totalSuratMasuk', 
            'totalSuratKeluar', 
            'totalTemplat',
            'upcomingMeetings'
        ));
    }
}
