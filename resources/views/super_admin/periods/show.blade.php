@extends('layouts.dashboard')

@section('title', 'Detail Periode: ' . $period->name)

@section('breadcrumbs')
    <a href="{{ route('super_admin.dashboard') }}" class="text-slate-500 hover:text-brand-600 transition-colors">Super Admin</a>
    <i class="ph-bold ph-caret-right text-slate-400 text-xs"></i>
    <a href="{{ route('super_admin.periods.index') }}" class="text-slate-500 hover:text-brand-600 transition-colors">Manajemen Periode</a>
    <i class="ph-bold ph-caret-right text-slate-400 text-xs"></i>
    <span class="text-slate-700">Periode {{ $period->name }}</span>
@endsection

@section('content')
<div class="mb-8 flex items-start justify-between">
    <div>
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">Periode {{ $period->name }}</h1>
            @if($period->is_active)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-brand-50 text-brand-700 text-xs font-bold rounded-lg border border-brand-100">
                    <div class="w-1.5 h-1.5 rounded-full bg-brand-500 animate-pulse"></div>
                    Aktif
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-lg border border-slate-200">
                    <i class="ph-fill ph-archive"></i>
                    Diarsipkan
                </span>
            @endif
        </div>
        <p class="text-sm text-slate-500 mt-1 font-medium">Mulai: {{ \Carbon\Carbon::parse($period->start_date)->translatedFormat('d M Y') }} {{ $period->end_date ? '— Selesai: ' . \Carbon\Carbon::parse($period->end_date)->translatedFormat('d M Y') : '' }}</p>
    </div>
    <a href="{{ route('super_admin.periods.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-brand-600 transition-colors mt-1.5">
        <i class="ph-bold ph-arrow-left"></i>
        Kembali
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Pintu 1: Struktur Kepengurusan --}}
    <a href="{{ route('super_admin.periods.structure', $period->id) }}" class="group bg-white rounded-3xl border border-slate-200 shadow-sm hover:border-brand-300 hover:shadow-md transition-all overflow-hidden flex flex-col relative">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="p-8 flex-1 relative z-10">
            <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-sm">
                <i class="ph-fill ph-users-three text-3xl"></i>
            </div>
            <h3 class="text-xl font-black text-slate-900 mb-2 group-hover:text-brand-600 transition-colors">1. Struktur Kepengurusan</h3>
            <p class="text-sm text-slate-500 leading-relaxed font-medium">
                Lihat hierarki kepengurusan lengkap mulai dari Dewan Pembina, Penasihat, Badan Pengurus Harian (BPH), hingga susunan anggota setiap divisi.
            </p>
        </div>
        <div class="px-8 py-5 bg-slate-50 border-t border-slate-100 text-sm font-bold text-slate-600 flex items-center justify-between group-hover:bg-brand-50 group-hover:text-brand-700 transition-colors relative z-10">
            <span>Buka Arsip Struktur</span>
            <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
        </div>
    </a>

    {{-- Pintu 2: Kinerja Divisi --}}
    <a href="{{ route('super_admin.periods.performance', $period->id) }}" class="group bg-white rounded-3xl border border-slate-200 shadow-sm hover:border-amber-300 hover:shadow-md transition-all overflow-hidden flex flex-col relative">
        <div class="absolute inset-0 bg-gradient-to-br from-amber-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="p-8 flex-1 relative z-10">
            <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-amber-500 group-hover:text-white transition-all shadow-sm">
                <i class="ph-fill ph-kanban text-3xl"></i>
            </div>
            <h3 class="text-xl font-black text-slate-900 mb-2 group-hover:text-amber-600 transition-colors">2. Kinerja & Riwayat Proker Divisi</h3>
            <p class="text-sm text-slate-500 leading-relaxed font-medium">
                Lihat daftar Program Kerja (Proker) yang telah dijalankan oleh masing-masing divisi, beserta rincian riwayat tugas (Kanban) dan pelaksana tugas.
            </p>
        </div>
        <div class="px-8 py-5 bg-slate-50 border-t border-slate-100 text-sm font-bold text-slate-600 flex items-center justify-between group-hover:bg-amber-50 group-hover:text-amber-700 transition-colors relative z-10">
            <span>Buka Arsip Kinerja</span>
            <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
        </div>
    </a>
</div>
@endsection
