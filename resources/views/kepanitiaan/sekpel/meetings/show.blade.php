@extends('layouts.dashboard')

@section('title', 'Detail Rapat Kepanitiaan')

@php
    $routeBase = request()->routeIs('kepanitiaan.sekpel.*') ? 'kepanitiaan.sekpel.events.meetings' : 'kepanitiaan.ketupel.events.meetings';
@endphp

@section('breadcrumbs')
    <a href="{{ route($routeBase . '.index', $event->id) }}" class="text-slate-400 hover:text-slate-600">Manajemen Rapat</a>
    <span class="text-slate-400 mx-2">/</span>
    <span class="text-slate-700">Detail</span>
@endsection

@section('content')
<div class="mb-8">
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ $meeting->title }}</h1>
            <div class="flex items-center gap-4 mt-2 text-sm font-bold text-slate-500">
                <span class="inline-flex items-center gap-1.5"><i class="ph-bold ph-calendar text-brand-600"></i> {{ $meeting->date->translatedFormat('d M Y') }}</span>
                <span class="inline-flex items-center gap-1.5"><i class="ph-bold ph-clock text-amber-600"></i> {{ \Carbon\Carbon::parse($meeting->time)->format('H:i') }}</span>
                <span class="inline-flex items-center gap-1.5"><i class="ph-bold ph-map-pin text-teal-600"></i> {{ $meeting->location ?: '-' }}</span>
            </div>
        </div>
        <button onclick="openModal('modal-edit-meeting')" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-xl text-sm font-bold transition-colors">
            Edit Detail
        </button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Kiri: Notulensi --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Notulensi Rapat</h2>
                    <p class="text-xs text-slate-500 mt-1">Catatan dan kesimpulan hasil rapat kepanitiaan</p>
                </div>
                <button onclick="openModal('modal-edit-minutes')" class="text-brand-600 hover:text-brand-700 text-sm font-bold">
                    Edit Notulensi
                </button>
            </div>
            <div class="p-6 flex-1">
                @if($meeting->description)
                    <div class="mb-6 pb-6 border-b border-slate-100">
                        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Agenda</h4>
                        <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ $meeting->description }}</p>
                    </div>
                @endif
                
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Isi Notulensi</h4>
                @if($meeting->minutes)
                    <div class="prose prose-sm prose-slate max-w-none whitespace-pre-wrap">{{ $meeting->minutes }}</div>
                @else
                    <p class="text-sm text-slate-400 italic">Notulensi belum ditulis.</p>
                @endif

                @if($meeting->minutes_file)
                    <div class="mt-8 pt-6 border-t border-slate-100">
                        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Lampiran File</h4>
                        <a href="{{ Storage::url($meeting->minutes_file) }}" target="_blank" class="inline-flex items-center gap-3 bg-brand-50 hover:bg-brand-100 text-brand-700 px-4 py-2 rounded-xl text-sm font-bold transition-colors">
                            <i class="ph-fill ph-file-text text-xl"></i>
                            Buka File Notulensi
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Kanan: Daftar Hadir --}}
    <div class="space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" x-data="{ activeDivision: '{{ $groupedAttendances->keys()->first() ? Str::slug($groupedAttendances->keys()->first()) : '' }}' }">
            <div class="p-6 border-b border-slate-100">
                <h2 class="text-lg font-bold text-slate-900">Daftar Hadir</h2>
                <p class="text-xs text-slate-500 mt-1">Rekapitulasi kehadiran panitia</p>
                <div class="mt-4 mb-4">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Pilih Divisi yang Ditampilkan</label>
                    <select x-model="activeDivision" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-700">
                        @foreach($groupedAttendances as $divisionName => $attendances)
                            <option value="{{ Str::slug($divisionName) }}">{{ $divisionName }}</option>
                        @endforeach
                    </select>
                </div>

                @foreach($groupedAttendances as $divisionName => $attendances)
                    <div x-show="activeDivision === '{{ Str::slug($divisionName) }}'" x-cloak class="grid grid-cols-2 gap-4">
                        <div class="bg-green-50/70 border border-green-100 rounded-2xl p-4 text-center flex flex-col items-center justify-center">
                            <p class="text-[10px] font-black text-green-600 uppercase tracking-widest mb-1">Hadir</p>
                            <p class="text-2xl font-black text-green-700" id="div-hadir-{{ Str::slug($divisionName) }}">{{ $attendances->where('status', 'hadir')->count() }}</p>
                        </div>
                        <div class="bg-red-50/70 border border-red-100 rounded-2xl p-4 text-center flex flex-col items-center justify-center">
                            <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mb-1">Alpa</p>
                            <p class="text-2xl font-black text-red-700" id="div-tidak-{{ Str::slug($divisionName) }}">{{ $attendances->where('status', '!=', 'hadir')->count() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <form id="attendance-form" action="{{ route($routeBase . '.attendance', ['event' => $event->id, 'meeting' => $meeting->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="max-h-[500px] overflow-y-auto p-2 space-y-4">
                    @foreach($groupedAttendances as $divisionName => $attendances)
                        <div x-show="activeDivision === '{{ Str::slug($divisionName) }}'" x-cloak data-division="{{ Str::slug($divisionName) }}" class="division-block">
                            <div class="sticky top-0 bg-slate-100 z-10 px-3 py-1.5 rounded-lg mb-2 flex items-center justify-between border border-slate-200">
                                <span class="text-[11px] font-black uppercase tracking-widest text-slate-600">{{ $divisionName }}</span>
                                <span class="text-[11px] font-bold text-slate-500">{{ $attendances->count() }} Anggota</span>
                            </div>
                            <div class="divide-y divide-slate-50 border border-slate-100 rounded-xl overflow-hidden">
                                @foreach($attendances as $attendance)
                                    <div class="p-2.5 flex items-center justify-between hover:bg-slate-50 transition-colors bg-white">
                                        <div class="flex-1 min-w-0 pr-4 flex items-center gap-3">
                                            @if($attendance->user->avatar)
                                                <img src="{{ asset('storage/' . $attendance->user->avatar) }}" alt="{{ $attendance->user->name }}" class="w-7 h-7 rounded-full object-cover shrink-0 border border-slate-200 shadow-sm">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($attendance->user->name) }}&background=f1f5f9&color=64748b&bold=true&size=100" alt="{{ $attendance->user->name }}" class="w-7 h-7 rounded-full object-cover shrink-0 border border-slate-200 shadow-sm">
                                            @endif
                                            <p class="text-xs font-bold text-slate-900 truncate">{{ $attendance->user->name }}</p>
                                        </div>
                                        <div class="flex items-center gap-4 shrink-0">
                                            <label class="inline-flex items-center gap-1.5 cursor-pointer group">
                                                <input type="radio" name="attendances[{{ $attendance->id }}]" value="hadir" class="peer w-3.5 h-3.5 accent-green-600 border-slate-300 text-green-600 focus:ring-green-500" {{ $attendance->status == 'hadir' ? 'checked' : '' }}>
                                                <span class="text-[11px] font-bold text-slate-700 group-hover:text-green-600 transition-colors">Hadir</span>
                                            </label>
                                            <label class="inline-flex items-center gap-1.5 cursor-pointer group">
                                                <input type="radio" name="attendances[{{ $attendance->id }}]" value="alpa" class="peer w-3.5 h-3.5 accent-red-600 border-slate-300 text-red-600 focus:ring-red-500" {{ $attendance->status != 'hadir' ? 'checked' : '' }}>
                                                <span class="text-[11px] font-bold text-slate-700 group-hover:text-red-600 transition-colors">Tidak</span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('attendance-form');
    if (!form) return;

    form.addEventListener('change', function(e) {
        if (e.target.type === 'radio') {
            const formData = new FormData(form);
            
            // 1. Send update to server in background
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).catch(err => console.error('Error saving attendance:', err));

            // 2. Instantly update UI counts locally
            document.querySelectorAll('.division-block').forEach(divBlock => {
                const divisionSlug = divBlock.getAttribute('data-division');
                const divHadirCount = divBlock.querySelectorAll('input[type="radio"][value="hadir"]:checked').length;
                const divTidakCount = divBlock.querySelectorAll('input[type="radio"][value="alpa"]:checked').length;

                const divHadirEl = document.getElementById(`div-hadir-${divisionSlug}`);
                const divTidakEl = document.getElementById(`div-tidak-${divisionSlug}`);

                if (divHadirEl) divHadirEl.textContent = divHadirCount;
                if (divTidakEl) divTidakEl.textContent = divTidakCount;
            });
        }
    });
});
</script>

