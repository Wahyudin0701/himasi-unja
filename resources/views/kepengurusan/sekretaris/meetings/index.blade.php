@extends('layouts.dashboard')

@section('title', 'Manajemen Rapat & Notulensi')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Manajemen Rapat Himpunan</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola jadwal rapat, notulensi, dan daftar hadir pengurus.</p>
    </div>
    <div class="flex items-center gap-3">
        <button onclick="openModal('modal-add-meeting')" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-xl text-sm font-bold transition-colors shadow-sm">
            <i class="ph-bold ph-calendar-plus"></i>
            Buat Rapat Baru
        </button>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest whitespace-nowrap">Tanggal & Waktu</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest whitespace-nowrap">Judul Rapat</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest whitespace-nowrap">Jenis Rapat</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest whitespace-nowrap">Lokasi</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest text-right whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($meetings as $meeting)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-1">
                                <span class="text-sm font-bold text-slate-900">{{ $meeting->date->translatedFormat('d M Y') }}</span>
                                <span class="text-[11px] font-bold text-slate-500">{{ \Carbon\Carbon::parse($meeting->time)->format('H:i') }} WIB</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-slate-900 line-clamp-2">{{ $meeting->title }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black bg-brand-50 text-brand-700 uppercase tracking-wider">
                                {{ $meeting->type ?: 'Rapat Umum' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                                <i class="ph-bold ph-map-pin text-slate-400"></i>
                                <span class="line-clamp-1 max-w-[200px]">{{ $meeting->location ?: 'Belum ditentukan' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('kepengurusan.sekretaris.meetings.show', $meeting) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition-colors">
                                    <i class="ph-bold ph-eye"></i> Detail
                                </a>
                                <button onclick="confirmDelete('Hapus Rapat', 'Yakin hapus rapat ini?', '{{ route('kepengurusan.sekretaris.meetings.destroy', $meeting) }}')" class="inline-flex items-center justify-center w-7 h-7 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors tooltip" data-tip="Hapus Rapat">
                                    <i class="ph-bold ph-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="ph-bold ph-calendar-blank text-2xl text-slate-400"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 mb-1">Belum ada jadwal rapat</h3>
                            <p class="text-xs text-slate-500">Buat jadwal rapat untuk mencatat notulensi dan absensi.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($meetings->hasPages())
<div class="mt-8">
    {{ $meetings->links() }}
</div>
@endif

{{-- Modal Tambah --}}
<div id="modal-add-meeting" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm modal-backdrop" onclick="closeModal('modal-add-meeting')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg bg-white rounded-2xl shadow-xl z-10 flex flex-col max-h-[90vh]">
        <div class="flex items-center justify-between p-6 border-b border-slate-100 shrink-0">
            <h3 class="text-lg font-black text-slate-900">Buat Rapat Baru</h3>
            <button onclick="closeModal('modal-add-meeting')" class="w-8 h-8 flex items-center justify-center rounded-xl hover:bg-slate-100 text-slate-500 transition-colors">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto">
            <form action="{{ route('kepengurusan.sekretaris.meetings.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Judul Rapat</label>
                        <input type="text" name="title" placeholder="Contoh: Rapat Evaluasi Proker Bulanan" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Tanggal</label>
                            <input type="date" name="date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Jam</label>
                            <input type="time" name="time" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Lokasi / Platform</label>
                        <input type="text" name="location" placeholder="Ruang Rapat A / Zoom" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Jenis Rapat</label>
                        <select name="type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                            <option value="Rapat BPH">Rapat BPH</option>
                            <option value="Rapat Pleno">Rapat Pleno</option>
                            <option value="Rapat Divisi">Rapat Divisi</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Agenda Singkat</label>
                        <textarea name="agenda" rows="3" placeholder="Tuliskan poin-poin utama yang akan dibahas..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition-colors">
                        Buat Rapat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
