@extends('layouts.dashboard')

@section('title', 'Progres Divisi')

@section('content')
<div class="max-w-7xl mx-auto pb-10 space-y-8">

    <div class="flex flex-row items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 mb-2">Progres Divisi & Tugas</h1>
            <p class="text-slate-500 text-sm">Pantau progres setiap anggota beserta rincian tugas yang sedang dikerjakan pada program kerja {{ $proker->name }}.</p>
        </div>
        <div class="flex items-center shrink-0 mt-1">
            <a href="{{ route('kepengurusan.kadiv.proker.progress', $proker->id) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-brand-600 transition-colors">
                <i class="ph-bold ph-arrow-left"></i>
                Kembali
            </a>
        </div>
    </div>

    @forelse($divisionsData as $data)
        @php
            $event = $data['event'];
            $division = $data['division'];
            $members = $data['members'];

            $totalTasks = 0;
            $totalDone = 0;
            foreach ($members as $member) {
                $totalTasks += $member->tasks->count();
                $totalDone += $member->tasks->where('status', 'completed')->count();
            }
            $divisionProgress = $totalTasks > 0 ? round(($totalDone / $totalTasks) * 100) : 0;
        @endphp

        <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden mb-8">

            {{-- Division Header --}}
            <div class="border-b border-slate-100">
                <div class="px-6 md:px-8 pt-6 pb-4 flex flex-col sm:flex-row sm:items-center gap-5">
                    <div class="flex items-center gap-4 flex-1">
                    <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                        <i class="ph-fill ph-users-three text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg text-slate-900">{{ $division->name }}</h2>
                        <p class="text-xs font-semibold text-brand-600 mt-0.5">{{ $event->name }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-6 sm:gap-8">
                    <div class="text-center">
                        <p class="text-xl font-black text-slate-900">{{ $members->count() }}</p>
                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mt-0.5">Anggota</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xl font-black text-slate-900">{{ $totalTasks }}</p>
                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mt-0.5">Total Tugas</p>
                    </div>
                    <div class="text-center min-w-[80px]">
                        <p class="text-xl font-black {{ $divisionProgress == 100 && $totalTasks > 0 ? 'text-emerald-600' : 'text-brand-600' }}">{{ $divisionProgress }}%</p>
                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mt-0.5">Progres</p>
                    </div>
                </div>
                </div> {{-- End of Header Flex Container --}}

                {{-- Division Progress Bar --}}
                <div class="px-6 md:px-8 pb-6">
                    <div class="h-1.5 bg-slate-100 w-full rounded-full overflow-hidden">
                        <div class="h-full {{ $divisionProgress == 100 && $totalTasks > 0 ? 'bg-emerald-500' : 'bg-brand-500' }} transition-all duration-700 rounded-full"
                             style="width: {{ $divisionProgress }}%"></div>
                    </div>
                </div>
            </div> {{-- End of Border Bottom Container --}}

            {{-- Members List --}}
            <div class="p-6 md:p-8">
                @if($members->isEmpty())
                    <div class="py-12 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm border border-slate-100">
                            <i class="ph ph-user-minus text-2xl text-slate-300"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-700">Belum Ada Anggota</h3>
                        <p class="text-xs text-slate-500 mt-1">Belum ada anggota yang bergabung di divisi ini.</p>
                    </div>
                @else
                    <div class="space-y-4" x-data="{ openMember: null }">
                        @foreach($members as $member)
                            @php
                                $user = $member->user;
                                $tasks = $member->tasks;

                                $todo     = $tasks->where('status', 'todo')->count();
                                $waiting  = $tasks->where('status', 'waiting')->count();
                                $revision = $tasks->whereIn('status', ['revisi', 'revision'])->count();
                                $done     = $tasks->where('status', 'completed')->count();
                                $total    = $tasks->count();
                                $memberProgress = $total > 0 ? round(($done / $total) * 100) : 0;

                                $tasksByStatus = [
                                    'todo'      => $tasks->where('status', 'todo'),
                                    'waiting'   => $tasks->where('status', 'waiting'),
                                    'revisi'    => $tasks->whereIn('status', ['revisi', 'revision']),
                                    'completed' => $tasks->where('status', 'completed'),
                                ];
                            @endphp

                            <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white transition-all duration-200"
                                 :class="{ 'border-brand-300 shadow-md ring-4 ring-brand-50': openMember === {{ $member->id }} }">

                                {{-- Member Row / Trigger --}}
                                <div @click="openMember = openMember === {{ $member->id }} ? null : {{ $member->id }}"
                                     class="p-4 md:p-5 flex flex-col md:flex-row md:items-center gap-4 cursor-pointer hover:bg-slate-50/70 transition-colors">

                                    {{-- Avatar + Name --}}
                                    <div class="flex items-center gap-3.5 flex-1 min-w-0">
                                        <div class="w-11 h-11 rounded-full bg-brand-100 overflow-hidden shrink-0 flex items-center justify-center">
                                            @php
                                                $words = explode(' ', $user->name);
                                                $initials = '';
                                                foreach ($words as $w) {
                                                    if(isset($w[0])) $initials .= $w[0];
                                                }
                                                $initials = strtoupper(substr($initials, 0, 2));
                                            @endphp
                                            <span class="text-sm font-bold text-brand-700 tracking-wider">{{ $initials }}</span>
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="font-bold text-slate-900 text-sm truncate">{{ $user->name }}</h4>
                                            <p class="text-xs text-slate-400 mt-0.5">{{ $user->npm ?? 'NPM tidak tersedia' }}</p>
                                        </div>
                                    </div>

                                    {{-- Progress Bar (mid) --}}
                                    <div class="flex-1 max-w-xs hidden lg:block">
                                        <div class="flex items-center justify-between mb-1.5">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Progres</span>
                                            <span class="text-[10px] font-bold {{ $memberProgress == 100 ? 'text-emerald-600' : 'text-brand-600' }}">{{ $memberProgress }}%</span>
                                        </div>
                                        <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full {{ $memberProgress == 100 ? 'bg-emerald-500' : 'bg-brand-500' }} rounded-full"
                                                 style="width: {{ $memberProgress }}%"></div>
                                        </div>
                                    </div>

                                    {{-- Status Counters --}}
                                    <div class="flex items-center gap-1.5 ml-0 md:ml-2 shrink-0 flex-wrap">
                                        <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 border border-slate-200" title="Belum Dikerjakan">
                                            <div class="w-1.5 h-1.5 rounded-full bg-slate-400 shrink-0"></div>
                                            <span class="text-xs font-bold text-slate-600">{{ $todo }}</span>
                                            <span class="text-[10px] text-slate-400 hidden sm:inline">To Do</span>
                                        </div>
                                        <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 border border-blue-100" title="Menunggu Review">
                                            <div class="w-1.5 h-1.5 rounded-full bg-blue-500 shrink-0"></div>
                                            <span class="text-xs font-bold text-blue-700">{{ $waiting }}</span>
                                            <span class="text-[10px] text-blue-400 hidden sm:inline">Review</span>
                                        </div>
                                        <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 border border-amber-100" title="Perlu Revisi">
                                            <div class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></div>
                                            <span class="text-xs font-bold text-amber-700">{{ $revision }}</span>
                                            <span class="text-[10px] text-amber-400 hidden sm:inline">Revisi</span>
                                        </div>
                                        <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-100" title="Selesai">
                                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></div>
                                            <span class="text-xs font-bold text-emerald-700">{{ $done }}</span>
                                            <span class="text-[10px] text-emerald-400 hidden sm:inline">Selesai</span>
                                        </div>
                                    </div>

                                    {{-- Chevron --}}
                                    <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center shrink-0 transition-all duration-200 self-center"
                                         :class="{ 'rotate-180 bg-brand-100 text-brand-600': openMember === {{ $member->id }} }">
                                        <i class="ph ph-caret-down text-sm"></i>
                                    </div>
                                </div>

                                {{-- Accordion: Kanban Sprint Board --}}
                                <div x-show="openMember === {{ $member->id }}" x-collapse>
                                    <div class="border-t border-slate-100">

                                        @if($tasks->isEmpty())
                                            <div class="text-center py-10 mx-5 md:mx-6 my-5 bg-white rounded-2xl border border-dashed border-slate-200">
                                                <i class="ph ph-clipboard-text text-3xl text-slate-300 mb-2 block"></i>
                                                <p class="text-sm font-semibold text-slate-500">Belum Ada Tugas</p>
                                                <p class="text-xs text-slate-400 mt-0.5">Belum ada tugas yang ditugaskan kepada anggota ini.</p>
                                            </div>
                                        @else
                                            @php
                                                $tasksBySprint = $tasks->sortBy('sprint_number')->groupBy('sprint_number');
                                            @endphp

                                            <div x-data="{ openSprint: null }">
                                                @foreach($tasksBySprint as $sprintNum => $sprintTasks)
                                                @php
                                                    $colTodo     = $sprintTasks->where('status', 'todo');
                                                    $colWaiting  = $sprintTasks->where('status', 'waiting');
                                                    $colRevisi   = $sprintTasks->whereIn('status', ['revisi', 'revision']);
                                                    $colDone     = $sprintTasks->where('status', 'completed');
                                                @endphp

                                                {{-- Sprint Label --}}
                                                <div @click="openSprint = openSprint === {{ $sprintNum }} ? null : {{ $sprintNum }}" class="flex items-center gap-3 px-5 md:px-6 py-3 bg-slate-50 border-b border-slate-100 cursor-pointer hover:bg-slate-100/80 transition-colors select-none">
                                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-brand-700 bg-brand-100 border border-brand-200 px-3 py-1 rounded-full uppercase tracking-wider">
                                                        <i class="ph-fill ph-kanban text-xs"></i> Sprint {{ $sprintNum }}
                                                    </span>
                                                    <span class="text-[10px] text-slate-400 font-medium">{{ $sprintTasks->count() }} tugas</span>
                                                    <i class="ph ph-caret-down text-sm text-slate-400 ml-auto transition-transform duration-200" :class="openSprint === {{ $sprintNum }} ? 'rotate-180' : ''"></i>
                                                </div>

                                                {{-- Kanban Columns --}}
                                                <div x-show="openSprint === {{ $sprintNum }}" x-collapse>
                                                    <div class="grid grid-cols-1 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-slate-100">

                                                    {{-- TO DO Column --}}
                                                    <div class="p-4">
                                                        <div class="flex items-center gap-2 mb-3">
                                                            <div class="w-2 h-2 rounded-full bg-slate-400"></div>
                                                            <h6 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">To Do</h6>
                                                            <span class="ml-auto text-[10px] font-bold text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded-full">{{ $colTodo->count() }}</span>
                                                        </div>
                                                        <div class="space-y-2">
                                                            @forelse($colTodo as $task)
                                                                @php $isOverdue = $task->due_date && \Carbon\Carbon::parse($task->due_date)->endOfDay()->isPast(); @endphp
                                                                <a href="{{ route('kepanitiaan.co.tasks.show', $task->id) }}" class="block bg-white border {{ $isOverdue ? 'border-rose-200' : 'border-slate-200' }} rounded-xl p-3 shadow-sm hover:shadow-md hover:border-brand-200 transition-all duration-200 group">
                                                                    <div class="flex items-start justify-between gap-2 mb-2">
                                                                        @if($task->priority == 'high')
                                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-600 shrink-0">HIGH</span>
                                                                        @elseif($task->priority == 'medium')
                                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-600 shrink-0">MEDIUM</span>
                                                                        @else
                                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 shrink-0">LOW</span>
                                                                        @endif
                                                                        @if($isOverdue)
                                                                            <span class="text-[9px] font-bold text-rose-500 bg-rose-50 px-1.5 py-0.5 rounded-full shrink-0">Terlambat</span>
                                                                        @endif
                                                                    </div>
                                                                    <p class="text-xs font-bold text-slate-800 leading-snug mb-2 group-hover:text-brand-600 transition-colors">{{ $task->title }}</p>
                                                                    @if($task->description)
                                                                        <p class="text-[11px] text-slate-500 line-clamp-2 mb-2">{{ $task->description }}</p>
                                                                    @endif
                                                                    <div class="flex items-center gap-1 {{ $isOverdue ? 'text-rose-500' : 'text-slate-400' }}">
                                                                        <i class="ph ph-calendar-blank text-[10px]"></i>
                                                                        <span class="text-[10px] font-semibold">{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : '–' }}</span>
                                                                    </div>
                                                                </a>
                                                            @empty
                                                                <div class="py-6 text-center border border-dashed border-slate-200 rounded-xl">
                                                                    <p class="text-[11px] text-slate-400">Tidak ada</p>
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div>

                                                    {{-- WAITING REVIEW Column --}}
                                                    <div class="p-4 bg-blue-50/30">
                                                        <div class="flex items-center gap-2 mb-3">
                                                            <div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div>
                                                            <h6 class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">Review</h6>
                                                            <span class="ml-auto text-[10px] font-bold text-blue-500 bg-blue-100 px-1.5 py-0.5 rounded-full">{{ $colWaiting->count() }}</span>
                                                        </div>
                                                        <div class="space-y-2">
                                                            @forelse($colWaiting as $task)
                                                                <a href="{{ route('kepanitiaan.co.tasks.show', $task->id) }}" class="block bg-white border border-blue-200 rounded-xl p-3 shadow-sm hover:shadow-md hover:border-blue-300 transition-all duration-200 group">
                                                                    <div class="flex items-start justify-between gap-2 mb-2">
                                                                        @if($task->priority == 'high')
                                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-600 shrink-0">HIGH</span>
                                                                        @elseif($task->priority == 'medium')
                                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-600 shrink-0">MEDIUM</span>
                                                                        @else
                                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 shrink-0">LOW</span>
                                                                        @endif
                                                                    </div>
                                                                    <p class="text-xs font-bold text-slate-800 leading-snug mb-2 group-hover:text-blue-600 transition-colors">{{ $task->title }}</p>
                                                                    @if($task->description)
                                                                        <p class="text-[11px] text-slate-500 line-clamp-2 mb-2">{{ $task->description }}</p>
                                                                    @endif
                                                                    <div class="flex items-center gap-1 text-slate-400">
                                                                        <i class="ph ph-calendar-blank text-[10px]"></i>
                                                                        <span class="text-[10px] font-semibold">{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : '–' }}</span>
                                                                    </div>
                                                                </a>
                                                            @empty
                                                                <div class="py-6 text-center border border-dashed border-blue-200 rounded-xl bg-white/60">
                                                                    <p class="text-[11px] text-blue-300">Tidak ada</p>
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div>

                                                    {{-- REVISI Column --}}
                                                    <div class="p-4 bg-amber-50/30">
                                                        <div class="flex items-center gap-2 mb-3">
                                                            <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                                                            <h6 class="text-[10px] font-bold text-amber-600 uppercase tracking-widest">Revisi</h6>
                                                            <span class="ml-auto text-[10px] font-bold text-amber-600 bg-amber-100 px-1.5 py-0.5 rounded-full">{{ $colRevisi->count() }}</span>
                                                        </div>
                                                        <div class="space-y-2">
                                                            @forelse($colRevisi as $task)
                                                                <a href="{{ route('kepanitiaan.co.tasks.show', $task->id) }}" class="block bg-white border border-amber-200 rounded-xl p-3 shadow-sm hover:shadow-md hover:border-amber-300 transition-all duration-200 group">
                                                                    <div class="flex items-start justify-between gap-2 mb-2">
                                                                        @if($task->priority == 'high')
                                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-600 shrink-0">HIGH</span>
                                                                        @elseif($task->priority == 'medium')
                                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-600 shrink-0">MEDIUM</span>
                                                                        @else
                                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 shrink-0">LOW</span>
                                                                        @endif
                                                                    </div>
                                                                    <p class="text-xs font-bold text-slate-800 leading-snug mb-2 group-hover:text-amber-600 transition-colors">{{ $task->title }}</p>
                                                                    @if($task->description)
                                                                        <p class="text-[11px] text-slate-500 line-clamp-2 mb-2">{{ $task->description }}</p>
                                                                    @endif
                                                                    @if($task->revision_note)
                                                                        <div class="flex items-start gap-1.5 p-2 bg-amber-50 rounded-lg mb-2 border border-amber-100">
                                                                            <i class="ph-fill ph-warning-circle text-amber-500 text-[10px] mt-0.5 shrink-0"></i>
                                                                            <p class="text-[10px] text-amber-700 leading-snug line-clamp-2">{{ $task->revision_note }}</p>
                                                                        </div>
                                                                    @endif
                                                                    <div class="flex items-center gap-1 text-slate-400">
                                                                        <i class="ph ph-calendar-blank text-[10px]"></i>
                                                                        <span class="text-[10px] font-semibold">{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : '–' }}</span>
                                                                    </div>
                                                                </a>
                                                            @empty
                                                                <div class="py-6 text-center border border-dashed border-amber-200 rounded-xl bg-white/60">
                                                                    <p class="text-[11px] text-amber-300">Tidak ada</p>
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div>

                                                    {{-- COMPLETED Column --}}
                                                    <div class="p-4 bg-emerald-50/30">
                                                        <div class="flex items-center gap-2 mb-3">
                                                            <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                                            <h6 class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Selesai</h6>
                                                            <span class="ml-auto text-[10px] font-bold text-emerald-600 bg-emerald-100 px-1.5 py-0.5 rounded-full">{{ $colDone->count() }}</span>
                                                        </div>
                                                        <div class="space-y-2">
                                                            @forelse($colDone as $task)
                                                                <a href="{{ route('kepanitiaan.co.tasks.show', $task->id) }}" class="block bg-white border border-emerald-200 rounded-xl p-3 shadow-sm opacity-80 hover:opacity-100 hover:shadow-md transition-all duration-200 group">
                                                                    <div class="flex items-start justify-between gap-2 mb-2">
                                                                        @if($task->priority == 'high')
                                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-600 shrink-0">HIGH</span>
                                                                        @elseif($task->priority == 'medium')
                                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-600 shrink-0">MEDIUM</span>
                                                                        @else
                                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 shrink-0">LOW</span>
                                                                        @endif
                                                                        <i class="ph-fill ph-check-circle text-emerald-500 text-sm shrink-0"></i>
                                                                    </div>
                                                                    <p class="text-xs font-bold text-slate-500 leading-snug mb-2 line-through decoration-emerald-400 group-hover:text-emerald-600 transition-colors">{{ $task->title }}</p>
                                                                    <div class="flex items-center gap-1 text-slate-400">
                                                                        <i class="ph ph-check text-[10px] text-emerald-500"></i>
                                                                        <span class="text-[10px] font-semibold text-emerald-600">
                                                                            {{ $task->completed_at ? \Carbon\Carbon::parse($task->completed_at)->format('d M Y') : 'Selesai' }}
                                                                        </span>
                                                                    </div>
                                                                </a>
                                                            @empty
                                                                <div class="py-6 text-center border border-dashed border-emerald-200 rounded-xl bg-white/60">
                                                                    <p class="text-[11px] text-emerald-300">Tidak ada</p>
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>{{-- end x-show openSprint --}}

                                                @endforeach
                                            </div>{{-- end x-data openSprint --}}
                                        @endif
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-12 text-center max-w-lg mx-auto">
            <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-4 border border-slate-100 shadow-sm">
                <i class="ph-fill ph-users-three text-3xl text-slate-300"></i>
            </div>
            <h3 class="font-bold text-slate-900 mb-2 text-lg">Tidak Ada Data Divisi</h3>
            <p class="text-sm text-slate-500">Anda belum ditugaskan sebagai pengurus inti (CO/Ketupel) yang membawahi divisi mana pun saat ini.</p>
        </div>
    @endforelse
</div>
@endsection
