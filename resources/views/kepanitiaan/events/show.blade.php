@extends('layouts.dashboard')

@section('title', 'Dashboard Event')
@section('breadcrumbs')
    <span class="text-slate-700">Kepanitiaan</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-8 opacity-5 pointer-events-none">
            <i class="ph-fill ph-ticket text-[12rem]"></i>
        </div>
        
        <div class="relative flex flex-col md:flex-row md:items-start justify-between gap-6">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    @if($event->status == 'planning')
                        <span class="px-3 py-1 rounded-lg bg-amber-50 text-amber-600 text-xs font-bold uppercase tracking-widest border border-amber-200">Planning</span>
                    @elseif($event->status == 'ongoing')
                        <span class="px-3 py-1 rounded-lg bg-brand-50 text-brand-600 text-xs font-bold uppercase tracking-widest border border-brand-200">Ongoing</span>
                    @else
                        <span class="px-3 py-1 rounded-lg bg-emerald-50 text-emerald-600 text-xs font-bold uppercase tracking-widest border border-emerald-200">Completed</span>
                    @endif
                    <span class="text-sm font-medium text-slate-500">{{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d M Y') }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-2">{{ $event->name }}</h1>
                <p class="text-slate-600 max-w-2xl leading-relaxed">{{ $event->description }}</p>
                
                @if($event->workProgram)
                <div class="mt-4 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-100 text-sm font-medium text-slate-600">
                    <i class="ph-fill ph-link"></i>
                    Terkait Proker: {{ $event->workProgram->name }}
                </div>
                @endif
            </div>
            
            <div class="flex gap-2">
                <a href="{{ route('events.edit', $event->id) }}" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-medium transition-colors shadow-sm">
                    <i class="ph-bold ph-pencil-simple"></i>
                    Edit Event
                </a>
            </div>
        </div>
    </div>

    <!-- Modul-modul Operasional (Akan aktif di Sprint 2) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-4 flex flex-col items-center justify-center text-center gap-2 opacity-60 cursor-not-allowed group">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <i class="ph-fill ph-list-numbers text-2xl group-hover:scale-110 transition-transform"></i>
            </div>
            <div>
                <p class="font-bold text-slate-800">Rundown</p>
                <p class="text-xs text-slate-500">Coming Soon</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-4 flex flex-col items-center justify-center text-center gap-2 opacity-60 cursor-not-allowed group">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <i class="ph-fill ph-envelope-open text-2xl group-hover:scale-110 transition-transform"></i>
            </div>
            <div>
                <p class="font-bold text-slate-800">Surat & Proposal</p>
                <p class="text-xs text-slate-500">Coming Soon</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-4 flex flex-col items-center justify-center text-center gap-2 opacity-60 cursor-not-allowed group">
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                <i class="ph-fill ph-users-three text-2xl group-hover:scale-110 transition-transform"></i>
            </div>
            <div>
                <p class="font-bold text-slate-800">Buku Tamu</p>
                <p class="text-xs text-slate-500">Coming Soon</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-4 flex flex-col items-center justify-center text-center gap-2 opacity-60 cursor-not-allowed group">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i class="ph-fill ph-handshake text-2xl group-hover:scale-110 transition-transform"></i>
            </div>
            <div>
                <p class="font-bold text-slate-800">Sponsor</p>
                <p class="text-xs text-slate-500">Coming Soon</p>
            </div>
        </div>
    </div>

    <!-- Struktur Kepanitiaan -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-slate-900">Struktur Kepanitiaan</h2>
            <button onclick="document.getElementById('modal-add-division').classList.remove('hidden')" class="text-sm font-semibold text-brand-600 hover:text-brand-700 flex items-center gap-1">
                <i class="ph-bold ph-plus"></i> Tambah Divisi
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Tim Inti -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden sticky top-24">
                    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-slate-900">{{ $timInti->name }}</h3>
                            <p class="text-xs text-slate-500">{{ $timInti->description }}</p>
                        </div>
                        <button onclick="openAddMemberModal({{ $timInti->id }}, '{{ $timInti->name }}')" class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-600 hover:text-brand-600 hover:border-brand-200 transition-colors">
                            <i class="ph-bold ph-user-plus"></i>
                        </button>
                    </div>
                    <div class="p-5 space-y-4">
                        @forelse($timInti->committees as $panitia)
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($panitia->user->name) }}&background=random" class="w-10 h-10 rounded-full object-cover">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-slate-900 truncate">{{ $panitia->user->name }}</p>
                                    <p class="text-xs font-medium text-brand-600">{{ $panitia->committeeRole->name }}</p>
                                </div>
                                <form action="{{ route('events.committees.destroy', [$event->id, $panitia->id]) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-500 transition-colors" onclick="return confirm('Hapus panitia ini?')"><i class="ph ph-trash"></i></button>
                                </form>
                            </div>
                        @empty
                            <div class="text-center py-6 text-slate-500 text-sm">
                                Belum ada anggota di Tim Inti.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Divisi Lainnya -->
            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($divisiLain as $divisi)
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center group">
                            <div>
                                <h3 class="font-bold text-slate-900">{{ $divisi->name }}</h3>
                                <p class="text-xs text-slate-500">{{ $divisi->committees->count() }} Anggota</p>
                            </div>
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="openAddMemberModal({{ $divisi->id }}, '{{ $divisi->name }}')" class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-600 hover:text-brand-600 hover:border-brand-200 transition-colors" title="Tambah Anggota">
                                    <i class="ph-bold ph-user-plus"></i>
                                </button>
                                <form action="{{ route('events.divisions.destroy', [$event->id, $divisi->id]) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-600 hover:text-rose-600 hover:border-rose-200 transition-colors" title="Hapus Divisi" onclick="return confirm('Yakin hapus divisi ini beserta anggotanya?')">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="p-5 space-y-4">
                            @forelse($divisi->committees as $panitia)
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($panitia->user->name) }}&background=random" class="w-10 h-10 rounded-full object-cover">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-slate-900 truncate">{{ $panitia->user->name }}</p>
                                        <p class="text-xs font-medium text-slate-500">{{ $panitia->committeeRole->name }}</p>
                                    </div>
                                    <form action="{{ route('events.committees.destroy', [$event->id, $panitia->id]) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-rose-500 transition-colors" onclick="return confirm('Hapus panitia ini?')"><i class="ph ph-trash"></i></button>
                                    </form>
                                </div>
                            @empty
                                <div class="text-center py-4 text-slate-500 text-sm border border-dashed border-slate-200 rounded-xl">
                                    Belum ada anggota.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
                
                <!-- Tambah Divisi Card -->
                <div onclick="document.getElementById('modal-add-division').classList.remove('hidden')" class="bg-transparent border-2 border-dashed border-slate-300 rounded-2xl flex flex-col items-center justify-center p-8 text-center cursor-pointer hover:border-brand-500 hover:bg-brand-50 transition-colors group min-h-[200px]">
                    <div class="w-12 h-12 rounded-full bg-slate-100 group-hover:bg-brand-100 flex items-center justify-center text-slate-500 group-hover:text-brand-600 mb-3 transition-colors">
                        <i class="ph-bold ph-plus text-xl"></i>
                    </div>
                    <p class="font-bold text-slate-700 group-hover:text-brand-700">Tambah Divisi</p>
                    <p class="text-sm text-slate-500 mt-1">Buat divisi khusus untuk event ini.</p>
                </div>
            </div>
            
        </div>
    </div>