{{-- Modal Edit Notulensi --}}
<div id="modal-edit-minutes" class="fixed inset-0 z-[100] hidden">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('modal-edit-minutes')"></div>
    <div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4" onclick="closeModal('modal-edit-minutes')">
    <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl relative flex flex-col" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between p-6 border-b border-slate-100 shrink-0">
            <h3 class="text-lg font-black text-slate-900">Edit Notulensi</h3>
            <button onclick="closeModal('modal-edit-minutes')" class="w-8 h-8 flex items-center justify-center rounded-xl hover:bg-slate-100 text-slate-500 transition-colors">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto">
            <form action="{{ route($routeBase . '.minutes', ['event' => $event->id, 'meeting' => $meeting->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Teks Notulensi</label>
                        <textarea name="minutes" rows="12" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" placeholder="Ketik kesimpulan atau jalannya rapat di sini...">{{ $meeting->minutes }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Lampirkan File Fisik (Opsional)</label>
                        <input type="file" name="minutes_file" accept=".pdf,.doc,.docx" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                        @if($meeting->minutes_file)
                            <p class="text-xs text-brand-600 mt-1">Sudah ada file yang dilampirkan. Mengunggah file baru akan menimpa file lama.</p>
                        @endif
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition-colors">
                        Simpan Notulensi
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>
    </div>
