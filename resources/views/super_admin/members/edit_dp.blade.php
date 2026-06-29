@extends('layouts.dashboard')

@section('title', 'Edit Anggota DP')

@section('breadcrumbs')
    <a href="{{ route('super_admin.members.index') }}" class="text-slate-500 hover:text-brand-600 transition-colors">Data Pengurus</a>
    <i class="ph-bold ph-caret-right text-slate-400 text-xs"></i>
    <span class="text-slate-700">Edit Dewan Pengawas</span>
@endsection

@section('content')

    <div class="mb-8">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">
                    Edit Dewan Pengawas
                </h1>
                <p class="text-sm text-slate-500 mt-1.5 font-medium">Perbarui data anggota dewan pengawas.</p>
            </div>
            <a href="{{ route('super_admin.members.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-brand-600 transition-colors">
                <i class="ph-bold ph-arrow-left"></i>
                Kembali
            </a>
        </div>
    </div>

    <form action="{{ route('super_admin.members.update', $member->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
            <div class="p-6 sm:p-8">
                
                <div class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        {{-- Nama Lengkap --}}
                        <div class="sm:col-span-2">
                            <label for="name" class="block text-sm font-bold text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors" placeholder="Contoh: Budi Santoso" value="{{ old('name', $member->user->name) }}">
                            @error('name')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- NIM --}}
                        <div>
                            <label for="nim" class="block text-sm font-bold text-slate-700 mb-1.5">NIM <span class="text-red-500">*</span></label>
                            <input type="text" name="nim" id="nim" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors" placeholder="Contoh: F1E121001" value="{{ old('nim', $member->user->nim) }}">
                            @error('nim')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tahun Angkatan --}}
                        <div>
                            <label for="angkatan" class="block text-sm font-bold text-slate-700 mb-1.5">Tahun Angkatan <span class="text-red-500">*</span></label>
                            <input type="text" name="angkatan" id="angkatan" required maxlength="4" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors" placeholder="Contoh: 2021" value="{{ old('angkatan', $member->user->angkatan) }}">
                            @error('angkatan')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jabatan --}}
                        <div class="sm:col-span-2">
                            <label for="position_name" class="block text-sm font-bold text-slate-700 mb-1.5">Jabatan <span class="text-red-500">*</span></label>
                            <select name="position_name" id="position_name" disabled class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors opacity-70 cursor-not-allowed">
                                <option value="">-- Pilih Jabatan --</option>
                                @foreach($dpPositions as $position)
                                    <option value="{{ $position }}" {{ old('position_name', $member->position_title) == $position ? 'selected' : '' }}>{{ $position }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="position_name" value="{{ $member->position_title }}">
                            <p class="mt-1.5 text-xs text-slate-500"><i class="ph-fill ph-lock-key mr-1"></i>Jabatan ini tidak dapat diubah.</p>
                            @error('position_name')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        </div>
                        
                        {{-- Foto Profil --}}
                        <div class="sm:col-span-2">
                            <label for="avatar" class="block text-sm font-bold text-slate-700 mb-1.5">Foto Profil <span class="text-slate-400 font-medium">(Opsional)</span></label>
                            
                            @if($member->user->avatar)
                                <div class="mb-3 flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-full overflow-hidden border border-slate-200 shrink-0">
                                        <img src="{{ asset('storage/' . $member->user->avatar) }}" alt="{{ $member->user->name }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex flex-col gap-1.5">
                                        <span class="text-xs text-slate-500">Foto profil saat ini. Unggah file baru untuk menggantinya.</span>
                                        <label class="inline-flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" name="remove_avatar" value="1" class="rounded border-slate-300 text-red-500 focus:ring-red-500">
                                            <span class="text-xs font-bold text-red-600 hover:text-red-700 transition-colors">Hapus foto saat ini</span>
                                        </label>
                                    </div>
                                </div>
                            @endif
                            
                            <input type="file" name="avatar" id="avatar" accept="image/png, image/jpeg, image/jpg, image/webp" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-2.5 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">
                            <p class="mt-1.5 text-xs text-slate-500">Format yang didukung: JPG, JPEG, PNG, WEBP. Maksimal 2MB.</p>
                            @error('avatar')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex items-start gap-3 bg-amber-50 border border-amber-100 rounded-xl p-4">
                        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600 shrink-0 mt-0.5">
                            <i class="ph-bold ph-warning text-base"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-amber-900 mb-1">Informasi Pembaruan Data</h4>
                            <p class="text-xs text-amber-700 font-medium leading-relaxed">
                                Ubah email login di bawah ini jika diperlukan. Kosongkan kolom password jika tidak ingin menggantinya.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6 pt-6 border-t border-slate-100">
                        {{-- Email Prefix --}}
                        <div>
                            <label for="email_prefix" class="block text-sm font-bold text-slate-700 mb-1.5">Email Login <span class="text-red-500">*</span></label>
                            @php
                                $emailPrefix = old('email_prefix', str_replace('@himasi.unja.ac.id', '', $member->user->email));
                            @endphp
                            <div class="flex items-center">
                                <input type="text" name="email_prefix" id="email_prefix" required class="w-full bg-slate-50 border border-slate-200 border-r-0 text-slate-900 text-sm rounded-l-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors" placeholder="budi.santoso" value="{{ $emailPrefix }}">
                                <span class="px-4 py-3 bg-slate-100 border border-slate-200 text-slate-500 text-sm rounded-r-xl border-l-0 font-medium select-none">
                                    @himasi.unja.ac.id
                                </span>
                            </div>
                            @error('email_prefix')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div x-data="{ show: false }">
                            <label for="password" class="block text-sm font-bold text-slate-700 mb-1.5">Password Baru <span class="text-slate-400 font-normal">(opsional)</span></label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password" id="password" minlength="6" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 pr-12 transition-colors" placeholder="Minimal 6 karakter">
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 focus:outline-none">
                                    <i class="ph text-xl" :class="show ? 'ph-eye-slash' : 'ph-eye'"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 border-t border-slate-100 p-5 sm:px-8 flex items-center justify-end gap-3">
                <a href="{{ route('super_admin.members.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                    Batal
                </a>
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white rounded-xl px-6 py-2.5 text-sm font-bold transition-colors shadow-sm shadow-brand-500/30 flex items-center gap-2">
                    <i class="ph-bold ph-floppy-disk text-lg"></i>
                    Simpan Perubahan
                </button>
            </div>
        </div>

    </form>

@endsection
