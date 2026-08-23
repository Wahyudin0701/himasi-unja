<?php

namespace App\Http\Controllers\Kepengurusan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kepengurusan\Period;
use App\Models\Kepengurusan\Division;
use App\Models\Kepengurusan\FinanceTransaction;
use App\Models\Kepengurusan\WorkProgram;
use App\Models\Kepanitiaan\Event;
use App\Models\Kepengurusan\OrganizationLetter;
use App\Models\Kepengurusan\Meeting;

class DpDashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard pemantauan untuk Dewan Penasihat (DP)
     */
    public function index()
    {
        $activePeriod = Period::where('is_active', true)->first();
        
        $stats = [
            'total_anggota' => 0,
            'total_divisi' => 0,
            'saldo_kas' => 0,
            'proker_berjalan' => 0,
        ];

        $prokerMetrics = [
            'total' => 0,
            'planning' => 0,
            'ongoing' => 0,
            'completed' => 0,
            'cancelled' => 0,
            'progress_percentage' => 0,
        ];

        $upcomingEvents = collect();
        $divisionProgress = collect();
        $recentLetters = collect();
        $recentMeetings = collect();
        $recentTransactions = collect();

        if ($activePeriod) {
            // Hitung total anggota yang tergabung ke divisi pada periode ini
            $stats['total_anggota'] = \App\Models\Kepengurusan\Member::whereHas('division', function($query) use ($activePeriod) {
                $query->where('period_id', $activePeriod->id);
            })->count();

            // Hitung total divisi murni
            $stats['total_divisi'] = Division::where('period_id', $activePeriod->id)
                ->whereNotIn('type', ['pembina', 'dp'])
                ->count();

            // Hitung saldo kas
            $transactions = FinanceTransaction::where('period_id', $activePeriod->id)->get();
            $totalPemasukan = $transactions->where('type', 'pemasukan')->sum('amount');
            $totalPengeluaran = $transactions->where('type', 'pengeluaran')->sum('amount');
            $stats['saldo_kas'] = $totalPemasukan - $totalPengeluaran;
            
            // Hitung Metrik Proker
            $allProkers = WorkProgram::whereHas('division', function($query) use ($activePeriod) {
                $query->where('period_id', $activePeriod->id);
            })->get();

            $prokerMetrics['total'] = $allProkers->count();
            
            foreach ($allProkers as $proker) {
                $status = $proker->status;
                if ($status === 'ongoing') {
                    $prokerMetrics['ongoing']++;
                } elseif ($status === 'completed') {
                    $prokerMetrics['completed']++;
                } elseif ($status === 'planning') {
                    $prokerMetrics['planning']++;
                } elseif ($status === 'cancelled') {
                    $prokerMetrics['cancelled']++;
                }
            }
            
            $stats['proker_berjalan'] = $prokerMetrics['ongoing'];

            if ($prokerMetrics['total'] > 0) {
                $prokerMetrics['progress_percentage'] = round(($prokerMetrics['completed'] / $prokerMetrics['total']) * 100);
            }

            // Kepanitiaan (Event) Terdekat
            $upcomingEvents = Event::where('period_id', $activePeriod->id)
                ->where('status', '!=', 'completed')
                ->where('event_date', '>=', now()->startOfDay())
                ->orderBy('event_date', 'asc')
                ->take(3)
                ->get();

            // --- DATA BARU UNTUK DASHBOARD DP ---
            
            // 1. Progress Program Kerja per Divisi BPH
            $divisionProgress = Division::where('period_id', $activePeriod->id)
                ->where('type', 'bph')
                ->with(['workPrograms' => function($q) {
                    $q->select('id', 'division_id', 'status');
                }])
                ->get()
                ->map(function ($div) {
                    $total = $div->workPrograms->count();
                    $completed = $div->workPrograms->where('status', 'completed')->count();
                    $ongoing = $div->workPrograms->where('status', 'ongoing')->count();
                    $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;
                    
                    return (object) [
                        'name' => $div->name,
                        'total' => $total,
                        'completed' => $completed,
                        'ongoing' => $ongoing,
                        'percentage' => $percentage
                    ];
                })
                ->sortByDesc('percentage')
                ->values();

            // 2. Surat Masuk / Keluar Terbaru (tidak ada period_id)
            $recentLetters = OrganizationLetter::orderBy('letter_date', 'desc')
                ->take(5)
                ->get();
            
            // 3. Rapat (Meeting) Terbaru (tidak ada period_id)
            $recentMeetings = Meeting::orderBy('date', 'desc')
                ->take(4)
                ->get();
                
            // 4. Transaksi Kas Terbaru
            $recentTransactions = FinanceTransaction::where('period_id', $activePeriod->id)
                ->orderBy('date', 'desc')
                ->take(5)
                ->get();
        }

        return view('kepengurusan.dp.dashboard', compact(
            'activePeriod', 'stats', 'prokerMetrics', 'upcomingEvents', 
            'divisionProgress', 'recentLetters', 'recentMeetings', 'recentTransactions'
        ));
    }
}
