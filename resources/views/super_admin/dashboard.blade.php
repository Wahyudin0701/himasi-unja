@extends('layouts.dashboard')

@section('title', 'Dasbor Super Admin')

@section('breadcrumbs')
    <span class="text-slate-700">Super Admin</span>
@endsection

@section('content')
<div class="mb-8">
    <div class="flex items-center gap-4 mb-2">
        <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center shrink-0 shadow-sm border border-indigo-200">
            <i class="ph-bold ph-shield-check text-2xl"></i>
        </div>
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">Dasbor Super Admin</h1>
            <p class="text-sm text-slate-500 mt-1 font-medium">Pusat kendali utama sistem manajemen HIMASI.</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    {{-- Status Periode --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
        <div class="p-6 sm:p-8 flex-1">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">
                    <i class="ph-fill ph-calendar-blank text-xl"></i>
                </div>
                <h3 class="font-bold text-slate-900">Periode Aktif</h3>
            </div>
            
            @if($activePeriod)
                <div class="text-3xl font-black text-slate-900 mb-2">{{ $activePeriod->name }}</div>
                <div class="text-sm text-slate-500">Mulai: {{ \Carbon\Carbon::parse($activePeriod->start_date)->translatedFormat('d M Y') }}</div>
            @else
                <div class="text-xl font-bold text-slate-500 mb-2">Tidak ada periode aktif</div>
            @endif
        </div>
        <div class="bg-slate-50 border-t border-slate-100 p-4 sm:px-8">
            <a href="{{ route('super_admin.periods.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-brand-600 hover:text-brand-700 transition-colors">
                Kelola Periode Kepengurusan <i class="ph-bold ph-arrow-right"></i>
            </a>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
        <div class="p-6 sm:p-8 flex-1">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i class="ph-fill ph-chart-bar text-xl"></i>
                </div>
                <h3 class="font-bold text-slate-900">Statistik Sistem</h3>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Periode</div>
                    <div class="text-2xl font-black text-slate-900">{{ $totalPeriods }}</div>
                </div>
                <div>
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Divisi Aktif</div>
                    <div class="text-2xl font-black text-slate-900">{{ $totalDivisions }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
