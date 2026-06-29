@extends('layouts.dashboard')

@section('title', 'Kanban Proker')

@section('breadcrumbs')
    <span class="text-slate-700">Program Kerja Non-Event</span>
@endsection

@section('content')

    <!-- Greeting -->
    <div class="space-y-6">
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-brand-500 uppercase tracking-widest mb-1">Anggota Divisi</p>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">
                Kanban <span class="gradient-text">Proker</span>
            </h1>
            <p class="text-sm text-slate-500 mt-1.5 font-medium">Pantau dan selesaikan tugas-tugas program kerja non-event Anda.</p>
        </div>
    </div>

    <!-- Kanban Board Header -->
    <div>
        <div class="flex items-center justify-between py-5 border-t border-slate-200">
            <h3 class="text-xl font-bold text-slate-900 flex items-center gap-2"><i class="ph-fill ph-kanban text-brand-500"></i> Papan Tugas</h3>
        </div>
    </div>

    <!-- Global Kanban Headers (Desktop Only) -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm mb-8">
        <div class="hidden lg:grid grid-cols-3 divide-x divide-slate-200 border-b border-slate-200 bg-slate-50/80">
            <div class="p-4 text-center">
                <h4 class="text-xs font-extrabold text-slate-700 uppercase tracking-widest flex items-center justify-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-slate-300"></div> TO DO
                </h4>
            </div>
            <div class="p-4 text-center">
                <h4 class="text-xs font-extrabold text-slate-700 uppercase tracking-widest flex items-center justify-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-blue-400"></div> IN PROGRESS / REVIEW
                </h4>
            </div>
            <div class="p-4 text-center">
                <h4 class="text-xs font-extrabold text-slate-700 uppercase tracking-widest flex items-center justify-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-emerald-400"></div> COMPLETED
                </h4>
            </div>
        </div>

        <!-- Columns Container -->
        <div class="grid grid-cols-1 lg:grid-cols-3 divide-y lg:divide-y-0 lg:divide-x divide-slate-200 bg-slate-50/20">
            
            <!-- Col 1: TO DO -->
            <div class="p-4 flex flex-col">
                <div class="lg:hidden flex items-center gap-2 mb-4 px-1">
                    <div class="w-2 h-2 shrink-0 rounded-full bg-slate-300"></div>
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-widest">TO DO</h4>
                </div>
                <div class="space-y-3 flex-1">
                    @forelse($tasks->where('status', 'todo') as $task)
                    <div class="bg-white border border-slate-200 rounded-lg p-3 shadow-sm cursor-pointer hover:border-brand-300 hover:shadow-md transition group relative flex flex-col" onclick="window.location.href='{{ route('kepengurusan.anggota.proker.show', $task->id) }}'" title="Klik untuk melihat detail tugas">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-widest shrink-0
                                @if($task->priority === 'high') bg-rose-100 text-rose-700
                                @elseif($task->priority === 'medium') bg-amber-100 text-amber-700
                                @else bg-slate-100 text-slate-600
                                @endif">
                                {{ $task->priority }}
                            </span>
                            @if($task->due_date)
                                @php $isOverdue = \Carbon\Carbon::parse($task->due_date)->endOfDay()->isPast() && $task->status !== 'completed'; @endphp
                                <span class="text-[10px] font-semibold ml-auto {{ $isOverdue ? 'text-rose-600' : 'text-slate-500' }}">
                                    DL: {{ \Carbon\Carbon::parse($task->due_date)->format('d M') }}
                                </span>
                            @endif
                        </div>
                        <span class="font-bold text-xs text-slate-800 block leading-tight mt-1 mb-2">{{ $task->title }}</span>
                        <p class="text-[10px] text-slate-500 mb-2 font-medium bg-slate-100 px-2 py-1 rounded w-fit"><i class="ph-fill ph-clipboard-text"></i> {{ $task->workProgram->name ?? 'Proker' }}</p>
                    </div>
                    @empty
                    <div class="text-center py-6">
                        <p class="text-xs font-medium text-slate-400">Belum ada tugas To Do.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Col 2: IN PROGRESS & REVIEW -->
            <div class="p-4 flex flex-col">
                <div class="lg:hidden flex items-center gap-2 mb-4 px-1">
                    <div class="w-2 h-2 shrink-0 rounded-full bg-blue-400"></div>
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-widest">IN PROGRESS / REVIEW</h4>
                </div>
                <div class="space-y-3 flex-1">
                    @forelse($tasks->whereIn('status', ['waiting', 'revisi']) as $task)
                    <div class="bg-white border border-blue-100 rounded-lg p-3 shadow-sm cursor-pointer hover:border-blue-400 hover:shadow-md transition group relative flex flex-col" onclick="window.location.href='{{ route('kepengurusan.anggota.proker.show', $task->id) }}'" title="Klik untuk melihat detail tugas">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-widest shrink-0
                                @if($task->priority === 'high') bg-rose-100 text-rose-700
                                @elseif($task->priority === 'medium') bg-amber-100 text-amber-700
                                @else bg-slate-100 text-slate-600
                                @endif">
                                {{ $task->priority }}
                            </span>
                            
                            @if($task->status == 'revisi')
                                <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-widest bg-amber-100 text-amber-700 ml-auto">REVISI</span>
                            @elseif($task->status == 'waiting')
                                <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-widest bg-blue-100 text-blue-700 ml-auto">REVIEW</span>
                            @endif
                        </div>
                        <span class="font-bold text-xs text-slate-800 block leading-tight mt-1 mb-2">{{ $task->title }}</span>
                        <p class="text-[10px] text-slate-500 mb-2 font-medium bg-slate-100 px-2 py-1 rounded w-fit"><i class="ph-fill ph-clipboard-text"></i> {{ $task->workProgram->name ?? 'Proker' }}</p>
                    </div>
                    @empty
                    <div class="text-center py-6">
                        <p class="text-xs font-medium text-slate-400">Belum ada tugas In Progress.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Col 3: COMPLETED -->
            <div class="p-4 flex flex-col">
                <div class="lg:hidden flex items-center gap-2 mb-4 px-1">
                    <div class="w-2 h-2 shrink-0 rounded-full bg-emerald-400"></div>
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-widest">COMPLETED</h4>
                </div>
                <div class="space-y-3 flex-1">
                    @forelse($tasks->where('status', 'completed') as $task)
                    <div class="bg-emerald-50/30 border border-emerald-100 rounded-lg p-3 shadow-sm cursor-pointer hover:border-emerald-300 hover:shadow-md transition group relative flex flex-col" onclick="window.location.href='{{ route('kepengurusan.anggota.proker.show', $task->id) }}'" title="Klik untuk melihat detail tugas">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-widest shrink-0 bg-emerald-100 text-emerald-700">
                                SELESAI
                            </span>
                            <i class="ph-fill ph-check-circle text-emerald-500 ml-auto text-sm"></i>
                        </div>
                        <span class="font-bold text-xs text-slate-600 block leading-tight mt-1 mb-2 line-through">{{ $task->title }}</span>
                        <p class="text-[10px] text-slate-500 mb-2 font-medium bg-slate-100 px-2 py-1 rounded w-fit"><i class="ph-fill ph-clipboard-text"></i> {{ $task->workProgram->name ?? 'Proker' }}</p>
                    </div>
                    @empty
                    <div class="text-center py-6">
                        <p class="text-xs font-medium text-slate-400">Belum ada tugas Selesai.</p>
                    </div>
                    @endforelse
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
