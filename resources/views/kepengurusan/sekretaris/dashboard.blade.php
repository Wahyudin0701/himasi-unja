@extends('layouts.dashboard')

@section('title', 'Dasbor Sekretaris')

@section('breadcrumbs')
    <span class="text-slate-700">Kepengurusan</span>
@endsection

@section('content')

{{-- ===== HEADER ===== --}}
<div class="mb-8">
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">
                Dasbor Sekretaris HIMA
            </h1>
            <p class="text-sm text-slate-500 mt-1.5 font-medium">Selamat datang, {{ auth()->user()->name }}! Kelola data pengurus dan divisi di sini.</p>
        </div>
        @if($activePeriod)
            <div class="flex items-center gap-3">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-brand-50 border border-brand-100 text-brand-700 rounded-lg text-sm font-bold shadow-sm">
                    <div class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></div>
                    Periode Aktif: {{ $activePeriod->name }}
                </div>
            </div>
        @endif
    </div>
</div>

@if(!$activePeriod)
    <div class="bg-amber-50 border border-amber-200 text-amber-700 p-4 rounded-xl mb-6 flex gap-3 text-sm">
        <i class="ph-fill ph-warning-circle text-lg shrink-0"></i>
        <p><strong>Belum ada periode kepengurusan yang aktif.</strong> Harap minta Admin untuk mengaktifkan salah satu periode terlebih dahulu.</p>
    </div>
@endif

    {{-- ===== STATISTIK ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Total Surat Masuk --}}
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-blue-300 transition-colors">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full group-hover:bg-blue-100 transition-colors"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-slate-500 mb-1">Surat Masuk</p>
                    <h3 class="text-3xl font-black text-slate-900">{{ $totalSuratMasuk }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                    <i class="ph-bold ph-envelope-simple text-2xl"></i>
                </div>
            </div>
        </div>

        {{-- Total Surat Keluar --}}
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-green-300 transition-colors">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-green-50 rounded-full group-hover:bg-green-100 transition-colors"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-slate-500 mb-1">Surat Keluar</p>
                    <h3 class="text-3xl font-black text-slate-900">{{ $totalSuratKeluar }}</h3>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600">
                    <i class="ph-bold ph-paper-plane-right text-2xl"></i>
                </div>
            </div>
        </div>

        {{-- Total Templat --}}
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-orange-300 transition-colors">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-50 rounded-full group-hover:bg-orange-100 transition-colors"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-slate-500 mb-1">Templat Dokumen</p>
                    <h3 class="text-3xl font-black text-slate-900">{{ $totalTemplat }}</h3>
                </div>
                <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600">
                    <i class="ph-bold ph-files text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== QUICK ACTIONS & JADWAL RAPAT ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Jadwal Rapat Terdekat</h2>
                        <p class="text-xs text-slate-500 mt-1">Agenda pertemuan pengurus yang akan datang</p>
                    </div>
                </div>
                
                @if($upcomingMeetings->count() > 0)
                    <div class="divide-y divide-slate-100">
                        @foreach($upcomingMeetings as $meeting)
                            <div class="p-4 sm:p-6 hover:bg-slate-50 transition-colors flex items-center gap-4">
                                <div class="w-14 h-14 rounded-xl flex-shrink-0 flex flex-col items-center justify-center bg-slate-100 border border-slate-200">
                                    <span class="text-xs font-bold text-slate-500 uppercase">{{ \Carbon\Carbon::parse($meeting->date)->translatedFormat('M') }}</span>
                                    <span class="text-xl font-black text-brand-600 leading-none mt-0.5">{{ \Carbon\Carbon::parse($meeting->date)->format('d') }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        <h3 class="text-sm font-bold text-slate-900 truncate">{{ $meeting->title }}</h3>
                                        @if($meeting->type == 'pleno')
                                            <span class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 text-[10px] font-bold uppercase tracking-wider">Pleno</span>
                                        @elseif($meeting->type == 'ph')
                                            <span class="px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 text-[10px] font-bold uppercase tracking-wider">PH</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[10px] font-bold uppercase tracking-wider">{{ $meeting->type }}</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-4 text-xs text-slate-500">
                                        <div class="flex items-center gap-1.5">
                                            <i class="ph-bold ph-clock"></i>
                                            <span>{{ \Carbon\Carbon::parse($meeting->time)->format('H:i') }} WIB</span>
                                        </div>
                                        <div class="flex items-center gap-1.5 truncate">
                                            <i class="ph-bold ph-map-pin"></i>
                                            <span class="truncate">{{ $meeting->location }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <a href="{{ route('kepengurusan.sekretaris.meetings.show', $meeting) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-brand-600 hover:bg-brand-50 transition-colors">
                                        <i class="ph-bold ph-caret-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="ph-bold ph-calendar-blank text-2xl text-slate-400"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 mb-1">Tidak ada rapat dalam waktu dekat</h3>
                        <p class="text-xs text-slate-500">Belum ada agenda rapat yang dijadwalkan.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Quick Actions --}}
        <div>
            <div class="bg-brand-600 rounded-2xl p-6 text-white shadow-md">
                <h3 class="font-bold mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-lightning text-xl text-yellow-300"></i>
                    Akses Cepat
                </h3>
                <div class="space-y-2">
                    <a href="{{ route('kepengurusan.sekretaris.organization-letters.index') }}" class="flex items-center justify-between p-3 rounded-xl bg-white/10 hover:bg-white/20 transition-colors group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                                <i class="ph-fill ph-envelope-simple"></i>
                            </div>
                            <span class="text-sm font-semibold">Surat Himpunan</span>
                        </div>
                        <i class="ph-bold ph-arrow-right opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all"></i>
                    </a>
                    
                    <a href="{{ route('kepengurusan.sekretaris.templates.index') }}" class="flex items-center justify-between p-3 rounded-xl bg-white/10 hover:bg-white/20 transition-colors group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                                <i class="ph-fill ph-files"></i>
                            </div>
                            <span class="text-sm font-semibold">Bank Templat</span>
                        </div>
                        <i class="ph-bold ph-arrow-right opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all"></i>
                    </a>

                    <a href="{{ route('kepengurusan.sekretaris.meetings.index') }}" class="flex items-center justify-between p-3 rounded-xl bg-white/10 hover:bg-white/20 transition-colors group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                                <i class="ph-fill ph-users-three"></i>
                            </div>
                            <span class="text-sm font-semibold">Manajemen Rapat</span>
                        </div>
                        <i class="ph-bold ph-arrow-right opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
