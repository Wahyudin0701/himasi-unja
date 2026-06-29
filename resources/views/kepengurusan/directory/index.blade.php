@extends('layouts.dashboard')

@section('title', 'Daftar Pengurus')

@section('breadcrumbs')
    <span class="text-slate-700">Kepengurusan</span>
    <i class="ph-bold ph-caret-right text-slate-400 text-xs"></i>
    <span class="text-slate-500">Daftar Pengurus</span>
@endsection

@section('content')

{{-- ===== HEADER ===== --}}
<div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">
            Daftar Pengurus HIMASI
        </h1>
        <p class="text-sm text-slate-500 mt-1.5 font-medium">Periode Aktif: {{ $activePeriod ? $activePeriod->name : 'Belum ada periode aktif' }}</p>
    </div>
</div>



{{-- ===== DATA PEMBINA ===== --}}
<div id="section-pembina" class="mb-8 scroll-mt-24">
        <div class="flex items-center justify-between gap-2 mt-8 mb-4">
            <div>
                <h2 class="text-lg font-black text-slate-900 tracking-tight">Pembina HIMASI</h2>
                <p class="text-sm text-slate-500 font-medium">Data Pembina Himpunan Mahasiswa Sistem Informasi.</p>
            </div>
            
        </div>
    

    @if(($pembina ? $pembina->members : collect())->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach(($pembina ? $pembina->members : collect()) as $member)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden relative group hover:border-emerald-300 hover:shadow-md transition-all cursor-pointer"
                     @click="$dispatch('open-member-modal', {
                         name: '{{ addslashes($member->user->name) }}',
                         nim: '{{ $member->user->nim ?? '-' }}',
                         angkatan: '-',
                         email: '{{ $member->user->email }}',
                         position: '{{ addslashes($member->position_title ?: ($member->orgPosition->name ?? 'Pembina')) }}',
                         identifier_type: 'NIP',
                         avatar_url: '{{ $member->user->avatar ? (file_exists(public_path('storage/' . $member->user->avatar)) ? asset('storage/' . $member->user->avatar) : asset($member->user->avatar)) : '' }}',
                         initial: '{{ strtoupper(substr($member->user->name, 0, 1)) }}',
                         color_class: 'text-emerald-600 bg-emerald-50',
                         text_color_class: 'text-emerald-600'
                     })">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-emerald-500"></div>
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center sm:items-start gap-3 sm:gap-4">
                            @if($member->user->avatar)

                                <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-full overflow-hidden shrink-0 border border-slate-200">

                                    <img src="{{ file_exists(public_path('storage/' . $member->user->avatar)) ? asset('storage/' . $member->user->avatar) : asset($member->user->avatar) }}" alt="{{ $member->user->name }}" class="w-full h-full object-cover">

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
                                        {{ $member->position_title ?: ($member->orgPosition->name ?? 'Pembina') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 sm:mt-6 pt-3 sm:pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
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
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-12 text-center text-slate-500">
            <p class="font-bold text-slate-900 text-base mb-1">Belum ada Pembina</p>
        </div>
    @endif
</div>

{{-- ===== DATA DP ===== --}}
<div id="section-dp" class="mb-8 scroll-mt-24">
    <div class="flex items-center justify-between gap-2 mt-8 mb-4">
        <div>
            <h2 class="text-lg font-black text-slate-900 tracking-tight">Dewan Pengawas (DP)</h2>
            <p class="text-sm text-slate-500 font-medium">Data anggota Dewan Pengawas HIMASI.</p>
        </div>
        
    </div>
    
    @if(($dp ? $dp->members : collect())->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach(($dp ? $dp->members : collect()) as $member)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden relative group hover:border-amber-300 hover:shadow-md transition-all cursor-pointer"
                     @click="$dispatch('open-member-modal', {
                         name: '{{ addslashes($member->user->name) }}',
                         nim: '{{ $member->user->nim ?? '-' }}',
                         angkatan: '{{ $member->user->angkatan ?? '-' }}',
                         email: '{{ $member->user->email }}',
                         position: '{{ addslashes($member->position_title ?: ($member->orgPosition->name ?? 'Anggota DP')) }}',
                         identifier_type: 'NPM / NIP',
                         avatar_url: '{{ $member->user->avatar ? (file_exists(public_path('storage/' . $member->user->avatar)) ? asset('storage/' . $member->user->avatar) : asset($member->user->avatar)) : '' }}',
                         initial: '{{ strtoupper(substr($member->user->name, 0, 1)) }}',
                         color_class: 'text-amber-600 bg-amber-50',
                         text_color_class: 'text-amber-600'
                     })">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-amber-500"></div>
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center sm:items-start gap-3 sm:gap-4">
                            @if($member->user->avatar)

                                <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-full overflow-hidden shrink-0 border border-slate-200">

                                    <img src="{{ file_exists(public_path('storage/' . $member->user->avatar)) ? asset('storage/' . $member->user->avatar) : asset($member->user->avatar) }}" alt="{{ $member->user->name }}" class="w-full h-full object-cover">

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
                                        {{ $member->position_title ?: ($member->orgPosition->name ?? 'Anggota DP') }}
                                    </span>
                                    <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 sm:py-1 bg-amber-50 text-amber-600 text-[10px] sm:text-xs font-bold rounded sm:rounded-lg border border-amber-100">
                                        DP
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 sm:mt-6 pt-3 sm:pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
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
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-12 text-center text-slate-500">
            <p class="font-bold text-slate-900 text-base mb-1">Belum ada anggota Dewan Pengawas</p>
        </div>
    @endif
</div>

{{-- ===== DATA BPH ===== --}}
<div id="section-bph" class="mb-8 scroll-mt-24">
    <div class="mb-6">
        <h2 class="text-xl font-black text-slate-900 tracking-tight">Badan Pengurus Harian (BPH)</h2>
        <p class="text-sm text-slate-500 mt-1 font-medium">Data Kahim, Wakahim, Sekretaris, dan Bendahara.</p>
    </div>
    
    @if(($bph ? $bph->members : collect())->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach(($bph ? $bph->members : collect()) as $member)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden relative group hover:border-brand-300 hover:shadow-md transition-all cursor-pointer"
                     @click="$dispatch('open-member-modal', {
                         name: '{{ addslashes($member->user->name) }}',
                         nim: '{{ $member->user->nim ?? '-' }}',
                         angkatan: '{{ $member->user->angkatan ?? '-' }}',
                         email: '{{ $member->user->email }}',
                         position: '{{ addslashes($member->position_title ?: ($member->orgPosition->name ?? '-')) }}',
                         identifier_type: 'NIM',
                         avatar_url: '{{ $member->user->avatar ? (file_exists(public_path('storage/' . $member->user->avatar)) ? asset('storage/' . $member->user->avatar) : asset($member->user->avatar)) : '' }}',
                         initial: '{{ strtoupper(substr($member->user->name, 0, 1)) }}',
                         color_class: 'text-brand-600 bg-brand-50',
                         text_color_class: 'text-brand-600'
                     })">
                    {{-- Top border line --}}
                    <div class="absolute top-0 left-0 right-0 h-1 bg-brand-500"></div>
                    
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center sm:items-start gap-3 sm:gap-4">
                            {{-- Avatar Initial --}}
                            @if($member->user->avatar)

                                <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-full overflow-hidden shrink-0 border border-slate-200">

                                    <img src="{{ file_exists(public_path('storage/' . $member->user->avatar)) ? asset('storage/' . $member->user->avatar) : asset($member->user->avatar) }}" alt="{{ $member->user->name }}" class="w-full h-full object-cover">

                                </div>

                            @else

                                @php

                                    $initial = strtoupper(substr($member->user->name, 0, 1));

                                @endphp

                                <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center font-black text-lg sm:text-xl shrink-0">

                                    {{ $initial }}

                                </div>

                            @endif
                            
                            {{-- Info --}}
                            <div class="min-w-0 flex-1">
                                <h3 class="text-sm sm:text-base font-bold text-slate-900 leading-tight sm:leading-snug truncate">
                                    {{ $member->user->name }}
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">NIM {{ $member->user->nim ?? '-' }} ({{ $member->user->angkatan ?? '-' }})</p>
                                
                                <div class="mt-2 sm:mt-4 flex flex-wrap gap-1.5 sm:gap-2">
                                    <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 sm:py-1 bg-slate-100 text-slate-700 text-[10px] sm:text-xs font-bold rounded sm:rounded-lg border border-slate-200">
                                        {{ $member->position_title ?: ($member->orgPosition->name ?? '-') }}
                                    </span>
                                    <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 sm:py-1 bg-red-50 text-red-600 text-[10px] sm:text-xs font-bold rounded sm:rounded-lg border border-red-100">
                                        BPH
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 sm:mt-6 pt-3 sm:pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
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
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-12 text-center text-slate-500">
            <p class="font-bold text-slate-900 text-base mb-1">Belum ada pengurus BPH</p>
        </div>
    @endif
