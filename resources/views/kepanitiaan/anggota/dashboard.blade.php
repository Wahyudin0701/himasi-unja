@extends('layouts.dashboard')

@section('title', 'Dashboard Anggota')

@section('breadcrumbs')
    <span class="text-slate-700">Kepanitiaan</span>
@endsection

@section('content')

    <!-- Greeting -->
    <div class="space-y-6">
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-brand-500 uppercase tracking-widest mb-1">Anggota Divisi</p>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">
                Halo, <span class="gradient-text">{{ explode(' ', $user->name)[0] }}</span>
            </h1>
            <p class="text-sm text-slate-500 mt-1.5 font-medium">Berikut progres tugas sprint Anda.</p>
        </div>
    </div>

    <!-- Sprint Tasks Board Header -->
    <div>
        <div class="flex items-center justify-between py-5 border-t border-slate-200">
            <h3 class="text-xl font-bold text-slate-900 flex items-center gap-2"><i class="ph-fill ph-kanban text-brand-500"></i> Papan Sprint</h3>
        </div>

        @php
            $firstTask = $tasks->first();
            $divisionName = $firstTask ? ($firstTask->eventDivision->name ?? 'Divisi') : 'Divisi';
            $eventName = $firstTask ? ($firstTask->event->name ?? 'Event') : 'Event';
        @endphp

        <!-- Division Header -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center border border-brand-100 shrink-0">
                        <i class="ph-fill ph-users-three text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 leading-tight">{{ $divisionName }}</h2>
                        <p class="text-xs font-bold text-brand-600 uppercase tracking-widest mt-0.5">{{ $eventName }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Kanban Sprints -->
    @if($sprintGroups->count() > 0)
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm mb-8">
            
            <!-- Global Kanban Headers (Desktop Only) -->
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
                @foreach($sprintGroups as $sprintNumber => $sprintTasks)
                    <div class="flex flex-col lg:flex-row hover:bg-white transition-colors group/row">
                        
                        <!-- Rotated Sprint Header -->
                        <div class="lg:w-16 bg-brand-600 text-white flex items-center justify-center shrink-0 border-b lg:border-b-0 lg:border-r border-slate-200 py-3 lg:py-0 overflow-hidden">
                            <div class="flex flex-col items-center justify-center gap-1 lg:transform lg:-rotate-90 whitespace-nowrap">
                                <h3 class="text-sm font-extrabold tracking-widest uppercase leading-none">SPRINT {{ $sprintNumber }}</h3>
                                @php
                                    $firstTask = $sprintTasks->first();
                                    $startDate = $firstTask && $firstTask->sprint_start_date ? \Carbon\Carbon::parse($firstTask->sprint_start_date)->format('d M') : '';
                                    $endDate = $firstTask && $firstTask->sprint_end_date ? \Carbon\Carbon::parse($firstTask->sprint_end_date)->format('d M') : '';
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
                                    <div class="bg-white border border-slate-200 rounded-lg p-3 shadow-sm cursor-pointer hover:border-brand-300 hover:shadow-md transition group relative flex flex-col" onclick="window.location.href='{{ route('kepanitiaan.anggota.tasks.show', $task->id) }}'" title="Klik untuk melihat detail tugas">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-widest shrink-0
                                                @if($task->priority === 'high') bg-rose-100 text-rose-700
                                                @elseif($task->priority === 'medium') bg-amber-100 text-amber-700
                                                @else bg-slate-100 text-slate-600
                                                @endif">
                                                {{ $task->priority }}
                                            </span>
                                            @if($task->due_date)
                                                @php $isOverdue = \Carbon\Carbon::parse($task->due_date)->isPast() && $task->status !== 'completed'; @endphp
                                                <span class="text-[10px] font-semibold ml-auto {{ $isOverdue ? 'text-rose-600' : 'text-slate-500' }}">
                                                    DL: {{ \Carbon\Carbon::parse($task->due_date)->format('d M') }}
                                                </span>
                                            @endif
                                        </div>
                                        <span class="font-bold text-xs text-slate-800 block leading-tight mt-1 mb-2">{{ $task->title }}</span>
                                        @if($task->description)
                                            <p class="text-[10px] text-slate-500 line-clamp-2 mb-3">{{ $task->description }}</p>
                                        @endif
                                        @php
                                            $files = $task->attachments['files'] ?? [];
                                            $links = $task->attachments['links'] ?? [];
                                        @endphp
                                        @if(count($files) > 0 || count($links) > 0)
                                            <div class="flex flex-col gap-1 mb-3">
                                                @foreach($files as $file)
                                                    @php
                                                        $filePath = is_array($file) ? ($file['path'] ?? '') : $file;
                                                        $fileName = is_array($file) ? ($file['name'] ?? basename($filePath)) : basename($filePath);
                                                    @endphp
                                                    <a href="{{ Storage::url($filePath) }}" target="_blank" class="text-[10px] flex items-center gap-1.5 text-brand-600 hover:text-brand-700 bg-brand-50 hover:bg-brand-100 px-2 py-1 rounded transition-colors w-fit truncate max-w-full">
                                                        <i class="ph-fill ph-file-text shrink-0"></i> <span class="truncate">{{ $fileName }}</span>
                                                    </a>
                                                @endforeach
                                                @foreach($links as $link)
                                                    @php
                                                        $linkUrl = is_array($link) ? ($link['url'] ?? '') : $link;
                                                        $linkName = is_array($link) ? ($link['name'] ?? 'Buka Tautan') : 'Buka Tautan';
                                                    @endphp
                                                    <a href="{{ $linkUrl }}" target="_blank" class="text-[10px] flex items-center gap-1.5 text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-2 py-1 rounded transition-colors w-fit truncate max-w-full">
                                                        <i class="ph-fill ph-link shrink-0"></i> <span class="truncate">{{ $linkName }}</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
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
                                    <div class="bg-white border border-blue-100 rounded-lg p-3 shadow-sm cursor-pointer hover:border-blue-400 hover:shadow-md transition group relative flex flex-col" onclick="window.location.href='{{ route('kepanitiaan.anggota.tasks.show', $task->id) }}'" title="Klik untuk melihat detail tugas">
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
                                                <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-widest bg-blue-100 text-blue-700 ml-auto">WAITING</span>
                                            @endif
                                        </div>
                                        <span class="font-bold text-xs text-slate-800 block leading-tight mt-1 mb-2">{{ $task->title }}</span>
                                        @if($task->description)
                                            <p class="text-[10px] text-slate-500 line-clamp-2 mb-3">{{ $task->description }}</p>
                                        @endif
                                        @php
                                            $files = $task->attachments['files'] ?? [];
                                            $links = $task->attachments['links'] ?? [];
                                        @endphp
                                        @if(count($files) > 0 || count($links) > 0)
                                            <div class="flex flex-col gap-1 mb-3">
                                                @foreach($files as $file)
                                                    @php
                                                        $filePath = is_array($file) ? ($file['path'] ?? '') : $file;
                                                        $fileName = is_array($file) ? ($file['name'] ?? basename($filePath)) : basename($filePath);
                                                    @endphp
                                                    <a href="{{ Storage::url($filePath) }}" target="_blank" class="text-[10px] flex items-center gap-1.5 text-brand-600 hover:text-brand-700 bg-brand-50 hover:bg-brand-100 px-2 py-1 rounded transition-colors w-fit truncate max-w-full">
                                                        <i class="ph-fill ph-file-text shrink-0"></i> <span class="truncate">{{ $fileName }}</span>
                                                    </a>
                                                @endforeach
                                                @foreach($links as $link)
                                                    @php
                                                        $linkUrl = is_array($link) ? ($link['url'] ?? '') : $link;
                                                        $linkName = is_array($link) ? ($link['name'] ?? 'Buka Tautan') : 'Buka Tautan';
                                                    @endphp
                                                    <a href="{{ $linkUrl }}" target="_blank" class="text-[10px] flex items-center gap-1.5 text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-2 py-1 rounded transition-colors w-fit truncate max-w-full">
                                                        <i class="ph-fill ph-link shrink-0"></i> <span class="truncate">{{ $linkName }}</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif

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
                                    <div class="bg-white border border-emerald-100 rounded-lg p-3 shadow-sm cursor-pointer hover:border-emerald-400 hover:shadow-md transition group relative flex flex-col opacity-80 hover:opacity-100" onclick="window.location.href='{{ route('kepanitiaan.anggota.tasks.show', $task->id) }}'" title="Klik untuk melihat detail tugas">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-700 shrink-0">
                                                SELESAI
                                            </span>
                                            <i class="ph-fill ph-check-circle text-emerald-500 ml-auto text-sm"></i>
                                        </div>
                                        <span class="font-bold text-xs text-slate-600 block leading-tight mt-1 line-through mb-2">{{ $task->title }}</span>
                                        @php
                                            $files = $task->attachments['files'] ?? [];
                                            $links = $task->attachments['links'] ?? [];
                                        @endphp
                                        @if(count($files) > 0 || count($links) > 0)
                                            <div class="flex flex-col gap-1 mb-1">
                                                @foreach($files as $file)
                                                    @php
                                                        $filePath = is_array($file) ? ($file['path'] ?? '') : $file;
                                                        $fileName = is_array($file) ? ($file['name'] ?? basename($filePath)) : basename($filePath);
                                                    @endphp
                                                    <a href="{{ Storage::url($filePath) }}" target="_blank" class="text-[10px] flex items-center gap-1.5 text-slate-500 hover:text-slate-700 bg-slate-50 hover:bg-slate-100 px-2 py-1 rounded transition-colors w-fit truncate max-w-full">
                                                        <i class="ph-fill ph-file-text shrink-0"></i> <span class="truncate">{{ $fileName }}</span>
                                                    </a>
                                                @endforeach
                                                @foreach($links as $link)
                                                    @php
                                                        $linkUrl = is_array($link) ? ($link['url'] ?? '') : $link;
                                                        $linkName = is_array($link) ? ($link['name'] ?? 'Buka Tautan') : 'Buka Tautan';
                                                    @endphp
                                                    <a href="{{ $linkUrl }}" target="_blank" class="text-[10px] flex items-center gap-1.5 text-slate-500 hover:text-slate-700 bg-slate-50 hover:bg-slate-100 px-2 py-1 rounded transition-colors w-fit truncate max-w-full">
                                                        <i class="ph-fill ph-link shrink-0"></i> <span class="truncate">{{ $linkName }}</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
                <i class="ph ph-clipboard-text text-3xl text-slate-400"></i>
            </div>
            <h3 class="font-semibold text-slate-700 mb-1">Belum ada tugas</h3>
            <p class="text-sm text-slate-500">Koordinator Divisi Anda belum membuat tugas sprint untuk Anda.</p>
        </div>
    @endif
    </div>

@endsection
