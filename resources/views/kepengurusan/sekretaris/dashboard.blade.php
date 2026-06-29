@extends('layouts.dashboard')

@section('title', 'Dasbor Sekretaris')

@section('breadcrumbs')
    <span class="text-slate-700">Kepengurusan</span>
@endsection

@section('content')

{{-- ===== HEADER ===== --}}
<div class="mb-8">
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">
                Dasbor Sekretaris HIMA
            </h1>
            <p class="text-sm text-slate-500 mt-1.5 font-medium">Selamat datang, {{ auth()->user()->name }}! Kelola data pengurus dan divisi di sini.</p>
        </div>
        @if($activePeriod)
            <div class="flex items-center gap-3">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-brand-50 border border-brand-100 text-brand-700 rounded-lg text-sm font-bold shadow-sm">
                    <div class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></div>
                    Periode Aktif: {{ $activePeriod->name }}
                </div>
            </div>
        @endif
    </div>
</div>

@if(!$activePeriod)
    <div class="bg-amber-50 border border-amber-200 text-amber-700 p-4 rounded-xl mb-6 flex gap-3 text-sm">
        <i class="ph-fill ph-warning-circle text-lg shrink-0"></i>
        <p><strong>Belum ada periode kepengurusan yang aktif.</strong> Harap minta Admin untuk mengaktifkan salah satu periode terlebih dahulu.</p>
    </div>
@else
    {{-- ===== STATISTIK ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        {{-- Total Divisi --}}
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-brand-300 transition-colors">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-brand-50 rounded-full group-hover:bg-brand-100 transition-colors"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-slate-500 mb-1">Total Divisi</p>
                    <h3 class="text-3xl font-black text-slate-900">{{ $totalDivisions }}</h3>
                </div>
                <div class="w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center text-brand-600">
                    <i class="ph-bold ph-users-three text-2xl"></i>
                </div>
            </div>
        </div>

        {{-- Total Pengurus --}}
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-teal-300 transition-colors">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-teal-50 rounded-full group-hover:bg-teal-100 transition-colors"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-slate-500 mb-1">Total Pengurus</p>
                    <h3 class="text-3xl font-black text-slate-900">{{ $totalMembers }}</h3>
                </div>
                <div class="w-12 h-12 bg-teal-50 rounded-xl flex items-center justify-center text-teal-600">
                    <i class="ph-bold ph-identification-card text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== QUICK ACTIONS & LIST DIVISI ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Distribusi Pengurus per Divisi</h2>
                        <p class="text-xs text-slate-500 mt-1">Total anggota untuk setiap divisi pada periode ini</p>
                    </div>
                </div>
                
                @if($divisions->count() > 0)
                    <div class="divide-y divide-slate-100">
                        @foreach($divisions as $divisi)
                            <div class="p-4 sm:p-6 hover:bg-slate-50 transition-colors flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900">{{ $divisi->name }}</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Tipe: <span class="uppercase font-semibold">{{ $divisi->type }}</span></p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-brand-50 text-brand-700 text-xs font-bold rounded-lg">
                                        {{ $divisi->members_count }} Anggota
                                    </span>
                                    <a href="{{ route('kepengurusan.sekretaris.members.index', ['division_id' => $divisi->id]) }}" class="text-slate-400 hover:text-brand-600 transition-colors">
                                        <i class="ph-bold ph-arrow-right text-lg"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="ph-bold ph-users-three text-2xl text-slate-400"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 mb-1">Belum ada divisi</h3>
                        <p class="text-xs text-slate-500">Divisi belum dibuat untuk periode ini.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-gradient-to-br from-brand-600 to-brand-800 rounded-2xl p-6 text-white relative overflow-hidden shadow-lg shadow-brand-500/20">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                <div class="relative z-10">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-white mb-4 backdrop-blur-sm">
                        <i class="ph-bold ph-user-plus text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Input Data Pengurus</h3>
                    <p class="text-sm text-brand-100 mb-6 leading-relaxed">Tambahkan pengurus baru ke dalam divisi dan sistem akan otomatis membuatkan akun login mereka.</p>
                    <a href="{{ route('kepengurusan.sekretaris.members.create') }}" class="block w-full text-center bg-white text-brand-600 hover:bg-brand-50 px-4 py-2.5 rounded-xl text-sm font-bold transition-colors">
                        Tambah Pengurus
                    </a>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm text-center">
                 <h3 class="text-sm font-bold text-slate-900 mb-2">Kelola Semua Pengurus</h3>
                 <p class="text-xs text-slate-500 mb-4">Lihat, cari, dan ubah data pengurus secara keseluruhan.</p>
                 <a href="{{ route('kepengurusan.sekretaris.members.index') }}" class="block w-full text-center border border-slate-200 text-slate-700 hover:bg-slate-50 px-4 py-2.5 rounded-xl text-sm font-bold transition-colors">
                     Lihat Daftar Pengurus
                 </a>
            </div>
        </div>
    </div>
@endif

@endsection
