@extends('layouts.dashboard')

@section('title', 'Arsip Dokumen Vital')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Arsip Dokumen Vital</h1>
        <p class="text-sm text-slate-500 mt-1">Penyimpanan dokumen penting organisasi (Proposal, LPJ, SK, dll).</p>
    </div>
    <div class="flex items-center gap-3">
        <button onclick="openModal('modal-add-archive')" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-xl text-sm font-bold transition-colors shadow-sm">
            <i class="ph-bold ph-plus"></i>
            Tambah Arsip
        </button>
    </div>
</div>

{{-- Filter --}}
<div class="bg-white p-2 rounded-2xl border border-slate-200 inline-flex mb-6 shadow-sm overflow-x-auto max-w-full">
    <a href="{{ route('kepengurusan.sekretaris.archives.index') }}" class="px-4 py-1.5 rounded-xl text-sm font-bold transition-colors whitespace-nowrap {{ !request('category') ? 'bg-slate-100 text-slate-900' : 'text-slate-500 hover:text-slate-700' }}">Semua</a>
    <a href="{{ route('kepengurusan.sekretaris.archives.index', ['category' => 'Proposal']) }}" class="px-4 py-1.5 rounded-xl text-sm font-bold transition-colors whitespace-nowrap {{ request('category') == 'Proposal' ? 'bg-brand-50 text-brand-700' : 'text-slate-500 hover:text-slate-700' }}">Proposal</a>
    <a href="{{ route('kepengurusan.sekretaris.archives.index', ['category' => 'LPJ']) }}" class="px-4 py-1.5 rounded-xl text-sm font-bold transition-colors whitespace-nowrap {{ request('category') == 'LPJ' ? 'bg-brand-50 text-brand-700' : 'text-slate-500 hover:text-slate-700' }}">LPJ</a>
    <a href="{{ route('kepengurusan.sekretaris.archives.index', ['category' => 'SK / Legalitas']) }}" class="px-4 py-1.5 rounded-xl text-sm font-bold transition-colors whitespace-nowrap {{ request('category') == 'SK / Legalitas' ? 'bg-brand-50 text-brand-700' : 'text-slate-500 hover:text-slate-700' }}">SK / Legalitas</a>
    <a href="{{ route('kepengurusan.sekretaris.archives.index', ['category' => 'Lainnya']) }}" class="px-4 py-1.5 rounded-xl text-sm font-bold transition-colors whitespace-nowrap {{ request('category') == 'Lainnya' ? 'bg-brand-50 text-brand-700' : 'text-slate-500 hover:text-slate-700' }}">Lainnya</a>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                    <th class="p-4 font-bold">Judul Dokumen</th>
                    <th class="p-4 font-bold">Kategori</th>
                    <th class="p-4 font-bold">Tanggal Ditetapkan/Arsip</th>
                    <th class="p-4 font-bold">Diupload Oleh</th>
                    <th class="p-4 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($archives as $archive)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="p-4">
                        <span class="font-bold text-slate-900">{{ $archive->title }}</span>
                    </td>
                    <td class="p-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700">
                            {{ $archive->category }}
                        </span>
                    </td>
                    <td class="p-4 text-sm text-slate-600">
                        {{ $archive->date->translatedFormat('d M Y') }}
                    </td>
                    <td class="p-4 text-sm text-slate-600">
                        {{ $archive->uploader ? $archive->uploader->name : 'Sistem' }}
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ Storage::url($archive->file_path) }}" target="_blank" download class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-600 flex items-center justify-center transition-colors tooltip" data-tip="Unduh File">
                                <i class="ph-bold ph-download-simple text-lg"></i>
                            </a>
                            <button onclick="openModal('modal-edit-{{ $archive->id }}')" class="w-8 h-8 rounded-lg bg-brand-50 hover:bg-brand-100 text-brand-600 flex items-center justify-center transition-colors">
                                <i class="ph-bold ph-pencil-simple text-lg"></i>
                            </button>
                            <button onclick="confirmDelete('Hapus Arsip', 'Apakah Anda yakin ingin menghapus arsip {{ $archive->title }}?', '{{ route('kepengurusan.sekretaris.archives.destroy', $archive) }}')" class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition-colors">
                                <i class="ph-bold ph-trash text-lg"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                {{-- Modal Edit --}}
                <div id="modal-edit-{{ $archive->id }}" class="fixed inset-0 z-[100] hidden">
                    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm modal-backdrop" onclick="closeModal('modal-edit-{{ $archive->id }}')"></div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg bg-white rounded-2xl shadow-xl z-10 flex flex-col max-h-[90vh]">
                        <div class="flex items-center justify-between p-6 border-b border-slate-100 shrink-0">
                            <h3 class="text-lg font-black text-slate-900">Edit Arsip Dokumen</h3>
                            <button onclick="closeModal('modal-edit-{{ $archive->id }}')" class="w-8 h-8 flex items-center justify-center rounded-xl hover:bg-slate-100 text-slate-500 transition-colors">
                                <i class="ph-bold ph-x text-lg"></i>
                            </button>
                        </div>
                        <div class="p-6 overflow-y-auto">
                            <form action="{{ route('kepengurusan.sekretaris.archives.update', $archive) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Judul Dokumen</label>
                                        <input type="text" name="title" value="{{ $archive->title }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Kategori</label>
                                            <select name="category" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                                                <option value="Proposal" {{ $archive->category === 'Proposal' ? 'selected' : '' }}>Proposal</option>
                                                <option value="LPJ" {{ $archive->category === 'LPJ' ? 'selected' : '' }}>LPJ</option>
                                                <option value="SK / Legalitas" {{ $archive->category === 'SK / Legalitas' ? 'selected' : '' }}>SK / Legalitas</option>
                                                <option value="Lainnya" {{ $archive->category === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Tanggal Arsip</label>
                                            <input type="date" name="date" value="{{ $archive->date->format('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Ganti File (Opsional)</label>
                                        <input type="file" name="file" accept=".pdf,.doc,.docx,.zip,.rar" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
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
                            <i class="ph-bold ph-archive-box text-2xl text-slate-400"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 mb-1">Belum ada arsip</h3>
                        <p class="text-xs text-slate-500">Pilih tambah arsip untuk mulai mengunggah.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($archives->hasPages())
    <div class="p-4 border-t border-slate-100">
        {{ $archives->links() }}
    </div>
    @endif
</div>

{{-- Modal Tambah --}}
<div id="modal-add-archive" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm modal-backdrop" onclick="closeModal('modal-add-archive')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg bg-white rounded-2xl shadow-xl z-10 flex flex-col max-h-[90vh]">
        <div class="flex items-center justify-between p-6 border-b border-slate-100 shrink-0">
            <h3 class="text-lg font-black text-slate-900">Tambah Arsip Vital</h3>
            <button onclick="closeModal('modal-add-archive')" class="w-8 h-8 flex items-center justify-center rounded-xl hover:bg-slate-100 text-slate-500 transition-colors">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto">
            <form action="{{ route('kepengurusan.sekretaris.archives.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Judul Dokumen</label>
                        <input type="text" name="title" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Kategori</label>
                            <select name="category" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                                <option value="Proposal">Proposal</option>
                                <option value="LPJ">LPJ</option>
                                <option value="SK / Legalitas">SK / Legalitas</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Tanggal Arsip</label>
                            <input type="date" name="date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Upload File</label>
                        <input type="file" name="file" accept=".pdf,.doc,.docx,.zip,.rar" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
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
