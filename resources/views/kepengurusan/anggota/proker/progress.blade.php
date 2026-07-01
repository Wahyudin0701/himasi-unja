@extends('layouts.dashboard')

@section('title', 'Upload Progres')

@section('breadcrumbs')
    <a href="{{ route('kepengurusan.anggota.proker.index') }}" class="text-slate-500 hover:text-brand-500 transition-colors">Program Kerja</a>
    <i class="ph-bold ph-caret-right text-xs text-slate-400 mx-2"></i>
    <a href="{{ route('kepengurusan.anggota.proker.show', $proker->id) }}" class="text-slate-500 hover:text-brand-500 transition-colors">Detail</a>
    <i class="ph-bold ph-caret-right text-xs text-slate-400 mx-2"></i>
    <span class="text-slate-700">Upload Progres</span>
@endsection

@section('content')

{{-- ===== HEADER ===== --}}
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">
            Jurnal Progres Program Kerja
        </h1>
        <p class="text-sm text-slate-500 mt-1.5 font-medium">Unggah laporan harian dan pantau riwayat progres: <span class="font-bold text-brand-600">{{ $proker->name }}</span></p>
    </div>
    <div class="flex items-center shrink-0">
        <a href="{{ route('kepengurusan.anggota.proker.show', $proker->id) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-brand-600 transition-colors mt-1">
            <i class="ph-bold ph-arrow-left"></i>
            Kembali ke Detail
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Tambah Jurnal Progres -->
        @if($proker->status !== 'completed' && $proker->status !== 'cancelled')
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden w-full">
            <div class="p-5 border-b border-slate-100 bg-slate-50 flex items-center gap-2">
                <i class="ph-bold ph-plus-circle text-brand-500 text-lg"></i>
                <h3 class="text-sm font-bold text-slate-900">Buat Jurnal Progres Baru</h3>
            </div>
            <div class="p-5">
                <form action="{{ route('kepengurusan.anggota.proker.logs.store', $proker->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Apa yang sudah Anda kerjakan? <span class="text-rose-500">*</span></label>
                            <textarea name="content" required rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition-all text-sm font-medium text-slate-900 placeholder:text-slate-400" placeholder="Jelaskan progres pekerjaan Anda hari ini..."></textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Estimasi Progres Saat Ini (%) <span class="text-rose-500">*</span></label>
                                <input type="number" name="progress_update" required min="{{ $logs->first()->progress_update ?? 0 }}" max="100" value="{{ $logs->first()->progress_update ?? 0 }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition-all text-sm font-bold text-slate-900">
                                <p class="text-[10px] text-slate-400 mt-1">Nilai progres tidak bisa lebih rendah dari progres saat ini ({{ $logs->first()->progress_update ?? 0 }}%).</p>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Lampiran Bukti (Opsional)</label>
                                <input type="file" name="attachment" class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition-all text-sm font-medium text-slate-900 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Tautan / Link Terkait (Opsional)</label>
                            <input type="url" name="link" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition-all text-sm font-medium text-slate-900 placeholder:text-slate-400" placeholder="https://docs.google.com/... atau tautan lainnya">
                        </div>
                    </div>
                    <div class="mt-5 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-sm font-bold transition-all shadow-sm flex items-center gap-2">
                            <i class="ph-bold ph-paper-plane-tilt"></i>
                            Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

    </div>
    
    <!-- Kolom Kanan: Info Ringkas Progres -->
    <div class="space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 relative overflow-hidden group hover:border-brand-300 transition-colors">
            <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2 relative z-10">
                <i class="ph-fill ph-chart-pie-slice text-brand-500"></i> Pencapaian Saat Ini
            </h3>
            <div class="flex items-center gap-4 relative z-10">
                @php 
                    $currentProgress = isset($logs) && $logs->count() > 0 ? $logs->first()->progress_update : 0; 
                    $circumference = 2 * pi() * 28;
                    $strokeDashoffset = $circumference - ($currentProgress / 100) * $circumference;
                @endphp
                <div class="relative w-16 h-16 flex items-center justify-center shrink-0">
                    <svg class="absolute inset-0 w-full h-full -rotate-90" viewBox="0 0 64 64">
                        <circle class="{{ $currentProgress == 100 ? 'text-emerald-50' : 'text-slate-100' }}" stroke-width="6" stroke="currentColor" fill="transparent" r="28" cx="32" cy="32" />
                        <circle class="{{ $currentProgress == 100 ? 'text-emerald-500' : 'text-brand-500' }} transition-all duration-1000 ease-out" stroke-width="6" stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $strokeDashoffset }}" stroke-linecap="round" stroke="currentColor" fill="transparent" r="28" cx="32" cy="32" />
                    </svg>
                    <span class="relative z-10 text-lg font-black {{ $currentProgress == 100 ? 'text-emerald-600' : 'text-brand-600' }}">{{ $currentProgress }}%</span>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium mb-1">Total Progres Klaim Terakhir</p>
                    <p class="text-sm font-bold text-slate-900">Tercatat: {{ $logs->first() ? $logs->first()->created_at->format('d M Y') : '-' }}</p>
                </div>
            </div>
        </div>
        <!-- Riwayat Progres Full -->
        <!-- Riwayat Progres Full -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <i class="ph-bold ph-list-dashes text-brand-500 text-lg"></i>
                    Riwayat Progres
                </h3>
            </div>
            <div class="p-6 bg-slate-50 relative">
                @if(isset($logs) && $logs->count() > 0)
                    <div class="space-y-6 relative before:absolute before:inset-0 before:ml-3 before:-translate-x-px before:h-full before:w-0.5 before:bg-slate-200">
                        @foreach($logs as $log)
                            <div class="relative flex items-start gap-4">
                                <!-- Timeline Icon -->
                                <div class="relative z-10 flex items-center justify-center w-6 h-6 mt-1 rounded-full border-2 border-slate-50 bg-white shadow-sm shrink-0">
                                    <i class="ph-bold ph-clock text-slate-400 text-[10px]"></i>
                                </div>
                                
                                <div class="flex-1 bg-white p-4 rounded-2xl border {{ $loop->first ? 'border-brand-300' : 'border-slate-200' }} shadow-sm hover:border-brand-300 transition-colors">
                                {{-- Row 1: Author + Status --}}
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center gap-2">
                                        @if($log->author && $log->author->avatar)
                                            <img src="{{ file_exists(public_path('storage/' . $log->author->avatar)) ? asset('storage/' . $log->author->avatar) : asset($log->author->avatar) }}" alt="{{ $log->author->name }}" class="w-6 h-6 rounded-full object-cover">
                                        @else
                                            <div class="w-6 h-6 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center text-[9px] font-bold shrink-0">
                                                {{ strtoupper(substr($log->author->name ?? '-', 0, 2)) }}
                                            </div>
                                        @endif
                                        <span class="text-xs font-bold text-slate-700 truncate max-w-[120px]">{{ $log->author->name ?? '-' }}</span>
                                    </div>
                                </div>
                                {{-- Row 2: Date --}}
                                <p class="text-[10px] text-slate-400 font-medium mb-3 flex items-center gap-1">
                                    <i class="ph-bold ph-clock"></i>
                                    {{ $log->created_at->format('d M Y, H:i') }}
                                </p>
                                {{-- Content --}}
                                <p class="text-sm text-slate-700 font-medium mb-3 leading-relaxed">{{ $log->content }}</p>
                                {{-- Footer --}}
                                <div class="flex flex-wrap items-center gap-4 text-xs font-semibold text-slate-500 border-t border-slate-100 pt-2.5">
                                    <div class="flex items-center gap-1.5">
                                        <i class="ph-bold ph-trend-up text-brand-500"></i>
                                        Klaim Progres: <span class="text-brand-600 font-bold">{{ $log->progress_update }}%</span>
                                    </div>
                                    @if($log->attachment)
                                        <a href="{{ asset('storage/' . $log->attachment) }}" target="_blank" class="flex items-center gap-1.5 text-blue-500 hover:text-blue-600">
                                            <i class="ph-bold ph-paperclip"></i>
                                            Lihat Bukti
                                        </a>
                                    @endif
                                    @if($log->link)
                                        <a href="{{ $log->link }}" target="_blank" class="flex items-center gap-1.5 text-indigo-500 hover:text-indigo-600">
                                            <i class="ph-bold ph-link"></i>
                                            Buka Tautan
                                        </a>
                                    @endif
                                </div>
                                @if($log->feedback)
                                    <div class="mt-3 p-3 {{ $log->status == 'revised' ? 'bg-amber-50 border-amber-100' : 'bg-slate-50 border-slate-100' }} rounded-xl border">
                                        <p class="text-xs text-slate-800 font-medium"><span class="font-bold">Catatan Kadiv:</span> {{ $log->feedback }}</p>
                                    </div>
                                @endif
                            </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 px-4 bg-white rounded-2xl border border-slate-200 shadow-sm relative z-10">
                        <div class="w-10 h-10 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mx-auto mb-2">
                            <i class="ph-fill ph-notebook text-slate-300 text-lg"></i>
                        </div>
                        <p class="text-xs text-slate-500 font-medium">Belum ada progres</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    </div>
</div>

@endsection
