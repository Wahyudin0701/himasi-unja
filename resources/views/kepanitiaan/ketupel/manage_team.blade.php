@extends('layouts.dashboard')

@section('title', 'Kelola Tim - ' . $event->name)

@section('breadcrumbs')
    <span>Kepanitiaan</span>
    <i class="ph ph-caret-right text-[10px]"></i>
    <a href="{{ route('kepanitiaan.ketupel.dashboard') }}" class="hover:text-brand-600 transition-colors">Dashboard Ketupel</a>
    <i class="ph ph-caret-right text-[10px]"></i>
    <span class="text-slate-600">Kelola Tim</span>
@endsection

@section('content')

@php
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
        tab: 'existing',
        
        openModal(mode, roleId, divisionId, targetName) {
            this.mode = mode;
            this.roleId = roleId;
            this.divisionId = divisionId;
            this.targetName = targetName;
            this.tab = 'existing';
            this.modalOpen = true;
        }
    }">

    <!-- Event Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ $event->name }}</h1>
            <p class="text-slate-500 mt-1 text-sm font-medium">Lengkapi susunan tim inti dan anggota divisi kepanitiaan Anda.</p>
        </div>
        <div class="px-4 py-2 bg-brand-50 border border-brand-100 rounded-xl">
            <p class="text-xs font-bold text-brand-600 uppercase tracking-widest text-center">Status</p>
            <p class="text-sm font-bold text-brand-800">{{ ucfirst($event->status) }}</p>
        </div>
    </div>

    <!-- Tim Inti -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Tim Inti</h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Ketua, Sekretaris, & Bendahara Pelaksana</p>
            </div>
            <i class="ph-fill ph-crown text-2xl text-amber-500"></i>
        </div>
        <div class="p-6">
            @php
                $intiCommittees = $event->committees->filter(fn($c) => $c->role && $c->role->scope === 'inti');
                $hasSekpel = $intiCommittees->contains(fn($c) => $c->role->slug === 'sekretaris-pelaksana');
                $hasBenpel = $intiCommittees->contains(fn($c) => $c->role->slug === 'bendahara-pelaksana');
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Daftar Anggota Tim Inti Terisi --}}
                @foreach($intiCommittees->sortBy(fn($c) => $c->role->level) as $committee)
                    <div class="flex items-center justify-between p-4 rounded-xl bg-white border border-slate-200 shadow-sm relative group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-brand-50 flex items-center justify-center text-brand-700 font-bold border border-brand-100 shrink-0">
                                {{ strtoupper(substr($committee->user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0 pr-6">
                                <p class="font-bold text-slate-900 text-sm truncate">{{ $committee->user->name }}</p>
                                <p class="text-[11px] text-slate-500 font-medium uppercase tracking-wide mt-0.5">{{ $committee->role->name }}</p>
                            </div>
                        </div>
                        
                        {{-- Tombol Hapus (Kecuali Ketupel/Wakil) --}}
                        @if(!in_array($committee->role->slug, $ketupelSlugs))
                        <form action="{{ route('kepanitiaan.ketupel.remove-team', [$event, $committee]) }}" method="POST" class="absolute right-3 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity" onsubmit="return confirm('Hapus {{ $committee->user->name }} dari jabatannya?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-100 flex items-center justify-center transition-colors">
                                <i class="ph ph-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                @endforeach

                {{-- Tombol Tambah Sekpel --}}
                @if(!$hasSekpel)
                <button @click="openModal('sekpel', {{ $sekpelRole->id }}, '', 'Sekretaris Pelaksana')" class="flex flex-col items-center justify-center p-4 rounded-xl border-2 border-dashed border-slate-200 hover:border-brand-400 hover:bg-brand-50/50 transition-colors group h-[74px]">
                    <div class="flex items-center gap-2 text-slate-500 group-hover:text-brand-600 font-medium text-sm">
                        <i class="ph ph-plus-circle text-lg"></i>
                        <span>Tambah Sekpel</span>
                    </div>
                </button>
                @endif

                {{-- Tombol Tambah Benpel --}}
                @if(!$hasBenpel)
                <button @click="openModal('benpel', {{ $benpelRole->id }}, '', 'Bendahara Pelaksana')" class="flex flex-col items-center justify-center p-4 rounded-xl border-2 border-dashed border-slate-200 hover:border-brand-400 hover:bg-brand-50/50 transition-colors group h-[74px]">
                    <div class="flex items-center gap-2 text-slate-500 group-hover:text-brand-600 font-medium text-sm">
                        <i class="ph ph-plus-circle text-lg"></i>
                        <span>Tambah Benpel</span>
                    </div>
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Divisi Kepanitiaan -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Divisi Kepanitiaan</h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $event->divisions->count() }} divisi terdaftar</p>
            </div>
            <i class="ph-fill ph-tree-structure text-2xl text-violet-500"></i>
        </div>
        
        <div class="divide-y divide-slate-100">
            @forelse($event->divisions->sortBy('sort_order') as $division)
                @php
                    $divCommittees = $event->committees->filter(fn($c) => $c->event_division_id === $division->id);
                    $co = $divCommittees->first(fn($c) => $c->role && $c->role->slug === 'co-divisi');
                    $divAnggota = $divCommittees->filter(fn($c) => $c->role && $c->role->slug === 'anggota');
                @endphp
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                        
                        {{-- Info Divisi --}}
                        <div class="w-full md:w-1/4 shrink-0">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 rounded-xl bg-violet-50 flex items-center justify-center border border-violet-100">
                                    <i class="ph-fill ph-flag-banner text-xl text-violet-500"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900 text-base leading-tight">{{ $division->name }}</h3>
                                    <span class="text-[11px] text-slate-500 font-semibold uppercase tracking-wider">{{ $divCommittees->count() }} Orang</span>
                                </div>
                            </div>
                        </div>

                        {{-- Tim Divisi --}}
                        <div class="flex-1 w-full space-y-4">
                            
                            {{-- CO Section --}}
                            <div class="bg-slate-50 rounded-xl p-3 border border-slate-200">
                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="text-xs font-black text-brand-600 bg-brand-100 px-2.5 py-1 rounded-md uppercase tracking-widest shrink-0">Koordinator</span>
                                    @if($co)
                                        <div class="flex items-center justify-between flex-1 min-w-[200px] bg-white px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm group">
                                            <span class="text-sm font-bold text-slate-800">{{ $co->user->name }}</span>
                                            <form action="{{ route('kepanitiaan.ketupel.remove-team', [$event, $co]) }}" method="POST" onsubmit="return confirm('Hapus {{ $co->user->name }} dari Koordinator {{ $division->name }}?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-slate-400 hover:text-rose-500 transition-colors p-1">
                                                    <i class="ph ph-x-circle text-lg"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <button @click="openModal('co', {{ $coRole->id }}, {{ $division->id }}, 'Koordinator {{ $division->name }}')" class="flex-1 min-w-[200px] text-left text-sm text-brand-600 font-semibold hover:text-brand-700 hover:underline px-2 py-1.5 flex items-center gap-1.5 transition-all">
                                            <i class="ph ph-plus-circle text-lg"></i> Tunjuk CO
                                        </button>
                                    @endif
                                </div>
                            </div>

                            {{-- Anggota Section --}}
                            <div class="pl-2">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Anggota ({{ $divAnggota->count() }})</span>
                                    <button @click="openModal('anggota', {{ $anggotaRole->id }}, {{ $division->id }}, 'Anggota {{ $division->name }}')" class="text-xs font-bold text-brand-600 hover:text-brand-700 bg-brand-50 hover:bg-brand-100 px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5">
                                        <i class="ph ph-plus-circle"></i> Tambah Anggota
                                    </button>
                                </div>

                                @if($divAnggota->count() > 0)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($divAnggota as $anggota)
                                            <div class="group flex items-center gap-1.5 bg-white border border-slate-200 shadow-sm pl-3 pr-1.5 py-1.5 rounded-full hover:border-slate-300 transition-colors">
                                                <span class="text-sm font-semibold text-slate-700">{{ $anggota->user->name }}</span>
                                                <form action="{{ route('kepanitiaan.ketupel.remove-team', [$event, $anggota]) }}" method="POST" onsubmit="return confirm('Hapus {{ $anggota->user->name }} dari Anggota {{ $division->name }}?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="w-6 h-6 rounded-full bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-500 flex items-center justify-center transition-colors">
                                                        <i class="ph ph-x text-sm"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-slate-400 italic">Belum ada anggota di divisi ini.</p>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center bg-slate-50/50">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                        <i class="ph ph-tree-structure text-3xl text-slate-300"></i>
                    </div>
                    <p class="text-sm font-semibold text-slate-700">Belum ada divisi</p>
                    <p class="text-xs text-slate-500 mt-1">Sistem belum mengenerate divisi untuk acara ini.</p>
                </div>
            @endforelse
        </div>
    </div>


    <!-- MODAL ASSIGN TEAM -->
    <div x-show="modalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center px-4">
        <!-- Backdrop -->
        <div x-show="modalOpen" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="modalOpen = false"></div>

        <!-- Modal Body -->
        <div x-show="modalOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
             
             <!-- Header -->
             <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 shrink-0">
                 <div>
                     <h3 class="font-bold text-slate-900 text-lg">Assign <span x-text="targetName"></span></h3>
                     <p class="text-xs text-slate-500 font-medium mt-0.5">Pilih dari akun yang sudah ada atau buat baru.</p>
                 </div>
                 <button @click="modalOpen = false" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 transition-colors">
                     <i class="ph ph-x"></i>
                 </button>
             </div>

             <!-- Tabs -->
             <div class="flex p-2 bg-slate-50 border-b border-slate-100 shrink-0">
                 <button @click="tab = 'existing'" :class="tab === 'existing' ? 'bg-white shadow-sm text-brand-600' : 'text-slate-500 hover:text-slate-700'" class="flex-1 py-2 text-sm font-bold rounded-xl transition-all">Pilih Akun</button>
                 <button @click="tab = 'new'" :class="tab === 'new' ? 'bg-white shadow-sm text-brand-600' : 'text-slate-500 hover:text-slate-700'" class="flex-1 py-2 text-sm font-bold rounded-xl transition-all">Buat Baru</button>
             </div>

             <!-- Content -->
             <div class="p-6 overflow-y-auto">
                 <form action="{{ route('kepanitiaan.ketupel.store-team', $event) }}" method="POST">
                     @csrf
                     <input type="hidden" name="role_id" :value="roleId">
                     <input type="hidden" name="division_id" :value="divisionId">

                     <!-- TAB: Existing User -->
                     <div x-show="tab === 'existing'" class="space-y-4">
                         <div>
                             <label class="block text-sm font-bold text-slate-700 mb-2">Cari Anggota HIMASI</label>
                             <select name="user_id" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 font-medium transition-all" :required="tab === 'existing'">
                                 <option value="">-- Pilih Anggota --</option>
                                 @foreach($allUsers as $u)
                                     <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                                 @endforeach
                             </select>
                             <p class="text-xs text-slate-500 mt-2 flex items-center gap-1.5"><i class="ph-fill ph-info text-brand-500"></i> Hanya menampilkan user yang sudah terdaftar di sistem HIMASI.</p>
                         </div>
                     </div>

                     <!-- TAB: New User -->
                     <div x-show="tab === 'new'" class="space-y-4">
                         <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 flex gap-3 mb-4">
                             <i class="ph-fill ph-warning-circle text-amber-500 text-lg mt-0.5 shrink-0"></i>
                             <p class="text-[11px] text-amber-800 font-medium leading-relaxed">Fitur ini akan <b>membuat akun login baru</b> untuk panitia. Password default adalah <code class="bg-amber-100 px-1 rounded text-amber-900">password</code>. Pastikan email belum terdaftar.</p>
                         </div>
                         <div>
                             <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                             <input type="text" name="name" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-all" placeholder="Misal: Budi Santoso" :required="tab === 'new'">
                         </div>
                         <div>
                             <label class="block text-sm font-bold text-slate-700 mb-2">Email Aktif</label>
                             <input type="email" name="email" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-all" placeholder="budi@himasi.unja.ac.id" :required="tab === 'new'">
                         </div>
                     </div>

                     <div class="mt-8 pt-5 border-t border-slate-100 flex justify-end gap-3">
                         <button type="button" @click="modalOpen = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">Batal</button>
                         <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-brand-600 hover:bg-brand-700 rounded-xl transition-colors shadow-lg shadow-brand-500/30 flex items-center gap-2">
                             <i class="ph ph-check-circle text-lg"></i> Simpan
                         </button>
                     </div>
                 </form>
             </div>
        </div>
    </div>
</div>

@endsection
