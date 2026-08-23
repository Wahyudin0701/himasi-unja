@extends('layouts.dashboard')

@section('title', 'Arsip Kepanitiaan')

@section('breadcrumbs')
    <span class="text-slate-700">Sekretaris</span>
    <i class="ph-bold ph-caret-right text-xs text-slate-400"></i>
    <span class="font-bold text-brand-600">Arsip Kepanitiaan</span>
@endsection

@section('content')

{{-- Header --}}
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Arsip Kepanitiaan</h1>
        <p class="text-sm text-slate-500 mt-1">Review dan setujui permohonan surat dari Sekpel kepanitiaan.</p>
    </div>
    {{-- Summary badges --}}
    <div class="flex items-center gap-3 flex-wrap">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-amber-50 border border-amber-200 text-amber-700 rounded-lg text-sm font-bold">
            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
            {{ $letters->where('status', 'pending')->count() }} Menunggu Review
        </div>
        <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm font-bold">
            <i class="ph-bold ph-check-circle"></i>
            {{ $letters->where('status', 'approved')->count() }} Disetujui
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-6">
    <form action="{{ route('kepengurusan.sekretaris.arsip_surat.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[180px]">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Filter Event</label>
            <select name="event_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                <option value="">Semua Event</option>
                @foreach($events as $event)
                    <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                        {{ $event->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-[160px]">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Filter Status</label>
            <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Review</option>
                <option value="revision" {{ request('status') === 'revision' ? 'selected' : '' }}>Perlu Revisi</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                <i class="ph-bold ph-funnel"></i>
                Terapkan Filter
            </button>
            @if(request()->hasAny(['event_id', 'status']))
                <a href="{{ route('kepengurusan.sekretaris.arsip_surat.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-xl transition-colors">
                    <i class="ph-bold ph-x"></i> Reset
                </a>
            @endif
        </div>
    </form>
</div>

{{-- Tabel Permohonan Surat --}}
<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600 border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">Event</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">Perihal & Keterangan</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">Pihak Terkait</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">Pengaju</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">No. Surat</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($letters as $letter)
                <tr class="hover:bg-slate-50 transition-colors {{ $letter->status === 'pending' ? 'bg-amber-50/30' : '' }}">
                    {{-- Status Badge --}}
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

                    {{-- Event --}}
                    <td class="px-6 py-4">
                        <span class="font-semibold text-slate-800 block">{{ $letter->event?->name ?? '-' }}</span>
                        <span class="text-xs text-slate-400">{{ $letter->created_at->diffForHumans() }}</span>
                    </td>

                    {{-- Perihal --}}
                    <td class="px-6 py-4 max-w-xs">
                        <span class="font-semibold text-slate-900 block">{{ $letter->subject }}</span>
                        @if($letter->notes)
                            <p class="text-xs text-slate-500 mt-0.5 line-clamp-2">{{ $letter->notes }}</p>
                        @endif
                        @if($letter->status === 'revision' && $letter->review_notes)
                            <div class="mt-1.5 p-2 rounded-lg bg-red-50 border border-red-100 text-xs text-red-700">
                                <span class="font-bold">Catatan Revisi:</span> {{ $letter->review_notes }}
                            </div>
                        @endif
                    </td>

                    {{-- Pihak Terkait --}}
                    <td class="px-6 py-4 text-slate-600 whitespace-nowrap">{{ $letter->party ?? '-' }}</td>

                    {{-- Pengaju --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            @php
                                $submitter = $letter->submitter;
                                $avatarUrl = $submitter?->avatar
                                    ? (Str::startsWith($submitter->avatar, 'http') ? $submitter->avatar : Storage::url($submitter->avatar))
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($submitter?->name ?? 'N') . '&background=6d28d9&color=fff&bold=true&size=64';
                            @endphp
                            <img src="{{ $avatarUrl }}" alt="{{ $submitter?->name }}" class="w-7 h-7 rounded-full object-cover shrink-0">
                            <span class="text-sm text-slate-700 font-medium">{{ $submitter?->name ?? 'N/A' }}</span>
                        </div>
                    </td>

                    {{-- Nomor Surat --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($letter->isApproved())
                            <span class="font-bold text-emerald-700 text-sm block">{{ $letter->letter_number }}</span>
                            <span class="text-xs text-slate-400">{{ $letter->letter_date?->translatedFormat('d M Y') }}</span>
                        @else
                            <span class="text-slate-400 text-xs italic">—</span>
                        @endif
                    </td>

                    {{-- Aksi --}}
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex items-center justify-end gap-2">
                            {{-- Download file draft --}}
                            @if($letter->file_path)
                                <a href="{{ Storage::url($letter->file_path) }}" target="_blank"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition-colors tooltip" data-tip="Unduh Draft">
                                    <i class="ph-bold ph-download-simple"></i> Draft
                                </a>
                            @endif

                            {{-- Tombol Aksi Review (hanya jika pending atau revision) --}}
                            @if(!$letter->isApproved())
                                <button onclick="openModal('modal-setujui-{{ $letter->id }}')"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-colors">
                                    <i class="ph-bold ph-check"></i> Setujui
                                </button>
                                <button onclick="openModal('modal-revisi-{{ $letter->id }}')"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg text-xs font-bold transition-colors border border-red-200">
                                    <i class="ph-bold ph-pencil-simple"></i> Revisi
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>

                {{-- Modal: Setujui Surat --}}
                <div id="modal-setujui-{{ $letter->id }}" class="fixed inset-0 z-[100] hidden">
                    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm modal-backdrop" onclick="closeModal('modal-setujui-{{ $letter->id }}')"></div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg bg-white rounded-2xl shadow-xl z-10 flex flex-col">
                        <div class="flex items-center justify-between p-6 border-b border-slate-100 shrink-0 bg-emerald-50">
                            <div>
                                <h3 class="text-lg font-black text-emerald-900">Setujui Permohonan Surat</h3>
                                <p class="text-xs text-emerald-700 mt-0.5">Berikan nomor surat resmi untuk: <strong>{{ $letter->subject }}</strong></p>
                            </div>
                            <button onclick="closeModal('modal-setujui-{{ $letter->id }}')" class="w-8 h-8 flex items-center justify-center rounded-xl hover:bg-emerald-100 text-emerald-600 transition-colors">
                                <i class="ph-bold ph-x text-lg"></i>
                            </button>
                        </div>
                        <div class="p-6">
                            <form action="{{ route('kepengurusan.sekretaris.arsip_surat.approve', $letter) }}" method="POST">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Tipe Surat <span class="text-red-500">*</span></label>
                                        <select name="type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                                            <option value="keluar">Surat Keluar</option>
                                            <option value="masuk">Surat Masuk</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Nomor Surat Resmi <span class="text-red-500">*</span></label>
                                        <input type="text" name="letter_number" required
                                               placeholder="Contoh: 001/PAN-HIMASI/VII/2026"
                                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Tanggal Surat <span class="text-red-500">*</span></label>
                                        <input type="date" name="letter_date" required value="{{ date('Y-m-d') }}"
                                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                                    </div>
                                </div>
                                <div class="mt-6 flex justify-end gap-3">
                                    <button type="button" onclick="closeModal('modal-setujui-{{ $letter->id }}')"
                                            class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">Batal</button>
                                    <button type="submit"
                                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-sm transition-colors">
                                        <i class="ph-bold ph-check-circle"></i> Setujui & Kirim Nomor Surat
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Modal: Minta Revisi --}}
                <div id="modal-revisi-{{ $letter->id }}" class="fixed inset-0 z-[100] hidden">
                    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm modal-backdrop" onclick="closeModal('modal-revisi-{{ $letter->id }}')"></div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg bg-white rounded-2xl shadow-xl z-10 flex flex-col">
                        <div class="flex items-center justify-between p-6 border-b border-slate-100 shrink-0 bg-red-50">
                            <div>
                                <h3 class="text-lg font-black text-red-900">Minta Revisi</h3>
                                <p class="text-xs text-red-700 mt-0.5">Berikan catatan revisi untuk: <strong>{{ $letter->subject }}</strong></p>
                            </div>
                            <button onclick="closeModal('modal-revisi-{{ $letter->id }}')" class="w-8 h-8 flex items-center justify-center rounded-xl hover:bg-red-100 text-red-600 transition-colors">
                                <i class="ph-bold ph-x text-lg"></i>
                            </button>
                        </div>
                        <div class="p-6">
                            <form action="{{ route('kepengurusan.sekretaris.arsip_surat.revision', $letter) }}" method="POST">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Catatan Revisi <span class="text-red-500">*</span></label>
                                    <textarea name="review_notes" rows="4" required
                                              placeholder="Jelaskan apa yang perlu diperbaiki oleh Sekpel..."
                                              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-500 resize-none"></textarea>
                                </div>
                                <div class="mt-6 flex justify-end gap-3">
                                    <button type="button" onclick="closeModal('modal-revisi-{{ $letter->id }}')"
                                            class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">Batal</button>
                                    <button type="submit"
                                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-sm transition-colors">
                                        <i class="ph-bold ph-pencil-simple"></i> Kirim Catatan Revisi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="ph-bold ph-envelope-open text-2xl text-slate-400"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 mb-1">Belum ada pengajuan surat</h3>
                        <p class="text-xs text-slate-500">Belum ada arsip surat yang diajukan oleh Sekpel kepanitiaan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
