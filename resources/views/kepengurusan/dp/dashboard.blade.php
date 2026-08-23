@extends('layouts.dashboard')

@section('title', 'Dashboard Pemantauan Dewan Penasihat')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">Halo, {{ auth()->user()->name }}! 👋</h1>
        <p class="text-sm text-slate-500 mt-2">Selamat datang di Panel Pemantauan Dewan Penasihat HIMASI periode {{ $activePeriod->name ?? 'Aktif' }}.</p>
    </div>
</div>

@if(!$activePeriod)
<div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 mb-8 flex gap-4">
    <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center shrink-0">
        <i class="ph-bold ph-warning text-2xl"></i>
    </div>
    <div>
        <h3 class="text-lg font-bold text-amber-900">Belum Ada Periode Aktif</h3>
        <p class="text-sm text-amber-700 mt-1">Sistem mendeteksi belum ada periode kepengurusan yang berjalan. Beberapa statistik mungkin bernilai nol.</p>
    </div>
</div>
@endif

{{-- Kartu Statistik Makro --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-brand-300 transition-colors">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-4">
                <i class="ph-bold ph-users text-2xl"></i>
            </div>
            <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Total Pengurus</p>
            <h3 class="text-3xl font-black text-slate-900">{{ number_format($stats['total_anggota']) }} <span class="text-sm text-slate-400 font-bold">Orang</span></h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-brand-300 transition-colors">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center mb-4">
                <i class="ph-bold ph-buildings text-2xl"></i>
            </div>
            <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Total Divisi & BPH</p>
            <h3 class="text-3xl font-black text-slate-900">{{ number_format($stats['total_divisi']) }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-brand-300 transition-colors">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-4">
                <i class="ph-bold ph-wallet text-2xl"></i>
            </div>
            <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Saldo Kas Himasi</p>
            <h3 class="text-2xl font-black text-slate-900 mt-1">Rp {{ number_format($stats['saldo_kas'], 0, ',', '.') }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-brand-300 transition-colors">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center mb-4">
                <i class="ph-bold ph-briefcase text-2xl"></i>
            </div>
            <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Proker Berjalan</p>
            <h3 class="text-3xl font-black text-slate-900">{{ number_format($stats['proker_berjalan']) }}</h3>
        </div>
    </div>
</div>

{{-- Layout Kolom --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
    
    {{-- Kolom Utama Kiri (Proker & Kotak Pesan) --}}
    <div class="xl:col-span-2 space-y-8">
        
        {{-- Progress Program Kerja Keseluruhan --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 relative overflow-hidden">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-black text-slate-900">Pencapaian Program Kerja Himpunan</h3>
                    <p class="text-sm text-slate-500 mt-1">Pantau pergerakan progres eksekusi program kerja secara keseluruhan (High-Level Overview).</p>
                </div>
                <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center shrink-0">
                    <i class="ph-bold ph-target text-xl text-slate-600"></i>
                </div>
            </div>

            <div class="mb-4">
                <div class="flex justify-between items-end mb-2">
                    <span class="text-3xl font-black text-brand-600">{{ $prokerMetrics['progress_percentage'] }}%</span>
                    <span class="text-sm font-bold text-slate-500">Selesai ({{ $prokerMetrics['completed'] }} dari {{ $prokerMetrics['total'] }})</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden flex">
                    <div class="bg-brand-500 h-3" style="width: {{ $prokerMetrics['progress_percentage'] }}%"></div>
                    @if($prokerMetrics['total'] > 0)
                        <div class="bg-amber-400 h-3" style="width: {{ ($prokerMetrics['ongoing'] / $prokerMetrics['total']) * 100 }}%"></div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-center">
                    <div class="text-xs font-black text-slate-400 uppercase">Perencanaan</div>
                    <div class="text-lg font-black text-slate-700">{{ $prokerMetrics['planning'] }}</div>
                </div>
                <div class="p-3 bg-amber-50 rounded-xl border border-amber-100 text-center">
                    <div class="text-xs font-black text-amber-500 uppercase">Sedang Berjalan</div>
                    <div class="text-lg font-black text-amber-700">{{ $prokerMetrics['ongoing'] }}</div>
                </div>
                <div class="p-3 bg-brand-50 rounded-xl border border-brand-100 text-center">
                    <div class="text-xs font-black text-brand-500 uppercase">Telah Selesai</div>
                    <div class="text-lg font-black text-brand-700">{{ $prokerMetrics['completed'] }}</div>
                </div>
                <div class="p-3 bg-red-50 rounded-xl border border-red-100 text-center">
                    <div class="text-xs font-black text-red-500 uppercase">Batal / Gagal</div>
                    <div class="text-lg font-black text-red-700">{{ $prokerMetrics['cancelled'] }}</div>
                </div>
            </div>

            {{-- Progress Per Divisi --}}
            <div class="mt-8 pt-6 border-t border-slate-100">
                <h4 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-chart-bar text-brand-500"></i> Progres Spesifik Per Divisi BPH
                </h4>
                <div class="space-y-4">
                    @forelse($divisionProgress as $div)
                        <div>
                            <div class="flex justify-between items-end mb-1.5">
                                <span class="text-xs font-bold text-slate-700">{{ $div->name }}</span>
                                <span class="text-[11px] font-black {{ $div->percentage >= 75 ? 'text-emerald-600' : ($div->percentage >= 40 ? 'text-amber-600' : 'text-brand-600') }}">
                                    {{ $div->percentage }}% <span class="font-bold text-slate-400">({{ $div->completed }}/{{ $div->total }})</span>
                                </span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                <div class="h-2 rounded-full {{ $div->percentage >= 75 ? 'bg-emerald-500' : ($div->percentage >= 40 ? 'bg-amber-400' : 'bg-brand-500') }}" style="width: {{ $div->percentage }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 italic">Belum ada data proker divisi.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Panel Kotak Pesan Evaluasi --}}
        <div class="bg-brand-50 rounded-2xl border border-brand-200 shadow-sm p-6 relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-brand-100/50 rounded-full group-hover:scale-125 transition-transform duration-500"></div>
            <div class="relative z-10 flex flex-col md:flex-row gap-6 items-center">
                <div class="w-16 h-16 bg-white text-brand-600 rounded-2xl shadow-sm flex items-center justify-center shrink-0">
                    <i class="ph-bold ph-envelope-open text-3xl"></i>
                </div>
                <div class="flex-1 text-center md:text-left">
                    <h3 class="text-xl font-black text-brand-900 mb-1">Berikan Catatan Evaluasi</h3>
                    <p class="text-sm text-brand-700 font-medium">Beri teguran, arahan strategis, atau masukan berharga langsung kepada Ketua Himpunan & Pengurus Inti (BPH) melalui fitur Pesan internal.</p>
                </div>
                <div class="shrink-0 w-full md:w-auto">
                    <a href="{{ route('messages.index') }}" class="block w-full text-center px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl shadow-sm transition-colors">
                        <i class="ph-bold ph-paper-plane-tilt mr-2"></i> Kirim Arahan
                    </a>
                </div>
            </div>
        </div>

    </div>

    {{-- Kolom Kanan (Informasi Administrasi) --}}
    <div class="space-y-6">
        
        {{-- Agenda Terdekat --}}
        <div class="bg-gradient-to-br from-brand-900 via-brand-800 to-indigo-900 rounded-2xl shadow-sm p-6 text-white relative overflow-hidden border border-brand-700/50">
            <div class="absolute right-0 top-0 opacity-10 pointer-events-none">
                <i class="ph-fill ph-calendar-star text-9xl -mr-6 -mt-6"></i>
            </div>
            
            <div class="flex justify-between items-center mb-6 relative z-10">
                <h3 class="text-lg font-black">Agenda Terdekat</h3>
            </div>
            
            <div class="space-y-4 relative z-10">
                @forelse($upcomingEvents as $event)
                    <div class="bg-white/10 rounded-xl p-4 border border-white/10 hover:bg-white/20 transition-colors">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-bold text-sm leading-tight">{{ $event->name }}</h4>
                            @if($event->status === 'ongoing')
                                <span class="px-2 py-0.5 bg-emerald-500/20 text-emerald-300 rounded text-[10px] font-black uppercase shadow-sm">Berjalan</span>
                            @else
                                <span class="px-2 py-0.5 bg-amber-500/20 text-amber-300 rounded text-[10px] font-black uppercase shadow-sm">Persiapan</span>
                            @endif
                        </div>
                        
                        <div class="flex items-center gap-2 text-xs text-indigo-200 mt-2 font-medium">
                            <i class="ph-bold ph-calendar-blank"></i>
                            <span>{{ $event->event_date->translatedFormat('d M Y') }}</span>
                        </div>
                        
                        @if($event->location)
                        <div class="flex items-center gap-2 text-xs text-indigo-300 mt-1">
                            <i class="ph-bold ph-map-pin"></i>
                            <span class="truncate">{{ $event->location }}</span>
                        </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-6 border border-dashed border-white/20 rounded-xl bg-white/5">
                        <i class="ph-bold ph-calendar-check text-2xl text-indigo-300 mb-2"></i>
                        <p class="text-sm font-medium text-indigo-200">Tidak ada agenda besar dalam waktu dekat.</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        {{-- Surat Terbaru --}}
        <div class="bg-white rounded-2xl shadow-sm p-5 border border-slate-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <i class="ph-fill ph-envelope-simple text-brand-500"></i> Surat Terbaru
                </h3>
            </div>
            <div class="space-y-3">
                @forelse($recentLetters as $letter)
                    <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100">
                        <div class="w-8 h-8 rounded-lg {{ $letter->type === 'masuk' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }} flex items-center justify-center shrink-0">
                            <i class="ph-bold {{ $letter->type === 'masuk' ? 'ph-download-simple' : 'ph-upload-simple' }}"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-slate-800 truncate">{{ $letter->letter_number ?? 'Belum ada nomor' }}</p>
                            <p class="text-[10px] font-semibold text-slate-500 truncate mt-0.5">{{ $letter->subject }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-[11px] font-medium text-slate-400 text-center py-2">Belum ada surat masuk/keluar.</p>
                @endforelse
            </div>
        </div>

        {{-- Transaksi Kas --}}
        <div class="bg-white rounded-2xl shadow-sm p-5 border border-slate-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <i class="ph-fill ph-wallet text-emerald-500"></i> Transaksi Kas Terakhir
                </h3>
            </div>
            <div class="space-y-3">
                @forelse($recentTransactions as $trx)
                    <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg {{ $trx->type === 'pemasukan' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }} flex items-center justify-center shrink-0">
                                <i class="ph-bold {{ $trx->type === 'pemasukan' ? 'ph-trend-up' : 'ph-trend-down' }}"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-slate-800 truncate">{{ $trx->description }}</p>
                                <p class="text-[10px] font-semibold text-slate-400 truncate mt-0.5">{{ \Carbon\Carbon::parse($trx->date)->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="text-xs font-black {{ $trx->type === 'pemasukan' ? 'text-emerald-600' : 'text-rose-600' }} shrink-0">
                            {{ $trx->type === 'pemasukan' ? '+' : '-' }}Rp{{ number_format($trx->amount, 0, ',', '.') }}
                        </div>
                    </div>
                @empty
                    <p class="text-[11px] font-medium text-slate-400 text-center py-2">Belum ada transaksi.</p>
                @endforelse
            </div>
        </div>

        {{-- Rapat Terbaru --}}
        <div class="bg-white rounded-2xl shadow-sm p-5 border border-slate-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <i class="ph-fill ph-users-three text-indigo-500"></i> Risalah Rapat Terbaru
                </h3>
            </div>
            <div class="space-y-3">
                @forelse($recentMeetings as $meeting)
                    <div class="p-3 rounded-xl border border-slate-100 hover:border-indigo-200 hover:shadow-sm transition-all bg-slate-50 hover:bg-white">
                        <p class="text-xs font-bold text-slate-800 truncate mb-1">{{ $meeting->title }}</p>
                        @if($meeting->agenda)
                            <p class="text-[10px] text-slate-500 truncate mt-0.5">{{ Str::limit($meeting->agenda, 50) }}</p>
                        @endif
                        <div class="flex items-center gap-2 text-[10px] font-semibold text-slate-400 mt-1.5">
                            <span class="flex items-center gap-1"><i class="ph-fill ph-calendar-blank text-indigo-400"></i> {{ \Carbon\Carbon::parse($meeting->date)->format('d M Y') }}</span>
                            @if($meeting->location)
                                <span class="flex items-center gap-1"><i class="ph-fill ph-map-pin text-rose-400"></i> {{ Str::limit($meeting->location, 15) }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-[11px] font-medium text-slate-400 text-center py-2">Belum ada data rapat.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
