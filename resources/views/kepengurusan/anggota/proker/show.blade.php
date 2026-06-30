@extends('layouts.dashboard')

@section('title', 'Detail Program Kerja')

@section('breadcrumbs')
    <a href="{{ route('kepengurusan.anggota.proker.index') }}" class="text-slate-500 hover:text-brand-500 transition-colors">Program Kerja</a>
    <i class="ph-bold ph-caret-right text-xs text-slate-400 mx-2"></i>
    <span class="text-slate-700">Detail & Jurnal</span>
@endsection

@section('content')

    <div class="mb-8 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <p class="text-xs font-semibold text-brand-500 uppercase tracking-widest">Detail Proker</p>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                    @if($proker->status == 'planning') bg-slate-100 text-slate-600
                    @elseif($proker->status == 'ongoing') bg-blue-100 text-blue-600
                    @elseif($proker->status == 'completed') bg-emerald-100 text-emerald-600
                    @elseif($proker->status == 'cancelled') bg-rose-100 text-rose-600
                    @endif
                ">
                    {{ $proker->status }}
                </span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight mb-2">
                {{ $proker->name }}
            </h1>
            <p class="text-sm text-slate-500 font-medium">{{ $proker->description }}</p>
        </div>
        <div class="shrink-0 flex items-center gap-3 bg-white p-3 rounded-2xl border border-slate-200 shadow-sm">
            <div class="text-right">
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Progres</span>
                <span class="block text-xl font-black text-brand-600">{{ $proker->progress_percentage }}%</span>
            </div>
            <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $proker->progress_percentage == 100 ? 'bg-emerald-100 text-emerald-600' : 'bg-brand-50 text-brand-600' }}">
                <i class="ph-bold ph-chart-pie-slice text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Kolom Kiri: Riwayat Jurnal -->
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
                                    <input type="number" name="progress_update" required min="{{ $proker->progress_percentage }}" max="100" value="{{ $proker->progress_percentage }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition-all text-sm font-bold text-slate-900">
                                    <p class="text-[10px] text-slate-400 mt-1">Nilai progres tidak bisa lebih rendah dari progres saat ini ({{ $proker->progress_percentage }}%).</p>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Lampiran Bukti (Opsional)</label>
                                    <input type="file" name="attachment" class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition-all text-sm font-medium text-slate-900 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                                </div>
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

            <!-- Riwayat Jurnal -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden w-full">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i class="ph-bold ph-notebook text-brand-500"></i>
                        Riwayat Jurnal Laporan
                    </h3>
                </div>
                <div class="p-6 bg-slate-50">
                    @if(isset($logs) && $logs->count() > 0)
                        <div class="space-y-4 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">
                            @foreach($logs as $log)
                                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-slate-50 bg-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2">
                                        @if($log->status == 'approved')
                                            <i class="ph-bold ph-check text-emerald-500"></i>
                                        @elseif($log->status == 'revised')
                                            <i class="ph-bold ph-pencil text-amber-500"></i>
                                        @else
                                            <i class="ph-bold ph-clock text-slate-400"></i>
                                        @endif
                                    </div>
                                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:border-brand-300 transition-colors">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-[10px] font-bold text-slate-400 flex items-center gap-1">
                                                <i class="ph-bold ph-calendar-blank"></i>
                                                {{ $log->created_at->format('d M Y, H:i') }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                                @if($log->status == 'pending') bg-slate-100 text-slate-600
                                                @elseif($log->status == 'revised') bg-amber-100 text-amber-600
                                                @elseif($log->status == 'approved') bg-emerald-100 text-emerald-600
                                                @endif
                                            ">
                                                {{ $log->status }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-slate-700 font-medium mb-3 leading-relaxed">{{ $log->content }}</p>
                                        <div class="flex items-center gap-4 text-xs font-semibold text-slate-500 border-t border-slate-100 pt-3">
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
                        <div class="text-center py-10 px-4 bg-white rounded-2xl border border-dashed border-slate-200">
                            <div class="w-14 h-14 rounded-full bg-slate-50 border border-slate-100 shadow-sm flex items-center justify-center mx-auto mb-3">
                                <i class="ph-fill ph-notebook text-slate-300 text-2xl"></i>
                            </div>
                            <h4 class="text-sm font-bold text-slate-900 mb-1">Belum Ada Riwayat Laporan</h4>
                            <p class="text-xs text-slate-500 font-medium max-w-sm mx-auto">Silakan buat jurnal progres pertama Anda untuk program kerja ini.</p>
                        </div>
                    @endif
                </div>
            </div>
            
        </div>
        
        <!-- Kolom Kanan: Info Proker -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 relative overflow-hidden group hover:border-brand-300 transition-colors">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity group-hover:scale-110 duration-500">
                    <i class="ph-fill ph-info text-6xl text-brand-600"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2 relative z-10">
                    <i class="ph-fill ph-info text-brand-500"></i> Informasi Proker
                </h3>
                <div class="space-y-4 relative z-10">
                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Status Saat Ini</span>
                        <span class="font-bold text-sm text-slate-800 capitalize">{{ $proker->status }}</span>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Penanggung Jawab (Anda)</span>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="w-6 h-6 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center text-[10px] font-bold shrink-0">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <span class="text-sm font-bold text-slate-700">{{ $user->name }}</span>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Waktu Pelaksanaan</span>
                        <span class="font-bold text-sm text-slate-700">
                            {{ $proker->start_date ? \Carbon\Carbon::parse($proker->start_date)->format('d M Y') : 'Belum ditentukan' }} 
                            <i class="ph-bold ph-arrow-right text-slate-400 mx-1"></i> 
                            {{ $proker->end_date ? \Carbon\Carbon::parse($proker->end_date)->format('d M Y') : 'Belum ditentukan' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
