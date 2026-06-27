@extends('layouts.dashboard')

@section('title', 'Edit Event')
@section('breadcrumbs')
    <span class="text-slate-300">/</span>
    <a href="{{ route('events.index') }}" class="text-slate-500 hover:text-slate-800">Events</a>
    <span class="text-slate-300">/</span>
    <a href="{{ route('events.show', $event->id) }}" class="text-slate-500 hover:text-slate-800">{{ Str::limit($event->name, 20) }}</a>
    <span class="text-slate-300">/</span>
    <span class="text-slate-800">Edit</span>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-xl font-bold text-slate-900">Update Event</h2>
            <p class="text-slate-500 text-sm mt-1">Ubah informasi dasar tentang kegiatan ini.</p>
        </div>

        <form action="{{ route('events.update', $event->id) }}" method="POST" class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Field: Status Event -->
            <div>
                <label for="status" class="block text-sm font-semibold text-slate-700 mb-2">Status Event <span class="text-rose-500">*</span></label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="relative cursor-pointer">
                        <input type="radio" name="status" value="planning" class="peer sr-only" {{ old('status', $event->status) == 'planning' ? 'checked' : '' }}>
                        <div class="px-4 py-3 rounded-xl border border-slate-200 text-center text-sm font-medium text-slate-600 peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:text-amber-700 hover:bg-slate-50 transition-all">
                            Planning
                        </div>
                    </label>
                    <label class="relative cursor-pointer">
                        <input type="radio" name="status" value="ongoing" class="peer sr-only" {{ old('status', $event->status) == 'ongoing' ? 'checked' : '' }}>
                        <div class="px-4 py-3 rounded-xl border border-slate-200 text-center text-sm font-medium text-slate-600 peer-checked:border-brand-500 peer-checked:bg-brand-50 peer-checked:text-brand-700 hover:bg-slate-50 transition-all">
                            Ongoing
                        </div>
                    </label>
                    <label class="relative cursor-pointer">
                        <input type="radio" name="status" value="completed" class="peer sr-only" {{ old('status', $event->status) == 'completed' ? 'checked' : '' }}>
                        <div class="px-4 py-3 rounded-xl border border-slate-200 text-center text-sm font-medium text-slate-600 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 hover:bg-slate-50 transition-all">
                            Completed
                        </div>
                    </label>
                </div>
                @error('status') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Field: Nama Event -->
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nama Event <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $event->name) }}" required 
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none text-slate-700">
                @error('name') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Field: Deskripsi -->
            <div>
                <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Singkat <span class="text-rose-500">*</span></label>
                <textarea name="description" id="description" rows="3" required
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none text-slate-700 resize-none">{{ old('description', $event->description) }}</textarea>
                @error('description') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Field: Tanggal Mulai -->
                <div>
                    <label for="event_date" class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Mulai <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="date" name="event_date" id="event_date" value="{{ old('event_date', $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('Y-m-d') : '') }}" required
                               class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none text-slate-700">
                        <i class="ph ph-calendar-blank absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                    </div>
                    @error('event_date') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Field: Tanggal Selesai -->
                <div>
                    <label for="end_date" class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Selesai (Opsional)</label>
                    <div class="relative">
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $event->end_date ? \Carbon\Carbon::parse($event->end_date)->format('Y-m-d') : '') }}"
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
                    <input type="text" name="location" id="location" value="{{ old('location', $event->location) }}"
                           class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none text-slate-700">
                    <i class="ph ph-map-pin absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                </div>
                @error('location') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Field: Hubungkan Program Kerja -->
            <div>
                <label for="work_program_id" class="block text-sm font-semibold text-slate-700 mb-2">Terkait Program Kerja HIMA (Opsional)</label>
                <select name="work_program_id" id="work_program_id"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none text-slate-700 appearance-none">
                    <option value="">-- Tidak Berhubungan dengan Proker --</option>
                    @foreach($workPrograms as $wp)
                        <option value="{{ $wp->id }}" {{ old('work_program_id', $event->work_program_id) == $wp->id ? 'selected' : '' }}>
                            {{ $wp->name }} ({{ $wp->division->name ?? 'BPH' }})
                        </option>
                    @endforeach
                </select>
                @error('work_program_id') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('events.show', $event->id) }}" class="px-5 py-2.5 rounded-xl text-slate-600 font-medium hover:bg-slate-100 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-medium transition-colors shadow-sm shadow-brand-500/20">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
