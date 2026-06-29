@extends('layouts.dashboard')

@section('title', 'Struktur Kepengurusan - ' . $period->name)

@section('breadcrumbs')
    <a href="{{ route('super_admin.dashboard') }}" class="text-slate-500 hover:text-brand-600 transition-colors">Super Admin</a>
    <i class="ph-bold ph-caret-right text-slate-400 text-xs"></i>
    <a href="{{ route('super_admin.periods.index') }}" class="text-slate-500 hover:text-brand-600 transition-colors">Manajemen Periode</a>
    <i class="ph-bold ph-caret-right text-slate-400 text-xs"></i>
    <a href="{{ route('super_admin.periods.show', $period->id) }}" class="text-slate-500 hover:text-brand-600 transition-colors">Periode {{ $period->name }}</a>
    <i class="ph-bold ph-caret-right text-slate-400 text-xs"></i>
    <span class="text-slate-700">Struktur</span>
@endsection

@section('content')

{{-- ===== HEADER ===== --}}
<div class="mb-8 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">
            Arsip Struktur Kepengurusan
        </h1>
        <p class="text-sm text-slate-500 mt-1.5 font-medium max-w-2xl leading-relaxed">
            Arsip susunan organisasi Himpunan Mahasiswa Sistem Informasi pada periode kepengurusan {{ $period->name }}.
        </p>
    </div>
    <a href="{{ route('super_admin.periods.show', $period->id) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-brand-600 transition-colors shrink-0">
        <i class="ph-bold ph-arrow-left"></i>
        Kembali
    </a>
</div>


