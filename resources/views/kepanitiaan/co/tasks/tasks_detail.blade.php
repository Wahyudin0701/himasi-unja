@extends('layouts.dashboard')

@section('title', 'Detail Tugas')

@section('content')
    <div x-data="{ revisiModalOpen: false, acceptModalOpen: false }">



    <div class="flex items-center justify-end mb-8">
        <a href="{{ route('kepanitiaan.co.dashboard', ['event' => $task->event_id, 'division' => $task->event_division_id]) }}" class="text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors flex items-center gap-1.5">
            <i class="ph-bold ph-caret-left text-base"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden mb-8">
        <div class="p-6 md:p-8">
            <div class="mb-8 pb-8 border-b border-slate-200">
                <h2 class="text-3xl font-bold text-slate-900 leading-tight mb-6">{{ $task->title }}</h2>
                <div class="grid grid-cols-2 md:flex md:flex-row gap-4 flex-wrap">
                    <div class="md:flex-1 bg-brand-50 p-3 rounded-xl border border-brand-100">
                        <p class="text-[10px] font-bold text-brand-400 uppercase tracking-widest mb-1">Ditugaskan Kepada</p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <div class="w-5 h-5 rounded-full bg-brand-200 flex items-center justify-center shrink-0 overflow-hidden">
                                @if($task->assignee && $task->assignee->avatar && (file_exists(public_path('storage/' . $task->assignee->avatar)) || file_exists(public_path($task->assignee->avatar))))
                                    <img src="{{ file_exists(public_path('storage/' . $task->assignee->avatar)) ? asset('storage/' . $task->assignee->avatar) : asset($task->assignee->avatar) }}" class="w-full h-full object-cover">
                                @else
                                    <i class="ph-fill ph-user text-[10px] text-brand-600"></i>
                                @endif
                            </div>
                            <p class="text-xs font-bold text-brand-700 truncate">{{ $task->assignee ? $task->assignee->name : 'Anggota' }}</p>
                        </div>
                    </div>
                    @if($task->status !== 'todo')
                    <div class="md:flex-1 bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Status</p>
                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest shrink-0 inline-block mt-0.5
                            @if($task->status === 'waiting') bg-blue-100 text-blue-700
                            @elseif(in_array($task->status, ['revisi', 'revision'])) bg-amber-100 text-amber-700
                            @elseif($task->status === 'completed') bg-emerald-100 text-emerald-700
                            @else bg-slate-200 text-slate-600
                            @endif">
                            {{ $task->status }}
                        </span>
                    </div>
                    @endif
                    <div class="md:flex-1 bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Prioritas</p>
                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest shrink-0 inline-block mt-0.5
                            @if($task->priority === 'high') bg-rose-100 text-rose-700
                            @elseif($task->priority === 'medium') bg-amber-100 text-amber-700
                            @else bg-slate-200 text-slate-600
                            @endif">
                            {{ $task->priority }}
                        </span>
                    </div>
                    <div class="md:flex-1 bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tenggat Waktu</p>
                        @php
                            $dueDate = $task->due_date ? \Carbon\Carbon::parse($task->due_date)->startOfDay() : null;
                            $today = now()->startOfDay();
                            $isOverdue = false;
                            if (!$dueDate) {
                                $diffStr = '-';
                            } else {
                                $diff = $today->diffInDays($dueDate, false);
                                if ($diff == 0) $diffStr = 'Hari ini';
                                elseif ($diff == 1) $diffStr = 'Besok';
                                elseif ($diff > 0) $diffStr = $diff . ' Hari lagi';
                                else {
                                    $diffStr = 'Terlewat ' . abs($diff) . ' hari';
                                    if ($task->status !== 'completed' && $task->status !== 'done') {
                                        $isOverdue = true;
                                    }
                                }
                            }
                        @endphp
                        <p class="text-sm font-bold {{ $isOverdue ? 'text-rose-600' : 'text-slate-700' }}">
                            {{ $diffStr }}
                        </p>
                    </div>
                    <div class="md:flex-1 bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Sprint</p>
                        <p class="text-sm font-bold text-slate-700">Sprint {{ $task->sprint_number }}</p>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-3">Deskripsi Tugas</h3>
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5">
                    <p class="text-slate-700 whitespace-pre-wrap leading-relaxed">{{ $task->description ?: 'Tidak ada deskripsi yang disertakan.' }}</p>
                </div>
            </div>

            @php
                $files = $task->attachments['files'] ?? [];
                $links = $task->attachments['links'] ?? [];
            @endphp
            @if(count($files) > 0)
            <div class="mb-8">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-3">Lampiran File Tugas</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($files as $file)
                    @php
                        $filePath = is_array($file) ? ($file['path'] ?? '') : $file;
                        $fileName = is_array($file) ? ($file['name'] ?? basename($filePath)) : basename($filePath);
                    @endphp
                    <a href="{{ Storage::url($filePath) }}" target="_blank" class="flex items-center gap-4 p-4 rounded-2xl border border-slate-200 bg-white hover:border-brand-300 hover:shadow-md transition-all group">
                        <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                            <i class="ph-fill ph-file-text text-2xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-700 truncate group-hover:text-brand-600 transition-colors">{{ $fileName }}</p>
                            <p class="text-xs text-slate-500 truncate">Lampiran File Tugas</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
            
            @if(count($links) > 0)
            <div class="mb-8">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-3">Tautan Terkait Tugas</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($links as $link)
                    @php
                        $linkUrl = is_array($link) ? ($link['url'] ?? '') : $link;
                        $linkName = is_array($link) ? ($link['name'] ?? 'Tautan Terkait Tugas') : 'Tautan Terkait Tugas';
                    @endphp
                    <a href="{{ $linkUrl }}" target="_blank" class="flex items-center gap-4 p-4 rounded-2xl border border-slate-200 bg-white hover:border-blue-300 hover:shadow-md transition-all group">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <i class="ph-fill ph-link text-2xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-700 truncate group-hover:text-blue-600 transition-colors">{{ $linkName }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ $linkUrl }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            @if($task->revision_note)
            <div class="border-t border-slate-200 pt-8 mt-8">
                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-100/50 rounded-full blur-2xl"></div>
                    <div class="relative z-10">
                        <h3 class="text-sm font-bold text-amber-900 mb-2 flex items-center gap-2">
                            <i class="ph-fill ph-warning-circle text-amber-500 text-lg"></i> Catatan Revisi
                        </h3>
                        <p class="text-sm text-amber-800 whitespace-pre-wrap leading-relaxed">{{ $task->revision_note }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if($latestReport)
            <div class="border-t border-slate-200 pt-8 mt-8">
                <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-paper-plane-tilt text-blue-500"></i> Laporan Progres Anggota
                </h3>
                <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-6">
                    <div class="mb-6">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Catatan Progres</p>
                        <p class="text-sm text-slate-800 whitespace-pre-wrap leading-relaxed">{{ $latestReport->content }}</p>
                    </div>

                    @if($latestReport->attachments)
                        @php 
                            $atts = $latestReport->attachments; 
                            $reportFiles = $atts['files'] ?? (isset($atts['file_path']) ? [['path' => $atts['file_path'], 'name' => 'File Laporan']] : []);
                            $reportLinks = $atts['links'] ?? (isset($atts['link']) ? [['url' => $atts['link'], 'name' => 'Tautan Laporan']] : []);
                        @endphp
                        
                        @if(count($reportFiles) > 0)
                        <div class="mb-4">
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Lampiran File Laporan</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($reportFiles as $file)
                                <a href="{{ Storage::url($file['path']) }}" target="_blank" class="flex items-center gap-4 p-3 rounded-xl border border-slate-200 bg-white hover:border-brand-300 transition-all group">
                                    <div class="w-10 h-10 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                                        <i class="ph-fill ph-file-text text-xl"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-slate-700 truncate group-hover:text-brand-600 transition-colors">{{ $file['name'] ?? 'File Laporan' }}</p>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if(count($reportLinks) > 0)
                        <div>
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Tautan Terkait Laporan</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($reportLinks as $link)
                                <a href="{{ $link['url'] }}" target="_blank" class="flex items-center gap-4 p-3 rounded-xl border border-slate-200 bg-white hover:border-blue-300 transition-all group">
                                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                        <i class="ph-fill ph-link text-xl"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-slate-700 truncate group-hover:text-blue-600 transition-colors">{{ $link['name'] ?? 'Tautan Laporan' }}</p>
                                        <p class="text-[10px] text-slate-500 truncate">{{ $link['url'] }}</p>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endif
                    
                    @if($task->status === 'waiting')
                        <div class="mt-6 pt-6 border-t border-blue-200 flex items-center justify-end gap-3">
                            <div>
                                <button type="button" @click="revisiModalOpen = true" class="px-6 py-2.5 bg-white hover:bg-amber-50 text-amber-600 border border-amber-200 hover:border-amber-400 font-bold text-sm rounded-xl transition-all shadow-sm flex items-center gap-2">
                                    <i class="ph-bold ph-arrow-counter-clockwise text-lg"></i> Revisi
                                </button>
                            </div>
                            <div>
                                <button type="button" @click="acceptModalOpen = true" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition-all shadow-lg shadow-emerald-500/30 flex items-center gap-2">
                                    <i class="ph-bold ph-check-circle text-lg"></i> Accept
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            @if($task->status !== 'waiting' && $task->status !== 'completed')
            <div class="mt-8 pt-8 border-t border-slate-200 flex justify-end">
                <a href="{{ route('kepanitiaan.co.tasks.edit', $task) }}" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-brand-600 hover:bg-brand-700 transition-all shadow-md flex items-center gap-2">
                    <i class="ph-bold ph-pencil-simple"></i> Edit Tugas
                </a>
            </div>
            @endif
        </div>
        <!-- Modal Revisi -->
        <div x-show="revisiModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center px-4">
            <div x-show="revisiModalOpen" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="revisiModalOpen = false"></div>
            
            <div x-show="revisiModalOpen" 
                 x-transition.scale.95 
                 x-transition.opacity 
                 class="relative bg-white rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden">
                 
                 <form action="{{ route('kepanitiaan.co.tasks.review', $task) }}" method="POST">
                     @csrf
                     <input type="hidden" name="status" value="revisi">
                     
                     <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                         <div>
                             <h3 class="text-lg font-bold text-slate-900">Catatan Revisi</h3>
                             <p class="text-xs text-slate-500 mt-1">Berikan alasan atau detail revisi untuk anggota.</p>
                         </div>
                         <button type="button" @click="revisiModalOpen = false" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 transition-colors">
                             <i class="ph ph-x"></i>
                         </button>
                     </div>

                     <div class="p-6">
                         <div>
                             <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Catatan</label>
                             <textarea name="revision_note" rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all text-sm outline-none resize-none" placeholder="Tuliskan catatan revisi di sini..." required></textarea>
                         </div>
                     </div>
                     
                     <div class="px-6 py-5 border-t border-slate-100 bg-slate-50 flex justify-end gap-3">
                         <button type="button" @click="revisiModalOpen = false" class="px-5 py-2.5 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-200 bg-slate-100 transition-all border border-slate-200">
                             Batal
                         </button>
                         <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-amber-500 hover:bg-amber-600 transition-all shadow-sm flex items-center gap-2">
                             <i class="ph-bold ph-paper-plane-tilt"></i> Kirim Revisi
                         </button>
                     </div>
                 </form>
            </div>
        </div>

        <!-- Modal Accept -->
        <div x-show="acceptModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center px-4">
            <div x-show="acceptModalOpen" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="acceptModalOpen = false"></div>
            
            <div x-show="acceptModalOpen" 
                 x-transition.scale.95 
                 x-transition.opacity 
                 class="relative bg-white rounded-3xl w-full max-w-sm shadow-2xl overflow-hidden text-center p-8">
                 
                 <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center mx-auto mb-5">
                     <i class="ph-fill ph-check-circle text-3xl"></i>
                 </div>
                 
                 <h3 class="text-xl font-bold text-slate-900 mb-2">Terima Progres?</h3>
                 <p class="text-sm text-slate-500 mb-8">Dengan menerima progres ini, tugas akan ditandai sebagai selesai dan tidak bisa direvisi lagi.</p>
                 
                 <div class="flex gap-3">
                     <button type="button" @click="acceptModalOpen = false" class="flex-1 py-3 px-4 rounded-xl font-bold text-sm text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                         Batal
                     </button>
                     <form action="{{ route('kepanitiaan.co.tasks.review', $task) }}" method="POST" class="flex-1">
                         @csrf
                         <input type="hidden" name="status" value="completed">
                         <button type="submit" class="w-full py-3 px-4 rounded-xl font-bold text-sm text-white bg-emerald-600 hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 transition-all">
                             Ya, Terima
                         </button>
                     </form>
                 </div>
            </div>
        </div>
    </div>
@endsection
