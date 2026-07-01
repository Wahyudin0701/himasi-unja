@extends('layouts.dashboard')

@section('title', 'Manajemen Panitia')

@section('breadcrumbs')
    <span class="text-slate-700">Kepanitiaan</span>
@endsection

@section('content')

@php
    $waketupelRole = $roles->where('slug', 'wakil-ketua-pelaksana')->first();
    $sekpelRole = $roles->where('slug', 'sekretaris-pelaksana')->first();
    $benpelRole = $roles->where('slug', 'bendahara-pelaksana')->first();
    $coRole = $roles->where('slug', 'co-divisi')->first();
    $anggotaRole = $roles->where('slug', 'anggota')->first();
    $ketupelSlugs = ['ketua-pelaksana', 'wakil-ketua-pelaksana'];
@endphp
    <div x-data="{ 
        modalOpen: false, 
        mode: '', 
        divisionId: '', 
        roleId: '', 
        targetName: '',
        committeeId: '',
        userId: '',
        tab: 'existing',

        detailOpen: false,
        detailUser: { name: '', nim: '', angkatan: '', role: '', email: '' },
        
        deleteModalOpen: false,
        deleteActionUrl: '',
        deleteMessage: '',

        confirmDelete(url, message) {
            this.deleteActionUrl = url;
            this.deleteMessage = message;
            this.deleteModalOpen = true;
        },

        openModal(mode, roleId, divisionId, targetName, committeeId = '', userId = '') {
            this.mode = mode;
            this.roleId = roleId;
            this.divisionId = divisionId;
            this.targetName = targetName;
            this.committeeId = committeeId;
            this.userId = userId ? String(userId) : '';
            this.tab = 'existing';
            this.modalOpen = true;
        },

        openDetail(name, nim, angkatan, role, email) {
            this.detailUser = { name, nim, angkatan, role, email };
            this.detailOpen = true;
        }
    }">

    <!-- Event Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <p class="text-xs font-semibold text-brand-500 uppercase tracking-widest mb-1">{{ $userRoleName ?? 'Ketua Pelaksana' }}</p>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Kepanitiaan Event : <span class="text-brand-600">{{ $event->name }}</span></h1>
        </div>
    </div>

    <!-- Tim Inti -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm mb-6 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
            <h2 class="text-base font-bold text-slate-900">Susunan Tim Inti</h2>
        </div>
        <div class="p-6">
            @php
                $intiCommittees = $event->committees->filter(fn($c) => $c->role && $c->role->scope === 'inti');
                $hasWaketupel = $intiCommittees->contains(fn($c) => $c->role->slug === 'wakil-ketua-pelaksana');
                $hasSekpel = $intiCommittees->contains(fn($c) => $c->role->slug === 'sekretaris-pelaksana');
                $hasBenpel = $intiCommittees->contains(fn($c) => $c->role->slug === 'bendahara-pelaksana');
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Daftar Anggota Tim Inti Terisi --}}
                @foreach($intiCommittees->sortBy(fn($c) => $c->role->level) as $committee)
                    <div class="flex flex-col p-4 rounded-xl border border-slate-200 bg-slate-50 relative group transition-colors hover:border-slate-300 cursor-pointer"
                         @click="openDetail('{{ addslashes($committee->user->name) }}', '{{ $committee->user->nim ?? '-' }}', '{{ $committee->user->angkatan ?? '-' }}', '{{ $committee->role->name }}', '{{ $committee->user->email }}')">
                        <div class="flex-1 pr-6 pointer-events-none">
                            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">{{ $committee->role->name }}</p>
                            <p class="font-bold text-slate-900 text-sm">{{ $committee->user->name }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">NIM {{ $committee->user->nim ?? '-' }} ({{ $committee->user->angkatan ?? '-' }})</p>
                        </div>
                        
                        {{-- Tombol Edit & Hapus (Kecuali Ketupel/Wakil) --}}
                        @if(!in_array($committee->role->slug, $ketupelSlugs))
                        <div class="absolute right-3 top-3 flex items-center justify-end gap-2 transition-opacity" @click.stop>
                            <button type="button" @click="openModal('edit', {{ $committee->committee_role_id }}, '', '{{ $committee->role->name }}', {{ $committee->id }}, {{ $committee->user_id }})" title="Ganti" class="inline-flex items-center justify-center w-8 h-8 text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 hover:text-blue-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                            </button>
                            <button type="button" @click="confirmDelete('{{ route('kepanitiaan.ketupel.remove-team', [$event, $committee]) }}', 'Hapus {{ $committee->user->name }} dari jabatannya?')" title="Hapus" class="inline-flex items-center justify-center w-8 h-8 text-rose-600 bg-rose-50 border border-rose-200 rounded-lg hover:bg-rose-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                            </button>
                        </div>
                        @endif
                    </div>
                @endforeach

                {{-- Tombol Tambah Waketupel --}}
                @if(!$hasWaketupel)
                <button @click="openModal('waketupel', {{ $waketupelRole->id }}, '', 'Wakil Ketua Pelaksana')" class="flex items-center justify-center p-4 rounded-xl border border-dashed border-slate-300 hover:border-brand-500 hover:bg-brand-50 transition-colors h-[72px] text-slate-500 hover:text-brand-600 text-sm font-semibold">
                    + Tambah Waketupel
                </button>
                @endif

                {{-- Tombol Tambah Sekpel --}}
                @if(!$hasSekpel)
                <button @click="openModal('sekpel', {{ $sekpelRole->id }}, '', 'Sekretaris Pelaksana')" class="flex items-center justify-center p-4 rounded-xl border border-dashed border-slate-300 hover:border-brand-500 hover:bg-brand-50 transition-colors h-[72px] text-slate-500 hover:text-brand-600 text-sm font-semibold">
                    + Tambah Sekpel
                </button>
                @endif

                {{-- Tombol Tambah Benpel --}}
                @if(!$hasBenpel)
                <button @click="openModal('benpel', {{ $benpelRole->id }}, '', 'Bendahara Pelaksana')" class="flex items-center justify-center p-4 rounded-xl border border-dashed border-slate-300 hover:border-brand-500 hover:bg-brand-50 transition-colors h-[72px] text-slate-500 hover:text-brand-600 text-sm font-semibold">
                    + Tambah Benpel
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Divisi Kepanitiaan -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6" x-data="{ activeTab: '{{ $event->divisions->first()?->id ?? '' }}' }">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <div>
                <h2 class="text-base font-bold text-slate-900">Divisi Kepanitiaan</h2>
            </div>
            <a href="{{ route('kepanitiaan.ketupel.create-division', $event) }}" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-lg transition-colors">
                + Tambah Divisi
            </a>
        </div>
        
        <div class="flex flex-col md:flex-row min-h-[500px]">
            @if($event->divisions->count() > 0)
                {{-- Mobile Dropdown Filter --}}
                <div class="md:hidden p-4 border-b border-slate-100 bg-slate-50/30 relative" x-data="{ mobileMenuOpen: false }">
                    <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="w-full bg-white text-left px-4 py-3 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between font-bold text-sm text-slate-900">
                        <div>
                            @foreach($event->divisions->sortBy('sort_order') as $division)
                                <span x-show="activeTab === '{{ $division->id }}'" style="display: none;">{{ $division->name }} ({{ $event->committees->where('event_division_id', $division->id)->count() }} Anggota)</span>
                            @endforeach
                        </div>
                        <i class="ph-bold ph-caret-down text-slate-400 transition-transform" :class="mobileMenuOpen ? 'rotate-180' : ''"></i>
                    </button>
                    
                    <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" style="display: none;" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-[-10px]"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute top-[calc(100%-0.5rem)] left-4 right-4 bg-white border border-slate-200 shadow-xl rounded-xl z-20 flex flex-col divide-y divide-slate-100 max-h-[60vh] overflow-y-auto">
                        @foreach($event->divisions->sortBy('sort_order') as $division)
                            @php
                                $divCount = $event->committees->where('event_division_id', $division->id)->count();
                            @endphp
                            <button type="button" @click="activeTab = '{{ $division->id }}'; mobileMenuOpen = false" 
                                    class="text-left px-4 py-3 hover:bg-slate-50 transition-colors flex items-center justify-between group"
                                    :class="activeTab === '{{ $division->id }}' ? 'bg-brand-50' : ''">
                                <div>
                                    <h3 class="font-bold text-sm mb-0.5" :class="activeTab === '{{ $division->id }}' ? 'text-brand-700' : 'text-slate-900'">{{ $division->name }}</h3>
                                    <p class="text-xs text-slate-500">{{ $divCount }} Anggota</p>
                                </div>
                                <i x-show="activeTab === '{{ $division->id }}'" class="ph-bold ph-check text-brand-600"></i>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Sidebar Filter (Desktop) --}}
                <div class="hidden md:flex w-full md:w-1/3 lg:w-1/4 border-r border-slate-100 bg-slate-50/30 p-4 shrink-0 flex-col gap-3">
                    @foreach($event->divisions->sortBy('sort_order') as $division)
                        @php
                            $divCount = $event->committees->where('event_division_id', $division->id)->count();
                        @endphp
                        <button type="button" @click="activeTab = '{{ $division->id }}'" 
                                :class="activeTab === '{{ $division->id }}' ? 'bg-brand-600 text-white border-brand-600 shadow-md' : 'bg-white text-slate-700 border-slate-200 hover:border-brand-300 hover:bg-brand-50'"
                                class="text-left p-4 rounded-xl border transition-all flex items-center justify-between group">
                            <div>
                                <h3 class="font-bold text-sm mb-0.5" :class="activeTab === '{{ $division->id }}' ? 'text-white' : 'text-slate-900'">{{ $division->name }}</h3>
                                <p class="text-xs" :class="activeTab === '{{ $division->id }}' ? 'text-brand-100' : 'text-slate-500'">{{ $divCount }} Anggota</p>
                            </div>
                            <i class="ph-bold ph-caret-right text-lg transition-transform" 
                               :class="activeTab === '{{ $division->id }}' ? 'text-white translate-x-1' : 'text-slate-400 group-hover:translate-x-1 group-hover:text-brand-500'"></i>
                        </button>
                    @endforeach
                </div>

                {{-- Detail Divisi --}}
                <div class="flex-1 w-full bg-white relative">
                    @foreach($event->divisions->sortBy('sort_order') as $division)
                        @php
                            $divCommittees = $event->committees->filter(fn($c) => $c->event_division_id === $division->id);
                            $co = $divCommittees->first(fn($c) => $c->role && $c->role->slug === 'co-divisi');
                            $divAnggota = $divCommittees->filter(fn($c) => $c->role && $c->role->slug === 'anggota');
                        @endphp
                        <div x-show="activeTab === '{{ $division->id }}'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="p-6 md:p-8 w-full">
                            
                            {{-- Header Divisi --}}
                            <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100">
                                <div>
                                    <h3 class="text-xl font-black text-slate-900 tracking-tight">{{ $division->name }}</h3>
                                    <p class="text-sm text-slate-500 mt-1">Struktur Kepengurusan Divisi</p>
                                </div>
                                @if($division->name !== 'Tim Inti')
                                <button type="button" @click="confirmDelete('{{ route('kepanitiaan.ketupel.destroy-division', [$event, $division]) }}', 'Hapus divisi {{ $division->name }} beserta seluruh anggotanya secara permanen?')" class="text-sm font-bold text-rose-500 hover:text-rose-700 bg-rose-50 px-4 py-2 rounded-lg transition-colors border border-rose-100" title="Hapus Divisi">
                                    Hapus Divisi
                                </button>
                                @endif
                            </div>

                            {{-- Tim Divisi --}}
                            <div class="space-y-6 max-w-3xl">
                                {{-- CO Section --}}
                                <div>
                                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Koordinator</p>
                                    @if($co)
                                        <div class="flex items-center justify-between bg-white border border-slate-200 px-4 py-2 rounded-lg hover:bg-slate-50 transition-colors group cursor-pointer"
                                             @click="openDetail('{{ addslashes($co->user->name) }}', '{{ $co->user->nim ?? '-' }}', '{{ $co->user->angkatan ?? '-' }}', 'Koordinator {{ $division->name }}', '{{ $co->user->email }}')">
                                            <div class="pointer-events-none">
                                                <span class="text-sm font-semibold text-slate-900 block">{{ $co->user->name }}</span>
                                                <span class="text-xs text-slate-500 block mt-0.5">NIM {{ $co->user->nim ?? '-' }} ({{ $co->user->angkatan ?? '-' }})</span>
                                            </div>
                                            <div class="flex items-center justify-end gap-2 transition-opacity" @click.stop>
                                                <button type="button" @click="openModal('co', {{ $coRole->id }}, {{ $division->id }}, 'Koordinator {{ $division->name }}', {{ $co->id }}, {{ $co->user_id }})" title="Ganti" class="inline-flex items-center justify-center sm:justify-start gap-1.5 w-8 h-8 sm:w-auto sm:px-3 sm:py-1.5 text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 hover:text-blue-600 transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 sm:w-3.5 sm:h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                                    <span class="hidden sm:inline">Ganti</span>
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <button type="button" @click="openModal('co', {{ $coRole->id }}, {{ $division->id }}, 'Koordinator {{ $division->name }}')" class="inline-flex items-center gap-1 text-sm font-semibold text-brand-600 hover:text-brand-700 transition-colors">
                                            + Tunjuk Koordinator
                                        </button>
                                    @endif
                                </div>

                                {{-- Anggota Section --}}
                                <div>
                                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Anggota</p>
                                    @if($divAnggota->count() > 0)
                                        <div class="flex flex-col gap-2">
                                            @foreach($divAnggota as $anggota)
                                                <div class="flex items-center justify-between bg-white border border-slate-200 px-4 py-2 rounded-lg hover:bg-slate-50 transition-colors group cursor-pointer"
                                                     @click="openDetail('{{ addslashes($anggota->user->name) }}', '{{ $anggota->user->nim ?? '-' }}', '{{ $anggota->user->angkatan ?? '-' }}', 'Anggota {{ $division->name }}', '{{ $anggota->user->email }}')">
                                                    <div class="pointer-events-none">
                                                        <span class="text-sm font-semibold text-slate-700 block">{{ $anggota->user->name }}</span>
                                                        <span class="text-xs text-slate-500 block mt-0.5">NIM {{ $anggota->user->nim ?? '-' }} ({{ $anggota->user->angkatan ?? '-' }})</span>
                                                    </div>
                                                    <div class="flex items-center justify-end gap-2 transition-opacity" @click.stop>
                                                        <button type="button" @click="openModal('anggota', {{ $anggotaRole->id }}, {{ $division->id }}, 'Anggota {{ $division->name }}', {{ $anggota->id }}, {{ $anggota->user_id }})" title="Ganti" class="inline-flex items-center justify-center sm:justify-start gap-1.5 w-8 h-8 sm:w-auto sm:px-3 sm:py-1.5 text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 hover:text-blue-600 transition-colors">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 sm:w-3.5 sm:h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                                            <span class="hidden sm:inline">Ganti</span>
                                                        </button>
                                                        <button type="button" @click="confirmDelete('{{ route('kepanitiaan.ketupel.remove-team', [$event, $anggota]) }}', 'Hapus {{ $anggota->user->name }} dari Anggota {{ $division->name }}?')" title="Hapus" class="inline-flex items-center justify-center sm:justify-start gap-1.5 w-8 h-8 sm:w-auto sm:px-3 sm:py-1.5 text-xs font-bold text-rose-600 bg-rose-50 border border-rose-200 rounded-lg hover:bg-rose-100 transition-colors">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 sm:w-3.5 sm:h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                                            <span class="hidden sm:inline">Hapus</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-sm text-slate-400 italic mb-2">Belum ada anggota di divisi ini.</p>
                                    @endif
                                    <button type="button" @click="openModal('anggota', {{ $anggotaRole->id }}, {{ $division->id }}, 'Anggota {{ $division->name }}')" class="mt-3 inline-flex items-center gap-1 text-sm font-semibold text-brand-600 hover:text-brand-700 transition-colors">
                                        + Tambah Anggota
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex items-center justify-center w-full py-12 bg-slate-50/50">
                    <div class="text-center">
                        <p class="text-sm font-semibold text-slate-700 mb-1">Belum ada divisi</p>
                        <p class="text-xs text-slate-500">Sistem belum membuat divisi untuk acara ini.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>


    <!-- MODAL ASSIGN TEAM -->
    <div x-show="modalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center px-4">
        <!-- Backdrop -->
        <div x-show="modalOpen" x-transition.opacity class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="modalOpen = false"></div>

        <!-- Modal Body -->
        <div x-show="modalOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col max-h-[90vh]">
             
             <!-- Header -->
             <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between shrink-0">
                 <div>
                     <h3 class="font-bold text-slate-900 text-lg">Assign <span x-text="targetName"></span></h3>
                 </div>
                 <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600 transition-colors text-xl font-bold leading-none">&times;</button>
             </div>

             <!-- Tabs -->
             <div class="flex p-2 bg-slate-50 border-b border-slate-100 shrink-0">
                 <button @click="tab = 'existing'" :class="tab === 'existing' ? 'bg-white shadow-sm text-brand-600' : 'text-slate-500 hover:text-slate-700'" class="flex-1 py-2 text-sm font-bold rounded-lg transition-all">Pilih Akun Terdaftar</button>
                 <button @click="tab = 'new'" :class="tab === 'new' ? 'bg-white shadow-sm text-brand-600' : 'text-slate-500 hover:text-slate-700'" class="flex-1 py-2 text-sm font-bold rounded-lg transition-all">Buat Akun Baru</button>
             </div>

             <!-- Content -->
             <div class="p-6 overflow-y-auto">
                 <form :action="committeeId ? '{{ url('kepanitiaan/ketupel/events/'.$event->id.'/team') }}/' + committeeId : '{{ route('kepanitiaan.ketupel.store-team', $event) }}'" method="POST">
                     @csrf
                     <input type="hidden" name="_method" x-bind:value="committeeId ? 'PUT' : 'POST'">
                     <input type="hidden" name="role_id" :value="roleId">
                     <input type="hidden" name="division_id" :value="divisionId">

                     <!-- TAB: Existing User -->
                     <div x-show="tab === 'existing'" class="space-y-4">
                         <div>
                             <label class="block text-sm font-bold text-slate-700 mb-2">Cari Anggota HIMASI</label>
                             <select name="user_id" x-model="userId" class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors" :required="tab === 'existing'">
                                 <option value="">-- Pilih Anggota --</option>
                                 @foreach($allUsers as $u)
                                     @php
                                         $divName = $u->memberships->first() ? $u->memberships->first()->division->name : 'Anggota Biasa';
                                     @endphp
                                     <option value="{{ $u->id }}">{{ $u->name }} ({{ $divName }})</option>
                                 @endforeach
                             </select>
                         </div>
                     </div>

                     <!-- TAB: New User -->
                     <div x-show="tab === 'new'" class="space-y-4">
                         <div>
                             <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                             <input type="text" name="name" class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block p-3 transition-all" placeholder="Nama Lengkap" :required="tab === 'new'">
                         </div>
                         
                         <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                             <div>
                                 <label class="block text-sm font-bold text-slate-700 mb-2">NIM <span class="text-rose-500">*</span></label>
                                 <input type="text" name="nim" class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block p-3 transition-all" placeholder="NIM" :required="tab === 'new'">
                             </div>
                             <div>
                                 <label class="block text-sm font-bold text-slate-700 mb-2">Angkatan <span class="text-rose-500">*</span></label>
                                 <input type="number" name="angkatan" class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block p-3 transition-all" placeholder="contoh: 2023" :required="tab === 'new'">
                             </div>
                         </div>
                         
                         <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                             <div>
                                 <label class="block text-sm font-bold text-slate-700 mb-2">Email Login <span class="text-rose-500">*</span></label>
                                 <div class="flex">
                                     <input type="text" name="email_prefix" class="w-full bg-white border border-slate-200 border-r-0 text-slate-900 text-sm rounded-l-lg focus:ring-brand-500 focus:border-brand-500 p-3 transition-all" placeholder="cth: budi" :required="tab === 'new'">
                                     <span class="inline-flex items-center px-3 text-sm font-medium text-slate-500 bg-slate-50 border border-l-0 border-slate-200 rounded-r-lg shrink-0">@himasi.unja.ac.id</span>
                                 </div>
                             </div>
                             <div>
                                 <label class="block text-sm font-bold text-slate-700 mb-2">Password Sementara <span class="text-rose-500">*</span></label>
                                 <input type="text" name="password" class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 p-3 transition-all" placeholder="Masukkan password awal" :required="tab === 'new'">
                             </div>
                         </div>
                     </div>

                     <div class="mt-8 flex justify-end gap-3">
                         <button type="button" @click="modalOpen = false" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 rounded-lg transition-colors border border-slate-200">Batal</button>
                         <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-lg transition-colors">
                             Simpan
                         </button>
                     </div>
                 </form>
             </div>
         </div>
    </div>

    <!-- MODAL DETAIL PANITIA -->
    <div x-show="detailOpen" style="display: none;" class="fixed inset-0 z-[110] flex items-center justify-center px-4">
        <!-- Backdrop -->
        <div x-show="detailOpen" x-transition.opacity class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="detailOpen = false"></div>

        <!-- Modal Body -->
        <div x-show="detailOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="relative w-full max-w-sm bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col">
             
             <!-- Header -->
             <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between shrink-0 bg-slate-50">
                 <div>
                     <h3 class="font-bold text-slate-900 text-lg">Detail Panitia</h3>
                 </div>
                 <button @click="detailOpen = false" class="text-slate-400 hover:text-slate-600 transition-colors text-xl font-bold leading-none">&times;</button>
             </div>

             <!-- Content -->
             <div class="p-6">
                 <div class="space-y-4">
                     <div>
                         <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Lengkap</p>
                         <p class="text-sm font-semibold text-slate-900" x-text="detailUser.name"></p>
                     </div>
                     <div class="grid grid-cols-2 gap-4">
                         <div>
                             <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">NIM</p>
                             <p class="text-sm font-semibold text-slate-900" x-text="detailUser.nim"></p>
                         </div>
                         <div>
                             <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Angkatan</p>
                             <p class="text-sm font-semibold text-slate-900" x-text="detailUser.angkatan"></p>
                         </div>
                     </div>
                     <div>
                         <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Jabatan di Panitia</p>
                         <p class="inline-flex items-center px-2.5 py-1 rounded-md bg-brand-50 text-brand-600 text-xs font-bold" x-text="detailUser.role"></p>
                     </div>
                     <hr class="border-slate-100">
                     <div>
                         <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Email</p>
                         <p class="text-sm font-semibold text-slate-900" x-text="detailUser.email"></p>
                     </div>
                 </div>
             </div>
             
             <!-- Footer -->
             <div class="px-6 py-4 border-t border-slate-100 flex justify-end bg-slate-50">
                 <button type="button" @click="detailOpen = false" class="px-5 py-2.5 text-sm font-semibold text-white bg-slate-900 hover:bg-slate-800 rounded-xl transition-colors">Tutup</button>
             </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="deleteModalOpen" class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="deleteModalOpen = false"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div class="relative w-full max-w-sm bg-white rounded-2xl shadow-xl p-6 overflow-hidden" 
                 x-show="deleteModalOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                <div class="w-12 h-12 rounded-full bg-rose-100 flex items-center justify-center mx-auto mb-4 text-rose-500">
                    <i class="ph-fill ph-warning-circle text-2xl"></i>
                </div>
                <h3 class="text-lg font-black text-slate-800 mb-2">Konfirmasi Hapus</h3>
                <p class="text-sm font-medium text-slate-500 mb-6" x-text="deleteMessage"></p>
                
                <div class="flex justify-center gap-3">
                    <button type="button" @click="deleteModalOpen = false" class="px-4 py-2 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                        Batal
                    </button>
                    <form :action="deleteActionUrl" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-rose-500 hover:bg-rose-600 rounded-xl shadow-sm transition-colors">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
