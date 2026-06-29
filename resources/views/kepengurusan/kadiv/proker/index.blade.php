@extends('layouts.dashboard')

@section('title', 'Daftar Program Kerja')

@section('breadcrumbs')
    <span class="text-slate-700">Kepengurusan</span>
@endsection

@section('content')

{{-- ===== HEADER ===== --}}
<div class="mb-8">
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">
                Program Kerja Divisi <span class="text-brand-500 uppercase">{{ $division->name }}</span>
            </h1>
            <p class="text-sm text-slate-500 mt-1.5 font-medium">Kelola seluruh program kerja divisi Anda pada periode ini.</p>
        </div>
        <a href="{{ route('kepengurusan.kadiv.proker.create') }}" class="hidden sm:flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white rounded-xl px-4 py-2.5 text-sm font-semibold transition-colors shadow-sm shadow-brand-500/20">
            <i class="ph-bold ph-plus"></i>
            Tambah Proker
        </a>
    </div>
</div>

{{-- ===== TABLE PROKER ===== --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/80 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                    <th class="px-6 py-4">Nama Program Kerja</th>
                    <th class="px-6 py-4 text-center">Tanggal Pelaksanaan</th>
                    <th class="px-6 py-4 text-center">Jenis Program</th>
                    <th class="px-6 py-4 text-center">PJ/Ketupel</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($prokers as $proker)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="px-6 py-4">
                        <p class="text-sm font-bold text-slate-900">{{ $proker->name }}</p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <p class="text-sm font-medium text-slate-600">
                            @if($proker->start_date && $proker->end_date)
                                {{ $proker->start_date->translatedFormat('d F Y') }} - {{ $proker->end_date->translatedFormat('d F Y') }}
                            @elseif($proker->start_date)
                                Mulai {{ $proker->start_date->translatedFormat('d F Y') }}
                            @else
                                -
                            @endif
                        </p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <p class="text-sm font-bold text-slate-700">
                            {{ $proker->type === 'event' ? 'Event' : 'Non-Event' }}
                        </p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <p class="text-sm font-medium text-slate-600">
                            {{ $proker->pic ? $proker->pic->name : '-' }}
                        </p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                            @if($proker->status === 'ongoing') bg-emerald-100 text-emerald-700
                            @elseif($proker->status === 'planning') bg-brand-100 text-brand-700
                            @elseif($proker->status === 'completed') bg-slate-100 text-slate-600
                            @else bg-rose-100 text-rose-700
                            @endif">
                            {{ $proker->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center space-x-1">
                            <a href="{{ route('kepengurusan.kadiv.proker.show', $proker->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors text-xs font-bold" title="Detail">
                                Detail
                            </a>
                            <a href="{{ route('kepengurusan.kadiv.proker.edit', $proker->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-brand-50 text-brand-600 hover:bg-brand-100 transition-colors text-xs font-bold" title="Edit">
                                Edit
                            </a>
                            @if($proker->status !== 'cancelled')
                            <form action="{{ route('kepengurusan.kadiv.proker.cancel', $proker->id) }}" method="POST" class="inline-block m-0 p-0 cancel-proker-form">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors text-xs font-bold" title="Batalkan">
                                    Batalkan
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                                <i class="ph-fill ph-kanban text-3xl text-slate-300"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-800 mb-1">Belum ada Program Kerja</h3>
                            <p class="text-sm text-slate-500 font-medium max-w-sm">Divisi ini belum memiliki program kerja yang didaftarkan pada periode aktif.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.cancel-proker-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Batalkan Program Kerja?',
                    text: 'Apakah Anda yakin ingin membatalkan program kerja ini? Silakan berikan alasan pembatalan.',
                    icon: 'warning',
                    input: 'textarea',
                    inputPlaceholder: 'Tulis alasan pembatalan di sini...',
                    inputValidator: (value) => {
                        if (!value || value.trim() === '') {
                            return 'Alasan pembatalan wajib diisi!'
                        }
                    },
                    showCancelButton: true,
                    confirmButtonColor: '#f43f5e',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, Batalkan!',
                    cancelButtonText: 'Kembali',
                    reverseButtons: true,
                    customClass: {
                        title: 'text-lg font-bold text-slate-800',
                        htmlContainer: 'text-sm text-slate-500',
                        input: '!w-11/12 mx-auto min-h-[100px] p-3 border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-sm mt-4 resize-none',
                        confirmButton: 'px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white rounded-lg font-bold text-sm transition-colors',
                        cancelButton: 'px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg font-bold text-sm transition-colors',
                        actions: 'gap-3 mt-6'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'cancellation_reason';
                        input.value = result.value;
                        form.appendChild(input);
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
