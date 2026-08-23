@extends('layouts.dashboard')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Laporan Keuangan</h1>
        <p class="text-sm text-slate-500 mt-1">Rekapitulasi arus kas Kas Himasi pada periode kepengurusan {{ $activePeriod->name ?? 'Aktif' }}.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('kepengurusan.bendahara.laporan.cetak') }}" target="_blank" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-xl text-sm font-bold transition-colors shadow-sm">
            <i class="ph-bold ph-printer"></i>
            Cetak Laporan PDF
        </a>
    </div>
</div>

{{-- Ringkasan Total --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-4">
                <i class="ph-bold ph-trend-up text-2xl"></i>
            </div>
            <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Total Pemasukan</p>
            <h3 class="text-2xl font-black text-slate-900">Rp {{ number_format($totalPemasukanPeriode, 0, ',', '.') }}</h3>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-red-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-12 h-12 rounded-xl bg-red-100 text-red-600 flex items-center justify-center mb-4">
                <i class="ph-bold ph-trend-down text-2xl"></i>
            </div>
            <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Total Pengeluaran</p>
            <h3 class="text-2xl font-black text-slate-900">Rp {{ number_format($totalPengeluaranPeriode, 0, ',', '.') }}</h3>
        </div>
    </div>

    <div class="bg-brand-600 rounded-2xl p-6 shadow-sm relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-12 h-12 rounded-xl bg-white/20 text-white flex items-center justify-center mb-4">
                <i class="ph-bold ph-wallet text-2xl"></i>
            </div>
            <p class="text-xs font-black text-brand-100 uppercase tracking-widest mb-1">Saldo Akhir Kas</p>
            <h3 class="text-2xl font-black text-white">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</h3>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-slate-900 tracking-tight">Riwayat Bulanan</h3>
            <p class="text-sm text-slate-500 mt-1">Rincian agregat pemasukan dan pengeluaran per bulan.</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest whitespace-nowrap">Bulan / Tahun</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest whitespace-nowrap text-right">Pemasukan</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest whitespace-nowrap text-right">Pengeluaran</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest whitespace-nowrap text-right">Saldo Bersih</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($report as $row)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-slate-900">{{ $row['month_name'] }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <span class="text-sm font-bold text-emerald-600">Rp {{ number_format($row['pemasukan'], 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <span class="text-sm font-bold text-red-600">Rp {{ number_format($row['pengeluaran'], 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <span class="text-sm font-bold {{ $row['saldo'] >= 0 ? 'text-brand-600' : 'text-red-600' }}">
                                {{ $row['saldo'] < 0 ? '-' : '' }}Rp {{ number_format(abs($row['saldo']), 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="ph-bold ph-file-dashed text-2xl text-slate-400"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 mb-1">Belum ada data bulanan</h3>
                            <p class="text-xs text-slate-500">Rekapitulasi akan muncul setelah ada pencatatan kas.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
