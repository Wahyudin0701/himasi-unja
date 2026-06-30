@extends('layouts.dashboard')

@section('title', 'Detail Program Kerja')

@section('breadcrumbs')
    <span class="text-slate-700">Kepengurusan</span>
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
        <a href="{{ route('kepengurusan.kadiv.proker.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-brand-600 transition-colors mt-1">
            <i class="ph-bold ph-arrow-left"></i>
            Kembali
        </a>
    </div>
</div>

{{-- ===== DETAIL PROKER ===== --}}
<div class="w-full space-y-6 mb-6">
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
                <a href="{{ route('kepengurusan.kadiv.proker.edit', $proker->id) }}" class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 transition-all text-sm font-bold shadow-sm gap-1.5">
                    <i class="ph-bold ph-pencil-simple"></i>
                    Edit
                </a>
                <a href="{{ route('kepengurusan.kadiv.proker.progress', $proker->id) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white transition-all text-sm font-bold shadow-sm">
                    <i class="ph-bold ph-chart-line-up"></i>
                    Progres
                </a>
            </div>
        </div>

        <div class="divide-y divide-slate-100">

            {{-- SECTION 1: PJ / Penanggung Jawab — Paling Atas --}}
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
                            <div class="w-10 h-10 rounded-full shrink-0 flex items-center justify-center font-black text-sm bg-brand-100 text-brand-700 border border-brand-200">
                                {{ $picInitials }}
                            </div>
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
                {{-- Kolom Kiri: Tujuan + Sasaran sejajar vertikal --}}
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

                {{-- Kolom Kanan: Deskripsi --}}
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Deskripsi Program Kerja</p>
                    <p class="text-sm text-slate-700 leading-relaxed">{{ $proker->description ?: 'Tidak ada deskripsi yang ditambahkan untuk program kerja ini.' }}</p>
                </div>
            </div>

            {{-- SECTION 4: Kolaborasi (jika tipe kolaborasi) --}}
            @if($proker->type === 'kolaborasi' && $proker->partnerDivisions->count() > 0)
            <div class="p-6 sm:p-8 space-y-5">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Divisi Mitra & Anggota Kolaborator</p>

                @if($proker->collaborators->count() > 0)
                    @php
                        // Group collaborators by their current division membership
                        $grouped = $proker->collaborators->load('memberships.division')->groupBy(function($user) {
                            $membership = $user->memberships->first();
                            return $membership?->division?->name ?? 'Tidak Diketahui';
                        });
                        // Map partnerDivisions for quick lookup
                        $partnerDivNames = $proker->partnerDivisions->pluck('name')->toArray();
                    @endphp

                    <div class="space-y-4">
                        @foreach($partnerDivNames as $divName)
                            @php
                                $members = $grouped->get($divName, collect());
                            @endphp
                            <div class="border border-slate-200 rounded-xl overflow-hidden">
                                {{-- Division header --}}
                                <div class="flex items-center gap-2 px-4 py-2.5 bg-purple-50 border-b border-purple-100">
                                    <i class="ph-bold ph-buildings text-purple-500 text-sm"></i>
                                    <span class="text-xs font-black text-purple-700 uppercase tracking-wider">{{ $divName }}</span>
                                    <span class="ml-auto text-[10px] font-bold text-purple-400">{{ $members->count() }} anggota</span>
                                </div>
                                {{-- Members --}}
                                @if($members->count() > 0)
                                    <div class="divide-y divide-slate-100">
                                        @foreach($members as $collab)
                                            @php
                                                $ci = strtoupper(collect(explode(' ', $collab->name))->map(fn($w)=>substr($w,0,1))->take(2)->implode(''));
                                            @endphp
                                            <div class="flex items-center gap-3 px-4 py-3 bg-white hover:bg-slate-50 transition-colors">
                                                <div class="w-9 h-9 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center text-xs font-black shrink-0">
                                                    {{ $ci }}
                                                </div>
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

                        {{-- Anggota yang tidak terkelompok (jika ada) --}}
                        @php
                            $ungrouped = $proker->collaborators->filter(function($user) use ($partnerDivNames) {
                                $divName = $user->memberships->first()?->division?->name ?? 'Tidak Diketahui';
                                return !in_array($divName, $partnerDivNames);
                            });
                        @endphp
                        @if($ungrouped->count() > 0)
                            <div class="border border-slate-200 rounded-xl overflow-hidden">
                                <div class="flex items-center gap-2 px-4 py-2.5 bg-slate-50 border-b border-slate-200">
                                    <i class="ph-bold ph-question text-slate-400 text-sm"></i>
                                    <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Lainnya</span>
                                </div>
                                <div class="divide-y divide-slate-100">
                                    @foreach($ungrouped as $collab)
                                        @php $ci = strtoupper(collect(explode(' ', $collab->name))->map(fn($w)=>substr($w,0,1))->take(2)->implode('')); @endphp
                                        <div class="flex items-center gap-3 px-4 py-3 bg-white">
                                            <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-xs font-black shrink-0">{{ $ci }}</div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-900">{{ $collab->name }}</p>
                                                <p class="text-xs text-slate-400 mt-0.5">{{ $collab->nim ?? '-' }}{{ $collab->angkatan ? ' (' . $collab->angkatan . ')' : '' }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    {{-- Hanya tampilkan badge divisi jika belum ada kolaborator --}}
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

        </div>{{-- /divide-y --}}
    </div>
</div>

@if($proker->type === 'non_event')
{{-- ===== RIWAYAT JURNAL PROGRES (NON-EVENT) ===== --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden w-full mb-6">
    <div class="p-6 sm:p-8 border-b border-slate-100 flex items-center justify-between">
        <h3 class="text-xl font-bold text-slate-900 flex items-center gap-2">
            <i class="ph-bold ph-notebook text-brand-500"></i>
            Riwayat Jurnal Progres
        </h3>
        <div class="flex items-center gap-2">
            <span class="text-sm font-semibold text-slate-500">Total Progres:</span>
            <div class="flex items-center gap-2">
                <div class="w-32 h-2.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-brand-500 rounded-full" style="width: {{ $proker->progress_percentage }}%"></div>
                </div>
                <span class="text-sm font-black text-slate-900">{{ $proker->progress_percentage }}%</span>
            </div>
        </div>
    </div>
    <div class="p-6 sm:p-8 bg-slate-50">
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
                                    <i class="ph-bold ph-user text-slate-400"></i>
                                    {{ $log->author->name }}
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <i class="ph-bold ph-trend-up text-brand-500"></i>
                                    Klaim: <span class="text-brand-600 font-bold">{{ $log->progress_update }}%</span>
                                </div>
                            </div>
                            @if($log->feedback)
                                <div class="mt-3 p-3 bg-amber-50 rounded-xl border border-amber-100">
                                    <p class="text-xs text-amber-800 font-medium"><span class="font-bold">Catatan Kadiv:</span> {{ $log->feedback }}</p>
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
                <h4 class="text-sm font-bold text-slate-900 mb-1">Belum Ada Jurnal Progres</h4>
                <p class="text-xs text-slate-500 font-medium max-w-sm mx-auto">Anggota PJ belum membuat laporan jurnal untuk program kerja ini.</p>
            </div>
        @endif
    </div>
</div>
@endif

@if($proker->type === 'event' && isset($event))
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden w-full mb-6">
    <div class="p-6 sm:p-8 border-b border-slate-100 flex items-center justify-between">
        <h3 class="text-xl font-bold text-slate-900 flex items-center gap-2">
            <i class="ph-bold ph-users text-brand-500"></i>
            Daftar Divisi Kepanitiaan
        </h3>
    </div>
    <div class="p-6 sm:p-8 bg-slate-50">
        @if($event->divisions && $event->divisions->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($event->divisions as $div)
                    @php
                        $co = $div->committees->where('role.slug', 'co-divisi')->first();
                        $anggota = $div->committees->where('role.slug', 'anggota');
                    @endphp
                    <div onclick="openDivisionModal('{{ $div->id }}')" class="bg-white border border-slate-200 rounded-2xl p-5 hover:border-brand-500 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 cursor-pointer flex flex-col justify-between h-full">
                        <div>
                            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                                <h4 class="text-sm font-black text-slate-900 uppercase tracking-wider">{{ $div->name }}</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-slate-100 text-xs font-black text-slate-500">
                                    {{ $anggota->count() + ($co ? 1 : 0) }} Anggota
                                </span>
                            </div>
                            <div class="space-y-1">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Koordinator (CO)</span>
                                <span class="block text-sm font-bold {{ $co ? 'text-slate-800' : 'text-slate-400 italic font-medium' }}">
                                    {{ $co->user->name ?? 'Belum di set' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Modals for Division Details --}}
            @foreach($event->divisions as $div)
                @php
                    $co = $div->committees->where('role.slug', 'co-divisi')->first();
                    $anggota = $div->committees->where('role.slug', 'anggota');
                    $coInitials = '';
                    if ($co && $co->user) {
                        $coWords = explode(' ', $co->user->name);
                        $coInitials = count($coWords) >= 2
                            ? strtoupper(substr($coWords[0], 0, 1) . substr($coWords[1], 0, 1))
                            : strtoupper(substr($co->user->name, 0, 2));
                    }
                @endphp
                <div id="modal-div-{{ $div->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:block sm:p-0">
                        <div onclick="closeDivisionModal('{{ $div->id }}')" class="fixed inset-0 bg-slate-900/60 transition-opacity backdrop-blur-sm" aria-hidden="true"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div class="inline-block align-middle w-full max-w-[92%] sm:max-w-lg sm:w-full bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle border border-slate-200">
                            <div class="bg-white px-6 pt-6 pb-4 sm:p-6 border-b border-slate-100 flex items-center justify-between">
                                <h3 class="text-base font-black text-slate-900 uppercase tracking-wide flex items-center gap-2" id="modal-title">
                                    <i class="ph-bold ph-users text-brand-500"></i>
                                    Detail {{ $div->name }}
                                </h3>
                                <button onclick="closeDivisionModal('{{ $div->id }}')" class="text-slate-400 hover:text-slate-600 transition-colors">
                                    <i class="ph-bold ph-x text-lg"></i>
                                </button>
                            </div>
                            <div class="px-6 py-6 space-y-6">
                                <div>
                                    <h5 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                                        <i class="ph-bold ph-star text-brand-400"></i>
                                        Koordinator (CO)
                                    </h5>
                                    @if($co && $co->user)
                                        <div class="flex items-center gap-3 bg-brand-50/50 p-3 rounded-xl border border-brand-100/50">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 font-black text-xs shadow-sm bg-brand-100 text-brand-700 border border-brand-200">
                                                {{ $coInitials }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm font-bold text-slate-900">{{ $co->user->name }}</p>
                                                <p class="text-xs font-medium text-slate-500 mt-0.5">{{ $co->user->email }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 border-dashed bg-slate-50/50">
                                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center border border-white shadow-sm">
                                                <i class="ph-bold ph-user text-slate-300 text-sm"></i>
                                            </div>
                                            <p class="text-sm font-bold text-slate-400">Belum di set</p>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h5 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                                        <i class="ph-bold ph-users text-slate-400"></i>
                                        Anggota Divisi ({{ $anggota->count() }})
                                    </h5>
                                    @if($anggota->count() > 0)
                                        <div class="space-y-2.5 max-h-60 overflow-y-auto pr-1">
                                            @foreach($anggota as $member)
                                                @php
                                                    $memberInitials = '';
                                                    if ($member->user) {
                                                        $memberWords = explode(' ', $member->user->name);
                                                        $memberInitials = count($memberWords) >= 2
                                                            ? strtoupper(substr($memberWords[0], 0, 1) . substr($memberWords[1], 0, 1))
                                                            : strtoupper(substr($member->user->name, 0, 2));
                                                    }
                                                @endphp
                                                <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 font-black text-xs shadow-sm bg-slate-200 text-slate-600 border border-slate-300">
                                                        {{ $memberInitials ?: '?' }}
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <p class="text-xs font-bold text-slate-900">{{ $member->user->name ?? 'User tidak ditemukan' }}</p>
                                                        <p class="text-[10px] font-medium text-slate-500 mt-0.5">{{ $member->user->email ?? '-' }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="py-6 text-center rounded-xl border border-dashed border-slate-200 bg-slate-50/50">
                                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Belum ada anggota divisi</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="bg-slate-50 px-6 py-4 flex justify-end">
                                <button onclick="closeDivisionModal('{{ $div->id }}')" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors shadow-sm">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-10 px-4">
                <div class="w-16 h-16 rounded-full bg-white border border-slate-200 shadow-sm flex items-center justify-center mx-auto mb-4">
                    <i class="ph-fill ph-users-three text-slate-300 text-2xl"></i>
                </div>
                <h4 class="text-base font-black text-slate-900 mb-1">Divisi Belum Terbentuk</h4>
                <p class="text-sm text-slate-500 font-medium max-w-sm mx-auto">Event ini belum memiliki susunan divisi kepanitiaan. Divisi dapat dibentuk oleh Ketua Pelaksana melalui Dashboard Event.</p>
            </div>
        @endif
    </div>
</div>
@endif

@push('scripts')
<script>
    function openDivisionModal(id) {
        const modal = document.getElementById('modal-div-' + id);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }
    
    function closeDivisionModal(id) {
        const modal = document.getElementById('modal-div-' + id);
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }
</script>
@endpush

@endsection
