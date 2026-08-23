@extends('layouts.dashboard')

@section('title', 'Buku Agenda Surat Himpunan')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Buku Agenda Surat Himpunan</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola arsip surat masuk dan keluar khusus HIMASI.</p>
    </div>
    <div class="flex items-center gap-3">
        <button onclick="openModal('modal-add-letter')" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-xl text-sm font-bold transition-colors shadow-sm">
            <i class="ph-bold ph-plus"></i>
            Tambah Surat
        </button>
    </div>
</div>

{{-- Filter --}}
<div class="bg-white p-2 rounded-2xl border border-slate-200 inline-flex mb-6 shadow-sm">
    <a href="{{ route('kepengurusan.sekretaris.organization-letters.index') }}" class="px-4 py-1.5 rounded-xl text-sm font-bold transition-colors {{ !request('type') ? 'bg-slate-100 text-slate-900' : 'text-slate-500 hover:text-slate-700' }}">Semua</a>
    <a href="{{ route('kepengurusan.sekretaris.organization-letters.index', ['type' => 'masuk']) }}" class="px-4 py-1.5 rounded-xl text-sm font-bold transition-colors {{ request('type') == 'masuk' ? 'bg-brand-50 text-brand-700' : 'text-slate-500 hover:text-slate-700' }}">Surat Masuk</a>
    <a href="{{ route('kepengurusan.sekretaris.organization-letters.index', ['type' => 'keluar']) }}" class="px-4 py-1.5 rounded-xl text-sm font-bold transition-colors {{ request('type') == 'keluar' ? 'bg-amber-50 text-amber-700' : 'text-slate-500 hover:text-slate-700' }}">Surat Keluar</a>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                    <th class="p-4 font-bold">No. Surat</th>
                    <th class="p-4 font-bold">Tanggal</th>
                    <th class="p-4 font-bold">Perihal / Pihak Terkait</th>
                    <th class="p-4 font-bold text-center">Tipe</th>
                    <th class="p-4 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($letters as $letter)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="p-4">
                        <span class="font-bold text-slate-900">{{ $letter->letter_number }}</span>
                    </td>
                    <td class="p-4 text-sm text-slate-600">
                        {{ $letter->letter_date->translatedFormat('d M Y') }}
                    </td>
                    <td class="p-4">
                        <p class="font-bold text-slate-900 text-sm">{{ $letter->subject }}</p>
                        @if($letter->party)
                            <p class="text-xs text-slate-500 mt-0.5">{{ $letter->party }}</p>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        @if($letter->type === 'masuk')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-black bg-brand-50 text-brand-700 border border-brand-200 uppercase tracking-wider">
                                Masuk
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-black bg-amber-50 text-amber-700 border border-amber-200 uppercase tracking-wider">
                                Keluar
                            </span>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            @if($letter->file_path)
                                <a href="{{ Storage::url($letter->file_path) }}" target="_blank" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-600 flex items-center justify-center transition-colors tooltip" data-tip="Lihat File">
                                    <i class="ph-bold ph-file-pdf text-lg"></i>
                                </a>
                            @endif
                            <button onclick="openModal('modal-edit-{{ $letter->id }}')" class="w-8 h-8 rounded-lg bg-brand-50 hover:bg-brand-100 text-brand-600 flex items-center justify-center transition-colors">
                                <i class="ph-bold ph-pencil-simple text-lg"></i>
                            </button>
                            <button onclick="confirmDelete('Hapus Surat Himpunan', 'Apakah Anda yakin ingin menghapus surat {{ $letter->letter_number }}?', '{{ route('kepengurusan.sekretaris.organization-letters.destroy', $letter) }}')" class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition-colors">
                                <i class="ph-bold ph-trash text-lg"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                {{-- Modal Edit --}}
                <div id="modal-edit-{{ $letter->id }}" class="fixed inset-0 z-[100] hidden">
                    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm modal-backdrop" onclick="closeModal('modal-edit-{{ $letter->id }}')"></div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg bg-white rounded-2xl shadow-xl z-10 flex flex-col max-h-[90vh]">
                        <div class="flex items-center justify-between p-6 border-b border-slate-100 shrink-0">
                            <h3 class="text-lg font-black text-slate-900">Edit Surat Himpunan</h3>
                            <button onclick="closeModal('modal-edit-{{ $letter->id }}')" class="w-8 h-8 flex items-center justify-center rounded-xl hover:bg-slate-100 text-slate-500 transition-colors">
                                <i class="ph-bold ph-x text-lg"></i>
                            </button>
                        </div>
                        <div class="p-6 overflow-y-auto">
                            <form action="{{ route('kepengurusan.sekretaris.organization-letters.update', $letter) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Tipe Surat</label>
                                        <select name="type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                                            <option value="masuk" {{ $letter->type === 'masuk' ? 'selected' : '' }}>Surat Masuk</option>
                                            <option value="keluar" {{ $letter->type === 'keluar' ? 'selected' : '' }}>Surat Keluar</option>
                                        </select>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Nomor Surat</label>
                                            <input type="text" name="letter_number" value="{{ $letter->letter_number }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Tanggal Surat</label>
                                            <input type="date" name="letter_date" value="{{ $letter->letter_date->format('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Perihal</label>
                                        <input type="text" name="subject" value="{{ $letter->subject }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Instansi / Pihak Terkait</label>
                                        <input type="text" name="party" value="{{ $letter->party }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Ganti File Fisik (PDF/DOCX) - Opsional</label>
                                        <input type="file" name="file" accept=".pdf,.doc,.docx" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
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
                @empty
                <tr>
                    <td colspan="5" class="p-12 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="ph-bold ph-envelope-simple text-2xl text-slate-400"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 mb-1">Belum ada surat</h3>
                        <p class="text-xs text-slate-500">Buku agenda masih kosong.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($letters->hasPages())
    <div class="p-4 border-t border-slate-100">
        {{ $letters->links() }}
    </div>
    @endif
</div>

{{-- Modal Tambah --}}
<div id="modal-add-letter" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm modal-backdrop" onclick="closeModal('modal-add-letter')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg bg-white rounded-2xl shadow-xl z-10 flex flex-col max-h-[90vh]">
        <div class="flex items-center justify-between p-6 border-b border-slate-100 shrink-0">
            <h3 class="text-lg font-black text-slate-900">Tambah Surat Himpunan</h3>
            <button onclick="closeModal('modal-add-letter')" class="w-8 h-8 flex items-center justify-center rounded-xl hover:bg-slate-100 text-slate-500 transition-colors">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto">
            <form action="{{ route('kepengurusan.sekretaris.organization-letters.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Tipe Surat</label>
                        <select name="type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                            <option value="masuk">Surat Masuk</option>
                            <option value="keluar">Surat Keluar</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Nomor Surat</label>
                            <input type="text" name="letter_number" placeholder="Contoh: 001/HIMASI/VI/2026" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Tanggal Surat</label>
                            <input type="date" name="letter_date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Perihal</label>
                        <input type="text" name="subject" placeholder="Contoh: Undangan Rapat Pleno" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Instansi / Pihak Terkait</label>
                        <input type="text" name="party" placeholder="Contoh: BEM Universitas Jambi" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Upload File Fisik (PDF/DOCX) - Opsional</label>
                        <input type="file" name="file" accept=".pdf,.doc,.docx" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition-colors">
                        Simpan Arsip
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
