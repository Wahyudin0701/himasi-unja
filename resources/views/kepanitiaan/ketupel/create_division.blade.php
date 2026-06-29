@extends('layouts.dashboard')

@section('title', 'Tambah Divisi - ' . $event->name)

@section('breadcrumbs')
    <a href="{{ route('kepanitiaan.ketupel.manage-team', $event) }}" class="text-slate-500 hover:text-brand-600 transition-colors">Kelola Tim</a>
    <i class="ph ph-caret-right text-slate-400 text-xs"></i>
    <span class="text-slate-700 font-medium">Tambah Divisi</span>
@endsection

@section('content')
    <div x-data="{ 
        div_co_type: 'existing',
        div_co_user_id: '',
        div_anggota: [
            { id: 1, type: 'existing', user_id: '', name: '', email: '' }
        ],
        addAnggota() {
            this.div_anggota.push({ id: Date.now(), type: 'existing', user_id: '', name: '', email: '' });
        },
        removeAnggota(index) {
            this.div_anggota.splice(index, 1);
        },
        isOptionDisabled(userId, currentIndex = null, isCo = false) {
            if (!userId) return false;
            
            if (isCo) {
                return this.div_anggota.some(a => a.type === 'existing' && String(a.user_id) === String(userId));
            }
            
            if (this.div_co_type === 'existing' && String(this.div_co_user_id) === String(userId)) return true;
            
            return this.div_anggota.some((a, idx) => idx !== currentIndex && a.type === 'existing' && String(a.user_id) === String(userId));
        }
    }">

    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Tambah Divisi Baru</h1>
            <p class="text-slate-500 mt-1 text-sm font-medium">Buat divisi lengkap beserta Koordinator dan Anggotanya.</p>
        </div>
        <a href="{{ route('kepanitiaan.ketupel.manage-team', $event) }}" class="px-4 py-2 bg-white border border-slate-200 hover:border-slate-300 text-slate-600 text-sm font-bold rounded-xl transition-colors shadow-sm flex items-center gap-2">
            <i class="ph ph-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Form Content -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('kepanitiaan.ketupel.store-division', $event) }}" method="POST">
            @csrf
            
            <div class="p-6 md:p-8">
                <!-- Bagian 1: Data Divisi -->
                <div class="mb-10">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-8 h-8 rounded-xl bg-violet-100 text-violet-600 flex items-center justify-center text-sm font-black shadow-sm">1</div>
                        <h4 class="text-lg font-bold text-slate-900">Informasi Divisi</h4>
                    </div>
                    <div class="bg-slate-50/70 p-5 md:p-6 rounded-2xl border border-slate-100">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Divisi <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-violet-500 focus:border-violet-500 block p-3.5 transition-all shadow-sm" placeholder="Misal: Divisi Acara" required>
                        </div>
                    </div>
                </div>

                <!-- Bagian 2: Koordinator -->
                <div class="mb-10">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-8 h-8 rounded-xl bg-brand-100 text-brand-600 flex items-center justify-center text-sm font-black shadow-sm">2</div>
                        <h4 class="text-lg font-bold text-slate-900">Penunjukan Koordinator (CO)</h4>
                    </div>
                    <div class="bg-slate-50/70 p-5 md:p-6 rounded-2xl border border-slate-100">
                        <!-- Tipe Input CO -->
                        <div class="flex flex-wrap gap-6 mb-5">
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="radio" name="co_type" value="existing" x-model="div_co_type" class="text-brand-600 focus:ring-brand-500 w-4.5 h-4.5 border-slate-300">
                                <span class="text-sm font-bold text-slate-700 group-hover:text-brand-600 transition-colors">Pilih Akun Terdaftar</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="radio" name="co_type" value="new" x-model="div_co_type" class="text-brand-600 focus:ring-brand-500 w-4.5 h-4.5 border-slate-300">
                                <span class="text-sm font-bold text-slate-700 group-hover:text-brand-600 transition-colors">Buat Akun Baru</span>
                            </label>
                        </div>

                        <!-- Input CO Existing -->
                        <div x-show="div_co_type === 'existing'" x-transition class="w-full">
                            <select name="co_user_id" x-model="div_co_user_id" class="w-full bg-white border border-slate-200 text-slate-900 text-sm font-medium rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3.5 transition-all shadow-sm" :required="div_co_type === 'existing'">
                                <option value="">-- Pilih Anggota HIMASI --</option>
                                @foreach($allUsers as $u)
                                    @php
                                        $divName = $u->memberships->first() ? $u->memberships->first()->division->name : 'Anggota Biasa';
                                    @endphp
                                    <option value="{{ $u->id }}" :disabled="isOptionDisabled('{{ $u->id }}', null, true)">{{ $u->name }} ({{ $divName }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Input CO New -->
                        <div x-show="div_co_type === 'new'" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                            <div>
                                <input type="text" name="co_name" class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3.5 transition-all shadow-sm" placeholder="Nama Lengkap CO" :required="div_co_type === 'new'">
                            </div>
                            <div>
                                <input type="email" name="co_email" class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3.5 transition-all shadow-sm" placeholder="Email CO" :required="div_co_type === 'new'">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bagian 3: Anggota -->
                <div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm font-black shadow-sm">3</div>
                            <h4 class="text-lg font-bold text-slate-900">Anggota Divisi</h4>
                        </div>
                        <button type="button" @click="addAnggota()" class="text-sm font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-100 hover:bg-emerald-200 px-4 py-2 rounded-xl transition-colors flex items-center gap-2 shadow-sm">
                            <i class="ph-bold ph-plus"></i> Tambah Baris Anggota
                        </button>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(anggota, index) in div_anggota" :key="anggota.id">
                            <div class="flex flex-col md:flex-row gap-4 bg-slate-50/70 p-4 md:p-5 rounded-2xl border border-slate-100 items-start md:items-center relative transition-all hover:border-emerald-200">
                                
                                <!-- Tipe Input Anggota -->
                                <div class="shrink-0 w-full md:w-40">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 md:hidden">Tipe Input</label>
                                    <select x-model="anggota.type" :name="'anggota['+index+'][type]'" class="w-full bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl focus:ring-emerald-500 focus:border-emerald-500 p-3 transition-all shadow-sm">
                                        <option value="existing">Pilih Terdaftar</option>
                                        <option value="new">Akun Baru</option>
                                    </select>
                                </div>

                                <!-- Input Existing -->
                                <div x-show="anggota.type === 'existing'" class="flex-1 w-full">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 md:hidden">Pilih Anggota</label>
                                    <select :name="'anggota['+index+'][user_id]'" x-model="anggota.user_id" class="w-full bg-white border border-slate-200 text-slate-900 text-sm font-medium rounded-xl focus:ring-emerald-500 focus:border-emerald-500 p-3 transition-all shadow-sm" :required="anggota.type === 'existing'">
                                        <option value="">-- Pilih Anggota HIMASI --</option>
                                        @foreach($allUsers as $u)
                                            @php
                                                $divName = $u->memberships->first() ? $u->memberships->first()->division->name : 'Anggota Biasa';
                                            @endphp
                                            <option value="{{ $u->id }}" :disabled="isOptionDisabled('{{ $u->id }}', index, false)">{{ $u->name }} ({{ $divName }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Input New -->
                                <div x-show="anggota.type === 'new'" class="flex-1 w-full flex flex-col md:flex-row gap-4">
                                    <div class="flex-1">
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 md:hidden">Nama Lengkap</label>
                                        <input type="text" :name="'anggota['+index+'][name]'" x-model="anggota.name" class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 p-3 transition-all shadow-sm" placeholder="Nama Lengkap" :required="anggota.type === 'new'">
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 md:hidden">Email</label>
                                        <input type="email" :name="'anggota['+index+'][email]'" x-model="anggota.email" class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 p-3 transition-all shadow-sm" placeholder="Email" :required="anggota.type === 'new'">
                                    </div>
                                </div>

                                <!-- Tombol Hapus -->
                                <button type="button" @click="removeAnggota(index)" x-show="div_anggota.length > 1" class="absolute -right-3 -top-3 md:relative md:right-0 md:top-0 w-8 h-8 md:w-11 md:h-11 rounded-full md:rounded-xl bg-white md:bg-rose-50 border border-slate-200 md:border-transparent hover:bg-rose-100 hover:border-rose-200 text-rose-500 flex items-center justify-center transition-all shrink-0 shadow-md md:shadow-none" title="Hapus Baris">
                                    <i class="ph-bold ph-trash md:text-xl"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="px-6 py-5 md:px-8 border-t border-slate-100 bg-slate-50/50 flex flex-col-reverse sm:flex-row justify-end gap-3">
                <a href="{{ route('kepanitiaan.ketupel.manage-team', $event) }}" class="px-6 py-3 text-sm font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-100 rounded-xl transition-colors text-center shadow-sm">Batal</a>
                <button type="submit" class="px-6 py-3 text-sm font-bold text-white bg-violet-600 hover:bg-violet-700 rounded-xl transition-colors shadow-lg shadow-violet-500/30 flex items-center justify-center gap-2">
                    <i class="ph-bold ph-check-circle text-lg"></i> Simpan Divisi & Tim
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
