@extends('layouts.dashboard')

@section('title', 'Edit Program Kerja')

@section('breadcrumbs')
    <span class="text-slate-700">Kepengurusan</span>
@endsection

@section('content')

{{-- ===== HEADER ===== --}}
<div class="mb-8">
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">
                Edit Program Kerja
            </h1>
            <p class="text-sm text-slate-500 mt-1.5 font-medium font-medium">Perbarui informasi program kerja divisi Anda.</p>
        </div>
        <a href="{{ route('kepengurusan.kadiv.proker.show', $proker->id) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-brand-600 transition-colors">
            <i class="ph-bold ph-arrow-left"></i>
            Kembali
        </a>
    </div>
</div>

{{-- ===== FORM EDIT PROKER ===== --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden w-full">
    <form action="{{ route('kepengurusan.kadiv.proker.update', $proker->id) }}" method="POST" class="p-6 sm:p-8">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nama Program Kerja --}}
                <div>
                    <label for="name" class="block text-sm font-bold text-slate-700 mb-1.5">Nama Program Kerja <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors" placeholder="Contoh: Malam Keakraban HIMASI" value="{{ old('name', $proker->name) }}">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jenis --}}
                <div>
                    <label for="type" class="block text-sm font-bold text-slate-700 mb-1.5">Jenis <span class="text-red-500">*</span></label>
                    <select id="type" disabled class="w-full bg-slate-100 border border-slate-200 text-slate-500 text-sm rounded-xl cursor-not-allowed block p-3 transition-colors">
                        <option value="">-- Pilih Jenis Proker --</option>
                        <option value="internal" {{ old('type', $proker->type) == 'internal' ? 'selected' : '' }}>Internal Divisi</option>
                        <option value="kolaborasi" {{ old('type', $proker->type) == 'kolaborasi' ? 'selected' : '' }}>Kolaborasi Lintas Divisi</option>
                        <option value="event" {{ old('type', $proker->type) == 'event' ? 'selected' : '' }}>Event Kepanitiaan</option>
                    </select>
                    <input type="hidden" name="type" value="{{ $proker->type }}">
                    <p class="text-[10px] text-slate-500 mt-1"><i class="ph-fill ph-info"></i> Jenis program kerja dikunci setelah dibuat untuk menjaga integritas data.</p>
                    @error('type')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Bidang --}}
                <div>
                    <label for="sub_division_id" class="block text-sm font-bold text-slate-700 mb-1.5">Bidang <span class="text-red-500">*</span></label>
                    <select name="sub_division_id" id="sub_division_id" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors">
                        <option value="">-- Pilih Bidang --</option>
                        @foreach($subDivisions as $sub)
                            <option value="{{ $sub->id }}" {{ old('sub_division_id', $proker->sub_division_id) == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                        @endforeach
                    </select>
                    @error('sub_division_id')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Rencana Anggaran --}}
                <div id="budget-field-container">
                    <label for="budget_plan" class="block text-sm font-bold text-slate-700 mb-1.5">Rencana Anggaran (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="budget_plan" id="budget_plan" min="0" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors" placeholder="Contoh: 1500000" value="{{ old('budget_plan', intval($proker->budget_plan)) }}">
                    <p class="text-[10px] text-slate-500 mt-1"><i class="ph-fill ph-info"></i> Event: Diisi terpisah melalui modul RAB.</p>
                    @error('budget_plan')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Dynamic Fields for Event --}}
            <div id="event-fields" class="space-y-6 hidden bg-brand-50 p-5 rounded-2xl border border-brand-100">
                <h3 class="text-sm font-black text-brand-700">Susunan Panitia Inti</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="ketupel_id" class="block text-sm font-bold text-slate-700 mb-1.5">Ketua Pelaksana <span class="text-red-500">*</span></label>
                        <select name="ketupel_id" id="ketupel_id" class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors">
                            <option value="">-- Pilih Ketua Pelaksana --</option>
                            @foreach($allEligibleMembers as $u)
                                @php
                                    $divName = $u->memberships->first() ? $u->memberships->first()->division->name : 'Anggota Biasa';
                                @endphp
                                <option value="{{ $u->id }}" {{ old('ketupel_id', $ketupelId) == $u->id ? 'selected' : '' }}>{{ $u->name }} - {{ $u->nim }} ({{ $divName }})</option>
                            @endforeach
                        </select>
                        @error('ketupel_id')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="waketupel_id" class="block text-sm font-bold text-slate-700 mb-1.5">Wakil Ketua Pelaksana <span class="text-red-500">*</span></label>
                        <select name="waketupel_id" id="waketupel_id" class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors">
                            <option value="">-- Pilih Wakil Ketua Pelaksana --</option>
                            @foreach($allEligibleMembers as $u)
                                @php
                                    $divName = $u->memberships->first() ? $u->memberships->first()->division->name : 'Anggota Biasa';
                                @endphp
                                <option value="{{ $u->id }}" {{ old('waketupel_id', $waketupelId) == $u->id ? 'selected' : '' }}>{{ $u->name }} - {{ $u->nim }} ({{ $divName }})</option>
                            @endforeach
                        </select>
                        @error('waketupel_id')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Dynamic Fields for Internal & Kolaborasi --}}
            <div id="internal-fields" class="space-y-6 hidden bg-slate-50 p-5 rounded-2xl border border-slate-200">
                <div>
                    <label for="pj_id" class="block text-sm font-bold text-slate-700 mb-1.5">Penanggung Jawab Utama (Dari Divisi Internal) <span class="text-red-500">*</span></label>
                    <select name="pj_id" id="pj_id" class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors">
                        <option value="">-- Pilih Penanggung Jawab --</option>
                        @foreach($divisionMembers as $u)
                            <option value="{{ $u->id }}" {{ old('pj_id', $pjId) == $u->id ? 'selected' : '' }}>{{ $u->name }} - {{ $u->nim }}</option>
                        @endforeach
                    </select>
                    @error('pj_id')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Dynamic Fields for Kolaborasi Only --}}
            <div id="kolaborasi-fields" class="space-y-6 hidden bg-purple-50 p-5 rounded-2xl border border-purple-200">
                <h3 class="text-sm font-black text-purple-700">Divisi Mitra & Anggota Kolaborator</h3>
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Pilih Divisi Mitra <span class="text-red-500">*</span></label>
                    <p class="text-xs text-slate-500 mb-3">Pilih divisi mana saja yang berkolaborasi dalam proker ini.</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($otherDivisions as $div)
                        <label class="flex items-center p-3 border border-slate-200 rounded-xl bg-white cursor-pointer hover:bg-slate-50 transition-colors">
                            <input type="checkbox" name="partner_divisions[]" value="{{ $div->id }}" 
                                class="division-checkbox w-4 h-4 text-purple-600 bg-slate-100 border-slate-300 rounded focus:ring-purple-500"
                                {{ collect(old('partner_divisions', $proker->partnerDivisions->pluck('id')->toArray()))->contains($div->id) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm font-medium text-slate-700">{{ $div->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('partner_divisions')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div id="collaborators-container" class="hidden mt-4">
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Pilih Anggota Divisi Mitra <span class="text-red-500">*</span></label>
                    <p class="text-xs text-slate-500 mb-3">Pilih anggota dari divisi mitra yang terlibat langsung.</p>
                    <div id="collaborators-list" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        <!-- Checkboxes will be rendered here via JS -->
                    </div>
                    @error('collaborators')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tujuan & Sasaran --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="objective" class="block text-sm font-bold text-slate-700 mb-1.5">Tujuan Program Kerja <span class="text-red-500">*</span></label>
                    <textarea name="objective" id="objective" rows="3" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors placeholder-slate-400" placeholder="Contoh: Meningkatkan kualitas akademik mahasiswa...">{{ old('objective', $proker->objective) }}</textarea>
                    @error('objective')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="target_audience" class="block text-sm font-bold text-slate-700 mb-1.5">Sasaran Program Kerja <span class="text-red-500">*</span></label>
                    <textarea name="target_audience" id="target_audience" rows="3" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors placeholder-slate-400" placeholder="Contoh: Seluruh Mahasiswa Sistem Informasi...">{{ old('target_audience', $proker->target_audience) }}</textarea>
                    @error('target_audience')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="description" class="block text-sm font-bold text-slate-700 mb-1.5">Deskripsi Singkat</label>
                <textarea name="description" id="description" rows="4" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors placeholder-slate-400" placeholder="Jelaskan secara singkat mengenai program kerja ini (opsional)...">{{ old('description', $proker->description) }}</textarea>
                @error('description')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6" x-data="{ startDate: '{{ old('start_date', $proker->start_date ? $proker->start_date->format('Y-m-d') : '') }}' }">
                {{-- Tanggal Mulai --}}
                <div>
                    <label for="start_date" class="block text-sm font-bold text-slate-700 mb-1.5">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" id="start_date" x-model="startDate" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors">
                    @error('start_date')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal Selesai --}}
                <div>
                    <label for="end_date" class="block text-sm font-bold text-slate-700 mb-1.5">Tanggal Selesai <span class="text-red-500">*</span></label>
                    <input type="date" name="end_date" id="end_date" :min="startDate" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors" value="{{ old('end_date', $proker->end_date ? $proker->end_date->format('Y-m-d') : '') }}">
                    @error('end_date')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('kepengurusan.kadiv.proker.show', $proker->id) }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                Batal
            </a>
            <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white rounded-xl px-6 py-2.5 text-sm font-bold transition-colors shadow-sm shadow-brand-500/30">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const eventFields = document.getElementById('event-fields');
        const internalFields = document.getElementById('internal-fields');
        const kolaborasiFields = document.getElementById('kolaborasi-fields');
        const budgetFieldContainer = document.getElementById('budget-field-container');
        const budgetPlanInput = document.getElementById('budget_plan');
        
        const divisionCheckboxes = document.querySelectorAll('.division-checkbox');
        const collaboratorsContainer = document.getElementById('collaborators-container');
        const collaboratorsList = document.getElementById('collaborators-list');
        
        // Pass existing collaborators from server
        const existingCollaborators = @json(old('collaborators', $proker->collaborators->pluck('id')->toArray()));

        function toggleFields() {
            const type = typeSelect.value;
            
            if (type === 'event') {
                eventFields.classList.remove('hidden');
                internalFields.classList.add('hidden');
                kolaborasiFields.classList.add('hidden');
                budgetFieldContainer.classList.add('hidden');
                budgetPlanInput.value = '';
                
                document.getElementById('ketupel_id').setAttribute('required', 'required');
                document.getElementById('waketupel_id').setAttribute('required', 'required');
                document.getElementById('pj_id').removeAttribute('required');
            } else if (type === 'internal') {
                eventFields.classList.add('hidden');
                internalFields.classList.remove('hidden');
                kolaborasiFields.classList.add('hidden');
                budgetFieldContainer.classList.remove('hidden');
                
                document.getElementById('ketupel_id').removeAttribute('required');
                document.getElementById('waketupel_id').removeAttribute('required');
                document.getElementById('pj_id').setAttribute('required', 'required');
            } else if (type === 'kolaborasi') {
                eventFields.classList.add('hidden');
                internalFields.classList.remove('hidden');
                kolaborasiFields.classList.remove('hidden');
                budgetFieldContainer.classList.remove('hidden');
                
                document.getElementById('ketupel_id').removeAttribute('required');
                document.getElementById('waketupel_id').removeAttribute('required');
                document.getElementById('pj_id').setAttribute('required', 'required');
                
                // Fetch members if not already fetched
                fetchDivisionMembers();
            }
        }

        async function fetchDivisionMembers() {
            const selectedDivisions = Array.from(divisionCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            if (selectedDivisions.length === 0) {
                collaboratorsContainer.classList.add('hidden');
                collaboratorsList.innerHTML = '';
                return;
            }

            collaboratorsContainer.classList.remove('hidden');
            collaboratorsList.innerHTML = '<div class="col-span-full text-center py-2 text-sm text-slate-500">Memuat anggota...</div>';

            try {
                let allMembers = [];
                for (const divId of selectedDivisions) {
                    const response = await fetch(`/kepengurusan/api/divisions/${divId}/members`);
                    const data = await response.json();
                    allMembers = allMembers.concat(data);
                }

                if (allMembers.length === 0) {
                    collaboratorsList.innerHTML = '<div class="col-span-full text-sm text-slate-500">Tidak ada anggota di divisi yang dipilih.</div>';
                    return;
                }

                collaboratorsList.innerHTML = allMembers.map(member => {
                    const isChecked = existingCollaborators.includes(member.id) ? 'checked' : '';
                    return `
                    <label class="flex items-center p-3 border border-slate-200 rounded-xl bg-white cursor-pointer hover:bg-slate-50 transition-colors">
                        <input type="checkbox" name="collaborators[]" value="${member.id}" ${isChecked} class="w-4 h-4 text-purple-600 bg-slate-100 border-slate-300 rounded focus:ring-purple-500">
                        <span class="ml-2 text-sm font-medium text-slate-700">${member.name}</span>
                    </label>
                    `;
                }).join('');
            } catch (error) {
                console.error('Error fetching members:', error);
                collaboratorsList.innerHTML = '<div class="col-span-full text-sm text-red-500">Gagal memuat anggota.</div>';
            }
        }

        typeSelect.addEventListener('change', toggleFields);
        divisionCheckboxes.forEach(cb => cb.addEventListener('change', fetchDivisionMembers));
        
        toggleFields();
    });
</script>
@endpush
