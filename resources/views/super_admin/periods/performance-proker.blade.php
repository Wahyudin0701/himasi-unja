@extends('layouts.dashboard')

@section('title', 'Detail Riwayat Proker: ' . $proker->name)

@section('breadcrumbs')
    <a href="{{ route('super_admin.dashboard') }}" class="text-slate-500 hover:text-brand-600 transition-colors">Super Admin</a>
    <i class="ph-bold ph-caret-right text-slate-400 text-xs"></i>
    <a href="{{ route('super_admin.periods.index') }}" class="text-slate-500 hover:text-brand-600 transition-colors">Manajemen Periode</a>
    <i class="ph-bold ph-caret-right text-slate-400 text-xs"></i>
    <a href="{{ route('super_admin.periods.show', $period->id) }}" class="text-slate-500 hover:text-brand-600 transition-colors">Periode {{ $period->name }}</a>
    <i class="ph-bold ph-caret-right text-slate-400 text-xs"></i>
    <a href="{{ route('super_admin.periods.performance', $period->id) }}" class="text-slate-500 hover:text-brand-600 transition-colors">Kinerja Divisi</a>
    <i class="ph-bold ph-caret-right text-slate-400 text-xs"></i>
    <a href="{{ route('super_admin.periods.performance.division', ['period' => $period->id, 'division' => $proker->division_id]) }}" class="text-slate-500 hover:text-brand-600 transition-colors">{{ $proker->division->name }}</a>
    <i class="ph-bold ph-caret-right text-slate-400 text-xs"></i>
    <span class="text-slate-700 truncate max-w-[150px] inline-block align-bottom">{{ $proker->name }}</span>
@endsection

@section('content')
<div class="mb-8 flex items-start justify-between">
    <div>
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">{{ $proker->name }}</h1>
            @if($proker->status === 'completed')
                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-green-50 text-green-700 border border-green-200">
                    <i class="ph-bold ph-check-circle mr-1"></i>Selesai
                </span>
            @elseif($proker->status === 'cancelled')
                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-red-50 text-red-700 border border-red-200">
                    <i class="ph-bold ph-x-circle mr-1"></i>Batal
                </span>
            @endif
        </div>
        <p class="text-sm text-slate-500 mt-2 font-medium leading-relaxed max-w-3xl">
            {{ $proker->description ?? 'Tidak ada deskripsi rinci untuk program kerja ini.' }}
        </p>
    </div>
    <a href="{{ route('super_admin.periods.performance.division', ['period' => $period->id, 'division' => $proker->division_id]) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-brand-600 transition-colors mt-1.5 shrink-0">
        <i class="ph-bold ph-arrow-left"></i>
        Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- Card Info --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 lg:col-span-1 space-y-6">
        <div>
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Penanggung Jawab (PIC)</div>
            @if($proker->pic)
                <div class="flex items-center gap-3 mt-2">
                    <div class="w-10 h-10 rounded-full bg-brand-50 text-brand-700 flex items-center justify-center font-bold text-sm shrink-0">
                        {{ strtoupper(substr($proker->pic->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-900">{{ $proker->pic->name }}</div>
                        <div class="text-xs text-slate-500">{{ $proker->pic->email }}</div>
                    </div>
                </div>
            @else
                <div class="text-sm font-medium text-slate-500 italic">Belum ditentukan</div>
            @endif
        </div>
        <hr class="border-slate-100">
        <div>
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Tanggal Pelaksanaan</div>
            <div class="text-sm font-bold text-slate-900 mt-1">
                {{ $proker->start_date ? \Carbon\Carbon::parse($proker->start_date)->translatedFormat('d M Y') : '-' }} 
                <span class="text-slate-400 mx-1">s/d</span> 
                {{ $proker->end_date ? \Carbon\Carbon::parse($proker->end_date)->translatedFormat('d M Y') : '-' }}
            </div>
        </div>
        <hr class="border-slate-100">
        <div>
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Rencana Anggaran (RAB)</div>
            <div class="text-lg font-black text-slate-900 mt-1">
                Rp {{ number_format($proker->budget_plan, 0, ',', '.') }}
            </div>
        </div>
        @if($proker->status === 'cancelled')
        <hr class="border-slate-100">
        <div>
            <div class="text-xs font-bold text-red-400 uppercase tracking-widest mb-1">Alasan Pembatalan</div>
            <div class="text-sm font-medium text-red-700 mt-1 bg-red-50 p-3 rounded-xl border border-red-100">
                {{ $proker->cancellation_reason ?? 'Tidak ada alasan yang dicatat.' }}
            </div>
        </div>
        @endif
    </div>

    {{-- Daftar Tugas (Kanban History) --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden lg:col-span-2 flex flex-col">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <h2 class="text-base font-black text-slate-900">Riwayat Tugas (Kanban)</h2>
            <span class="text-xs font-bold text-slate-500 bg-slate-200 px-2.5 py-1 rounded-md">{{ $proker->tasks->count() }} Tugas</span>
        </div>
        
        <div class="flex-1 p-6">
            @if($proker->tasks->count() > 0)
                <div class="space-y-4">
                    @foreach($proker->tasks->sortByDesc('updated_at') as $task)
                    <div class="border border-slate-200 rounded-2xl p-4 hover:border-brand-300 transition-colors bg-white shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
                        <div class="flex items-start justify-between gap-4 mb-3">
                            <h3 class="text-sm font-bold text-slate-900">{{ $task->title }}</h3>
                            @if($task->status === 'done')
                                <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-50 text-green-700 border border-green-200">Selesai</span>
                            @elseif($task->status === 'in_progress')
                                <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">Dikerjakan</span>
                            @elseif($task->status === 'todo')
                                <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">To Do</span>
                            @else
                                <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">{{ $task->status }}</span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 mb-4 line-clamp-2">{{ $task->description ?? 'Tidak ada deskripsi tugas.' }}</p>
                        
                        <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                            <div class="flex items-center gap-2">
                                @if($task->pic)
                                    <div class="w-6 h-6 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-[10px] shrink-0">
                                        {{ strtoupper(substr($task->pic->name, 0, 1)) }}
                                    </div>
                                    <span class="text-xs font-bold text-slate-700">{{ $task->pic->name }}</span>
                                @else
                                    <span class="text-xs text-slate-400 italic">Tanpa PIC</span>
                                @endif
                            </div>
                            <div class="text-[10px] font-bold text-slate-400">
                                Diupdate: {{ $task->updated_at ? $task->updated_at->translatedFormat('d M Y') : '-' }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="h-full flex flex-col items-center justify-center py-12 text-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                        <i class="ph-fill ph-kanban text-2xl text-slate-400"></i>
                    </div>
                    <div class="text-sm font-bold text-slate-900 mb-1">Riwayat Tugas Kosong</div>
                    <div class="text-xs text-slate-500 max-w-xs mx-auto">Proker ini tidak memiliki catatan tugas atau kanban board selama pelaksanaannya.</div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