</div>

{{-- ===== DATA DESCRIPTION BAR ===== --}}
<div class="mb-6">
    <h2 class="text-xl font-black text-slate-900 tracking-tight">Data Divisi Himasi</h2>
    <p class="text-sm text-slate-500 mt-1 font-medium">Data ketua divisi dan anggota divisinya.</p>
</div>

{{-- ===== DATA DIVISI LIST ===== --}}
<div>
    @foreach($filteredDivisions as $div)
        <div id="divisi-{{ $div->id }}" class="scroll-mt-24"></div>
    @endforeach
</div>
<div id="section-divisi" class="flex flex-col lg:flex-row gap-5 lg:gap-6 mb-8 items-start scroll-mt-24 relative" x-data="{ activeTab: window.location.hash.startsWith('#divisi-') ? parseInt(window.location.hash.replace('#divisi-', '')) : {{ $filteredDivisions->first()->id ?? 'null' }} }" x-init="window.addEventListener('hashchange', () => { if(window.location.hash.startsWith('#divisi-')) activeTab = parseInt(window.location.hash.replace('#divisi-', '')) })">
    @if($filteredDivisions->count() > 0)
        {{-- Mobile Dropdown Tabs --}}
        <div class="block lg:hidden w-full mb-2 relative">
            <select x-model.number="activeTab" class="appearance-none w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3.5 shadow-sm font-bold pr-10">
                @foreach($filteredDivisions as $div)
                    <option value="{{ $div->id }}">{{ $div->name }} ({{ $div->members->count() }} Anggota)</option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                <i class="ph-bold ph-caret-down"></i>
            </div>
        </div>

        {{-- Desktop Tabs Sidebar --}}
        <div class="hidden lg:flex w-[260px] xl:w-[280px] shrink-0 flex-col gap-2">
            @foreach($filteredDivisions as $div)
                <button @click="activeTab = {{ $div->id }}" 
                        :class="activeTab === {{ $div->id }} ? 'bg-indigo-600 text-white border-indigo-600 shadow-md shadow-indigo-200 translate-x-1' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50 hover:border-slate-300'"
                        class="w-full px-5 py-4 rounded-xl border text-left transition-all group relative overflow-hidden">
                    
                    {{-- Active Indicator Line (Desktop) --}}
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-white/20 transition-opacity" :class="activeTab === {{ $div->id }} ? 'opacity-100' : 'opacity-0'"></div>
                    
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-sm" :class="activeTab === {{ $div->id }} ? 'text-white' : 'text-slate-900'">{{ $div->name }}</h3>
                            <p class="text-xs mt-1 font-medium transition-colors" :class="activeTab === {{ $div->id }} ? 'text-indigo-200' : 'text-slate-500 group-hover:text-slate-600'">{{ $div->members->count() }} Anggota</p>
                        </div>
                        
                        {{-- Chevron icon for desktop sidebar --}}
                        <div class="flex shrink-0 w-5 h-5 items-center justify-center rounded-full transition-colors" :class="activeTab === {{ $div->id }} ? 'bg-indigo-500/50 text-white' : 'text-slate-400 group-hover:bg-slate-100'">
                            <i class="ph-bold ph-caret-right text-xs"></i>
                        </div>
                    </div>
                </button>
            @endforeach
        </div>

        {{-- Tab Content --}}
        <div class="relative w-full min-w-0">
            @foreach($filteredDivisions as $div)
                <div x-show="activeTab === {{ $div->id }}" style="display: none;"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 lg:translate-x-4 lg:translate-y-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-x-0 translate-y-0"
                     class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                
                @php
                    $leaders = $div->members->filter(function($m) {
                        return $m->user && $m->user->global_role === 'kadiv';
                    });
                    $staff = $div->members->filter(function($m) {
                        return !$m->user || $m->user->global_role !== 'kadiv';
                    });
                @endphp

                {{-- Content Header --}}
                <div class="px-5 py-4 lg:px-6 lg:py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-black text-slate-900 tracking-tight">{{ $div->name }}</h2>
                        <p class="text-sm text-slate-500 font-medium mt-0.5">Struktur kepengurusan dan anggota divisi.</p>
                    </div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-600 text-xs font-bold rounded-lg border border-slate-200 shrink-0">
                        <i class="ph-bold ph-users"></i>
                        {{ $div->members->count() }} Anggota Total
                    </div>
                </div>

                {{-- Kadiv & Wakadiv Cards --}}
                @if($leaders->count() > 0)
                    <div class="p-4 sm:p-5 bg-slate-50/50 border-b border-slate-100">
                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                            @foreach($leaders as $leader)
                                <div class="bg-white rounded-xl border border-slate-200 p-4 flex items-center justify-between gap-4 shadow-sm hover:border-indigo-200 transition-colors cursor-pointer"
                                     @click="$dispatch('open-member-modal', {
                                         name: '{{ addslashes($leader->user->name) }}',
                                         nim: '{{ $leader->user->nim ?? '-' }}',
                                         angkatan: '{{ $leader->user->angkatan ?? '-' }}',
                                         email: '{{ $leader->user->email }}',
                                         position: '{{ addslashes($leader->position_title ?: ($leader->orgPosition->name ?? '-')) }}',
                                         identifier_type: 'NIM',
                                         avatar_url: '{{ $leader->user->avatar ? (file_exists(public_path('storage/' . $leader->user->avatar)) ? asset('storage/' . $leader->user->avatar) : asset($leader->user->avatar)) : '' }}',
                                         initial: '{{ strtoupper(substr($leader->user->name, 0, 1)) }}',
                                         color_class: 'text-indigo-600 bg-indigo-50',
                                         text_color_class: 'text-indigo-700'
                                     })">
                                    <div class="flex items-center gap-3 min-w-0">
                                        @if($leader->user->avatar)
                                            <div class="w-10 h-10 rounded-full overflow-hidden shrink-0 border border-slate-200">
                                                <img src="{{ file_exists(public_path('storage/' . $leader->user->avatar)) ? asset('storage/' . $leader->user->avatar) : asset($leader->user->avatar) }}" alt="{{ $leader->user->name }}" class="w-full h-full object-cover">
                                            </div>
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-sm shrink-0">
                                                {{ strtoupper(substr($leader->user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <h4 class="text-sm font-bold text-slate-900 leading-tight break-words">{{ $leader->user->name }}</h4>
                                            <p class="text-[10px] text-slate-500 mt-0.5">NIM {{ $leader->user->nim ?? '-' }} ({{ $leader->user->angkatan ?? '-' }})</p>
                                            <div class="mt-1.5 flex flex-wrap gap-1">
                                                <span class="inline-flex items-center px-2 py-0.5 bg-indigo-50 text-indigo-700 text-[10px] font-bold rounded border border-indigo-100">
                                                    {{ $leader->position_title ?: ($leader->orgPosition->name ?? '-') }}
                                                </span>
                                                <span class="inline-flex items-center px-2 py-0.5 bg-slate-150 text-slate-600 text-[10px] font-medium rounded border border-slate-200 break-words">
                                                    {{ $leader->user->email }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                {{-- Action Bar --}}
                

                {{-- Table Members (Staff) --}}
                <div class="overflow-x-auto border-t border-slate-100">
                    <table class="w-full min-w-[1000px] text-sm text-left">
                        <thead class="text-xs text-slate-500 bg-slate-50 border-b border-slate-200 uppercase font-bold">
                            <tr>
                                <th scope="col" class="px-6 py-3.5 w-[25%]">Nama Pengurus</th>
                                <th scope="col" class="px-6 py-3.5 w-[15%]">NIM</th>
                                <th scope="col" class="px-6 py-3.5 w-[10%]">Angkatan</th>
                                <th scope="col" class="px-6 py-3.5 w-[25%] whitespace-nowrap">Jabatan</th>
                                <th scope="col" class="px-6 py-3.5 w-[20%]">Email</th>
                                
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 bg-white">
                            @forelse($staff as $member)
                                <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer"
                                    @click="$dispatch('open-member-modal', {
                                         name: '{{ addslashes($member->user->name) }}',
                                         nim: '{{ $member->user->nim ?? '-' }}',
                                         angkatan: '{{ $member->user->angkatan ?? '-' }}',
                                         email: '{{ $member->user->email }}',
                                         position: '{{ addslashes($member->position_title ?: ($member->orgPosition->name ?? '-')) }}',
                                         identifier_type: 'NIM',
                                         avatar_url: '{{ $member->user->avatar ? (file_exists(public_path('storage/' . $member->user->avatar)) ? asset('storage/' . $member->user->avatar) : asset($member->user->avatar)) : '' }}',
                                         initial: '{{ strtoupper(substr($member->user->name, 0, 1)) }}',
                                         color_class: 'text-slate-600 bg-slate-100',
                                         text_color_class: 'text-indigo-700'
                                    })">
                                    <td class="px-6 py-3 font-bold text-slate-900">
                                        <div class="flex items-center gap-3">
                                            @if($member->user->avatar)
                                                <div class="w-8 h-8 rounded-full overflow-hidden shrink-0 border border-slate-200 shadow-sm">
                                                    <img src="{{ file_exists(public_path('storage/' . $member->user->avatar)) ? asset('storage/' . $member->user->avatar) : asset($member->user->avatar) }}" alt="{{ $member->user->name }}" class="w-full h-full object-cover">
                                                </div>
                                            @else
                                                <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-black text-xs shrink-0 shadow-sm border border-slate-200">
                                                    {{ strtoupper(substr($member->user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <span>{{ $member->user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 font-medium text-slate-900">
                                        {{ $member->user->nim ?? '-' }}
                                    </td>
                                    <td class="px-6 py-3 text-slate-600 font-medium">
                                        {{ $member->user->angkatan ?? '-' }}
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="flex flex-col items-start gap-1">
                                            <div class="inline-flex items-center gap-1.5 px-2 sm:px-2.5 py-0.5 sm:py-1 bg-indigo-50 text-indigo-700 text-[10px] sm:text-xs font-bold rounded sm:rounded-lg border border-indigo-100 whitespace-nowrap">
                                                {{ $member->position_title ?: ($member->orgPosition->name ?? '-') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-slate-600 text-xs">
                                        {{ $member->user->email }}
                                    </td>
                                    
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                                        <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 border border-slate-100">
                                            <i class="ph-bold ph-users text-xl text-slate-400"></i>
                                        </div>
                                        <p class="font-medium text-sm">Belum ada anggota staf di divisi ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-12 text-center text-slate-500">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="ph-bold ph-identification-card text-2xl text-slate-400"></i>
            </div>
            <p class="font-bold text-slate-900 text-base mb-1">Divisi tidak ditemukan</p>
            <p class="text-sm">Tidak ada data divisi yang dapat ditampilkan.</p>
        </div>
    @endif
</div>

<!-- MEMBER DETAIL MODAL -->
<div x-data="{ open: false, member: null }" 
     @open-member-modal.window="member = $event.detail; open = true"
     x-show="open" 
     class="relative z-50" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true"
     style="display: none;">
     
    <!-- Backdrop -->
    <div x-show="open" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <!-- Modal Panel -->
            <div x-show="open"
                 @click.away="open = false"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md w-full border border-slate-200">
                
                <!-- Modal Content -->
                <div class="relative">
                    <!-- Header Background (Brand color) -->
                    <div class="h-24 bg-gradient-to-r from-brand-500 to-indigo-600"></div>
                    
                    <!-- Close button -->
                    <button type="button" @click="open = false" class="absolute top-4 right-4 text-white/80 hover:text-white bg-black/10 hover:bg-black/20 rounded-full w-8 h-8 flex items-center justify-center transition-colors">
                        <i class="ph-bold ph-x text-lg"></i>
                    </button>
                    
                    <div class="px-6 pb-6 relative">
                        <!-- Avatar -->
                        <div class="flex justify-center -mt-12 mb-4">
                            <template x-if="member?.avatar_url">
                                <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-md bg-white">
                                    <img :src="member.avatar_url" :alt="member.name" class="w-full h-full object-cover">
                                </div>
                            </template>
                            <template x-if="!member?.avatar_url">
                                <div class="w-24 h-24 rounded-full border-4 border-white shadow-md flex items-center justify-center font-black text-3xl" :class="member?.color_class">
                                    <span x-text="member?.initial"></span>
                                </div>
                            </template>
                        </div>
                        
                        <!-- Text Content -->
                        <div class="text-center mb-6">
                            <h3 class="text-xl font-black text-slate-900 leading-tight" id="modal-title" x-text="member?.name"></h3>
                            <p class="text-sm font-bold mt-1" :class="member?.text_color_class" x-text="member?.position"></p>
                        </div>
                        
                        <!-- Details Grid -->
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 space-y-3">
                            <!-- NIP / NIM -->
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 shrink-0 shadow-sm">
                                    <i class="ph-fill ph-identification-card text-base"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-500 mb-0.5" x-text="member?.identifier_type"></p>
                                    <p class="text-sm font-bold text-slate-900 break-all" x-text="member?.nim"></p>
                                </div>
                            </div>
                            
                            <!-- Email -->
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 shrink-0 shadow-sm">
                                    <i class="ph-fill ph-envelope-simple text-base"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-500 mb-0.5">EMAIL</p>
                                    <p class="text-sm font-bold text-slate-900 break-all" x-text="member?.email"></p>
                                </div>
                            </div>
                            
                            <!-- Angkatan (if exists) -->
                            <template x-if="member?.angkatan && member?.angkatan !== '-'">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 shrink-0 shadow-sm">
                                        <i class="ph-fill ph-graduation-cap text-base"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-[10px] uppercase tracking-wider font-bold text-slate-500 mb-0.5">ANGKATAN</p>
                                        <p class="text-sm font-bold text-slate-900 break-all" x-text="member?.angkatan"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDeleteMember(id) {
        Swal.fire({
            title: 'Hapus Anggota?',
            text: "Data anggota yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush

@endsection
