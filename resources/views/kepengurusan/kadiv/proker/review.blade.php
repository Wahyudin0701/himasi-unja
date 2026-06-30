@extends('layouts.dashboard')

@section('title', 'Reviu Jurnal Progres')

@section('breadcrumbs')
    <span class="text-slate-700">Program Kerja Non-Event</span>
@endsection

@section('content')
<div class="space-y-6">
    <div class="mb-8">
        <p class="text-xs font-semibold text-brand-500 uppercase tracking-widest mb-1">Ketua Divisi</p>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">
            Reviu <span class="gradient-text">Jurnal Progres</span>
        </h1>
        <p class="text-sm text-slate-500 mt-1.5 font-medium">Periksa dan setujui jurnal laporan progres yang diajukan oleh PJ program kerja Anda.</p>
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
                <span class="bg-brand-100 text-brand-700 text-xs py-0.5 px-2 rounded-full font-black ml-2">{{ $logs->count() }}</span>
            </h3>
        </div>
        
        <div class="divide-y divide-slate-100">
            @forelse($logs as $log)
                <div class="p-6 hover:bg-slate-50 transition-colors" x-data="{ openRevise: false }">
                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="bg-brand-100 text-brand-700 text-[10px] font-black px-2 py-1 rounded uppercase tracking-widest">{{ $log->workProgram->name ?? 'Proker' }}</span>
                                <span class="bg-blue-100 text-blue-700 text-[10px] font-black px-2 py-1 rounded uppercase tracking-widest">
                                    <i class="ph-bold ph-clock mr-1"></i>Menunggu Reviu
                                </span>
                            </div>
                            <h4 class="text-lg font-bold text-slate-900 mb-1">Laporan Progres</h4>
                            <div class="flex items-center gap-2 text-sm text-slate-600 mb-4 border-b border-slate-100 pb-4">
                                <i class="ph-fill ph-user-circle text-slate-400"></i>
                                <span>Dilaporkan oleh <span class="font-bold">{{ explode(' ', $log->author->name ?? 'Anggota')[0] }}</span></span>
                                <span class="text-slate-300">&bull;</span>
                                <span class="text-xs">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                            
                            <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 mb-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Isi Laporan</p>
                                    <span class="ml-auto bg-brand-50 text-brand-600 px-2 py-1 rounded-md text-[10px] font-bold border border-brand-100">Klaim Progres: {{ $log->progress_update }}%</span>
                                </div>
                                <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ $log->content }}</p>
                                
                                @if($log->attachment)
                                <div class="mt-4 pt-4 border-t border-slate-200">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Lampiran Bukti</p>
                                    <a href="{{ Storage::url($log->attachment) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white border border-slate-200 hover:border-brand-300 text-xs font-bold text-slate-600 hover:text-brand-600 transition-colors">
                                        <i class="ph-bold ph-paperclip text-lg"></i>
                                        Lihat File Lampiran
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="shrink-0 flex flex-col gap-3 min-w-[200px]">
                            <!-- Approve Button -->
                            <form action="{{ route('kepengurusan.kadiv.proker.review.log', $log->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-all hover:-translate-y-0.5">
                                    <i class="ph-bold ph-check text-lg"></i>
                                    Setujui Laporan
                                </button>
                            </form>
                            
                            <!-- Revise Button -->
                            <button @click="openRevise = !openRevise" class="w-full flex items-center justify-center gap-2 bg-white border-2 border-amber-500 hover:bg-amber-50 text-amber-600 px-4 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-colors">
                                <i class="ph-bold ph-pencil-simple text-lg"></i>
                                Minta Revisi
                            </button>
                        </div>
                    </div>
                    
                    <!-- Revise Form (Hidden by default) -->
                    <div x-show="openRevise" x-transition.opacity style="display: none;" class="mt-6 pt-6 border-t border-slate-200">
                        <form action="{{ route('kepengurusan.kadiv.proker.review.log', $log->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="action" value="revise">
                            
                            <label class="block text-sm font-bold text-slate-900 mb-2">
                                Catatan Revisi <span class="text-rose-500">*</span>
                            </label>
                            <p class="text-xs text-slate-500 mb-3">Berikan catatan apa yang perlu diperbaiki atau ditambahkan oleh anggota.</p>
                            
                            <textarea name="feedback" rows="3" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all text-sm mb-4" placeholder="Contoh: Laporan belum melampirkan file desain. Tolong lampirkan file desainnya ya."></textarea>
                            
                            <div class="flex justify-end gap-3">
                                <button type="button" @click="openRevise = false" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">
                                    Batal
                                </button>
                                <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm font-bold transition-all shadow-sm flex items-center gap-2">
                                    <i class="ph-bold ph-paper-plane-tilt"></i>
                                    Kirim Revisi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 px-4">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                        <i class="ph-fill ph-check-circle text-4xl text-slate-300"></i>
                    </div>
                    <h4 class="text-lg font-bold text-slate-900 mb-2">Semua Tuntas!</h4>
                    <p class="text-slate-500 text-sm max-w-sm mx-auto">Tidak ada laporan jurnal yang menunggu untuk direviu saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
