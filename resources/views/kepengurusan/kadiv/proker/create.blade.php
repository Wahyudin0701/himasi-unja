@extends('layouts.dashboard')

@section('title', 'Tambah Program Kerja')

@section('breadcrumbs')
    <span class="text-slate-700">Kepengurusan</span>
@endsection

@section('content')

{{-- ===== HEADER ===== --}}
<div class="mb-8">
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">
                Tambah Program Kerja
            </h1>
            <p class="text-sm text-slate-500 mt-1.5 font-medium">Buat program kerja atau agenda baru untuk divisi Anda.</p>
        </div>
        <a href="{{ route('kepengurusan.kadiv.proker.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-brand-600 transition-colors">
            <i class="ph-bold ph-arrow-left"></i>
            Kembali
        </a>
    </div>
</div>

{{-- ===== FORM ADD PROKER ===== --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden w-full">
    <form action="{{ route('kepengurusan.kadiv.proker.store') }}" method="POST" class="p-6 sm:p-8">
        @csrf

        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nama Program Kerja --}}
                <div>
                    <label for="name" class="block text-sm font-bold text-slate-700 mb-1.5">Nama Program Kerja <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors" placeholder="Contoh: Malam Keakraban HIMASI" value="{{ old('name') }}">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jenis --}}
                <div>
                    <label for="type" class="block text-sm font-bold text-slate-700 mb-1.5">Jenis <span class="text-red-500">*</span></label>
                    <select name="type" id="type" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors">
                        <option value="non_event" {{ old('type', 'non_event') == 'non_event' ? 'selected' : '' }}>Non-Event</option>
                        <option value="event" {{ old('type') == 'event' ? 'selected' : '' }}>Event</option>
                    </select>
                    @error('type')
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
                                <option value="{{ $u->id }}" {{ old('ketupel_id') == $u->id ? 'selected' : '' }}>{{ $u->name }} - {{ $u->nim }} ({{ $divName }})</option>
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
                                <option value="{{ $u->id }}" {{ old('waketupel_id') == $u->id ? 'selected' : '' }}>{{ $u->name }} - {{ $u->nim }} ({{ $divName }})</option>
                            @endforeach
                        </select>
                        @error('waketupel_id')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Dynamic Fields for Non-Event --}}
            <div id="nonevent-fields" class="space-y-6 hidden bg-slate-50 p-5 rounded-2xl border border-slate-200">
                <div>
                    <label for="pj_id" class="block text-sm font-bold text-slate-700 mb-1.5">Penanggung Jawab (PJ) <span class="text-red-500">*</span></label>
                    <select name="pj_id" id="pj_id" class="w-full bg-white border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors">
                        <option value="">-- Pilih Penanggung Jawab --</option>
                        @foreach($divisionMembers as $u)
                            <option value="{{ $u->id }}" {{ old('pj_id') == $u->id ? 'selected' : '' }}>{{ $u->name }} - {{ $u->nim }}</option>
                        @endforeach
                    </select>
                    @error('pj_id')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6" x-data="{ startDate: '{{ old('start_date') }}' }">
                {{-- Tanggal Mulai --}}
                <div>
                    <label for="start_date" class="block text-sm font-bold text-slate-700 mb-1.5">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" x-model="startDate" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors">
                    @error('start_date')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal Selesai --}}
                <div>
                    <label for="end_date" class="block text-sm font-bold text-slate-700 mb-1.5">Tanggal Selesai</label>
                    <input type="date" name="end_date" id="end_date" :min="startDate" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors" value="{{ old('end_date') }}">
                    @error('end_date')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="description" class="block text-sm font-bold text-slate-700 mb-1.5">Deskripsi Singkat</label>
                <textarea name="description" id="description" rows="3" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-colors" placeholder="Tuliskan tujuan atau deskripsi singkat program kerja ini...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>



        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('kepengurusan.kadiv.proker.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                Batal
            </a>
            <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white rounded-xl px-6 py-2.5 text-sm font-bold transition-colors shadow-sm shadow-brand-500/30">
                Simpan Program Kerja
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
        const nonEventFields = document.getElementById('nonevent-fields');

        function toggleFields() {
            if (typeSelect.value === 'event') {
                eventFields.classList.remove('hidden');
                nonEventFields.classList.add('hidden');
            } else {
                eventFields.classList.add('hidden');
                nonEventFields.classList.remove('hidden');
            }
        }

        typeSelect.addEventListener('change', toggleFields);
        toggleFields();
    });
</script>
@endpush
