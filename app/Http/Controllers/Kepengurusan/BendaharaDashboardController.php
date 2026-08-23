<?php

namespace App\Http\Controllers\Kepengurusan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kepengurusan\Period;
use Barryvdh\DomPDF\Facade\Pdf;

class BendaharaDashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard utama untuk Bendahara HIMA
     */
    public function index()
    {
        $activePeriod = Period::where('is_active', true)->first();
        
        $stats = [
            'saldo_kas' => 0,
            'pemasukan_bulan_ini' => 0,
            'pengeluaran_bulan_ini' => 0,
            'anggota_belum_bayar_kas' => 0
        ];

        if ($activePeriod) {
            $transactions = \App\Models\Kepengurusan\FinanceTransaction::where('period_id', $activePeriod->id)->get();
            
            $totalPemasukan = $transactions->where('type', 'pemasukan')->sum('amount');
            $totalPengeluaran = $transactions->where('type', 'pengeluaran')->sum('amount');
            $stats['saldo_kas'] = $totalPemasukan - $totalPengeluaran;

            $monthStr = now()->format('Y-m');
            $stats['pemasukan_bulan_ini'] = $transactions->where('type', 'pemasukan')->filter(function($t) use ($monthStr) {
                return $t->date->format('Y-m') === $monthStr;
            })->sum('amount');
            
            $stats['pengeluaran_bulan_ini'] = $transactions->where('type', 'pengeluaran')->filter(function($t) use ($monthStr) {
                return $t->date->format('Y-m') === $monthStr;
            })->sum('amount');
        }

        return view('kepengurusan.bendahara.dashboard', compact('activePeriod', 'stats'));
    }

    /**
     * Tampilkan halaman laporan keuangan bulanan
     */
    public function laporan()
    {
        $activePeriod = Period::where('is_active', true)->firstOrFail();
        
        $transactions = \App\Models\Kepengurusan\FinanceTransaction::where('period_id', $activePeriod->id)
            ->orderBy('date', 'asc')
            ->get();

        // Group by Year-Month (e.g. 2026-07)
        $grouped = $transactions->groupBy(function ($item) {
            return $item->date->format('Y-m');
        });

        $report = [];
        $totalPemasukanPeriode = 0;
        $totalPengeluaranPeriode = 0;

        foreach ($grouped as $monthYear => $items) {
            $pemasukan = $items->where('type', 'pemasukan')->sum('amount');
            $pengeluaran = $items->where('type', 'pengeluaran')->sum('amount');
            $saldoBulan = $pemasukan - $pengeluaran;

            $totalPemasukanPeriode += $pemasukan;
            $totalPengeluaranPeriode += $pengeluaran;

            $report[] = [
                'month_year_raw' => $monthYear,
                'month_name' => \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->translatedFormat('F Y'),
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'saldo' => $saldoBulan
            ];
        }

        // Urutkan dari yang terbaru
        usort($report, function($a, $b) {
            return strcmp($b['month_year_raw'], $a['month_year_raw']);
        });

        $saldoAkhir = $totalPemasukanPeriode - $totalPengeluaranPeriode;

        return view('kepengurusan.bendahara.laporan', compact(
            'activePeriod', 'report', 'totalPemasukanPeriode', 'totalPengeluaranPeriode', 'saldoAkhir'
        ));
    }

    /**
     * Cetak laporan keuangan (tampilan PDF / print-friendly)
     */
    public function cetakLaporan()
    {
        $activePeriod = Period::where('is_active', true)->firstOrFail();
        
        $transactions = \App\Models\Kepengurusan\FinanceTransaction::where('period_id', $activePeriod->id)
            ->orderBy('date', 'asc')
            ->get();

        $grouped = $transactions->groupBy(function ($item) {
            return $item->date->format('Y-m');
        });

        $report = [];
        $totalPemasukanPeriode = 0;
        $totalPengeluaranPeriode = 0;

        foreach ($grouped as $monthYear => $items) {
            $pemasukan = $items->where('type', 'pemasukan')->sum('amount');
            $pengeluaran = $items->where('type', 'pengeluaran')->sum('amount');
            $saldoBulan = $pemasukan - $pengeluaran;

            $totalPemasukanPeriode += $pemasukan;
            $totalPengeluaranPeriode += $pengeluaran;

            $report[] = [
                'month_year_raw' => $monthYear,
                'month_name' => \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->translatedFormat('F Y'),
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'saldo' => $saldoBulan
            ];
        }

        // Urutkan dari yang terbaru
        usort($report, function($a, $b) {
            return strcmp($b['month_year_raw'], $a['month_year_raw']);
        });

        $saldoAkhir = $totalPemasukanPeriode - $totalPengeluaranPeriode;

        $pdf = Pdf::loadView('kepengurusan.bendahara.cetak-laporan', compact(
            'activePeriod', 'report', 'totalPemasukanPeriode', 'totalPengeluaranPeriode', 'saldoAkhir'
        ))->setPaper('a4', 'portrait');

        return $pdf->stream('laporan-keuangan-himasi.pdf');
    }
}
