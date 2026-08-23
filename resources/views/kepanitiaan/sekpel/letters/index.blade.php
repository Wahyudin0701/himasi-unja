@extends('layouts.dashboard')

@section('title', 'Pengajuan Surat - Sekpel')

@section('breadcrumbs')
    <span class="text-slate-700">Kepanitiaan</span>
    <i class="ph-bold ph-caret-right text-xs text-slate-400"></i>
    <span class="text-slate-700">Sekretaris Pelaksana</span>
    <i class="ph-bold ph-caret-right text-xs text-slate-400"></i>
    <span class="font-bold text-brand-600">Pengajuan Surat</span>
@endsection

@section('content')

@php
    $storeRoute = route('kepanitiaan.sekpel.letters.store', $event);
    $destroyRouteBase = url('kepanitiaan/sekpel/events/' . $event->id . '/letters');
@endphp

<div class="space-y-6 max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 mb-1">Pengajuan Surat Kepanitiaan</h1>
            <p class="text-sm font-medium text-slate-500">
                Ajukan permohonan surat resmi kepanitiaan <span class="font-bold text-brand-600">{{ $event->name }}</span> ke Sekretaris HIMA.
            </p>
        </div>
        <button onclick="openModal('modal-ajukan-surat')" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm whitespace-nowrap">
            Ajukan Permohonan Surat
        </button>
    </div>

    {{-- Panduan Alur --}}
    <div class="bg-brand-50 border border-brand-100 rounded-2xl p-5 flex flex-col md:flex-row items-start md:items-center gap-4 text-sm">
        <div class="w-10 h-10 rounded-xl bg-brand-100 text-brand-600 flex items-center justify-center shrink-0">
            <i class="ph-fill ph-info text-xl"></i>
        </div>
        <div class="flex-1">
            <p class="font-bold text-brand-800 mb-1">Alur Pengajuan Surat</p>
            <p class="text-brand-700">Ajukan permohonan surat beserta lampiran draft surat → Sekretaris HIMA akan me-review → Jika disetujui, nomor surat resmi akan diberikan. Jika perlu revisi, catatan akan dikirimkan kembali ke Anda.</p>
        </div>
    </div>

    {{-- Daftar Pengajuan --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="font-bold text-slate-900">Daftar Permohonan Surat</h2>
                <p class="text-xs text-slate-500 mt-0.5">Total {{ $letters->count() }} permohonan tercatat</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Perihal</th>
                        <th class="px-6 py-4">Pihak Terkait</th>
                        <th class="px-6 py-4">Nomor Surat</th>
                        <th class="px-6 py-4">Diajukan Oleh</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($letters as $letter)
                    <tr class="hover:bg-slate-50 transition-colors">
                        {{-- Status --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($letter->status === 'pending')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                    Menunggu Review
                                </span>
                            @elseif($letter->status === 'revision')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-red-50 text-red-700 border border-red-200 text-xs font-bold">
                                    <i class="ph-bold ph-pencil-simple"></i>
                                    Perlu Revisi
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">
                                    <i class="ph-bold ph-check-circle"></i>
                                    Disetujui
                                </span>
                            @endif
                        </td>

                        {{-- Perihal & Catatan Revisi --}}
                        <td class="px-6 py-4">
                            <span class="font-semibold text-slate-900 block">{{ $letter->subject }}</span>
                            @if($letter->notes)
                                <p class="text-xs text-slate-500 mt-0.5 line-clamp-1">{{ $letter->notes }}</p>
                            @endif
                            @if($letter->status === 'revision' && $letter->review_notes)
                                <div class="mt-2 p-2 rounded-lg bg-red-50 border border-red-100">
                                    <p class="text-xs text-red-700 font-semibold">Catatan Revisi:</p>
                                    <p class="text-xs text-red-600 mt-0.5">{{ $letter->review_notes }}</p>
                                </div>
                            @endif
                        </td>

                        {{-- Pihak Terkait --}}
                        <td class="px-6 py-4 text-slate-600">{{ $letter->party ?? '-' }}</td>

                        {{-- Nomor Surat (hanya muncul jika approved) --}}
                        <td class="px-6 py-4">
                            @if($letter->isApproved())
                                <div>
                                    <span class="font-bold text-emerald-700 block">{{ $letter->letter_number }}</span>
                                    <span class="text-xs text-slate-500">{{ $letter->letter_date?->translatedFormat('d M Y') }}</span>
                                </div>
                            @else
                                <span class="text-slate-400 text-xs italic">Belum ditetapkan</span>
                            @endif
                        </td>

                        {{-- Diajukan Oleh --}}
                        <td class="px-6 py-4">
                            <span class="text-slate-700 text-xs">{{ $letter->submitter?->name ?? 'N/A' }}</span>
                            <span class="text-slate-400 text-xs block">{{ $letter->created_at->diffForHumans() }}</span>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center items-center gap-2">
                                {{-- Download file lampiran --}}
                                @if($letter->file_path)
                                    <a href="{{ Storage::url($letter->file_path) }}" target="_blank"
                                       class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-colors tooltip" data-tip="Unduh File Draft">
                                        <i class="ph-bold ph-download-simple"></i>
                                    </a>
                                @endif

                                {{-- Hapus (hanya jika bukan approved) --}}
                                @if(!$letter->isApproved())
                                    <form action="{{ $destroyRouteBase }}/{{ $letter->id }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin membatalkan pengajuan surat ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white flex items-center justify-center transition-colors tooltip" data-tip="Batalkan Pengajuan">
                                            <i class="ph-bold ph-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-slate-500">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 text-slate-400 mb-4">
                                <i class="ph-fill ph-envelope text-3xl"></i>
                            </div>
                            <p class="font-bold text-slate-600 mb-1">Belum Ada Permohonan Surat</p>
                            <p class="text-sm">Klik tombol "Ajukan Permohonan Surat" untuk memulai.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Modal: Ajukan Surat Baru --}}
<div id="modal-ajukan-surat" class="fixed inset-0 z-[100] hidden">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
    <div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4" onclick="closeModal('modal-ajukan-surat')">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl relative flex flex-col" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between p-6 border-b border-slate-100 shrink-0">
            <div>
                <h3 class="text-lg font-black text-slate-900">Ajukan Permohonan Surat</h3>
                <p class="text-xs text-slate-500 mt-0.5">Surat akan dikirim ke Sekretaris HIMA untuk di-review</p>
            </div>
            <button onclick="closeModal('modal-ajukan-surat')" class="w-8 h-8 flex items-center justify-center rounded-xl hover:bg-slate-100 text-slate-500 transition-colors">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto">
            <form action="{{ $storeRoute }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Perihal Surat <span class="text-red-500">*</span></label>
                        <input type="text" name="subject" required
                               placeholder="Contoh: Permohonan Peminjaman Ruangan Seminar"
                               class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Pihak / Instansi Terkait <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="party"
                               placeholder="Contoh: Dekan Fakultas Teknik, BEM Universitas"
                               class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Keterangan Tambahan <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <textarea name="notes" rows="3"
                                  placeholder="Jelaskan kebutuhan surat ini secara singkat..."
                                  class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 resize-none"></textarea>
                    </div>

                    <div x-data="{ fileName: '' }">
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Lampiran Draft Surat <span class="text-red-500">*</span></label>
                        <label for="file-upload"
                               class="group flex flex-col items-center justify-center gap-2 border-2 border-dashed border-slate-200 rounded-xl p-6 text-center cursor-pointer hover:border-brand-400 hover:bg-brand-50/30 transition-all duration-200">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 group-hover:bg-brand-100 flex items-center justify-center transition-colors">
                                <i class="ph-bold ph-file-arrow-up text-2xl text-slate-400 group-hover:text-brand-600 transition-colors"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-600 group-hover:text-brand-700 transition-colors" x-text="fileName || 'Klik untuk memilih file'"></p>
                                <p class="text-xs text-slate-400 mt-0.5">PDF, Word (DOC/DOCX) • Maks. 5MB</p>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-brand-50 group-hover:bg-brand-100 text-brand-700 border border-brand-200 rounded-lg text-xs font-bold transition-colors">
                                <i class="ph-bold ph-folder-open"></i> Pilih File
                            </span>
                            <input id="file-upload" type="file" name="file" required accept=".pdf,.doc,.docx"
                                   class="sr-only"
                                   @change="fileName = $event.target.files[0]?.name || ''">
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('modal-ajukan-surat')"
                            class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 text-sm font-bold text-white bg-brand-600 hover:bg-brand-700 rounded-xl shadow-sm transition-colors">
                        Kirim Permohonan
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>
    </div>
</div>

@endsection
