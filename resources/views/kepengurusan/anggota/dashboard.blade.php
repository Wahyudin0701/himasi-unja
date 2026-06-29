@extends('layouts.dashboard')

@section('title', 'Dashboard Anggota Divisi')

@section('breadcrumbs')
    <span class="text-slate-700">Kepengurusan</span>
@endsection

@section('content')

{{-- ===== HEADER GREETING ===== --}}
<div class="mb-6">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-xs font-semibold text-emerald-500 uppercase tracking-widest mb-1">{{ $division->name }}</p>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">
                Selamat datang, <span class="gradient-text">{{ explode(' ', $user->name)[0] }}</span> 👋
            </h1>
            <p class="text-sm text-slate-500 mt-1.5 font-medium">Berikut ringkasan divisi dan program kerja Anda.</p>
        </div>
        <div class="hidden sm:flex items-center gap-2 bg-white border border-slate-200 rounded-2xl px-4 py-3 shadow-sm">
            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
            <span class="text-xs font-semibold text-slate-700">{{ $activeProkers }} Proker Aktif</span>
        </div>
    </div>
</div>

{{-- ===== BANNER KEPANITIAAN AKTIF ===== --}}
@if($activeCommitteeRoles->count() > 0)
<div class="mb-6 space-y-3">
    @foreach($activeCommitteeRoles as $eventId => $roles)
        @php
            $firstRole = $roles->first();
            $event = $firstRole->event;
            $isKetupel = $roles->contains(fn($r) => in_array($r->role?->slug, ['ketua-pelaksana', 'wakil-ketua-pelaksana']));
            $isCO = $roles->contains(fn($r) => $r->role?->slug === 'co-divisi');
            
            if ($isKetupel) {
                $roleLabel = 'Ketua Pelaksana';
                $dashboardRoute = route('kepanitiaan.ketupel.dashboard');
                $badgeColor = 'bg-rose-100 text-rose-700 border-rose-200';
                $bannerColor = 'bg-rose-50 border-rose-200';
                $iconColor = 'text-rose-500 bg-rose-100';
                $btnColor = 'bg-rose-600 hover:bg-rose-700';
                $icon = 'ph-crown-simple';
            } elseif ($isCO) {
                $roleLabel = 'Koordinator Divisi';
                $dashboardRoute = route('kepanitiaan.co.dashboard');
                $badgeColor = 'bg-violet-100 text-violet-700 border-violet-200';
                $bannerColor = 'bg-violet-50 border-violet-200';
                $iconColor = 'text-violet-500 bg-violet-100';
                $btnColor = 'bg-violet-600 hover:bg-violet-700';
                $icon = 'ph-users-three';
            } else {
                $roleLabel = $firstRole->role?->name ?? 'Panitia';
                $dashboardRoute = route('kepanitiaan.anggota.dashboard');
                $badgeColor = 'bg-amber-100 text-amber-700 border-amber-200';
                $bannerColor = 'bg-amber-50 border-amber-200';
                $iconColor = 'text-amber-500 bg-amber-100';
                $btnColor = 'bg-amber-600 hover:bg-amber-700';
                $icon = 'ph-ticket';
            }
        @endphp
        <div class="flex items-center gap-4 px-5 py-4 rounded-2xl border {{ $bannerColor }} shadow-sm">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $iconColor }}">
                <i class="ph-fill {{ $icon }} text-xl"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap mb-0.5">
                    <p class="text-sm font-black text-slate-900 truncate">{{ $event->name }}</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $badgeColor }}">
                        {{ $roleLabel }}
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 uppercase">
                        {{ $event->status }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 font-medium">Anda memiliki peran aktif di kepanitiaan event ini.</p>
            </div>
            <a href="{{ $dashboardRoute }}" class="shrink-0 inline-flex items-center gap-1.5 px-3 py-2 {{ $btnColor }} text-white text-xs font-bold rounded-xl transition-colors shadow-sm">
                <i class="ph-bold ph-arrow-right"></i>
                <span class="hidden sm:inline">Ke Dashboard</span>
            </a>
        </div>
    @endforeach
</div>
@endif

{{-- ===== STATS CARDS ===== --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

    {{-- Total Anggota --}}
    <div class="stat-card bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center mb-4">
            <i class="ph-fill ph-users text-xl text-brand-500"></i>
        </div>
        <p class="text-3xl font-black text-slate-900">{{ $totalMembers }}</p>
        <p class="text-xs text-slate-500 font-semibold mt-1 uppercase tracking-wide">Total Anggota</p>
    </div>

    {{-- Total Proker --}}
    <div class="stat-card bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-violet-50 flex items-center justify-center mb-4">
            <i class="ph-fill ph-kanban text-xl text-violet-500"></i>
        </div>
        <p class="text-3xl font-black text-slate-900">{{ $totalProkers }}</p>
        <p class="text-xs text-slate-500 font-semibold mt-1 uppercase tracking-wide">Total Proker</p>
    </div>

    {{-- Proker Aktif --}}
    <div class="stat-card bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center mb-4">
            <i class="ph-fill ph-play-circle text-xl text-emerald-500"></i>
        </div>
        <p class="text-3xl font-black text-slate-900">{{ $activeProkers }}</p>
        <p class="text-xs text-slate-500 font-semibold mt-1 uppercase tracking-wide">Proker Aktif</p>
    </div>

    {{-- Anggaran (Jika ada) --}}
    <div class="stat-card bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center mb-4">
            <i class="ph-fill ph-wallet text-xl text-amber-500"></i>
        </div>
        <p class="text-2xl font-black text-slate-900">Rp{{ number_format($totalBudget, 0, ',', '.') }}</p>
        <p class="text-xs text-slate-500 font-semibold mt-1 uppercase tracking-wide">Total Anggaran</p>
    </div>

</div>

{{-- ===== TABEL PROKER & ANGGOTA ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Daftar Program Kerja --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <h2 class="text-base font-bold text-slate-900">Program Kerja</h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Daftar proker divisi Anda</p>
            </div>
            <i class="ph-fill ph-kanban text-2xl text-violet-500"></i>
        </div>
        
        <div class="divide-y divide-slate-50 flex-1 overflow-y-auto">
            @forelse($prokers as $proker)
            <div class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50/70 transition-colors">
                <div class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center text-lg
                    @if($proker->status === 'ongoing') bg-emerald-50 text-emerald-600
                    @elseif($proker->status === 'planning') bg-brand-50 text-brand-600
                    @elseif($proker->status === 'completed') bg-slate-100 text-slate-400
                    @else bg-amber-50 text-amber-600
                    @endif">
                    <i class="ph-fill ph-kanban"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-900 truncate">{{ $proker->name }}</p>
                    <p class="text-xs text-slate-500 font-medium mt-0.5 truncate">{{ $proker->description ?: 'Tidak ada deskripsi' }}</p>
                </div>
                <div class="shrink-0">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                        @if($proker->status === 'ongoing') bg-emerald-100 text-emerald-700
                        @elseif($proker->status === 'planning') bg-brand-100 text-brand-700
                        @elseif($proker->status === 'completed') bg-slate-100 text-slate-600
                        @else bg-amber-100 text-amber-700
                        @endif">
                        {{ $proker->status }}
                    </span>
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center py-10 text-center px-6">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center mb-3">
                    <i class="ph ph-kanban text-2xl text-slate-300"></i>
                </div>
                <p class="text-sm font-bold text-slate-700">Belum ada proker</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Daftar Anggota Divisi --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <h2 class="text-base font-bold text-slate-900">Anggota Divisi</h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Daftar anggota di {{ $division->singkatan ?? 'divisi ini' }}</p>
            </div>
            <i class="ph-fill ph-users-three text-2xl text-brand-500"></i>
        </div>
        
        <div class="divide-y divide-slate-50 flex-1 overflow-y-auto">
            @forelse($members as $member)
            <div class="flex items-center justify-between px-6 py-4 hover:bg-slate-50/70 transition-colors">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-full bg-brand-50 flex items-center justify-center text-brand-700 font-bold border border-brand-100 shrink-0">
                        {{ strtoupper(substr($member->user->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-slate-900 truncate">{{ $member->user->name }}</p>
                        <p class="text-[11px] text-slate-500 font-semibold uppercase tracking-wide mt-0.5">{{ $member->orgPosition->name ?? 'Anggota' }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center py-10 text-center px-6">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center mb-3">
                    <i class="ph ph-users-slash text-2xl text-slate-300"></i>
                </div>
                <p class="text-sm font-bold text-slate-700">Belum ada anggota</p>
            </div>
            @endforelse
        </div>
    </div>

</div>

@endsection

@push('styles')
<style>
    .gradient-text {
        background: linear-gradient(135deg, #10b981, #34d399); /* Emerald gradient for Kadiv */
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>
@endpush