</div>

{{-- Modal Edit Rapat --}}
<div id="modal-edit-meeting" class="fixed inset-0 z-[100] hidden">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('modal-edit-meeting')"></div>
    <div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4" onclick="closeModal('modal-edit-meeting')">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl relative flex flex-col" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between p-6 border-b border-slate-100 shrink-0">
            <h3 class="text-lg font-black text-slate-900">Edit Detail Rapat</h3>
            <button onclick="closeModal('modal-edit-meeting')" class="w-8 h-8 flex items-center justify-center rounded-xl hover:bg-slate-100 text-slate-500 transition-colors">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto">
            <form action="{{ route($routeBase . '.update', ['event' => $event->id, 'meeting' => $meeting->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Judul Rapat</label>
                        <input type="text" name="title" value="{{ $meeting->title }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Tanggal</label>
                            <input type="date" name="date" value="{{ $meeting->date->format('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Jam</label>
                            <input type="time" name="time" value="{{ \Carbon\Carbon::parse($meeting->time)->format('H:i') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Lokasi / Platform</label>
                        <input type="text" name="location" value="{{ $meeting->location }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Status Rapat</label>
                        <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                            <option value="scheduled" {{ $meeting->status == 'scheduled' ? 'selected' : '' }}>Dijadwalkan</option>
                            <option value="ongoing" {{ $meeting->status == 'ongoing' ? 'selected' : '' }}>Berlangsung</option>
                            <option value="completed" {{ $meeting->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ $meeting->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Agenda Singkat</label>
                        <textarea name="description" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">{{ $meeting->description }}</textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>
    </div>
</div>
@endsection
