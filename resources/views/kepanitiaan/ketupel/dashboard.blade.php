@extends('layouts.dashboard')

@section('title', 'Dashboard Ketupel')

@section('breadcrumbs')
    <span>Kepanitiaan</span>
    <i class="ph ph-caret-right text-[9px]"></i>
    <span class="text-slate-600">Ketua Pelaksana</span>
@endsection

@section('content')

{{-- ===== HEADER GREETING ===== --}}
<div class="mb-8">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-xs font-semibold text-brand-500 uppercase tracking-widest mb-1">Kepanitiaan</p>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">
                Selamat datang, <span class="gradient-text">{{ explode(' ', $user->name)[0] }}</span> 👋
            </h1>
            <p class="text-sm text-slate-500 mt-1.5 font-medium">Berikut ringkasan semua acara yang Anda ketuai.</p>
        </div>
        <div class="hidden sm:flex items-center gap-2 bg-white border border-slate-200 rounded-2xl px-4 py-3 shadow-sm">
            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
            <span class="text-xs font-semibold text-slate-700">{{ $activeEvents }} Acara Aktif</span>
        </div>
    </div>
</div>

{{-- ===== STATS CARDS ===== --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

    {{-- Total Acara --}}
    <div class="stat-card bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center mb-4">
            <i class="ph-fill ph-calendar-star text-xl text-brand-500"></i>
        </div>
        <p class="text-3xl font-black text-slate-900">{{ $totalEvents }}</p>
        <p class="text-xs text-slate-500 font-semibold mt-1 uppercase tracking-wide">Total Acara</p>
    </div>

    {{-- Acara Aktif --}}
    <div class="stat-card bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center mb-4">
            <i class="ph-fill ph-play-circle text-xl text-emerald-500"></i>
        </div>
        <p class="text-3xl font-black text-slate-900">{{ $activeEvents }}</p>
        <p class="text-xs text-slate-500 font-semibold mt-1 uppercase tracking-wide">Acara Aktif</p>
    </div>

    {{-- Total Divisi --}}
    <div class="stat-card bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-violet-50 flex items-center justify-center mb-4">
            <i class="ph-fill ph-tree-structure text-xl text-violet-500"></i>
        </div>
        <p class="text-3xl font-black text-slate-900">{{ $totalDivisions }}</p>
        <p class="text-xs text-slate-500 font-semibold mt-1 uppercase tracking-wide">Total Divisi</p>
    </div>

    {{-- Total Panitia --}}
    <div class="stat-card bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center mb-4">
            <i class="ph-fill ph-users text-xl text-orange-500"></i>
        </div>
        <p class="text-3xl font-black text-slate-900">{{ $totalMembers }}</p>
        <p class="text-xs text-slate-500 font-semibold mt-1 uppercase tracking-wide">Total Panitia</p>
    </div>

</div>

{{-- ===== MY EVENTS TABLE ===== --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

    {{-- Header --}}
    <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
        <div>
            <h2 class="text-base font-bold text-slate-900">Acara Saya</h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Daftar acara yang Anda ketuai</p>
        </div>
        <span class="text-xs font-bold text-brand-500 bg-brand-50 px-3 py-1.5 rounded-full border border-brand-100">
            {{ $totalEvents }} Acara
        </span>
    </div>

    {{-- Event List --}}
    <div class="divide-y divide-slate-50">
        @forelse($myEvents as $event)
        <div class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50/70 transition-colors group">

            {{-- Status indicator + icon --}}
            <div class="shrink-0 w-11 h-11 rounded-xl flex items-center justify-center text-lg
                @if($event->status === 'ongoing') bg-emerald-50 text-emerald-600
                @elseif($event->status === 'planning') bg-brand-50 text-brand-600
                @elseif($event->status === 'preparation') bg-amber-50 text-amber-600
                @elseif($event->status === 'completed') bg-slate-100 text-slate-400
                @else bg-rose-50 text-rose-500
                @endif">
                <i class="ph-fill ph-calendar-star"></i>
            </div>

            {{-- Event info --}}
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-slate-900 truncate">{{ $event->name }}</p>
                <div class="flex items-center gap-3 mt-0.5">
                    <span class="text-xs text-slate-400 font-medium flex items-center gap-1">
                        <i class="ph ph-calendar text-[10px]"></i>
                        {{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->isoFormat('D MMM YYYY') : 'TBD' }}
                    </span>
                    <span class="text-slate-300">·</span>
                    <span class="text-xs text-slate-400 font-medium flex items-center gap-1">
                        <i class="ph ph-tree-structure text-[10px]"></i>
                        {{ $event->divisions->count() }} divisi
                    </span>
                    <span class="text-slate-300">·</span>
                    <span class="text-xs text-slate-400 font-medium flex items-center gap-1">
                        <i class="ph ph-users text-[10px]"></i>
                        {{ $event->committees->count() }} panitia
                    </span>
                </div>
            </div>

            {{-- Status badge --}}
            <div class="shrink-0 hidden sm:block">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold
                    @if($event->status === 'ongoing') bg-emerald-50 text-emerald-700 border border-emerald-200
                    @elseif($event->status === 'planning') bg-brand-50 text-brand-700 border border-brand-200
                    @elseif($event->status === 'preparation') bg-amber-50 text-amber-700 border border-amber-200
                    @elseif($event->status === 'completed') bg-slate-100 text-slate-500 border border-slate-200
                    @else bg-rose-50 text-rose-700 border border-rose-200
                    @endif">
                    <span class="w-1.5 h-1.5 rounded-full
                        @if($event->status === 'ongoing') bg-emerald-500
                        @elseif($event->status === 'planning') bg-brand-500
                        @elseif($event->status === 'preparation') bg-amber-500
                        @elseif($event->status === 'completed') bg-slate-400
                        @else bg-rose-500
                        @endif"></span>
                    {{ ucfirst($event->status) }}
                </span>
            </div>

            {{-- Action buttons --}}
            <div class="shrink-0 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                <a href="{{ route('kepanitiaan.ketupel.manage-team', $event) }}"
                   class="p-2 rounded-xl text-slate-400 hover:text-brand-600 hover:bg-brand-50 transition-colors"
                   title="Kelola Tim">
                    <i class="ph ph-gear text-base"></i>
                </a>
                <a href="{{ route('events.show', $event) }}"
                   class="p-2 rounded-xl text-slate-400 hover:text-brand-600 hover:bg-brand-50 transition-colors"
                   title="Lihat Detail">
                    <i class="ph ph-arrow-right text-base"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="flex flex-col items-center justify-center py-16 text-center px-6">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                <i class="ph ph-calendar-x text-3xl text-slate-300"></i>
            </div>
            <h3 class="text-sm font-bold text-slate-700 mb-1">Belum ada acara</h3>
            <p class="text-xs text-slate-400 max-w-xs">Anda belum ditugaskan sebagai Ketua Pelaksana di acara manapun.</p>
        </div>
        @endforelse
    </div>
</div>

@endsection

@push('styles')
<style>
    .gradient-text {
        background: linear-gradient(135deg, #635BFF, #818cf8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>
@endpush
