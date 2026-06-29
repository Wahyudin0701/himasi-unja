@extends('layouts.dashboard')

@section('title', 'Progres Program Kerja')

@section('breadcrumbs')
    <span class="text-slate-700">Kepengurusan</span>
@endsection

@section('content')

{{-- ===== HEADER ===== --}}
<div class="mb-8 flex flex-row items-start justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight mb-2">
            Progres : {{ $proker->name }}
        </h1>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-brand-50 text-brand-700 border border-brand-200">
                {{ $proker->type === 'event' ? 'Event Kepanitiaan' : 'Non-Event' }}
            </span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                @if($proker->status === 'ongoing') bg-emerald-50 text-emerald-700 border border-emerald-200
                @elseif($proker->status === 'planning') bg-brand-50 text-brand-700 border border-brand-200
                @elseif($proker->status === 'completed') bg-slate-100 text-slate-600 border border-slate-200
                @else bg-rose-50 text-rose-700 border border-rose-200
                @endif">
                {{ $proker->status }}
            </span>
        </div>
    </div>
    <div class="flex items-center shrink-0">
        <a href="{{ route('kepengurusan.kadiv.proker.show', $proker->id) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-brand-600 transition-colors mt-1">
            <i class="ph-bold ph-arrow-left"></i>
            Kembali
        </a>
    </div>
</div>

{{-- ===== OVERALL PROGRESS CARD ===== --}}
<div class="bg-gradient-to-br from-brand-600 to-brand-800 text-white rounded-2xl border border-brand-700 shadow-lg p-6 sm:p-8 mb-8 relative overflow-hidden">

    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 relative z-10">
        
        {{-- Left: Progress Value & Bar --}}
        <div class="flex-1 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-4">
                <div>
                    <h3 class="text-xs font-bold text-brand-200 uppercase tracking-widest">
                        Penyelesaian Proker
                    </h3>
                    <div class="flex items-baseline gap-2 mt-1">
                        <span class="text-4xl font-black text-white tracking-tight">{{ $progressPercentage }}%</span>
                        <span class="text-sm font-bold text-brand-200">dari {{ $totalTasks }} tugas</span>
                    </div>
                </div>
                
                {{-- Right Side Info --}}
                <div class="sm:text-right flex flex-row sm:flex-col items-start sm:items-end gap-6 sm:gap-3">
                    @php
                        $picName = 'Belum di set';
                        $picDetails = null;
                        $picLabel = $proker->type === 'event' ? 'Ketua Pelaksana' : 'Penanggung Jawab';
                        if ($proker->type === 'event') {
                            $ev = \App\Models\Kepanitiaan\Event::where('work_program_id', $proker->id)->first();
                            if ($ev) {
                                $ketupel = \App\Models\Kepanitiaan\EventCommittee::where('event_id', $ev->id)
                                    ->whereHas('role', fn($q) => $q->where('slug', 'ketua-pelaksana'))
                                    ->with('user')
                                    ->first();
                                if ($ketupel && $ketupel->user) {
                                    $picName = $ketupel->user->name;
                                    $picDetails = 'NIM ' . $ketupel->user->nim . ' (' . $ketupel->user->angkatan . ')';
                                }
                            }
                        } else {
                            if ($proker->pic) {
                                $picName = $proker->pic->name;
                                $picDetails = 'NIM ' . $proker->pic->nim . ' (' . $proker->pic->angkatan . ')';
                            }
                        }
                    @endphp
                    <div class="text-left sm:text-right">
                        <p class="text-[10px] text-brand-200 font-bold uppercase tracking-wider mb-0.5">{{ $picLabel }}</p>
                        <p class="text-sm font-semibold text-white">{{ $picName }}</p>
                        @if($picDetails)
                        <p class="text-[10px] font-medium text-brand-200 mt-0.5">{{ $picDetails }}</p>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Progress Bar --}}
            <div class="w-full bg-brand-900/50 rounded-full h-3 overflow-hidden border border-brand-500/30 p-[1px]">
                <div class="bg-white h-full rounded-full transition-all duration-1000" style="width: {{ $progressPercentage }}%"></div>
            </div>
            
            {{-- Legend --}}
            <div class="flex flex-wrap items-center gap-x-6 gap-y-2 pt-2 text-xs font-bold text-brand-200">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>{{ $completedTasks }} Selesai</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    <span>{{ $waitingTasks }} Menunggu Review</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                    <span>{{ $revisiTasks }} Perlu Revisi</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                    <span>{{ $todoTasks }} Belum</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mb-8">
    {{-- ===== DIVISION-WISE PROGRESS ===== --}}
    <div class="space-y-6">
        @if($event && $divisionProgress->count() > 0)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 flex flex-col">
                <div class="pb-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-base font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i class="ph-bold ph-chart-bar text-brand-500 text-xl"></i>
                        Progres Divisi Kepanitiaan
                    </h3>
                </div>
                
                <div class="pt-6 space-y-4">
                    @foreach($divisionProgress as $divProg)
                        <a href="{{ route('kepengurusan.kadiv.proker.division-progress', [$proker->id, $divProg['division']->id]) }}" class="block bg-slate-50 hover:bg-brand-50 p-4 rounded-xl border border-slate-100 hover:border-brand-200 transition-colors cursor-pointer group">
                            <div class="flex items-center justify-between text-xs sm:text-sm">
                                <div>
                                    <h4 class="font-black text-slate-900 group-hover:text-brand-700 uppercase tracking-wide text-xs">{{ $divProg['division']->name }}</h4>
                                    @if($divProg['co'])
                                        <p class="text-[10px] font-bold text-slate-400 mt-1">CO: <span class="text-slate-600 font-black">{{ $divProg['co']->name }}</span></p>
                                        <p class="text-[9px] font-medium text-slate-400 mt-0.5">NIM {{ $divProg['co']->nim }} ({{ $divProg['co']->angkatan }})</p>
                                    @else
                                        <p class="text-[10px] font-bold text-slate-400 mt-1">CO: <span class="text-slate-600 font-black">Belum di set</span></p>
                                    @endif
                                </div>
                                <span class="font-black text-brand-600 bg-brand-50 group-hover:bg-brand-100 border border-brand-100 group-hover:border-brand-300 px-2 py-0.5 rounded-lg text-xs transition-colors">{{ $divProg['percentage'] }}% ({{ $divProg['completed'] }}/{{ $divProg['total'] }} Tugas)</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 text-center">
                <div class="w-16 h-16 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mx-auto mb-4">
                    <i class="ph-fill ph-chart-bar text-slate-300 text-2xl"></i>
                </div>
                <h4 class="text-base font-bold text-slate-900 mb-1">Divisi Belum Terbentuk</h4>
                <p class="text-xs text-slate-500 font-medium max-w-sm mx-auto">Progres divisi belum tersedia karena divisi kepanitiaan belum dibentuk.</p>
            </div>
        @endif
    </div>
</div>

@endsection
