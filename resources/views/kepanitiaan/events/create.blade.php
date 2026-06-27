@extends('layouts.dashboard')

@section('title', 'Buat Event Baru')
@section('breadcrumbs')
    <span class="text-slate-300">/</span>
    <a href="{{ route('events.index') }}" class="text-slate-500 hover:text-slate-800">Events</a>
    <span class="text-slate-300">/</span>
    <span class="text-slate-800">Buat Baru</span>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-xl font-bold text-slate-900">Informasi Event</h2>
            <p class="text-slate-500 text-sm mt-1">Lengkapi form di bawah ini untuk merencanakan event baru.</p>
        </div>

        <form action="{{ route('events.store') }}" method="POST" class="p-6 sm:p-8 space-y-6">
            @csrf
            
            <input type="hidden" name="period_id" value="{{ $activePeriod?->id }}">

            @if(!$activePeriod)
                <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 mb-6 flex gap-3">
                    <i class="ph-fill ph-warning-circle text-xl text-amber-500"></i>
                    <div>
                        <p class="font-bold">Peringatan</p>
                        <p class="text-sm mt-1">Belum ada periode kepengurusan yang aktif. Anda harus mengaktifkan periode kepengurusan di menu Kepengurusan terlebih dahulu.</p>
                    </div>
                </div>
            @endif

            <!-- Field: Nama Event -->
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nama Event <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none text-slate-700 placeholder:text-slate-400"
                       placeholder="Misal: Latihan Kepemimpinan Manajemen Mahasiswa (LKMM)">
                @error('name') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Field: Deskripsi -->
            <div>
                <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Singkat <span class="text-rose-500">*</span></label>
                <textarea name="description" id="description" rows="3" required
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none text-slate-700 placeholder:text-slate-400 resize-none"
                          placeholder="Jelaskan secara singkat mengenai event ini...">{{ old('description') }}</textarea>
                @error('description') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Field: Tanggal Mulai -->
                <div>
                    <label for="event_date" class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Mulai <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="date" name="event_date" id="event_date" value="{{ old('event_date') }}" required
                               class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none text-slate-700">
                        <i class="ph ph-calendar-blank absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                    </div>
                    @error('event_date') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Field: Tanggal Selesai -->
                <div>
                    <label for="end_date" class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Selesai (Opsional)</label>
                    <div class="relative">
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                               class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none text-slate-700">
                        <i class="ph ph-calendar-blank absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                    </div>
                    @error('end_date') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Field: Lokasi -->
            <div>
                <label for="location" class="block text-sm font-semibold text-slate-700 mb-2">Lokasi (Opsional)</label>
                <div class="relative">
                    <input type="text" name="location" id="location" value="{{ old('location') }}"
                           class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none text-slate-700 placeholder:text-slate-400"
                           placeholder="Misal: Aula Rektorat Lantai 3">
                    <i class="ph ph-map-pin absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                </div>
                @error('location') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Field: Hubungkan Program Kerja -->
            <div>
                <label for="work_program_id" class="block text-sm font-semibold text-slate-700 mb-2">Terkait Program Kerja HIMA (Opsional)</label>
                <select name="work_program_id" id="work_program_id"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none text-slate-700 appearance-none">
                    <option value="">-- Pilih Program Kerja (Jika Ada) --</option>
                    @foreach($workPrograms as $wp)
                        <option value="{{ $wp->id }}" {{ old('work_program_id') == $wp->id ? 'selected' : '' }}>
                            {{ $wp->name }} ({{ $wp->division->name ?? 'BPH' }})
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-500 mt-2"><i class="ph ph-info"></i> Jika event ini adalah turunan dari Program Kerja Divisi, silakan hubungkan di sini agar mudah dilaporkan ke BPH.</p>
                @error('work_program_id') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('events.index') }}" class="px-5 py-2.5 rounded-xl text-slate-600 font-medium hover:bg-slate-100 transition-colors">Batal</a>
                <button type="submit" {{ !$activePeriod ? 'disabled' : '' }} class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-medium transition-colors shadow-sm shadow-brand-500/20 disabled:opacity-50 disabled:cursor-not-allowed">
                    Buat Event & Kepanitiaan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
