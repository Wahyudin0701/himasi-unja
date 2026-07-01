@extends('layouts.dashboard')

@section('title', 'Dashboard ' . (isset($userRoleName) && $userRoleName === 'WAKIL KETUA PELAKSANA' ? 'Waketupel' : 'Ketupel'))

@section('breadcrumbs')
    <span class="text-slate-700">Kepanitiaan</span>
@endsection

@section('content')

{{-- ===== HEADER GREETING ===== --}}
<div class="mb-8">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-xs font-semibold text-brand-500 uppercase tracking-widest mb-1">{{ $userRoleName ?? 'Ketua Pelaksana' }}</p>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-tight">
                Kepanitiaan Event : <span class="text-brand-600">{{ $event->name ?? 'Acara' }}</span>
            </h1>
        </div>
    </div>
</div>

{{-- ===== STATS CARDS ===== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">

    {{-- Total Divisi --}}
    <a href="{{ route('kepanitiaan.ketupel.manage-team', $event) }}" class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-sm hover:border-brand-300 hover:shadow-md transition-all flex items-center gap-5 cursor-pointer">
        <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center shrink-0 border border-slate-100">
            <i class="ph-bold ph-tree-structure text-2xl text-slate-600"></i>
        </div>
        <div>
            <p class="text-sm font-bold text-slate-500 mb-1">Total Divisi</p>
            <p class="text-4xl font-black text-slate-900">{{ $totalDivisions }}</p>
        </div>
    </a>

    {{-- Progres Divisi --}}
    <a href="{{ route('kepanitiaan.ketupel.progres.divisi', $event) }}" class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-sm hover:border-brand-300 hover:shadow-md transition-all flex items-center gap-5 cursor-pointer">
        <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center shrink-0 border border-blue-100">
            <i class="ph-bold ph-chart-line-up text-2xl text-blue-600"></i>
        </div>
        <div>
            <p class="text-sm font-bold text-slate-500 mb-1">Progres Divisi</p>
            <p class="text-4xl font-black text-slate-900">{{ $overallProgress }}%</p>
        </div>
    </a>

</div>



{{-- ===== SPRINT BOARD (ALL DIVISIONS) ===== --}}
<div class="mb-12" 
     x-data="{ 
        filterDivision: '',
        filterMember: '',
        filterStatus: '',
        filterPriority: '',
        
        matchesFilters(memberId, status, priority, divisionId) {
            if (this.filterDivision && this.filterDivision != divisionId) return false;
            if (this.filterMember && this.filterMember != memberId) return false;
            if (this.filterStatus && this.filterStatus != status) return false;
            if (this.filterPriority && this.filterPriority != priority) return false;
            return true;
        }
     }">
     
    <!-- Sprint Tasks Board Header -->
    <div>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between py-5 border-t border-slate-200 gap-4">
            <h3 class="text-xl font-bold text-slate-900 flex items-center gap-2"><i class="ph-fill ph-kanban text-brand-500"></i> Papan Sprint Keseluruhan</h3>
        </div>

        <!-- Progress Overview -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-end gap-4">
                <div class="flex flex-col items-end gap-1">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-500 uppercase">Progress Keseluruhan Divisi</span>
                        <span class="text-sm font-bold text-emerald-600">{{ $overallProgress }}%</span>
                    </div>
                    <div class="w-48 h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full transition-all duration-500" style="width: {{ $overallProgress }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        @if($groupedTasks->count() > 0)
            <!-- Filters -->
            <div class="mb-6 flex flex-col gap-3">
                <div class="w-full bg-white border border-slate-200 rounded-xl p-5 sm:px-6 flex flex-col lg:flex-row gap-4 lg:gap-6 shadow-sm lg:items-center">
                    <div class="flex items-center gap-2 shrink-0 border-b lg:border-b-0 pb-2 lg:pb-0 border-slate-100 mt-5">
                        <i class="ph-fill ph-funnel text-slate-400"></i>
                        <span class="text-xs font-bold text-slate-600 uppercase tracking-widest">Filter:</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 flex-1 w-full items-end">
                        <div class="flex flex-col gap-1">
                            <select x-model="filterDivision" class="text-sm border-slate-200 rounded-lg focus:ring-brand-500 focus:border-brand-500 w-full bg-slate-50 py-2.5 px-4">
                                <option value="">Semua Divisi</option>
                                @foreach($event->divisions as $division)
                                    <option value="{{ $division->id }}">{{ $division->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-1">
                            <select x-model="filterMember" class="text-sm border-slate-200 rounded-lg focus:ring-brand-500 focus:border-brand-500 w-full bg-slate-50 py-2.5 px-4">
                                <option value="">Semua Anggota</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->user->id }}">{{ $member->user->name }} ({{ $member->division->name ?? '?' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-1">
                            <select x-model="filterStatus" class="text-sm border-slate-200 rounded-lg focus:ring-brand-500 focus:border-brand-500 w-full bg-slate-50 py-2.5 px-4">
                                <option value="">Semua Status</option>
                                <option value="todo">To Do</option>
                                <option value="waiting">Menunggu Review</option>
                                <option value="revisi">Revisi</option>
                                <option value="completed">Selesai</option>
                            </select>
                        </div>
                        <div class="flex flex-col-reverse sm:flex-col gap-2 sm:gap-1">
                            <div class="flex justify-end px-1">
                                <button @click="filterDivision=''; filterMember=''; filterStatus=''; filterPriority=''" class="text-[10px] font-bold text-brand-600 hover:text-brand-700 flex items-center gap-1 transition-colors">
                                    <i class="ph-bold ph-arrows-clockwise"></i> Reset Filter
                                </button>
                            </div>
                            <select x-model="filterPriority" class="text-sm border-slate-200 rounded-lg focus:ring-brand-500 focus:border-brand-500 w-full bg-slate-50 py-2.5 px-4">
                                <option value="">Semua Prioritas</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Global Table Headers (Desktop Only) -->
            <div class="hidden lg:flex border-b border-slate-200 bg-slate-50/80">
                <div class="w-16 shrink-0 border-r border-slate-200"></div>
                <div class="flex-1 grid grid-cols-3 divide-x divide-slate-200">
                    <div class="p-4 text-center">
                        <h4 class="text-xs font-extrabold text-slate-700 uppercase tracking-widest flex items-center justify-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-slate-300"></div> TO DO
                        </h4>
                    </div>
                    <div class="p-4 text-center">
                        <h4 class="text-xs font-extrabold text-slate-700 uppercase tracking-widest flex items-center justify-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-blue-400"></div> IN PROGRESS
                        </h4>
                    </div>
                    <div class="p-4 text-center">
                        <h4 class="text-xs font-extrabold text-slate-700 uppercase tracking-widest flex items-center justify-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-emerald-400"></div> COMPLETED
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Sprints Data -->
            <div class="divide-y divide-slate-200 bg-slate-50/20">
                @foreach($groupedTasks as $sprintNumber => $sprintTasks)
                    <div class="flex flex-col lg:flex-row hover:bg-white transition-colors group/row lg:min-h-[150px]">
                        
                        <!-- Rotated Sprint Header -->
                        <div class="lg:w-16 bg-brand-600 text-white flex items-center justify-center shrink-0 border-b lg:border-b-0 lg:border-r border-slate-200 py-3 lg:py-0 overflow-hidden">
                            <div class="flex flex-col items-center justify-center gap-1 lg:transform lg:-rotate-90 whitespace-nowrap">
                                <h3 class="text-sm font-extrabold tracking-widest uppercase leading-none">SPRINT {{ $sprintNumber }}</h3>
                                @php
                                    $startDate = $sprintTasks->first()->sprint_start_date ? \Carbon\Carbon::parse($sprintTasks->first()->sprint_start_date)->format('d M') : '';
                                    $endDate = $sprintTasks->first()->sprint_end_date ? \Carbon\Carbon::parse($sprintTasks->first()->sprint_end_date)->format('d M') : '';
                                @endphp
                                @if($startDate && $endDate)
                                    <span class="text-[10px] font-medium text-slate-300 text-center">{{ $startDate }} - {{ $endDate }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Columns Container -->
                        <div class="flex-1 grid grid-cols-1 lg:grid-cols-3 divide-y lg:divide-y-0 lg:divide-x divide-slate-200">
                            
                            <!-- Col 1: TO DO -->
                            <div class="p-4 flex flex-col">
                                <div class="lg:hidden flex items-center gap-2 mb-4 px-1">
                                    <div class="w-2 h-2 shrink-0 rounded-full bg-slate-300"></div>
                                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-widest">TO DO</h4>
                                </div>
                                <div class="space-y-3 flex-1">
                                    @foreach($sprintTasks->where('status', 'todo') as $task)
                                    <div x-show="matchesFilters('{{ $task->assigned_to }}', '{{ $task->status }}', '{{ $task->priority }}', '{{ $task->event_division_id }}')" class="bg-white border border-slate-200 rounded-lg p-3 shadow-sm cursor-pointer hover:border-brand-300 hover:shadow-md transition group relative flex flex-col" onclick="window.location.href='{{ route('kepanitiaan.ketupel.tasks.show', $task->id) }}'" title="Lihat detail tugas">
                                        <div class="flex items-center gap-2 mb-2">
                                            <div class="flex items-start gap-1.5 min-w-0">
                                                <div class="w-5 h-5 rounded-full bg-slate-100 flex items-center justify-center text-[9px] font-bold text-slate-600 shrink-0">
                                                    {{ strtoupper(substr($task->assignee->name ?? '?', 0, 1)) }}
                                                </div>
                                                <div class="flex flex-col min-w-0">
                                                    <span class="text-[10px] font-semibold text-slate-500 whitespace-nowrap overflow-hidden text-ellipsis leading-tight">{{ $task->assignee->name ?? 'Unassigned' }}</span>
                                                    <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">{{ $task->division->name ?? 'Divisi' }}</span>
                                                </div>
                                            </div>
                                            <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-widest ml-auto shrink-0
                                                @if($task->priority === 'high') bg-rose-100 text-rose-700
                                                @elseif($task->priority === 'medium') bg-amber-100 text-amber-700
                                                @else bg-slate-100 text-slate-600
                                                @endif">
                                                {{ $task->priority }}
                                            </span>
                                        </div>
                                        <span class="font-bold text-xs text-slate-800 block leading-tight mt-1">{{ $task->title }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Col 2: IN PROGRESS -->
                            <div class="p-4 flex flex-col">
                                <div class="lg:hidden flex items-center gap-2 mb-4 px-1">
                                    <div class="w-2 h-2 shrink-0 rounded-full bg-blue-400"></div>
                                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-widest">IN PROGRESS</h4>
                                </div>
                                <div class="space-y-3 flex-1">
                                    @foreach($sprintTasks->whereIn('status', ['waiting', 'revisi']) as $task)
                                    <div x-show="matchesFilters('{{ $task->assigned_to }}', '{{ $task->status }}', '{{ $task->priority }}', '{{ $task->event_division_id }}')" class="bg-white border border-blue-100 rounded-lg p-3 shadow-sm cursor-pointer hover:border-blue-400 hover:shadow-md transition group relative flex flex-col" onclick="window.location.href='{{ route('kepanitiaan.ketupel.tasks.show', $task->id) }}'" title="Lihat detail tugas">
                                        <div class="flex items-center gap-2 mb-2">
                                            <div class="flex items-start gap-1.5 min-w-0">
                                                <div class="w-5 h-5 rounded-full bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center text-[9px] font-bold shrink-0">
                                                    {{ strtoupper(substr($task->assignee->name ?? '?', 0, 1)) }}
                                                </div>
                                                <div class="flex flex-col min-w-0">
                                                    <span class="text-[10px] font-semibold text-slate-500 whitespace-nowrap overflow-hidden text-ellipsis leading-tight">{{ $task->assignee->name ?? 'Unassigned' }}</span>
                                                    <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">{{ $task->division->name ?? 'Divisi' }}</span>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-1 ml-auto shrink-0">
                                                <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-widest
                                                    @if($task->priority === 'high') bg-rose-100 text-rose-700
                                                    @elseif($task->priority === 'medium') bg-amber-100 text-amber-700
                                                    @else bg-slate-100 text-slate-600
                                                    @endif">
                                                    {{ $task->priority }}
                                                </span>
                                                @if($task->status == 'revisi')
                                                    <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-widest bg-amber-100 text-amber-700">REVISI</span>
                                                @elseif($task->status == 'waiting')
                                                    <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-widest bg-blue-100 text-blue-700">WAITING</span>
                                                @endif
                                            </div>
                                        </div>
                                        <span class="font-bold text-xs text-slate-800 block leading-tight mt-1">{{ $task->title }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Col 3: COMPLETED -->
                            <div class="p-4 flex flex-col">
                                <div class="lg:hidden flex items-center gap-2 mb-4 px-1">
                                    <div class="w-2 h-2 shrink-0 rounded-full bg-emerald-400"></div>
                                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-widest">COMPLETED</h4>
                                </div>
                                <div class="space-y-3 flex-1">
                                    @foreach($sprintTasks->where('status', 'completed') as $task)
                                    <div x-show="matchesFilters('{{ $task->assigned_to }}', '{{ $task->status }}', '{{ $task->priority }}', '{{ $task->event_division_id }}')" class="bg-white border border-emerald-100 rounded-lg p-3 shadow-sm cursor-pointer hover:border-emerald-400 hover:shadow-md transition group relative flex flex-col opacity-80 hover:opacity-100" onclick="window.location.href='{{ route('kepanitiaan.ketupel.tasks.show', $task->id) }}'">
                                        <div class="flex items-center gap-2 mb-2">
                                            <div class="flex items-start gap-1.5 min-w-0">
                                                <div class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-[9px] font-bold shrink-0">
                                                    {{ strtoupper(substr($task->assignee->name ?? '?', 0, 1)) }}
                                                </div>
                                                <div class="flex flex-col min-w-0">
                                                    <span class="text-[10px] font-semibold text-slate-500 whitespace-nowrap overflow-hidden text-ellipsis leading-tight">{{ $task->assignee->name ?? 'Unassigned' }}</span>
                                                    <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">{{ $task->division->name ?? 'Divisi' }}</span>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-1 ml-auto shrink-0">
                                                <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-700">
                                                    SELESAI
                                                </span>
                                                <i class="ph-fill ph-check-circle text-emerald-500"></i>
                                            </div>
                                        </div>
                                        <span class="font-bold text-xs text-slate-600 block leading-tight mt-1 line-through">{{ $task->title }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-12 text-center bg-slate-50 border border-slate-200 border-dashed rounded-2xl">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm border border-slate-100">
                    <i class="ph ph-clipboard-text text-3xl text-slate-300"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-700">Belum ada tugas</h3>
                <p class="text-xs text-slate-500 mt-1">Belum ada tugas yang dikerjakan oleh divisi manapun.</p>
            </div>
        @endif
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