</div>

<!-- Modal Tambah Divisi -->
<div id="modal-add-division" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="document.getElementById('modal-add-division').classList.add('hidden')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
        <h3 class="text-lg font-bold text-slate-900 mb-4">Tambah Divisi Baru</h3>
        <form action="{{ route('events.divisions.store', $event->id) }}" method="POST">
            @csrf
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Divisi</label>
                    <input type="text" name="name" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none" placeholder="Misal: Divisi Konsumsi">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi</label>
                    <input type="text" name="description" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none" placeholder="Opsional">
                </div>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-add-division').classList.add('hidden')" class="px-4 py-2 rounded-xl font-medium text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl font-medium text-white bg-brand-600 hover:bg-brand-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Anggota -->
<div id="modal-add-member" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="document.getElementById('modal-add-member').classList.add('hidden')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-900">Tambah Anggota - <span id="member-division-name" class="text-brand-600"></span></h3>
            <button onclick="document.getElementById('modal-add-member').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="ph-bold ph-x text-lg"></i></button>
        </div>

        <div class="p-6 overflow-y-auto" x-data="{ tab: 'pengurus' }">
            <!-- Tabs -->
            <div class="flex gap-2 p-1 bg-slate-100 rounded-xl mb-6">
                <button @click="tab = 'pengurus'" :class="tab == 'pengurus' ? 'bg-white shadow-sm text-slate-800' : 'text-slate-500 hover:text-slate-700'" class="flex-1 py-2 text-sm font-semibold rounded-lg transition-all">Dari Pengurus HIMA</button>
                <button @click="tab = 'volunteer'" :class="tab == 'volunteer' ? 'bg-white shadow-sm text-slate-800' : 'text-slate-500 hover:text-slate-700'" class="flex-1 py-2 text-sm font-semibold rounded-lg transition-all">Volunteer Baru</button>
            </div>

            <!-- Form: Dari Pengurus -->
            <form x-show="tab == 'pengurus'" action="{{ route('events.committees.store', $event->id) }}" method="POST">
                @csrf
                <input type="hidden" name="event_division_id" id="event_division_id_existing">
                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Pilih Pengurus</label>
                        <select name="user_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-brand-500 outline-none">
                            <option value="">-- Pilih Mahasiswa --</option>
                            @foreach(\App\Models\User::orderBy('name')->get() as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Peran (Role)</label>
                        <select name="committee_role_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-brand-500 outline-none">
                            @foreach(\App\Models\Kepanitiaan\CommitteeRole::orderBy('name')->get() as $r)
                                <option value="{{ $r->id }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="w-full py-2.5 rounded-xl font-medium text-white bg-brand-600 hover:bg-brand-700">Tambahkan</button>
            </form>

            <!-- Form: Volunteer -->
            <form x-show="tab == 'volunteer'" action="{{ route('events.committees.volunteer', $event->id) }}" method="POST">
                @csrf
                <input type="hidden" name="event_division_id" id="event_division_id_new">
                <div class="space-y-4 mb-6">
                    <p class="text-sm text-slate-500 bg-blue-50 text-blue-800 p-3 rounded-xl border border-blue-100">
                        Sistem akan otomatis membuatkan akun baru dengan password default acak untuk volunteer ini.
                    </p>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-brand-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-brand-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">No. WhatsApp</label>
                        <input type="text" name="phone" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-brand-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Peran (Role)</label>
                        <select name="committee_role_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-brand-500 outline-none">
                            @foreach(\App\Models\Kepanitiaan\CommitteeRole::orderBy('name')->get() as $r)
                                <option value="{{ $r->id }}" {{ $r->name == 'Anggota' ? 'selected' : '' }}>{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="w-full py-2.5 rounded-xl font-medium text-white bg-slate-900 hover:bg-slate-800">Daftarkan Volunteer</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openAddMemberModal(divisionId, divisionName) {
        document.getElementById('member-division-name').innerText = divisionName;
        document.getElementById('event_division_id_existing').value = divisionId;
        document.getElementById('event_division_id_new').value = divisionId;
        document.getElementById('modal-add-member').classList.remove('hidden');
    }
</script>
@endpush
