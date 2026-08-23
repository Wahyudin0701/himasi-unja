@extends('layouts.dashboard')

@section('title', 'Dashboard Bendahara')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Halo, {{ Auth::user()->name }}! 👋</h1>
        <p class="text-sm text-slate-500 mt-1">Selamat datang di panel Bendahara Himpunan periode {{ $activePeriod->name ?? 'Aktif' }}.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('kepengurusan.bendahara.finances.index') }}" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-xl text-sm font-bold transition-colors shadow-sm">
            <i class="ph-bold ph-wallet"></i>
            Kas Himasi
        </a>
    </div>
</div>

{{-- Statistik Overview --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-brand-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-12 h-12 rounded-xl bg-brand-100 text-brand-600 flex items-center justify-center mb-4">
                <i class="ph-bold ph-wallet text-2xl"></i>
            </div>
            <p class="text-sm font-bold text-slate-500 mb-1">Total Saldo Kas</p>
            <h3 class="text-2xl font-black text-slate-900">Rp {{ number_format($stats['saldo_kas'], 0, ',', '.') }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-4">
                <i class="ph-bold ph-trend-up text-2xl"></i>
            </div>
            <p class="text-sm font-bold text-slate-500 mb-1">Pemasukan Bulan Ini</p>
            <h3 class="text-2xl font-black text-slate-900">Rp {{ number_format($stats['pemasukan_bulan_ini'], 0, ',', '.') }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-red-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-12 h-12 rounded-xl bg-red-100 text-red-600 flex items-center justify-center mb-4">
                <i class="ph-bold ph-trend-down text-2xl"></i>
            </div>
            <p class="text-sm font-bold text-slate-500 mb-1">Pengeluaran Bulan Ini</p>
            <h3 class="text-2xl font-black text-slate-900">Rp {{ number_format($stats['pengeluaran_bulan_ini'], 0, ',', '.') }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center mb-4">
                <i class="ph-bold ph-users text-2xl"></i>
            </div>
            <p class="text-sm font-bold text-slate-500 mb-1">Tunggakan Kas Anggota</p>
            <h3 class="text-2xl font-black text-slate-900">0 <span class="text-sm font-bold text-slate-400">Orang</span></h3>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Transaksi Terbaru</h2>
                    <p class="text-xs text-slate-500 mt-1">Riwayat mutasi kas himpunan</p>
                </div>
                <a href="{{ route('kepengurusan.bendahara.finances.index') }}" class="text-sm font-bold text-brand-600 hover:text-brand-700">Lihat Semua</a>
            </div>
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="ph-bold ph-receipt text-2xl text-slate-400"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900 mb-1">Belum ada transaksi</h3>
                <p class="text-xs text-slate-500">Catat pemasukan atau pengeluaran pertama Anda.</p>
            </div>
        </div>
    </div>
    
    <div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
            <div class="p-6 border-b border-slate-100">
                <h2 class="text-lg font-bold text-slate-900">Menu Cepat</h2>
            </div>
            <div class="p-4 space-y-2">
                <a href="{{ route('kepengurusan.bendahara.laporan') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors group">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="ph-bold ph-file-text text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900">Laporan Keuangan</h4>
                        <p class="text-xs text-slate-500">Cetak rekap bulanan</p>
                    </div>
                </a>
                <a href="#" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors group">
                    <div class="w-10 h-10 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="ph-bold ph-list-checks text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900">Tagihan Kas</h4>
                        <p class="text-xs text-slate-500">Kelola iuran wajib pengurus</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
