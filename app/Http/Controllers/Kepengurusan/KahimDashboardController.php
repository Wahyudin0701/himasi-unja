<?php

namespace App\Http\Controllers\Kepengurusan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kepengurusan\Period;
use App\Models\Kepengurusan\Division;
use App\Models\Kepengurusan\FinanceTransaction;
use App\Models\Kepengurusan\WorkProgram;
use App\Models\Kepengurusan\ProkerLog;
use App\Models\Kepanitiaan\Event;
use App\Models\User;

class KahimDashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard utama untuk Ketua Himpunan & Wakahim
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
        $recentActivities = collect();

        if ($activePeriod) {
            // Hitung total anggota yang tergabung ke divisi pada periode ini
            $stats['total_anggota'] = \App\Models\Kepengurusan\Member::whereHas('division', function($query) use ($activePeriod) {
                $query->where('period_id', $activePeriod->id);
            })->count();

            // Hitung total divisi
            $stats['total_divisi'] = Division::where('period_id', $activePeriod->id)
                ->whereNotIn('type', ['pembina', 'dp'])
                ->count();

            // Hitung saldo kas
            $transactions = FinanceTransaction::where('period_id', $activePeriod->id)->get();
            $totalPemasukan = $transactions->where('type', 'pemasukan')->sum('amount');
            $totalPengeluaran = $transactions->where('type', 'pengeluaran')->sum('amount');
            $stats['saldo_kas'] = $totalPemasukan - $totalPengeluaran;
            
            // Ambil semua proker aktif
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

            // Papan Aktivitas Terbaru (Proker Log)
            $recentActivities = ProkerLog::whereHas('workProgram.division', function($query) use ($activePeriod) {
                $query->where('period_id', $activePeriod->id);
            })
            ->with(['author', 'workProgram', 'workProgram.division'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        }

        return view('kepengurusan.kahim.dashboard', compact('activePeriod', 'stats', 'prokerMetrics', 'upcomingEvents', 'recentActivities'));
    }

    /**
     * Tampilkan halaman aktivitas (timeline) divisi
     */
    public function activities(\Illuminate\Http\Request $request)
    {
        $activePeriod = Period::where('is_active', true)->firstOrFail();

        $divisions = Division::where('period_id', $activePeriod->id)
            ->whereNotIn('type', ['bph', 'pembina', 'dp'])
            ->get();

        $query = ProkerLog::whereHas('workProgram.division', function($q) use ($activePeriod) {
            $q->where('period_id', $activePeriod->id);
        });

        if ($request->filled('division_id')) {
            $query->whereHas('workProgram', function($q) use ($request) {
                $q->where('division_id', $request->division_id);
            });
        }

        $activities = $query->with(['author', 'workProgram', 'workProgram.division'])
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->query());

        return view('kepengurusan.kahim.activities', compact('activePeriod', 'activities', 'divisions'));
    }

    /**
     * Tampilkan daftar seluruh agenda/event kepanitiaan
     */
    public function agendas(\Illuminate\Http\Request $request)
    {
        $activePeriod = Period::where('is_active', true)->firstOrFail();

        $query = \App\Models\Kepanitiaan\Event::where('period_id', $activePeriod->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $agendas = $query->orderBy('event_date', 'asc')->paginate(20)->appends($request->query());

        return view('kepengurusan.kahim.agendas', compact('activePeriod', 'agendas'));
    }
}
