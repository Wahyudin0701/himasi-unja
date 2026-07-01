@extends('layouts.dashboard')

@section('title', 'Tambah Divisi')

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
            { id: 1, type: 'existing', user_id: '', name: '', email_prefix: '', nim: '', angkatan: '', password: '' }
        ],
        addAnggota() {
            this.div_anggota.push({ id: Date.now(), type: 'existing', user_id: '', name: '', email_prefix: '', nim: '', angkatan: '', password: '' });
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
                        <h4 class="text-lg font-bold text-slate-900">Nama Divisi <span class="text-rose-500">*</span></h4>
                    </div>
                    <div>
                        <div>
                            <input type="text" name="name" class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-violet-500 focus:border-violet-500 block p-3.5 transition-all shadow-sm" placeholder="Misal: Divisi Acara" required>
                        </div>
                    </div>
                </div>

                <!-- Bagian 2: Koordinator -->
                <div class="mb-10">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-8 h-8 rounded-xl bg-brand-100 text-brand-600 flex items-center justify-center text-sm font-black shadow-sm">2</div>
                        <h4 class="text-lg font-bold text-slate-900">Penunjukan Koordinator (CO) <span class="text-rose-500">*</span></h4>
                    </div>
                    <div>
                        <!-- Input CO -->
                        <div class="w-full">
                            <input type="hidden" name="co_type" value="existing">
                            <select name="co_user_id" x-model="div_co_user_id" class="w-full bg-white border border-slate-200 text-slate-900 text-sm font-medium rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3.5 transition-all shadow-sm" required>
                                <option value="">-- Pilih Anggota HIMASI --</option>
                                @foreach($allUsers as $u)
                                    @php
                                        $divName = $u->memberships->first() ? $u->memberships->first()->division->name : 'Anggota Biasa';
                                    @endphp
                                    <option value="{{ $u->id }}" :disabled="isOptionDisabled('{{ $u->id }}', null, true)">{{ $u->name }} ({{ $divName }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Bagian 3: Anggota -->
                <div>
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm font-black shadow-sm">3</div>
                        <h4 class="text-lg font-bold text-slate-900">Anggota Divisi <span class="text-rose-500">*</span></h4>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(anggota, index) in div_anggota" :key="anggota.id">
                            <div class="flex flex-col gap-3 relative transition-all mb-6 pb-6 border-b border-slate-100 last:border-0 last:pb-0 last:mb-0">
                                
                                <!-- Tipe Input Anggota -->
                                <div class="flex flex-wrap gap-6 mb-2">
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input type="radio" :name="'anggota['+index+'][type]'" value="existing" x-model="anggota.type" class="text-brand-600 focus:ring-brand-500 w-4.5 h-4.5 border-slate-300">
                                        <span class="text-sm font-bold text-slate-700 group-hover:text-brand-600 transition-colors">Pilih Akun Terdaftar</span>
                                    </label>
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input type="radio" :name="'anggota['+index+'][type]'" value="new" x-model="anggota.type" class="text-brand-600 focus:ring-brand-500 w-4.5 h-4.5 border-slate-300">
                                        <span class="text-sm font-bold text-slate-700 group-hover:text-brand-600 transition-colors">Buat Akun Baru</span>
                                    </label>
                                </div>

                                <!-- Input Existing -->
                                <div x-show="anggota.type === 'existing'" class="w-full">
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
                                <div x-show="anggota.type === 'new'" class="w-full flex flex-col gap-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="md:col-span-2">
                                            <input type="text" :name="'anggota['+index+'][name]'" x-model="anggota.name" class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 p-3 transition-all shadow-sm" placeholder="Nama Lengkap *" :required="anggota.type === 'new'">
                                        </div>
                                        <div>
                                            <input type="text" :name="'anggota['+index+'][nim]'" x-model="anggota.nim" class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 p-3 transition-all shadow-sm" placeholder="NIM *" :required="anggota.type === 'new'">
                                        </div>
                                        <div>
                                            <input type="number" :name="'anggota['+index+'][angkatan]'" x-model="anggota.angkatan" class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 p-3 transition-all shadow-sm" placeholder="Angkatan (contoh: 2023) *" :required="anggota.type === 'new'">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Email Login <span class="text-rose-500">*</span></label>
                                            <div class="flex">
                                                <input type="text" :name="'anggota['+index+'][email_prefix]'" x-model="anggota.email_prefix" class="w-full bg-white border border-slate-200 border-r-0 text-slate-900 text-sm rounded-l-xl focus:ring-emerald-500 focus:border-emerald-500 p-3 transition-all shadow-sm" placeholder="contoh: budisantoso" :required="anggota.type === 'new'">
                                                <span class="inline-flex items-center px-3 text-sm font-medium text-slate-500 bg-slate-50 border border-l-0 border-slate-200 rounded-r-xl shrink-0">@himasi.unja.ac.id</span>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">Password Sementara <span class="text-rose-500">*</span></label>
                                            <input type="text" :name="'anggota['+index+'][password]'" x-model="anggota.password" class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 p-3 transition-all shadow-sm" placeholder="Masukkan password awal" :required="anggota.type === 'new'">
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol Hapus -->
                                <button type="button" @click="removeAnggota(index)" x-show="div_anggota.length > 1" class="absolute -right-3 -top-3 md:relative md:right-0 md:top-0 w-8 h-8 md:w-11 md:h-11 rounded-full md:rounded-xl bg-white md:bg-rose-50 border border-slate-200 md:border-transparent hover:bg-rose-100 hover:border-rose-200 text-rose-500 flex items-center justify-center transition-all shrink-0 shadow-md md:shadow-none" title="Hapus Baris">
                                    <i class="ph-bold ph-trash md:text-xl"></i>
                                </button>
                            </div>
                        </template>
                    </div>

                    <!-- Tombol Tambah Baris Anggota dipindah ke bawah -->
                    <div class="mt-4">
                        <button type="button" @click="addAnggota()" class="text-sm font-medium text-brand-600 hover:text-brand-700 transition-colors">
                            + Tambah Anggota
                        </button>
                    </div>
                </div>
            </div>

            <div class="px-6 py-5 md:px-8 border-t border-slate-100 bg-slate-50/50 flex flex-col-reverse sm:flex-row justify-end gap-3">
                <a href="{{ route('kepanitiaan.ketupel.manage-team', $event) }}" class="px-6 py-3 text-sm font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-100 rounded-xl transition-colors text-center shadow-sm">Batal</a>
                <button type="submit" class="px-6 py-3 text-sm font-bold text-white bg-brand-600 hover:bg-brand-700 rounded-xl transition-colors shadow-lg shadow-brand-500/30 flex items-center justify-center gap-2">
                    <i class="ph-bold ph-check-circle text-lg"></i> Simpan Divisi & Tim
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
