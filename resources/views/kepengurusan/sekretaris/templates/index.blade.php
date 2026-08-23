@extends('layouts.dashboard')

@section('title', 'Bank Templat Dokumen')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Bank Templat Dokumen</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola dan sediakan templat dokumen resmi Himpunan.</p>
    </div>
    <div class="flex items-center gap-3">
        <button onclick="openModal('modal-add-template')" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-xl text-sm font-bold transition-colors shadow-sm">
            <i class="ph-bold ph-upload-simple"></i>
            Unggah Templat
        </button>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest whitespace-nowrap w-16">Tipe</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest whitespace-nowrap">Nama Templat</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest whitespace-nowrap">Deskripsi</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest text-right whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($templates as $template)
                    @php
                        $ext = pathinfo($template->file_path, PATHINFO_EXTENSION);
                        $icon = 'ph-file text-slate-500';
                        if (in_array($ext, ['doc', 'docx'])) $icon = 'ph-file-doc text-blue-500';
                        elseif (in_array($ext, ['pdf'])) $icon = 'ph-file-pdf text-red-500';
                        elseif (in_array($ext, ['xls', 'xlsx'])) $icon = 'ph-file-xls text-green-500';
                        elseif (in_array($ext, ['ppt', 'pptx'])) $icon = 'ph-file-ppt text-amber-500';
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="ph-fill {{ $icon }} text-xl"></i>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-slate-900 line-clamp-1">{{ $template->name }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase mt-0.5">{{ $ext }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs text-slate-500 line-clamp-2 max-w-md">{{ $template->description ?: 'Tidak ada deskripsi.' }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ Storage::url($template->file_path) }}" download class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-brand-50 hover:bg-brand-100 text-brand-700 rounded-lg text-xs font-bold transition-colors tooltip" data-tip="Unduh">
                                    <i class="ph-bold ph-download-simple"></i> Unduh
                                </a>
                                <button onclick="openModal('modal-edit-template-{{ $template->id }}')" class="inline-flex items-center justify-center w-8 h-8 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition-colors tooltip" data-tip="Edit">
                                    <i class="ph-bold ph-pencil-simple"></i>
                                </button>
                                <button onclick="confirmDelete('Hapus Templat', 'Apakah Anda yakin ingin menghapus templat {{ $template->name }}?', '{{ route('kepengurusan.sekretaris.templates.destroy', $template) }}')" class="inline-flex items-center justify-center w-8 h-8 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors tooltip" data-tip="Hapus">
                                    <i class="ph-bold ph-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Modal Edit --}}
                    <div id="modal-edit-template-{{ $template->id }}" class="fixed inset-0 z-[100] hidden">
                        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm modal-backdrop" onclick="closeModal('modal-edit-template-{{ $template->id }}')"></div>
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg bg-white rounded-2xl shadow-xl z-10 flex flex-col max-h-[90vh]">
                            <div class="flex items-center justify-between p-6 border-b border-slate-100 shrink-0">
                                <h3 class="text-lg font-black text-slate-900">Edit Templat Dokumen</h3>
                                <button onclick="closeModal('modal-edit-template-{{ $template->id }}')" class="w-8 h-8 flex items-center justify-center rounded-xl hover:bg-slate-100 text-slate-500 transition-colors">
                                    <i class="ph-bold ph-x text-lg"></i>
                                </button>
                            </div>
                            <div class="p-6 overflow-y-auto">
                                <form action="{{ route('kepengurusan.sekretaris.templates.update', $template) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Templat</label>
                                            <input type="text" name="name" value="{{ $template->name }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Deskripsi Singkat</label>
                                            <textarea name="description" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">{{ $template->description }}</textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Ganti File Dokumen (Opsional)</label>
                                            <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                                            <p class="text-xs text-slate-500 mt-1">Biarkan kosong jika tidak ingin mengubah file.</p>
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
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="ph-bold ph-files text-2xl text-slate-400"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 mb-1">Belum ada templat dokumen</h3>
                            <p class="text-xs text-slate-500">Unggah templat proposal, LPJ, atau kop surat pertama.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($templates->hasPages())
<div class="mt-8">
    {{ $templates->links() }}
</div>
@endif

{{-- Modal Tambah --}}
<div id="modal-add-template" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm modal-backdrop" onclick="closeModal('modal-add-template')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg bg-white rounded-2xl shadow-xl z-10 flex flex-col max-h-[90vh]">
        <div class="flex items-center justify-between p-6 border-b border-slate-100 shrink-0">
            <h3 class="text-lg font-black text-slate-900">Unggah Templat Baru</h3>
            <button onclick="closeModal('modal-add-template')" class="w-8 h-8 flex items-center justify-center rounded-xl hover:bg-slate-100 text-slate-500 transition-colors">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto">
            <form action="{{ route('kepengurusan.sekretaris.templates.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Templat</label>
                        <input type="text" name="name" placeholder="Misal: Format Proposal BEM" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Deskripsi Singkat</label>
                        <textarea name="description" placeholder="Panduan singkat terkait penggunaan dokumen ini..." rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Upload File Dokumen</label>
                        <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                        <p class="text-xs text-slate-500 mt-1">Mendukung: PDF, Word, Excel, PowerPoint.</p>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition-colors">
                        Simpan Templat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
