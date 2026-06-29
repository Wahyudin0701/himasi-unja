@extends('layouts.dashboard')

@section('title', 'Kelola Tim')

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
        
        openModal(mode, roleId, divisionId, targetName, committeeId = '', userId = '') {
            this.mode = mode;
            this.roleId = roleId;
            this.divisionId = divisionId;
            this.targetName = targetName;
            this.committeeId = committeeId;
            this.userId = userId ? String(userId) : '';
            this.tab = 'existing';
            this.modalOpen = true;
        }
    }">

    <!-- Event Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Kelola Tim</h1>
            <p class="text-slate-500 mt-1 text-sm font-medium">Kepanitiaan: <strong class="text-slate-700">{{ $event->name }}</strong></p>
        </div>
        <div class="px-4 py-2 bg-white border border-slate-200 rounded-xl shadow-sm text-center min-w-[120px]">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">Status Acara</span>
            <span class="text-sm font-bold text-slate-800">{{ ucfirst($event->status) }}</span>
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
                    <div class="flex flex-col p-4 rounded-xl border border-slate-200 bg-slate-50 relative group transition-colors hover:border-slate-300">
                        <div class="flex-1 pr-6">
                            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">{{ $committee->role->name }}</p>
                            <p class="font-bold text-slate-900 text-sm">{{ $committee->user->name }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $committee->user->nim ?? '-' }} - ({{ $committee->user->angkatan ?? '-' }})</p>
                        </div>
                        
                        {{-- Tombol Edit & Hapus (Kecuali Ketupel/Wakil) --}}
                        @if(!in_array($committee->role->slug, $ketupelSlugs))
                        <div class="absolute right-3 top-3 flex items-center justify-end gap-2 transition-opacity">
                            <button type="button" @click="openModal('edit', {{ $committee->committee_role_id }}, '', '{{ $committee->role->name }}', {{ $committee->id }}, {{ $committee->user_id }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 hover:text-blue-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                Ganti
                            </button>
                            <form action="{{ route('kepanitiaan.ketupel.remove-team', [$event, $committee]) }}" method="POST" onsubmit="return confirm('Hapus {{ $committee->user->name }} dari jabatannya?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-rose-600 bg-rose-50 border border-rose-200 rounded-lg hover:bg-rose-100 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                    Hapus
                                </button>
                            </form>
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
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <div>
                <h2 class="text-base font-bold text-slate-900">Divisi Kepanitiaan</h2>
            </div>
            <a href="{{ route('kepanitiaan.ketupel.create-division', $event) }}" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-lg transition-colors">
                + Tambah Divisi
            </a>
        </div>
        
        <div class="divide-y divide-slate-100">
            @forelse($event->divisions->sortBy('sort_order') as $division)
                @php
                    $divCommittees = $event->committees->filter(fn($c) => $c->event_division_id === $division->id);
                    $co = $divCommittees->first(fn($c) => $c->role && $c->role->slug === 'co-divisi');
                    $divAnggota = $divCommittees->filter(fn($c) => $c->role && $c->role->slug === 'anggota');
                @endphp
                <div class="p-6 flex flex-col md:flex-row gap-6">
                    
                    {{-- Info Divisi --}}
                    <div class="w-full md:w-1/4 shrink-0 border-r border-transparent md:border-slate-100 pr-4">
                        <div class="flex items-center justify-between gap-2 mb-1">
                            <h3 class="font-bold text-slate-900 text-base leading-tight">{{ $division->name }}</h3>
                            @if($division->name !== 'Tim Inti')
                            <form action="{{ route('kepanitiaan.ketupel.destroy-division', [$event, $division]) }}" method="POST" onsubmit="return confirm('Hapus divisi {{ $division->name }} beserta seluruh anggotanya secara permanen?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs font-semibold text-rose-500 hover:text-rose-600 transition-colors" title="Hapus Divisi">
                                    Hapus Divisi
                                </button>
                            </form>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 font-medium">{{ $divCommittees->count() }} Anggota Terdaftar</p>
                    </div>

                    {{-- Tim Divisi --}}
                    <div class="flex-1 w-full space-y-5">
                        
                        {{-- CO Section --}}
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Koordinator</p>
                            @if($co)
                                <div class="flex items-center justify-between bg-white border border-slate-200 px-4 py-2 rounded-lg hover:bg-slate-50 transition-colors group">
                                    <div>
                                        <span class="text-sm font-semibold text-slate-900 block">{{ $co->user->name }}</span>
                                        <span class="text-xs text-slate-500 block mt-0.5">{{ $co->user->nim ?? '-' }} - ({{ $co->user->angkatan ?? '-' }})</span>
                                    </div>
                                    <div class="flex items-center justify-end gap-2 transition-opacity">
                                        <button type="button" @click="openModal('co', {{ $coRole->id }}, {{ $division->id }}, 'Koordinator {{ $division->name }}', {{ $co->id }}, {{ $co->user_id }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 hover:text-blue-600 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                            Ganti
                                        </button>
                                        <form action="{{ route('kepanitiaan.ketupel.remove-team', [$event, $co]) }}" method="POST" onsubmit="return confirm('Hapus {{ $co->user->name }} dari Koordinator {{ $division->name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-rose-600 bg-rose-50 border border-rose-200 rounded-lg hover:bg-rose-100 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <button @click="openModal('co', {{ $coRole->id }}, {{ $division->id }}, 'Koordinator {{ $division->name }}')" class="inline-flex items-center gap-1 text-sm font-semibold text-brand-600 hover:text-brand-700 transition-colors">
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
                                        <div class="flex items-center justify-between bg-white border border-slate-200 px-4 py-2 rounded-lg hover:bg-slate-50 transition-colors group">
                                            <div>
                                                <span class="text-sm font-semibold text-slate-700 block">{{ $anggota->user->name }}</span>
                                                <span class="text-xs text-slate-500 block mt-0.5">{{ $anggota->user->nim ?? '-' }} - ({{ $anggota->user->angkatan ?? '-' }})</span>
                                            </div>
                                            <div class="flex items-center justify-end gap-2 transition-opacity">
                                                <button type="button" @click="openModal('anggota', {{ $anggotaRole->id }}, {{ $division->id }}, 'Anggota {{ $division->name }}', {{ $anggota->id }}, {{ $anggota->user_id }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 hover:text-blue-600 transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                                    Ganti
                                                </button>
                                                <form action="{{ route('kepanitiaan.ketupel.remove-team', [$event, $anggota]) }}" method="POST" onsubmit="return confirm('Hapus {{ $anggota->user->name }} dari Anggota {{ $division->name }}?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-rose-600 bg-rose-50 border border-rose-200 rounded-lg hover:bg-rose-100 transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-slate-400 italic mb-2">Belum ada anggota di divisi ini.</p>
                            @endif
                            <button @click="openModal('anggota', {{ $anggotaRole->id }}, {{ $division->id }}, 'Anggota {{ $division->name }}')" class="mt-3 inline-flex items-center gap-1 text-sm font-semibold text-brand-600 hover:text-brand-700 transition-colors">
                                + Tambah Anggota
                            </button>
                        </div>

                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center bg-slate-50/50">
                    <p class="text-sm font-semibold text-slate-700 mb-1">Belum ada divisi</p>
                    <p class="text-xs text-slate-500">Sistem belum membuat divisi untuk acara ini.</p>
                </div>
            @endforelse
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
                         <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 mb-4">
                             <p class="text-xs text-blue-800 font-medium leading-relaxed">Fitur ini akan membuat <b>akun login baru</b>. Password default adalah <code class="bg-blue-100 px-1 rounded text-blue-900">password</code>.</p>
                         </div>
                         <div>
                             <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                             <input type="text" name="name" class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block p-3 transition-all" placeholder="Contoh: Budi Santoso" :required="tab === 'new'">
                         </div>
                         <div>
                             <label class="block text-sm font-bold text-slate-700 mb-2">Email Aktif</label>
                             <input type="email" name="email" class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block p-3 transition-all" placeholder="budi@himasi.unja.ac.id" :required="tab === 'new'">
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
</div>

@endsection
