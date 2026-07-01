@extends('layouts.dashboard')

@section('title', 'Detail Program Kerja')

@section('breadcrumbs')
    <span class="text-slate-700">Program Kerja</span>
@endsection

@section('content')

{{-- ===== HEADER ===== --}}
<div class="mb-8 flex flex-row items-start justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">
            Detail Program Kerja
        </h1>
        <p class="text-sm text-slate-500 mt-1.5 font-medium">Informasi lengkap terkait program kerja divisi.</p>
    </div>
    <div class="flex items-center shrink-0">
        <a href="{{ route('kepengurusan.anggota.proker.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-brand-600 transition-colors mt-1">
            <i class="ph-bold ph-arrow-left"></i>
            Kembali
        </a>
    </div>
</div>

{{-- ===== DETAIL PROKER ===== --}}
<div class="flex flex-col lg:flex-row gap-6 mb-6 items-start">
    @if($proker->type !== 'event')
    <div class="w-full lg:w-2/3 space-y-6">
    @else
    <div class="w-full space-y-6">
    @endif
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        {{-- Card Header: Nama + Badge + Aksi --}}
        <div class="p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3 flex-wrap">
                <h2 class="text-xl font-black text-slate-900 tracking-tight">{{ $proker->name }}</h2>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                    @if($proker->status === 'ongoing') bg-emerald-50 text-emerald-700 border border-emerald-200
                    @elseif($proker->status === 'planning') bg-brand-50 text-brand-700 border border-brand-200
                    @elseif($proker->status === 'completed') bg-slate-100 text-slate-600 border border-slate-200
                    @else bg-rose-50 text-rose-700 border border-rose-200
                    @endif">
                    {{ $proker->status }}
                </span>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                @if($proker->type !== 'event' && $proker->status !== 'completed' && $proker->status !== 'cancelled')
                <a href="{{ route('kepengurusan.anggota.proker.progress', $proker->id) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white transition-all text-sm font-bold shadow-sm">
                    <i class="ph-bold ph-upload-simple"></i>
                    Upload Progres
                </a>
                @endif
            </div>
        </div>

        <div class="divide-y divide-slate-100">

            {{-- SECTION 1: PJ / Penanggung Jawab --}}
            <div class="p-6 sm:p-8">
                @if($proker->type === 'event' && class_exists('\App\Models\Kepanitiaan\Event'))
                    @php
                        $event = \App\Models\Kepanitiaan\Event::where('work_program_id', $proker->id)
                                    ->with(['committees.user', 'committees.role'])
                                    ->first();
                        $inti = [];
                        if($event) {
                            $intiRoles = \App\Models\Kepanitiaan\CommitteeRole::where('scope', 'inti')->orderBy('level')->get();
                            foreach($intiRoles as $role) {
                                $inti[$role->slug] = [
                                    'name' => $role->name,
                                    'committee' => $event->committees->where('committee_role_id', $role->id)->first()
                                ];
                            }
                        }
                    @endphp
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Susunan Panitia Inti</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @if($event)
                            @foreach($inti as $slug => $data)
                                @php
                                    $person = $data['committee']?->user;
                                    $initials = $person ? strtoupper(collect(explode(' ', $person->name))->map(fn($w) => substr($w,0,1))->take(2)->implode('')) : '?';
                                @endphp
                                <div class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-200 rounded-xl">
                                    <div class="w-9 h-9 rounded-full shrink-0 flex items-center justify-center text-xs font-black {{ $person ? 'bg-brand-100 text-brand-700' : 'bg-slate-200 text-slate-400' }}">
                                        {{ $initials }}
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-tight">{{ $data['name'] }}</span>
                                        <p class="text-sm font-bold {{ !$person ? 'text-slate-400 italic' : 'text-slate-900' }} truncate leading-snug mt-0.5">{{ $person?->name ?? 'Belum di set' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-sm text-slate-400 font-medium col-span-4">Event belum terbentuk.</p>
                        @endif
                    </div>
                @else
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">
                        @if($proker->type === 'kolaborasi') Penanggung Jawab Utama (Internal) @else Penanggung Jawab @endif
                    </p>
                    @if($proker->pic)
                        @php
                            $picInitials = strtoupper(collect(explode(' ', $proker->pic->name))->map(fn($w)=>substr($w,0,1))->take(2)->implode(''));
                        @endphp
                        <div class="inline-flex items-center gap-3.5 p-4 bg-slate-50 border border-slate-200 rounded-xl">
                            @if($proker->pic->avatar)
                                <img src="{{ file_exists(public_path('storage/' . $proker->pic->avatar)) ? asset('storage/' . $proker->pic->avatar) : asset($proker->pic->avatar) }}" alt="{{ $proker->pic->name }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 shadow-sm">
                            @else
                                <div class="w-10 h-10 rounded-full shrink-0 flex items-center justify-center font-black text-sm bg-brand-100 text-brand-700 border border-brand-200">
                                    {{ $picInitials }}
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-bold text-slate-900">{{ $proker->pic->name }}</p>
                                <p class="text-xs font-medium text-slate-400 mt-0.5">{{ $proker->pic->nim ?? '-' }}{{ $proker->pic->angkatan ? ' (' . $proker->pic->angkatan . ')' : '' }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-slate-400 italic">Belum ditentukan</p>
                    @endif
                @endif
            </div>

            {{-- SECTION 2: Meta Info Strip --}}
            <div class="px-6 sm:px-8 py-5 flex flex-wrap items-start gap-x-8 gap-y-4 bg-slate-50/50 border-b border-slate-100">
                {{-- Pelaksanaan --}}
                <div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Pelaksanaan</span>
                    <span class="block text-sm font-bold text-slate-800">
                        @if($proker->start_date && $proker->end_date)
                            {{ $proker->start_date->translatedFormat('d M Y') }} – {{ $proker->end_date->translatedFormat('d M Y') }}
                        @elseif($proker->start_date)
                            Mulai {{ $proker->start_date->translatedFormat('d M Y') }}
                        @else
                            Menunggu Jadwal
                        @endif
                    </span>
                </div>
                <div class="hidden sm:block w-px self-stretch bg-slate-200"></div>
                {{-- Jenis --}}
                <div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Jenis Program</span>
                    <span class="block text-sm font-bold text-slate-800">
                        @if($proker->type === 'event') Event Kepanitiaan
                        @elseif($proker->type === 'kolaborasi') Kolaborasi Lintas Divisi
                        @else Internal Divisi
                        @endif
                    </span>
                </div>
                <div class="hidden sm:block w-px self-stretch bg-slate-200"></div>
                {{-- Bidang --}}
                <div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Bidang</span>
                    <span class="block text-sm font-bold text-slate-800">{{ $proker->subDivision->name ?? '-' }}</span>
                </div>
                @if($proker->budget_plan)
                    <div class="hidden sm:block w-px self-stretch bg-slate-200"></div>
                    <div>
                        <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Anggaran</span>
                        <span class="block text-sm font-bold text-slate-800">Rp {{ number_format($proker->budget_plan, 0, ',', '.') }}</span>
                    </div>
                @endif
            </div>

            {{-- SECTION 3: Tujuan, Sasaran, & Deskripsi --}}
            <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Tujuan Program Kerja</p>
                        <p class="text-sm text-slate-700 leading-relaxed">{{ $proker->objective ?: '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Sasaran</p>
                        <p class="text-sm text-slate-700 leading-relaxed">{{ $proker->target_audience ?: '—' }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Deskripsi Program Kerja</p>
                    <p class="text-sm text-slate-700 leading-relaxed">{{ $proker->description ?: 'Tidak ada deskripsi yang ditambahkan untuk program kerja ini.' }}</p>
                </div>
            </div>

            {{-- SECTION 4: Kolaborasi --}}
            @if($proker->type === 'kolaborasi' && $proker->partnerDivisions->count() > 0)
            <div class="p-6 sm:p-8 space-y-5">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Divisi Mitra & Anggota Kolaborator</p>

                @if($proker->collaborators->count() > 0)
                    @php
                        $grouped = $proker->collaborators->load('memberships.division')->groupBy(function($user) {
                            $membership = $user->memberships->first();
                            return $membership?->division?->name ?? 'Tidak Diketahui';
                        });
                        $partnerDivNames = $proker->partnerDivisions->pluck('name')->toArray();
                    @endphp

                    <div class="space-y-4">
                        @foreach($partnerDivNames as $divName)
                            @php
                                $members = $grouped->get($divName, collect());
                            @endphp
                            <div class="border border-slate-200 rounded-xl overflow-hidden">
                                <div class="flex items-center gap-2 px-4 py-2.5 bg-purple-50 border-b border-purple-100">
                                    <i class="ph-bold ph-buildings text-purple-500 text-sm"></i>
                                    <span class="text-xs font-black text-purple-700 uppercase tracking-wider">{{ $divName }}</span>
                                    <span class="ml-auto text-[10px] font-bold text-purple-400">{{ $members->count() }} anggota</span>
                                </div>
                                @if($members->count() > 0)
                                    <div class="divide-y divide-slate-100">
                                        @foreach($members as $collab)
                                            @php
                                                $ci = strtoupper(collect(explode(' ', $collab->name))->map(fn($w)=>substr($w,0,1))->take(2)->implode(''));
                                            @endphp
                                            <div class="flex items-center gap-3 px-4 py-3 bg-white hover:bg-slate-50 transition-colors">
                                                @if($collab->avatar)
                                                    <img src="{{ file_exists(public_path('storage/' . $collab->avatar)) ? asset('storage/' . $collab->avatar) : asset($collab->avatar) }}" alt="{{ $collab->name }}" class="w-9 h-9 rounded-full object-cover shrink-0 border border-slate-200 shadow-sm">
                                                @else
                                                    <div class="w-9 h-9 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center text-xs font-black shrink-0">
                                                        {{ $ci }}
                                                    </div>
                                                @endif
                                                <div class="min-w-0">
                                                    <p class="text-sm font-bold text-slate-900 leading-tight">{{ $collab->name }}</p>
                                                    <p class="text-xs text-slate-400 font-medium mt-0.5">{{ $collab->nim ?? '-' }}{{ $collab->angkatan ? ' (' . $collab->angkatan . ')' : '' }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="px-4 py-3 bg-white">
                                        <p class="text-xs text-slate-400 italic">Belum ada anggota dari divisi ini.</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-wrap gap-2">
                        @foreach($proker->partnerDivisions as $div)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 border border-purple-200 rounded-lg text-xs font-bold text-purple-700">
                                <i class="ph-bold ph-buildings text-xs"></i>{{ $div->name }}
                            </span>
                        @endforeach
                    </div>
                    <p class="text-xs text-slate-400 italic">Belum ada anggota kolaborator yang ditambahkan.</p>
                @endif
            </div>
            @endif

        </div>
    </div>
    </div> <!-- Close the left column div -->

    @if($proker->type !== 'event')
    <div class="w-full lg:w-1/3 space-y-6 shrink-0">
        
        <!-- Progres Tercapai Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 relative overflow-hidden hover:border-brand-300 transition-colors">
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
                    <p class="text-sm font-bold text-slate-900">Tercatat: {{ isset($logs) && $logs->count() > 0 ? $logs->first()->created_at->format('d M Y') : '-' }}</p>
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
    @endif
</div>



@endsection