{{-- ===== DATA PEMBINA ===== --}}
<div class="mb-8">
    <div class="mb-4 border-b border-slate-100 pb-2">
        <h2 class="text-lg font-black text-slate-900 tracking-tight">Pembina HIMASI</h2>
    </div>

    @if($pembina && $pembina->members->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($pembina->members as $member)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden relative group hover:border-emerald-300 hover:shadow-md transition-all">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-emerald-500"></div>
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center sm:items-start gap-3 sm:gap-4">
                            @if($member->user->avatar)

                                <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-full overflow-hidden shrink-0 border border-slate-200">

                                    <img src="{{ asset('storage/' . $member->user->avatar) }}" alt="{{ $member->user->name }}" class="w-full h-full object-cover">

                                </div>

                            @else

                                @php

                                    $initial = strtoupper(substr($member->user->name, 0, 1));

                                @endphp

                                <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-lg sm:text-xl shrink-0">

                                    {{ $initial }}

                                </div>

                            @endif
                            <div class="min-w-0 flex-1">
                                <h3 class="text-sm sm:text-base font-bold text-slate-900 leading-tight sm:leading-snug truncate">
                                    {{ $member->user->name }}
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">NIP {{ $member->user->nim ?? '-' }}</p>
                                
                                <div class="mt-2 sm:mt-4 flex flex-wrap gap-1.5 sm:gap-2">
                                    <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 sm:py-1 bg-emerald-50 text-emerald-700 text-[10px] sm:text-xs font-bold rounded sm:rounded-lg border border-emerald-200">
                                        {{ $member->orgPosition->name ?? 'Pembina' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 sm:mt-6 pt-3 sm:pt-4 border-t border-slate-100">
                            <div class="text-xs font-medium text-slate-500 flex items-center gap-1.5 min-w-0">
                                <i class="ph-fill ph-envelope-simple text-slate-400 shrink-0"></i>
                                <span class="truncate">{{ $member->user->email }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 text-center text-slate-500">
            <p class="font-bold text-slate-900 text-base mb-1">Belum ada Pembina</p>
        </div>
    @endif
</div>

{{-- ===== DATA DP ===== --}}
<div class="mb-8">
    <div class="mb-4 border-b border-slate-100 pb-2">
        <h2 class="text-lg font-black text-slate-900 tracking-tight">Dewan Pengawas (DP)</h2>
    </div>
    
    @if($dp && $dp->members->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($dp->members as $member)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden relative group hover:border-amber-300 hover:shadow-md transition-all">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-amber-500"></div>
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center sm:items-start gap-3 sm:gap-4">
                            @if($member->user->avatar)

                                <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-full overflow-hidden shrink-0 border border-slate-200">

                                    <img src="{{ asset('storage/' . $member->user->avatar) }}" alt="{{ $member->user->name }}" class="w-full h-full object-cover">

                                </div>

                            @else

                                @php

                                    $initial = strtoupper(substr($member->user->name, 0, 1));

                                @endphp

                                <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center font-black text-lg sm:text-xl shrink-0">

                                    {{ $initial }}

                                </div>

                            @endif
                            <div class="min-w-0 flex-1">
                                <h3 class="text-sm sm:text-base font-bold text-slate-900 leading-tight sm:leading-snug truncate">
                                    {{ $member->user->name }}
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">NIM {{ $member->user->nim ?? '-' }} ({{ $member->user->angkatan ?? '-' }})</p>
                                
                                <div class="mt-2 sm:mt-4 flex flex-wrap gap-1.5 sm:gap-2">
                                    <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 sm:py-1 bg-slate-100 text-slate-700 text-[10px] sm:text-xs font-bold rounded sm:rounded-lg border border-slate-200">
                                        {{ $member->orgPosition->name ?? 'Anggota DP' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 sm:mt-6 pt-3 sm:pt-4 border-t border-slate-100">
                            <div class="text-xs font-medium text-slate-500 flex items-center gap-1.5 min-w-0">
                                <i class="ph-fill ph-envelope-simple text-slate-400 shrink-0"></i>
                                <span class="truncate">{{ $member->user->email }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 text-center text-slate-500">
            <p class="font-bold text-slate-900 text-base mb-1">Belum ada anggota Dewan Pengawas</p>
        </div>
    @endif
</div>

{{-- ===== DATA BPH ===== --}}
<div class="mb-8">
    <div class="mb-4 border-b border-slate-100 pb-2">
        <h2 class="text-xl font-black text-slate-900 tracking-tight">Badan Pengurus Harian (BPH)</h2>
    </div>
    
    @if($bph && $bph->members->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($bph->members as $member)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden relative group hover:border-brand-300 hover:shadow-md transition-all">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-brand-500"></div>
                    
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center sm:items-start gap-3 sm:gap-4">
                            @if($member->user->avatar)

                                <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-full overflow-hidden shrink-0 border border-slate-200">

                                    <img src="{{ asset('storage/' . $member->user->avatar) }}" alt="{{ $member->user->name }}" class="w-full h-full object-cover">

                                </div>

                            @else

                                @php

                                    $initial = strtoupper(substr($member->user->name, 0, 1));

                                @endphp

                                <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center font-black text-lg sm:text-xl shrink-0">

                                    {{ $initial }}

                                </div>

                            @endif
                            
                            <div class="min-w-0 flex-1">
                                <h3 class="text-sm sm:text-base font-bold text-slate-900 leading-tight sm:leading-snug truncate">
                                    {{ $member->user->name }}
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">NIM {{ $member->user->nim ?? '-' }} ({{ $member->user->angkatan ?? '-' }})</p>
                                
                                <div class="mt-2 sm:mt-4 flex flex-wrap gap-1.5 sm:gap-2">
                                    <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 sm:py-1 bg-slate-100 text-slate-700 text-[10px] sm:text-xs font-bold rounded sm:rounded-lg border border-slate-200">
                                        {{ $member->orgPosition->name ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 sm:mt-6 pt-3 sm:pt-4 border-t border-slate-100">
                            <div class="text-xs font-medium text-slate-500 flex items-center gap-1.5 min-w-0">
                                <i class="ph-fill ph-envelope-simple text-slate-400 shrink-0"></i>
                                <span class="truncate">{{ $member->user->email }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 text-center text-slate-500">
            <p class="font-bold text-slate-900 text-base mb-1">Belum ada pengurus BPH</p>
        </div>
    @endif
</div>

{{-- ===== DATA DIVISI ===== --}}
<div class="mb-6 border-b border-slate-100 pb-2">
    <h2 class="text-xl font-black text-slate-900 tracking-tight">Divisi HIMASI</h2>
</div>

<div class="space-y-6 mb-8" x-data="{ activeAccordion: null }">
    @forelse($divisions as $div)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50 cursor-pointer flex justify-between items-center transition-colors hover:bg-slate-100"
                 @click="activeAccordion = activeAccordion === {{ $div->id }} ? null : {{ $div->id }}">
                <div>
                    <h3 class="text-base font-bold text-slate-900">{{ $div->name }}</h3>
                    <p class="text-xs text-slate-500 font-medium">{{ $div->members->count() }} Anggota</p>
                </div>
                <div>
                    <i class="ph-bold ph-caret-down text-slate-400 transition-transform duration-300 text-lg" :class="activeAccordion === {{ $div->id }} ? 'rotate-180' : ''"></i>
                </div>
            </div>
            
            <div x-show="activeAccordion === {{ $div->id }}" style="display: none;"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2">
            
            @php
                $leaders = $div->members->filter(function($m) {
                    return $m->user && $m->user->global_role === 'kadiv';
                });
                $staff = $div->members->filter(function($m) {
                    return !$m->user || $m->user->global_role !== 'kadiv';
                });
            @endphp

            @if($leaders->count() > 0)
                <div class="p-4 sm:p-5 bg-slate-50/50 border-b border-slate-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($leaders as $leader)
                            <div class="bg-white rounded-xl border border-slate-200 p-4 flex items-center gap-4 shadow-sm">
                                @php
                                    $initial = strtoupper(substr($leader->user->name, 0, 1));
                                @endphp
                                <div class="w-12 h-12 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center font-black text-lg shrink-0">
                                    {{ $initial }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-sm font-bold text-slate-900 truncate">{{ $leader->user->name }}</h4>
                                    <p class="text-[10px] text-slate-500 mt-0.5">NIM {{ $leader->user->nim ?? '-' }} ({{ $leader->user->angkatan ?? '-' }})</p>
                                    <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 bg-brand-50 text-brand-700 text-[10px] font-bold rounded border border-brand-100 uppercase tracking-wide">
                                            {{ $leader->orgPosition->name ?? 'Kadiv' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="overflow-x-auto border-t border-slate-100">
                <table class="w-full min-w-[800px] text-sm text-left">
                    <thead class="text-xs text-slate-500 bg-white border-b border-slate-100 uppercase font-bold">
                        <tr>
                            <th scope="col" class="px-6 py-3 w-[25%]">Nama Pengurus</th>
                            <th scope="col" class="px-6 py-3 w-[15%]">NIM</th>
                            <th scope="col" class="px-6 py-3 w-[10%]">Angkatan</th>
                            <th scope="col" class="px-6 py-3 w-[25%]">Jabatan & Bidang</th>
                            <th scope="col" class="px-6 py-3 w-[25%]">Email</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-slate-700 bg-white">
                        @forelse($staff as $member)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-3 font-bold text-slate-900">
                                    {{ $member->user->name }}
                                </td>
                                <td class="px-6 py-3 font-medium text-slate-900 text-xs">
                                    {{ $member->user->nim ?? '-' }}
                                </td>
                                <td class="px-6 py-3 font-medium text-slate-500 text-xs">
                                    {{ $member->user->angkatan ?? '-' }}
                                </td>
                                <td class="px-6 py-3">
                                    <div class="flex flex-col items-start gap-1">
                                        <div class="inline-flex items-center gap-1.5 px-2 sm:px-2.5 py-0.5 sm:py-1 bg-indigo-50 text-indigo-700 text-[10px] sm:text-xs font-bold rounded sm:rounded-lg border border-indigo-100">
                                            {{ $member->position_title ?? ($member->orgPosition->name ?? '-') }}
                                        </div>
                                        @if($member->subDivision)
                                            <div class="inline-flex items-center gap-1 px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-md border border-slate-200">
                                                <i class="ph-bold ph-git-branch text-slate-400"></i>
                                                {{ $member->subDivision->name }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-slate-600 text-xs">
                                    {{ $member->user->email }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                    <p class="font-medium text-sm">Belum ada anggota staf di divisi ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-12 text-center text-slate-500">
            <p class="font-bold text-slate-900 text-base mb-1">Divisi tidak ditemukan</p>
            <p class="text-sm">Tidak ada data divisi yang dapat ditampilkan.</p>
        </div>
    @endforelse
</div>

@endsection
