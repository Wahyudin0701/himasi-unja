@extends('layouts.dashboard')

@section('title', 'Tambah Anggota DP')

@section('breadcrumbs')
    <a href="{{ route('super_admin.members.index') }}" class="text-slate-500 hover:text-brand-600 transition-colors">Data Pengurus</a>
    <i class="ph-bold ph-caret-right text-slate-400 text-xs"></i>
    <span class="text-slate-700">Tambah Anggota DP</span>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarInput = document.getElementById('avatar');
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const previewContainer = document.getElementById('avatar_preview_container');
            if (previewContainer) {
                previewContainer.style.display = 'none';
            }
            
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    document.getElementById('avatar_base64').value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar_base64').value = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('avatar_base64').value = '';
            }
        });
    }
});

function clearAvatarPreview() {
    document.getElementById('avatar_base64').value = '';
    const previewContainer = document.getElementById('avatar_preview_container');
    if (previewContainer) {
        previewContainer.style.display = 'none';
    }
    document.getElementById('avatar').value = '';
}
</script>
@endpush

@section('content')

    <div class="mb-8">
        <div class="flex items-center gap-4">
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">
                Tambah Anggota Dewan Pengawas
            </h1>
        </div>
        <p class="text-sm text-slate-500 mt-2 font-medium">Input data anggota dewan pengawas (DP) baru. Akun sistem akan dibuat otomatis.</p>
    </div>

    <form action="{{ route('super_admin.members.store.dp') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
            <div class="p-6 sm:p-8">
                
                <div class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        {{-- Nama Lengkap --}}
                        <div class="sm:col-span-2">
                            <label for="name" class="block text-sm font-bold text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors" placeholder="Contoh: Budi Santoso" value="{{ old('name') }}">
                            @error('name')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- NIM --}}
                        <div>
                            <label for="nim" class="block text-sm font-bold text-slate-700 mb-1.5">NIM <span class="text-red-500">*</span></label>
                            <input type="text" name="nim" id="nim" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors" placeholder="Contoh: F1E121001" value="{{ old('nim') }}">
                            @error('nim')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tahun Angkatan --}}
                        <div>
                            <label for="angkatan" class="block text-sm font-bold text-slate-700 mb-1.5">Tahun Angkatan <span class="text-red-500">*</span></label>
                            <input type="text" name="angkatan" id="angkatan" required maxlength="4" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors" placeholder="Contoh: 2021" value="{{ old('angkatan') }}">
                            @error('angkatan')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jabatan --}}
                        <div class="sm:col-span-2">
                            <label for="position_name" class="block text-sm font-bold text-slate-700 mb-1.5">Jabatan <span class="text-red-500">*</span></label>
                            <select name="position_name" id="position_name" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors">
                                <option value="">-- Pilih Jabatan --</option>
                                @foreach($dpPositions as $position)
                                    @php
                                        $isOccupied = in_array($position, $occupiedPositions ?? []);
                                    @endphp
                                    <option value="{{ $position }}" 
                                        {{ old('position_name') == $position ? 'selected' : '' }}
                                        {{ $isOccupied ? 'disabled' : '' }}>
                                        {{ $position }} {{ $isOccupied ? '(Sudah Terisi)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('position_name')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        </div>
                        
                        {{-- Foto Profil --}}
                        <div class="sm:col-span-2">
                            <label for="avatar" class="block text-sm font-bold text-slate-700 mb-1.5">Foto Profil <span class="text-slate-400 font-medium">(Opsional)</span></label>
                            <input type="hidden" name="avatar_base64" id="avatar_base64" value="{{ old('avatar_base64') }}">
                            <input type="file" name="avatar" id="avatar" accept="image/png, image/jpeg, image/jpg, image/webp" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-2.5 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">
                            <p class="mt-1.5 text-xs text-slate-500">Format yang didukung: JPG, JPEG, PNG, WEBP. Maksimal 2MB.</p>
                            
                            @if(old('avatar_base64'))
                                <div id="avatar_preview_container" class="mt-3 flex items-center gap-3 p-3 bg-brand-50 border border-brand-100 rounded-xl">
                                    <img src="{{ old('avatar_base64') }}" class="w-10 h-10 rounded-lg object-cover border border-brand-200">
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold text-brand-700">Foto sebelumnya tersimpan sementara.</p>
                                        <p class="text-[10px] text-brand-600">Pilih file baru jika ingin menggantinya.</p>
                                    </div>
                                    <button type="button" onclick="clearAvatarPreview()" class="text-xs font-bold text-red-500 hover:text-red-600 px-2">Hapus</button>
                                </div>
                            @endif
                            @error('avatar')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Informasi Akun --}}
                    <div class="mt-8 bg-emerald-50 border border-emerald-100 rounded-xl p-5 flex items-start gap-3">
                        <i class="ph-fill ph-info text-emerald-500 text-xl shrink-0 mt-0.5"></i>
                        <div>
                            <h4 class="text-sm font-bold text-emerald-800 mb-1">Informasi Pembuatan Akun</h4>
                            <p class="text-xs text-emerald-600 font-medium leading-relaxed">
                                Format email otomatis ditambahkan domain <strong>@himasi.unja.ac.id</strong>. Password sesuai yang Anda isi di bawah ini.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6 pt-6 border-t border-slate-100">
                        {{-- Email Prefix --}}
                        <div>
                            <label for="email_prefix" class="block text-sm font-bold text-slate-700 mb-1.5">Email Login <span class="text-red-500">*</span></label>
                            <div class="flex items-center">
                                <input type="text" name="email_prefix" id="email_prefix" required class="w-full bg-slate-50 border border-slate-200 border-r-0 text-slate-900 text-sm rounded-l-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors" placeholder="budi.santoso" value="{{ old('email_prefix') }}">
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
                            <label for="password" class="block text-sm font-bold text-slate-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password" id="password" required minlength="6" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 pr-12 transition-colors" placeholder="Minimal 6 karakter">
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
                    Simpan Anggota DP
                </button>
            </div>
        </div>

    </form>

@endsection
