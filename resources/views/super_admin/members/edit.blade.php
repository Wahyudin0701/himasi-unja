@extends('layouts.dashboard')

@section('title', 'Edit Pengurus')

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
                Edit Pengurus
            </h1>
            <p class="text-sm text-slate-500 mt-1.5 font-medium">Perbarui data pengurus dan jabatannya.</p>
        </div>
        <a href="{{ route('super_admin.members.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-brand-600 transition-colors">
            <i class="ph-bold ph-arrow-left"></i>
            Kembali
        </a>
    </div>
</div>

<div>
    {{-- ===== FORM EDIT MEMBER ===== --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden w-full">
            <form action="{{ route('super_admin.members.update', $member->id) }}" method="POST" class="p-6 sm:p-8" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    {{-- Nama --}}
                    <div>
                        <label for="name" class="block text-sm font-bold text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors" placeholder="Contoh: Budi Santoso" value="{{ old('name', $member->user->name) }}">
                        @error('name')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        {{-- NIM --}}
                        <div>
                            <label for="nim" class="block text-sm font-bold text-slate-700 mb-1.5">NIM <span class="text-red-500">*</span></label>
                            <input type="text" name="nim" id="nim" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors" placeholder="Contoh: F1E121001" value="{{ old('nim', $member->user->nim) }}">
                            @error('nim')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Angkatan --}}
                        <div>
                            <label for="angkatan" class="block text-sm font-bold text-slate-700 mb-1.5">Tahun Angkatan <span class="text-red-500">*</span></label>
                            <input type="text" name="angkatan" id="angkatan" required maxlength="4" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors" placeholder="Contoh: 2021" value="{{ old('angkatan', $member->user->angkatan) }}">
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
                                $lowerPos = strtolower($member->position_title ?? ($member->orgPosition->name ?? ''));
                                $isPositionLocked = str_starts_with($lowerPos, 'ketua divisi') || str_starts_with($lowerPos, 'wakil ketua divisi');
                            @endphp
                            <select name="division_id" id="division_id" disabled class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors opacity-70 cursor-not-allowed">
                                <option value="">-- Pilih Divisi --</option>
                                @foreach($divisions as $divisi)
                                    <option value="{{ $divisi->id }}" {{ old('division_id', $member->division_id) == $divisi->id ? 'selected' : '' }}>{{ $divisi->name }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="division_id" value="{{ $member->division_id }}">
                            <p class="mt-1.5 text-xs text-slate-500"><i class="ph-fill ph-lock-key mr-1"></i>Divisi ini tidak dapat diubah.</p>
                            @error('division_id')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jabatan --}}
                        @php
                            $posParts = explode(' & ', $member->position_title ?? ($member->orgPosition->name ?? ''));
                            $isCustom = count($posParts) > 1 ? 'true' : 'false';
                        @endphp
                        <div x-data="{ custom: {{ old('position_name_2') ? 'true' : $isCustom }} }">
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="position_name" class="block text-sm font-bold text-slate-700">Jabatan <span class="text-red-500">*</span></label>
                                <label class="inline-flex items-center gap-1.5 cursor-pointer text-xs font-bold text-brand-600 hover:text-brand-700 select-none">
                                    <input type="checkbox" x-model="custom" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500" @change="if(custom) { $nextTick(() => $refs.customInput.focus()) } else { document.getElementById('position_name').dispatchEvent(new Event('change')) }">
                                    <span>Rangkap / Custom</span>
                                </label>
                            </div>
                            
                            <select name="position_name" id="position_name" {{ $isPositionLocked ? 'disabled' : 'required' }} class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors {{ $isPositionLocked ? 'opacity-70 cursor-not-allowed' : '' }}">
                                <option value="">-- Pilih Jabatan Utama --</option>
                                {{-- Options populated by JS --}}
                            </select>
                            
                            @if($isPositionLocked)
                                <input type="hidden" name="position_name" value="{{ explode(' & ', $member->position_title ?? ($member->orgPosition->name ?? ''))[0] }}">
                                <p class="mt-1.5 text-xs text-slate-500"><i class="ph-fill ph-lock-key mr-1"></i>Jabatan utama ini tidak dapat diubah.</p>
                            @endif
                            
                            <div x-show="custom" style="display: none;" class="mt-3">
                                <label for="position_name_2" class="block text-sm font-bold text-slate-700 mb-1.5">Jabatan Kedua (Rangkap) <span class="text-red-500">*</span></label>
                                <select name="position_name_2" id="position_name_2" :required="custom" :disabled="!custom" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors">
                                    <option value="">-- Pilih Jabatan Kedua --</option>
                                </select>
                            </div>
                            <input type="hidden" name="sub_division_id" id="hidden_sub_division_id" value="{{ old('sub_division_id', $member->sub_division_id) }}">
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

                    <hr class="border-slate-100 mt-8">

                    {{-- Info Akun --}}
                    <div class="mt-8 flex items-start gap-3 bg-amber-50 border border-amber-100 rounded-xl p-4">
                        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600 shrink-0 mt-0.5">
                            <i class="ph-bold ph-warning text-base"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-amber-900 mb-1">Informasi Pembaruan Data</p>
                            <p class="text-xs text-amber-700 leading-relaxed">Ubah email login di bawah ini jika diperlukan. Kosongkan kolom password jika tidak ingin menggantinya.</p>
                        </div>
                    </div>

                    {{-- Email & Password --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-8">
                        {{-- Email --}}
                        <div>
                            <label for="email_prefix" class="block text-sm font-bold text-slate-700 mb-1.5">Email Login <span class="text-red-500">*</span></label>
                            @php
                                $emailPrefix = old('email_prefix', str_replace('@himasi.unja.ac.id', '', $member->user->email));
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
                            <label for="password" class="block text-sm font-bold text-slate-700 mb-1.5">Password Baru <span class="text-slate-400 font-normal">(opsional)</span></label>
                            <div class="relative">
                                <input type="password" name="password" id="password" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 pr-10 transition-colors" placeholder="Kosongkan jika tidak diubah">
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
                        <i class="ph-bold ph-floppy-disk text-lg"></i>
                        Simpan Perubahan
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
    const currentMemberId = {{ $member->id }};
    const divisionSelect = document.getElementById('division_id');
    const positionSelect = document.getElementById('position_name');
    
    if (divisionSelect && positionSelect) {
        function updatePositions() {
            const selectedDivId = divisionSelect.value;
            if (!selectedDivId) return;
            
            const selectedDiv = divisions.find(d => d.id == selectedDivId);
            const hasSubDivisions = selectedDiv && selectedDiv.sub_divisions && selectedDiv.sub_divisions.length > 0;
            
            const filledPositions = [];
            if (selectedDiv && selectedDiv.members) {
                selectedDiv.members.forEach(m => {
                    if (m.id === currentMemberId) return;
                    
                    const pos = m.org_position || m.orgPosition;
                    if (pos && pos.name) {
                        filledPositions.push(pos.name);
                    }
                });
            }
            
            document.getElementById('hidden_sub_division_id').value = '{{ old("sub_division_id", $member->sub_division_id ?? "") }}';
            positionSelect.innerHTML = '<option value="">-- Pilih Jabatan Utama --</option>';
            const positionSelect2 = document.getElementById('position_name_2');
            if (positionSelect2) positionSelect2.innerHTML = '<option value="">-- Pilih Jabatan Kedua --</option>';
            
            const oldPositionPrimary = '{!! old("position_name", explode(" & ", $member->position_title ?? ($member->orgPosition->name ?? ""))[0] ?? "") !!}';
            const oldPositionSecondary = '{!! old("position_name_2", explode(" & ", $member->position_title ?? ($member->orgPosition->name ?? ""))[1] ?? "") !!}';
            
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
                if (oldPositionPrimary) {
                    addOption(oldPositionPrimary, oldPositionPrimary);
                }
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
            
            // Fallback: If the user already has a specific position (like Ketua Divisi or Wakil Ketua Divisi)
            // that is not in the standard generated list above, we inject it so the dropdown is not empty.
            let optionExists = Array.from(positionSelect.options).some(opt => opt.value === oldPositionPrimary);
            if (oldPositionPrimary && !optionExists) {
                addOption(oldPositionPrimary, oldPositionPrimary);
            }
            if (positionSelect2) {
                let option2Exists = Array.from(positionSelect2.options).some(opt => opt.value === oldPositionSecondary);
                if (oldPositionSecondary && !option2Exists) {
                    // Temporarily set oldPositionPrimary to secondary so addOption handles selection properly for select 2.
                    const temp = oldPositionPrimary;
                    addOption(oldPositionSecondary, oldPositionSecondary);
                }
            }
            
            positionSelect.dispatchEvent(new Event('change'));
        }
        
        function updateHiddenSubDivision() {
            let subId = '';
            
            // Check secondary position first if it's active and has a sub-division
            const positionSelect2 = document.getElementById('position_name_2');
            const selectedOpt2 = positionSelect2 && !positionSelect2.disabled ? positionSelect2.options[positionSelect2.selectedIndex] : null;
            
            if (selectedOpt2 && selectedOpt2.getAttribute('data-sub-id')) {
                subId = selectedOpt2.getAttribute('data-sub-id');
            } else {
                // Fallback to primary position
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
        
        // initial run
        updatePositions();
    }
});
</script>
@endpush

@endsection
