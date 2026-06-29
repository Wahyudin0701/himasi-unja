@extends('layouts.dashboard')

@section('title', 'Detail Tugas')

@section('breadcrumbs')
    <span class="text-slate-700">Kepanitiaan</span>
@endsection

@section('content')


    <div class="flex items-center justify-end mb-8">
        <a href="{{ route('kepanitiaan.anggota.dashboard') }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700 flex items-center gap-1.5 transition-colors">
            <i class="ph-bold ph-caret-left text-base"></i>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden mb-8">
        <div class="p-6 md:p-8">
            <div class="mb-8 pb-8 border-b border-slate-200">
                <h2 class="text-3xl font-bold text-slate-900 leading-tight mb-6">{{ $task->title }}</h2>
                <div class="grid grid-cols-2 md:flex md:flex-row gap-4">
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
                    <p class="text-slate-700 whitespace-pre-wrap leading-relaxed">{{ $task->description ?: 'Tidak ada deskripsi yang disertakan untuk tugas ini.' }}</p>
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

            @if(in_array($task->status, ['todo', 'revisi']))
            <div class="border-t border-slate-200 pt-8 mt-8">
                
                @if($task->status === 'revisi' && $task->revision_note)
                <div class="mb-6 bg-amber-50 border border-amber-200 rounded-2xl p-5 flex gap-4 items-start">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="ph-fill ph-warning-circle text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-amber-900 mb-1">Catatan Revisi dari Koordinator</h4>
                        <p class="text-sm text-amber-700 leading-relaxed">{{ $task->revision_note }}</p>
                    </div>
                </div>
                @endif

                <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-slate-900 mb-1">Ajukan Progres</h3>
                        <p class="text-sm text-slate-600">Sertakan catatan progres beserta lampiran (jika ada) untuk dilaporkan ke Koordinator.</p>
                    </div>
                    
                    <form action="{{ route('kepanitiaan.anggota.update-status', $task) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="waiting">
                        
                        <div class="space-y-5">
                            <div>
                                <label for="catatan" class="block text-sm font-bold text-slate-700 mb-1.5">Catatan Progres <span class="text-rose-500">*</span></label>
                                <textarea name="catatan" id="catatan" rows="3" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:text-slate-400" placeholder="Tuliskan detail progres atau kendala yang dialami..." required></textarea>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{ files: [1], links: [1] }">
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-bold text-slate-700">Lampiran File (Opsional)</label>
                                        <button type="button" @click="files.push(files.length + 1)" class="text-xs font-bold text-blue-600 hover:text-blue-700">
                                            + Tambah File
                                        </button>
                                    </div>
                                    <template x-for="(file, index) in files" :key="index">
                                        <div class="mb-3 p-3 bg-blue-50 border border-blue-100 rounded-xl flex flex-col gap-2">
                                            <input type="text" name="file_names[]" class="w-full bg-white border border-slate-200 text-slate-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2" placeholder="Nama File (opsional)">
                                            <div class="flex items-center gap-2">
                                                <input type="file" name="files[]" class="flex-1 min-w-0 bg-white border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2 transition-all file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                                <button type="button" x-show="files.length > 1" @click="files.splice(index, 1)" class="p-2.5 text-rose-500 hover:text-rose-700 transition-colors bg-white rounded-lg shadow-sm border border-slate-200 shrink-0">
                                                    <i class="ph-bold ph-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                    <p class="text-[10px] text-slate-500 mt-1.5">Maks. ukuran 5MB (PDF, DOCX, ZIP, JPG, PNG)</p>
                                </div>
                                
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-bold text-slate-700">Tautan Terkait (Opsional)</label>
                                        <button type="button" @click="links.push(links.length + 1)" class="text-xs font-bold text-blue-600 hover:text-blue-700">
                                            + Tambah Tautan
                                        </button>
                                    </div>
                                    <template x-for="(link, index) in links" :key="index">
                                        <div class="mb-3 p-3 bg-blue-50 border border-blue-100 rounded-xl flex flex-col gap-2">
                                            <input type="text" name="link_names[]" class="w-full bg-white border border-slate-200 text-slate-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2" placeholder="Nama Tautan (opsional)">
                                            <div class="flex items-center gap-2">
                                                <input type="url" name="links[]" class="flex-1 min-w-0 bg-white border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2 transition-all" placeholder="https://...">
                                                <button type="button" x-show="links.length > 1" @click="links.splice(index, 1)" class="p-2.5 text-rose-500 hover:text-rose-700 transition-colors bg-white rounded-lg shadow-sm border border-slate-200 shrink-0">
                                                    <i class="ph-bold ph-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-end">
                            <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-blue-500/30 flex items-center justify-center gap-2 hover:shadow-md">
                                <i class="ph-fill ph-paper-plane-tilt text-lg"></i>
                                Kirim Progres
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            @if($latestReport)
            <div class="border-t border-slate-200 pt-8 mt-8">
                <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-paper-plane-tilt text-blue-500"></i> Progres yang Diajukan
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
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
