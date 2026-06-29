@extends('layouts.dashboard')

@section('title', 'Periode Kepengurusan')

@section('breadcrumbs')
    <a href="{{ route('super_admin.dashboard') }}" class="text-slate-500 hover:text-brand-600 transition-colors">Super Admin</a>
    <i class="ph-bold ph-caret-right text-slate-400 text-xs"></i>
    <span class="text-slate-700">Manajemen Periode</span>
@endsection

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">Manajemen Periode</h1>
        <p class="text-sm text-slate-500 mt-2 leading-relaxed">
            Kelola data periode kepengurusan HIMA. Lihat riwayat periode sebelumnya atau mulai kepengurusan baru.
        </p>
    </div>
    @if(in_array(auth()->user()->global_role, ['super_admin']))
    <div class="shrink-0">
        <a href="{{ route('super_admin.periods.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-amber-500/20">
            <i class="ph-bold ph-calendar-plus text-lg"></i>
            <span>Mulai Periode Baru</span>
        </a>
    </div>
    @endif
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-slate-500 bg-slate-50 border-b border-slate-100 uppercase font-bold">
                <tr>
                    <th scope="col" class="px-6 py-4 w-[30%]">Nama Periode</th>
                    <th scope="col" class="px-6 py-4 w-[25%]">Masa Jabatan</th>
                    <th scope="col" class="px-6 py-4 w-[25%]">Status</th>
                    <th scope="col" class="px-6 py-4 w-[20%] text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700">
                @forelse($periods as $period)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900 text-base">{{ $period->name }}</div>
                            <div class="text-xs text-slate-500 mt-0.5">{{ $period->divisions->count() }} Divisi Terdaftar</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-700">
                                {{ \Carbon\Carbon::parse($period->start_date)->translatedFormat('d M Y') }} - 
                                @if($period->is_active)
                                    Sekarang
                                @else
                                    {{ \Carbon\Carbon::parse($period->end_date)->translatedFormat('d M Y') }}
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($period->is_active)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-brand-50 text-brand-700 text-xs font-bold rounded-lg border border-brand-100">
                                    <div class="w-1.5 h-1.5 rounded-full bg-brand-500 animate-pulse"></div>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-lg border border-slate-200">
                                    <i class="ph-fill ph-archive text-slate-400 mr-1"></i>
                                    Diarsipkan
                                </span>
                                @if($period->archived_at)
                                    <div class="text-[10px] text-slate-400 mt-1">Pada {{ \Carbon\Carbon::parse($period->archived_at)->translatedFormat('d M Y') }}</div>
                                @endif
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($period->is_active)
                                <button disabled class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-50 border border-slate-200 text-slate-400 text-xs font-bold rounded-lg cursor-not-allowed" title="Arsip hanya tersedia untuk periode yang sudah selesai">
                                    <i class="ph-bold ph-lock-key"></i>
                                    <span>Sedang Berjalan</span>
                                </button>
                            @else
                                <a href="{{ route('super_admin.periods.show', $period->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-brand-50 border border-brand-100 hover:bg-brand-500 hover:text-white hover:border-brand-500 text-brand-700 text-xs font-bold rounded-lg transition-all shadow-sm">
                                    <i class="ph-bold ph-eye"></i>
                                    <span>Detail Arsip</span>
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="ph-bold ph-calendar-blank text-2xl text-slate-400"></i>
                            </div>
                            <p class="font-bold text-slate-900 text-base mb-1">Belum ada periode kepengurusan</p>
                            <p class="text-sm">Silakan mulai periode kepengurusan baru.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
