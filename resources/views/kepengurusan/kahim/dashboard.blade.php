@extends('layouts.dashboard')

@section('title', 'Dashboard Eksekutif')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">Halo, {{ auth()->user()->name }}! 👋</h1>
        <p class="text-sm text-slate-500 mt-2">Selamat datang di Pusat Kendali Eksekutif HIMASI periode {{ $activePeriod->name ?? 'Aktif' }}.</p>
    </div>
</div>

@if(!$activePeriod)
<div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 mb-8 flex gap-4">
    <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center shrink-0">
        <i class="ph-bold ph-warning text-2xl"></i>
    </div>
    <div>
        <h3 class="text-lg font-bold text-amber-900">Belum Ada Periode Aktif</h3>
        <p class="text-sm text-amber-700 mt-1">Sistem mendeteksi belum ada periode kepengurusan yang berjalan. Beberapa statistik mungkin bernilai nol.</p>
    </div>
</div>
@endif

{{-- Kartu Statistik Makro --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-brand-300 transition-colors">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-4">
                <i class="ph-bold ph-users text-2xl"></i>
            </div>
            <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Total Pengurus</p>
            <h3 class="text-3xl font-black text-slate-900">{{ number_format($stats['total_anggota']) }} <span class="text-sm text-slate-400 font-bold">Orang</span></h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-brand-300 transition-colors">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center mb-4">
                <i class="ph-bold ph-buildings text-2xl"></i>
            </div>
            <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Total Divisi & BPH</p>
            <h3 class="text-3xl font-black text-slate-900">{{ number_format($stats['total_divisi']) }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-brand-300 transition-colors">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-4">
                <i class="ph-bold ph-wallet text-2xl"></i>
            </div>
            <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Saldo Kas Himasi</p>
            <h3 class="text-2xl font-black text-slate-900 mt-1">Rp {{ number_format($stats['saldo_kas'], 0, ',', '.') }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-brand-300 transition-colors">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center mb-4">
                <i class="ph-bold ph-briefcase text-2xl"></i>
            </div>
            <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Proker Berjalan</p>
            <h3 class="text-3xl font-black text-slate-900">{{ number_format($stats['proker_berjalan']) }}</h3>
        </div>
    </div>
</div>

{{-- Layout Kolom --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
    
    {{-- Kolom Utama Kiri (Proker & Aktivitas) --}}
    <div class="xl:col-span-2 space-y-8">
        
        {{-- Progress Program Kerja Keseluruhan --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 relative overflow-hidden">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-black text-slate-900">Pencapaian Program Kerja</h3>
                    <p class="text-sm text-slate-500 mt-1">Progres eksekusi proker secara kumulatif.</p>
                </div>
                <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center shrink-0">
                    <i class="ph-bold ph-target text-xl text-slate-600"></i>
                </div>
            </div>

            <div class="mb-4">
                <div class="flex justify-between items-end mb-2">
                    <span class="text-3xl font-black text-brand-600">{{ $prokerMetrics['progress_percentage'] }}%</span>
                    <span class="text-sm font-bold text-slate-500">Selesai ({{ $prokerMetrics['completed'] }} dari {{ $prokerMetrics['total'] }})</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden flex">
                    <div class="bg-brand-500 h-3" style="width: {{ $prokerMetrics['progress_percentage'] }}%"></div>
                    @if($prokerMetrics['total'] > 0)
                        <div class="bg-amber-400 h-3" style="width: {{ ($prokerMetrics['ongoing'] / $prokerMetrics['total']) * 100 }}%"></div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-center">
                    <div class="text-xs font-black text-slate-400 uppercase">Planning</div>
                    <div class="text-lg font-black text-slate-700">{{ $prokerMetrics['planning'] }}</div>
                </div>
                <div class="p-3 bg-amber-50 rounded-xl border border-amber-100 text-center">
                    <div class="text-xs font-black text-amber-500 uppercase">Ongoing</div>
                    <div class="text-lg font-black text-amber-700">{{ $prokerMetrics['ongoing'] }}</div>
                </div>
                <div class="p-3 bg-brand-50 rounded-xl border border-brand-100 text-center">
                    <div class="text-xs font-black text-brand-500 uppercase">Completed</div>
                    <div class="text-lg font-black text-brand-700">{{ $prokerMetrics['completed'] }}</div>
                </div>
                <div class="p-3 bg-red-50 rounded-xl border border-red-100 text-center">
                    <div class="text-xs font-black text-red-500 uppercase">Cancelled</div>
                    <div class="text-lg font-black text-red-700">{{ $prokerMetrics['cancelled'] }}</div>
                </div>
            </div>
        </div>

        {{-- Activity Feed --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-black text-slate-900">Aktivitas Divisi Terbaru</h3>
                <a href="{{ route('kepengurusan.kahim.activities') }}" class="text-sm font-bold text-brand-600 hover:text-brand-700 hover:underline transition-all">Lihat Semua &rarr;</a>
            </div>
            <div class="space-y-6">
                @forelse($recentActivities as $log)
                    <div class="flex gap-4 group">
                        <div class="shrink-0 relative">
                            <div class="w-10 h-10 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center font-bold relative z-10 overflow-hidden shadow-sm">
                                @if($log->author->avatar)
                                    @php
                                        $avatarPath = ltrim($log->author->avatar, '/');
                                        $avatarUrl = $avatarPath ? asset('storage/' . $avatarPath) : null;
                                    @endphp
                                    @if($avatarUrl)
                                        <img src="{{ $avatarUrl }}" alt="{{ $log->author->name }}" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($log->author->name, 0, 1) }}
                                    @endif
                                @else
                                    {{ substr($log->author->name, 0, 1) }}
                                @endif
                            </div>
                            @if(!$loop->last)
                            <div class="absolute top-10 left-1/2 -translate-x-1/2 w-0.5 h-full bg-slate-100"></div>
                            @endif
                        </div>
                        <div class="pb-2 w-full">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm text-slate-800">
                                        <span class="font-bold">{{ $log->author->name }}</span> 
                                        memperbarui progres <span class="font-semibold">"{{ $log->workProgram->name }}"</span>
                                    </p>
                                    <p class="text-xs font-bold text-slate-400 mt-0.5">{{ $log->workProgram->division->name }} • {{ $log->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-xs font-bold">{{ $log->progress_update }}%</span>
                            </div>
                            <div class="mt-2 p-3 bg-slate-50 rounded-xl border border-slate-100 text-sm text-slate-600 line-clamp-2">
                                {{ $log->content }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="ph-bold ph-activity text-2xl text-slate-400"></i>
                        </div>
                        <p class="text-sm font-bold text-slate-500">Belum ada aktivitas terbaru dari divisi.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Kolom Kanan (Agenda / Event) --}}
    <div class="space-y-8">
        <div class="bg-gradient-to-br from-brand-900 via-brand-800 to-indigo-900 rounded-2xl shadow-sm p-6 text-white relative overflow-hidden border border-brand-700/50">
            <div class="absolute right-0 top-0 opacity-10 pointer-events-none">
                <i class="ph-fill ph-calendar-star text-9xl -mr-6 -mt-6"></i>
            </div>
            
            <div class="flex justify-between items-center mb-6 relative z-10">
                <h3 class="text-lg font-black">Agenda Terdekat</h3>
                <a href="{{ route('kepengurusan.kahim.agendas') }}" class="text-xs font-bold text-slate-300 hover:text-white transition-colors">Lihat Semua &rarr;</a>
            </div>
            
            <div class="space-y-4 relative z-10">
                @forelse($upcomingEvents as $event)
                    <div class="bg-white/10 rounded-xl p-4 border border-white/10 hover:bg-white/20 transition-colors">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-bold text-sm leading-tight">{{ $event->name }}</h4>
                            @if($event->status === 'ongoing')
                                <span class="px-2 py-0.5 bg-emerald-500/20 text-emerald-300 rounded text-[10px] font-black uppercase">Berjalan</span>
                            @else
                                <span class="px-2 py-0.5 bg-amber-500/20 text-amber-300 rounded text-[10px] font-black uppercase">Persiapan</span>
                            @endif
                        </div>
                        
                        <div class="flex items-center gap-2 text-xs text-slate-300 mt-2">
                            <i class="ph-bold ph-calendar-blank"></i>
                            <span>{{ $event->event_date->translatedFormat('d M Y') }}</span>
                        </div>
                        
                        @if($event->location)
                        <div class="flex items-center gap-2 text-xs text-slate-400 mt-1">
                            <i class="ph-bold ph-map-pin"></i>
                            <span class="truncate">{{ $event->location }}</span>
                        </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-6 border border-dashed border-white/20 rounded-xl">
                        <i class="ph-bold ph-calendar-check text-2xl text-slate-500 mb-2"></i>
                        <p class="text-sm text-slate-400">Tidak ada agenda besar dalam waktu dekat.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
