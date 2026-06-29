@extends('layouts.dashboard')

@section('title', 'Reviu Proker Non-Event')

@section('breadcrumbs')
    <span class="text-slate-700">Program Kerja Non-Event</span>
@endsection

@section('content')
<div class="space-y-6">
    <div class="mb-8">
        <p class="text-xs font-semibold text-brand-500 uppercase tracking-widest mb-1">Ketua Divisi</p>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">
            Reviu <span class="gradient-text">Tugas Proker</span>
        </h1>
        <p class="text-sm text-slate-500 mt-1.5 font-medium">Periksa dan setujui progres tugas non-event yang diajukan oleh anggota divisi Anda.</p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 flex items-start gap-3">
            <i class="ph-fill ph-check-circle text-xl mt-0.5"></i>
            <div>
                <p class="font-bold text-sm">Berhasil</p>
                <p class="text-sm mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl mb-6 flex items-start gap-3">
            <i class="ph-fill ph-warning-circle text-xl mt-0.5"></i>
            <div>
                <p class="font-bold text-sm">Gagal</p>
                <p class="text-sm mt-0.5">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-200 bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <i class="ph-fill ph-list-checks text-brand-500"></i> Menunggu Reviu 
                <span class="bg-brand-100 text-brand-700 text-xs py-0.5 px-2 rounded-full font-black ml-2">{{ $tasks->count() }}</span>
            </h3>
        </div>
        
        <div class="divide-y divide-slate-100">
            @forelse($tasks as $task)
                <div class="p-6 hover:bg-slate-50 transition-colors" x-data="{ openDetail: false, openRevise: false }">
                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="bg-brand-100 text-brand-700 text-[10px] font-black px-2 py-1 rounded uppercase tracking-widest">{{ $task->workProgram->name ?? 'Proker' }}</span>
                                <span class="bg-blue-100 text-blue-700 text-[10px] font-black px-2 py-1 rounded uppercase tracking-widest">
                                    <i class="ph-bold ph-clock mr-1"></i>Menunggu Review
                                </span>
                            </div>
                            <h4 class="text-lg font-bold text-slate-900 mb-1">{{ $task->title }}</h4>
                            <div class="flex items-center gap-2 text-sm text-slate-600 mb-4">
                                <i class="ph-fill ph-user-circle text-slate-400"></i>
                                <span>Dikerjakan oleh <span class="font-bold">{{ explode(' ', $task->assignee->name ?? 'Anggota')[0] }}</span></span>
                            </div>
                            
                            @php
                                $latestReport = $task->reports->first();
                            @endphp
                            
                            @if($latestReport)
                            <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-4 mb-4">
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Catatan Progres</p>
                                <p class="text-sm text-slate-700 line-clamp-3">{{ $latestReport->content }}</p>
                                
                                <button @click="openDetail = !openDetail" class="text-xs font-bold text-brand-600 hover:text-brand-700 mt-2 flex items-center gap-1">
                                    <span x-text="openDetail ? 'Sembunyikan Detail' : 'Lihat Detail Lengkap'"></span>
                                    <i class="ph-bold ph-caret-down transition-transform" :class="openDetail ? 'rotate-180' : ''"></i>
                                </button>
                                
                                <div x-show="openDetail" style="display: none;" class="mt-4 pt-4 border-t border-blue-100">
                                    <p class="text-sm text-slate-700 whitespace-pre-wrap mb-4">{{ $latestReport->content }}</p>
                                    
                                    @php 
                                        $atts = $latestReport->attachments; 
                                        $reportFiles = $atts['files'] ?? [];
                                        $reportLinks = $atts['links'] ?? [];
                                    @endphp
                                    
                                    @if(count($reportFiles) > 0 || count($reportLinks) > 0)
                                        <div class="space-y-3 mt-4">
                                            @if(count($reportFiles) > 0)
                                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Lampiran</p>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                    @foreach($reportFiles as $file)
                                                    <a href="{{ Storage::url($file['path']) }}" target="_blank" class="flex items-center gap-3 p-3 rounded-lg border border-slate-200 bg-white hover:border-brand-300 transition-colors">
                                                        <i class="ph-fill ph-file-text text-xl text-brand-500 shrink-0"></i>
                                                        <span class="text-xs font-semibold text-slate-700 truncate">{{ $file['name'] ?? 'File' }}</span>
                                                    </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                            
                                            @if(count($reportLinks) > 0)
                                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-3">Tautan</p>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                    @foreach($reportLinks as $link)
                                                    <a href="{{ $link['url'] }}" target="_blank" class="flex items-center gap-3 p-3 rounded-lg border border-slate-200 bg-white hover:border-blue-300 transition-colors">
                                                        <i class="ph-fill ph-link text-xl text-blue-500 shrink-0"></i>
                                                        <span class="text-xs font-semibold text-slate-700 truncate">{{ $link['name'] ?? 'Tautan' }}</span>
                                                    </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <div class="flex flex-row md:flex-col gap-3 shrink-0">
                            <form action="{{ route('kepengurusan.kadiv.proker.review.approve', $task->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full md:w-auto px-4 py-2.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white text-sm font-bold rounded-lg transition-all border border-emerald-200 hover:border-emerald-600 flex items-center justify-center gap-2 group" onclick="return confirm('Setujui dan tandai tugas ini selesai?')">
                                    <i class="ph-bold ph-check text-lg transition-transform group-hover:scale-110"></i>
                                    Setujui
                                </button>
                            </form>
                            
                            <button @click="openRevise = !openRevise" class="w-full md:w-auto px-4 py-2.5 bg-white text-rose-600 hover:bg-rose-50 text-sm font-bold rounded-lg transition-all border border-rose-200 flex items-center justify-center gap-2">
                                <i class="ph-bold ph-x text-lg"></i>
                                Revisi
                            </button>
                        </div>
                    </div>
                    
                    <!-- Revisi Form -->
                    <div x-show="openRevise" style="display: none;" class="mt-4 p-5 bg-rose-50/50 border border-rose-100 rounded-xl">
                        <form action="{{ route('kepengurusan.kadiv.proker.review.revise', $task->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <h5 class="text-sm font-bold text-slate-900 mb-2">Kembalikan untuk Revisi</h5>
                            <p class="text-xs text-slate-600 mb-3">Berikan catatan apa saja yang perlu diperbaiki oleh anggota.</p>
                            
                            <textarea name="revision_note" rows="3" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all placeholder:text-slate-400 mb-3" placeholder="Catatan revisi..." required></textarea>
                            
                            <div class="flex items-center gap-3">
                                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg transition-all shadow-sm">Kirim Revisi</button>
                                <button type="button" @click="openRevise = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-lg transition-all">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center flex flex-col items-center justify-center">
                    <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center mb-4">
                        <i class="ph-fill ph-check-circle text-4xl text-slate-300"></i>
                    </div>
                    <h4 class="text-lg font-bold text-slate-900 mb-1">Semua Selesai!</h4>
                    <p class="text-sm text-slate-500">Tidak ada tugas yang menunggu reviu saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
