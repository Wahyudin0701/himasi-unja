@extends('layouts.dashboard')

@section('title', 'Tambah Pengurus')

@section('breadcrumbs')
    <span class="text-slate-700">Kepengurusan</span>
    <i class="ph-bold ph-caret-right text-slate-400 text-xs"></i>
    <a href="{{ route('super_admin.members.index') }}" class="text-slate-500 hover:text-brand-600 transition-colors">Pengurus</a>
@endsection

@section('content')

{{-- ===== HEADER ===== --}}
<div class="mb-8">
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">
                Tambah Pengurus
            </h1>
            <p class="text-sm text-slate-500 mt-1.5 font-medium">Input data pengurus baru dan buatkan akun sistem secara otomatis.</p>
        </div>
        <a href="{{ route('super_admin.members.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-brand-600 transition-colors">
            <i class="ph-bold ph-arrow-left"></i>
            Kembali
        </a>
    </div>
</div>

<div>
    {{-- ===== FORM ADD MEMBER ===== --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden w-full">
            <form action="{{ route('super_admin.members.store') }}" method="POST" class="p-6 sm:p-8" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6">
                    {{-- Nama --}}
                    <div>
                        <label for="name" class="block text-sm font-bold text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors" placeholder="Contoh: Budi Santoso" value="{{ old('name') }}">
                        @error('name')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        {{-- NIM --}}
                        <div>
                            <label for="nim" class="block text-sm font-bold text-slate-700 mb-1.5">NIM <span class="text-red-500">*</span></label>
                            <input type="text" name="nim" id="nim" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors" placeholder="Contoh: F1E121001" value="{{ old('nim') }}">
                            @error('nim')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Angkatan --}}
                        <div>
                            <label for="angkatan" class="block text-sm font-bold text-slate-700 mb-1.5">Tahun Angkatan <span class="text-red-500">*</span></label>
                            <input type="text" name="angkatan" id="angkatan" required maxlength="4" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors" placeholder="Contoh: 2021" value="{{ old('angkatan') }}">
                            @error('angkatan')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        {{-- Divisi --}}
                        <div>
                            <label for="division_id" class="block text-sm font-bold text-slate-700 mb-1.5">Divisi <span class="text-red-500">*</span></label>
                            @php
                                $isDivisionLocked = request()->has('division_id');
                            @endphp
                            <select {{ $isDivisionLocked ? 'disabled' : 'name=division_id' }} id="division_id" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors {{ $isDivisionLocked ? 'opacity-70 cursor-not-allowed' : '' }}">
                                <option value="">-- Pilih Divisi --</option>
                                @foreach($divisions as $divisi)
                                    <option value="{{ $divisi->id }}" {{ old('division_id', request('division_id')) == $divisi->id ? 'selected' : '' }}>{{ $divisi->name }}</option>
                                @endforeach
                            </select>
                            @if($isDivisionLocked)
                                <input type="hidden" name="division_id" value="{{ request('division_id') }}">
                            @endif
                            @error('division_id')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jabatan --}}
                        <div x-data="{ custom: false }">
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="position_name" class="block text-sm font-bold text-slate-700">Jabatan <span class="text-red-500">*</span></label>
                                <label class="inline-flex items-center gap-1.5 cursor-pointer text-xs font-bold text-brand-600 hover:text-brand-700 select-none">
                                    <input type="checkbox" x-model="custom" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500" @change="if(custom) { $nextTick(() => $refs.customInput.focus()) } else { document.getElementById('position_name').dispatchEvent(new Event('change')) }">
                                    <span>Rangkap / Custom</span>
                                </label>
                            </div>
                            
                            <select name="position_name" id="position_name" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors">
                                <option value="">-- Pilih Jabatan Utama --</option>
                            </select>
                            
                            <div x-show="custom" style="display: none;" class="mt-3">
                                <label for="position_name_2" class="block text-sm font-bold text-slate-700 mb-1.5">Jabatan Kedua (Rangkap) <span class="text-red-500">*</span></label>
                                <select name="position_name_2" id="position_name_2" :required="custom" :disabled="!custom" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors">
                                    <option value="">-- Pilih Jabatan Kedua --</option>
                                </select>
                            </div>
                            
                            @error('position_name')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Bidang (Sub-Division) di-hide sesuai permintaan, tapi kita pertahankan input hidden untuk menyimpan ID-nya --}}
                        <input type="hidden" name="sub_division_id" id="hidden_sub_division_id" value="{{ old('sub_division_id') }}">
                    </div>
                    
                    {{-- Foto Profil --}}
                    <div>
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

                    <hr class="border-slate-100 mt-8">

                    {{-- Email & Password --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-8">
                        {{-- Email --}}
                        <div>
                            <label for="email_prefix" class="block text-sm font-bold text-slate-700 mb-1.5">Email Login <span class="text-red-500">*</span></label>
                            @php
                                $emailPrefix = old('email_prefix');
                            @endphp
                            <div class="flex items-stretch bg-slate-50 border border-slate-200 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-brand-500 focus-within:border-brand-500 transition-all">
                                <input type="text" name="email_prefix" id="email_prefix" required class="flex-1 bg-transparent text-slate-900 text-sm px-3 py-3 outline-none min-w-0" placeholder="contoh: budisantoso" value="{{ $emailPrefix }}">
                                <span class="px-3 text-xs font-bold text-slate-400 bg-slate-100 border-l border-slate-200 py-3 shrink-0 flex items-center">@himasi.unja.ac.id</span>
                            </div>
                            @error('email_prefix')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div>
                            <label for="password" class="block text-sm font-bold text-slate-700 mb-1.5">Password Sementara <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="password" name="password" id="password" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 pr-10 transition-colors" placeholder="Masukkan password awal">
                                <button type="button" onclick="togglePassword('password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700">
                                    <i class="ph-bold ph-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('super_admin.members.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white rounded-xl px-6 py-2.5 text-sm font-bold transition-colors shadow-sm shadow-brand-500/30 flex items-center gap-2">
                        <i class="ph-bold ph-plus text-lg"></i>
                        Simpan Pengurus
                    </button>
                </div>
            </form>
    </div>
</div>

@push('scripts')
<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('ph-eye', 'ph-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('ph-eye-slash', 'ph-eye');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const divisions = @json($divisions);
    const divisionSelect = document.getElementById('division_id');
    const positionSelect = document.getElementById('position_name');
    
    if (divisionSelect && positionSelect) {
        function updatePositions() {
            const selectedDivId = divisionSelect.value;
            if (!selectedDivId) {
                positionSelect.innerHTML = '<option value="">-- Pilih Jabatan Utama --</option>';
                const positionSelect2 = document.getElementById('position_name_2');
                if (positionSelect2) positionSelect2.innerHTML = '<option value="">-- Pilih Jabatan Kedua --</option>';
                return;
            }
            
            const selectedDiv = divisions.find(d => d.id == selectedDivId);
            const hasSubDivisions = selectedDiv && selectedDiv.sub_divisions && selectedDiv.sub_divisions.length > 0;
            
            const filledPositions = [];
            if (selectedDiv && selectedDiv.members) {
                selectedDiv.members.forEach(m => {
                    const pos = m.org_position || m.orgPosition;
                    if (pos && pos.name) {
                        filledPositions.push(pos.name);
                    }
                });
            }
            
            document.getElementById('hidden_sub_division_id').value = '{{ old("sub_division_id") }}';
            positionSelect.innerHTML = '<option value="">-- Pilih Jabatan Utama --</option>';
            const positionSelect2 = document.getElementById('position_name_2');
            if (positionSelect2) positionSelect2.innerHTML = '<option value="">-- Pilih Jabatan Kedua --</option>';
            
            const oldPositionPrimary = '{!! old("position_name") !!}';
            const oldPositionSecondary = '{!! old("position_name_2") !!}';
            
            function addOption(name, value, subId = null) {
                const opt = new Option(name, value);
                if (subId) opt.setAttribute('data-sub-id', subId);
                if (oldPositionPrimary == value) opt.selected = true;
                positionSelect.add(opt);

                if (positionSelect2) {
                    const opt2 = new Option(name, value);
                    if (subId) opt2.setAttribute('data-sub-id', subId);
                    if (oldPositionSecondary == value) opt2.selected = true;
                    positionSelect2.add(opt2);
                }
            }
            
            if (selectedDiv.type === 'bph') {
                // bph specific positions, let's leave it as is if they don't have sub_divisions
            } else if (hasSubDivisions) {
                addOption('Sekretaris Divisi', 'Sekretaris Divisi');
                addOption('Bendahara Divisi', 'Bendahara Divisi');
                
                selectedDiv.sub_divisions.forEach(sub => {
                    let subName = sub.name;
                    if (subName.toLowerCase().startsWith('bidang ')) {
                        subName = subName.substring(7);
                    }
                    
                    addOption('Ketua Bidang ' + subName, 'Ketua Bidang ' + subName, sub.id);
                    addOption('Anggota Bidang ' + subName, 'Anggota Bidang ' + subName, sub.id);
                });
            } else {
                addOption('Sekretaris Divisi', 'Sekretaris Divisi');
                addOption('Bendahara Divisi', 'Bendahara Divisi');
                addOption('Anggota', 'Anggota');
            }
            
            let optionExists = Array.from(positionSelect.options).some(opt => opt.value === oldPositionPrimary);
            if (oldPositionPrimary && !optionExists) {
                addOption(oldPositionPrimary, oldPositionPrimary);
            }
            if (positionSelect2) {
                let option2Exists = Array.from(positionSelect2.options).some(opt => opt.value === oldPositionSecondary);
                if (oldPositionSecondary && !option2Exists) {
                    addOption(oldPositionSecondary, oldPositionSecondary);
                }
            }
            
            positionSelect.dispatchEvent(new Event('change'));
        }
        
        function updateHiddenSubDivision() {
            let subId = '';
            
            const positionSelect2 = document.getElementById('position_name_2');
            const selectedOpt2 = positionSelect2 && !positionSelect2.disabled ? positionSelect2.options[positionSelect2.selectedIndex] : null;
            
            if (selectedOpt2 && selectedOpt2.getAttribute('data-sub-id')) {
                subId = selectedOpt2.getAttribute('data-sub-id');
            } else {
                const selectedOpt = positionSelect.options[positionSelect.selectedIndex];
                if (selectedOpt && selectedOpt.getAttribute('data-sub-id')) {
                    subId = selectedOpt.getAttribute('data-sub-id');
                }
            }
            
            document.getElementById('hidden_sub_division_id').value = subId;
        }
        
        divisionSelect.addEventListener('change', updatePositions);
        positionSelect.addEventListener('change', updateHiddenSubDivision);
        
        const posSelect2 = document.getElementById('position_name_2');
        if (posSelect2) {
            posSelect2.addEventListener('change', updateHiddenSubDivision);
        }
        
        updatePositions();
    }
});

function clearAvatarPreview() {
    const avatarInput = document.getElementById('avatar');
    const avatarBase64Input = document.getElementById('avatar_base64');
    const previewContainer = document.getElementById('avatar_preview_container');
    
    avatarInput.value = '';
    avatarBase64Input.value = '';
    if (previewContainer) {
        previewContainer.style.display = 'none';
    }
}
</script>
@endpush
@endsection
