@extends('layouts.dashboard')

@section('title', 'Tambah Tugas Baru')

@section('content')
<div class="w-full pb-10">
    <div class="mb-6 flex justify-end">
        <a href="{{ route('kepanitiaan.co.dashboard', ['event' => $event->id, 'division' => $division->id]) }}" class="text-brand-600 hover:text-brand-700 font-semibold text-sm flex items-center gap-1.5 w-fit">
            <i class="ph ph-caret-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-black text-slate-900 text-2xl">Tambah Tugas Baru</h3>
            <p class="text-sm text-slate-500 font-medium mt-1.5">Berikan detail tugas dan deadline yang jelas untuk anggota divisi {{ $division->name }}.</p>
        </div>

        <div class="p-8">
            @php
                $sprintsData = isset($sprints) ? $sprints->map(function($s) {
                    return [
                        'sprint_number' => $s->sprint_number,
                        'start_date' => $s->start_date->format('Y-m-d'),
                        'end_date' => $s->end_date->format('Y-m-d')
                    ];
                })->values() : collect([]);
            @endphp
            <form action="{{ route('kepanitiaan.co.tasks.store') }}" method="POST" enctype="multipart/form-data" x-data="{ 
                selectedSprint: '{{ old('sprint_number', '') }}',
                sprints: {{ $sprintsData->toJson() }},
                dueDate: '{{ old('due_date', '') }}',
                init() {
                    let initialDate = this.dueDate;
                    this.$nextTick(() => {
                        this.dueDate = initialDate;
                    });
                    this.$watch('selectedSprint', (value, oldValue) => {
                        if (oldValue !== undefined) {
                            this.dueDate = '';
                        }
                    });
                },
                get currentSprint() {
                    return this.sprints.find(s => s.sprint_number == this.selectedSprint);
                },
                get minDate() {
                    return this.currentSprint ? this.currentSprint.start_date : '';
                },
                get maxDate() {
                    return this.currentSprint ? this.currentSprint.end_date : '';
                },
                get availableDates() {
                    if (!this.currentSprint) return [];
                    let dates = [];
                    let parts = this.currentSprint.start_date.split('-');
                    let curr = new Date(parts[0], parts[1] - 1, parts[2]);
                    let endParts = this.currentSprint.end_date.split('-');
                    let end = new Date(endParts[0], endParts[1] - 1, endParts[2]);
                    
                    while (curr <= end) {
                        let y = curr.getFullYear();
                        let m = String(curr.getMonth() + 1).padStart(2, '0');
                        let d = String(curr.getDate()).padStart(2, '0');
                        let dateStr = `${y}-${m}-${d}`;
                        
                        let formatter = new Intl.DateTimeFormat('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                        let label = formatter.format(curr);
                        
                        dates.push({ value: dateStr, label: label });
                        curr.setDate(curr.getDate() + 1);
                    }
                    return dates;
                }
            }">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">
                <input type="hidden" name="event_division_id" value="{{ $division->id }}">

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Judul Tugas <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3.5 transition-all" required placeholder="Contoh: Menyiapkan desain banner utama">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi (Opsional)</label>
                        <textarea name="description" rows="4" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3.5 transition-all" placeholder="Tuliskan instruksi atau detail lebih lanjut mengenai tugas ini..."></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{ files: [1], links: [1] }">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-bold text-slate-700">Lampiran File (Opsional)</label>
                                <button type="button" @click="files.push(files.length + 1)" class="text-xs font-bold text-brand-600 hover:text-brand-700">
                                    + Tambah File
                                </button>
                            </div>
                            <template x-for="(file, index) in files" :key="index">
                                <div class="mb-3 p-3 bg-brand-50 border border-brand-100 rounded-xl flex flex-col gap-2">
                                    <input type="text" name="file_names[]" class="w-full bg-white border border-slate-200 text-slate-900 text-xs rounded-lg focus:ring-brand-500 focus:border-brand-500 block p-2" placeholder="Nama File (opsional)">
                                    <div class="flex items-center gap-2">
                                        <input type="file" name="files[]" class="flex-1 min-w-0 bg-white border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block p-2 transition-all file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                                        <button type="button" x-show="files.length > 1" @click="files.splice(index, 1)" class="p-2.5 text-rose-500 hover:text-rose-700 transition-colors bg-white rounded-lg shadow-sm border border-slate-200 shrink-0">
                                            <i class="ph-bold ph-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>
                            <p class="text-[10px] text-slate-500 mt-1.5">Maks. ukuran per file 10MB.</p>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-bold text-slate-700">Tautan / Link (Opsional)</label>
                                <button type="button" @click="links.push(links.length + 1)" class="text-xs font-bold text-brand-600 hover:text-brand-700">
                                    + Tambah Tautan
                                </button>
                            </div>
                            <template x-for="(link, index) in links" :key="index">
                                <div class="mb-3 p-3 bg-blue-50 border border-blue-100 rounded-xl flex flex-col gap-2">
                                    <input type="text" name="link_names[]" class="w-full bg-white border border-slate-200 text-slate-900 text-xs rounded-lg focus:ring-brand-500 focus:border-brand-500 block p-2" placeholder="Nama Tautan (opsional)">
                                    <div class="flex items-center gap-2">
                                        <input type="url" name="links[]" class="flex-1 min-w-0 bg-white border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block p-2 transition-all" placeholder="Contoh: https://docs.google.com/...">
                                        <button type="button" x-show="links.length > 1" @click="links.splice(index, 1)" class="p-2.5 text-rose-500 hover:text-rose-700 transition-colors bg-white rounded-lg shadow-sm border border-slate-200 shrink-0">
                                            <i class="ph-bold ph-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kolom Kiri -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Ditugaskan Kepada <span class="text-rose-500">*</span></label>
                                <select name="assigned_to" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3.5 transition-all" required>
                                    <option value="">-- Pilih Anggota --</option>
                                    @foreach($members as $m)
                                        <option value="{{ $m->user_id }}">{{ $m->user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Tingkat Prioritas <span class="text-rose-500">*</span></label>
                                <select name="priority" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3.5 transition-all" required>
                                    <option value="low">Rendah (Low)</option>
                                    <option value="medium" selected>Menengah (Medium)</option>
                                    <option value="high">Tinggi (High)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Sprint Settings Box -->
                        <div>
                            <div class="bg-brand-50 border border-brand-100 rounded-2xl p-6 h-full">
                                <h4 class="font-bold text-brand-900 text-sm mb-5 flex items-center gap-2">
                                    <i class="ph-fill ph-flag-checkered text-brand-500 text-lg"></i> Pengaturan Sprint
                                </h4>
                                @if(isset($sprints) && $sprints->isEmpty())
                                    <div class="p-4 bg-amber-50 text-amber-700 border border-amber-200 rounded-xl text-sm">
                                        <i class="ph-fill ph-warning-circle text-lg mb-1 align-middle"></i> Anda belum mengatur jadwal Sprint untuk divisi ini. <a href="{{ route('kepanitiaan.co.sprints.index', ['event' => $event->id, 'division' => $division->id]) }}" class="font-bold underline hover:text-amber-800">Atur sekarang</a>
                                    </div>
                                @else
                                    <div>
                                        <label class="block text-sm font-bold text-brand-700 mb-2">Pilih Sprint (Ke-) <span class="text-rose-500">*</span></label>
                                        <select name="sprint_number" x-model="selectedSprint" class="w-full bg-white border border-brand-200 text-brand-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3.5 transition-all font-bold" required>
                                            <option value="">-- Pilih Sprint --</option>
                                            @if(isset($sprints))
                                                @foreach($sprints as $sprint)
                                                    <option value="{{ $sprint->sprint_number }}">Sprint {{ $sprint->sprint_number }} ({{ $sprint->start_date->format('d M') }} - {{ $sprint->end_date->format('d M Y') }})</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <p class="text-xs text-brand-600 mt-2">Tugas ini akan masuk ke dalam kolom Sprint yang Anda pilih di Papan Sprint.</p>
                                    </div>
                                    <div class="mt-5 pt-5 border-t border-brand-200/60" x-show="selectedSprint" style="display: none;">
                                        <label class="block text-sm font-bold text-brand-700 mb-2">Tenggat Waktu (Pilih Hari) <span class="text-rose-500">*</span></label>
                                        <select name="due_date" x-model="dueDate" class="w-full bg-white border border-brand-200 text-brand-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3.5 transition-all font-bold" :required="selectedSprint !== ''">
                                            <option value="">-- Pilih Hari --</option>
                                            <template x-for="date in availableDates" :key="date.value">
                                                <option :value="date.value" x-text="date.label" :selected="date.value === dueDate"></option>
                                            </template>
                                        </select>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <a href="{{ route('kepanitiaan.co.dashboard', ['event' => $event->id, 'division' => $division->id]) }}" class="px-6 py-3 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">Batal</a>
                    <button type="submit" class="px-6 py-3 text-sm font-bold text-white bg-brand-600 hover:bg-brand-700 rounded-xl transition-colors shadow-lg shadow-brand-500/30 flex items-center gap-2">
                        <i class="ph ph-check-circle text-lg"></i> Simpan Tugas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
