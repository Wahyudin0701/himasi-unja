@extends('layouts.dashboard')

@section('title', 'Riwayat Proker: ' . $division->name)

@section('breadcrumbs')
    <a href="{{ route('super_admin.dashboard') }}" class="text-slate-500 hover:text-brand-600 transition-colors">Super Admin</a>
    <i class="ph-bold ph-caret-right text-slate-400 text-xs"></i>
    <a href="{{ route('super_admin.periods.index') }}" class="text-slate-500 hover:text-brand-600 transition-colors">Manajemen Periode</a>
    <i class="ph-bold ph-caret-right text-slate-400 text-xs"></i>
    <a href="{{ route('super_admin.periods.show', $period->id) }}" class="text-slate-500 hover:text-brand-600 transition-colors">Periode {{ $period->name }}</a>
    <i class="ph-bold ph-caret-right text-slate-400 text-xs"></i>
    <a href="{{ route('super_admin.periods.performance', $period->id) }}" class="text-slate-500 hover:text-brand-600 transition-colors">Kinerja Divisi</a>
    <i class="ph-bold ph-caret-right text-slate-400 text-xs"></i>
    <span class="text-slate-700">{{ $division->name }}</span>
@endsection

@section('content')
<div class="mb-8 flex items-start justify-between">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">Riwayat Program Kerja</h1>
        <p class="text-sm text-slate-500 mt-2 font-medium leading-relaxed max-w-2xl">
            Arsip daftar Program Kerja (Proker) milik divisi <strong class="text-slate-700">{{ $division->name }}</strong> pada periode {{ $period->name }}.
        </p>
    </div>
    <a href="{{ route('super_admin.periods.performance', $period->id) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-brand-600 transition-colors mt-1.5 shrink-0">
        <i class="ph-bold ph-arrow-left"></i>
        Kembali
    </a>
</div>

<div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <i class="ph-fill ph-kanban text-xl"></i>
            </div>
            <h2 class="text-base font-black text-slate-900">Daftar Proker ({{ $prokers->count() }})</h2>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-slate-500 bg-white border-b border-slate-100 uppercase font-bold">
                <tr>
                    <th scope="col" class="px-6 py-4 w-[35%]">Nama Proker</th>
                    <th scope="col" class="px-6 py-4 w-[15%]">Pelaksanaan</th>
                    <th scope="col" class="px-6 py-4 w-[20%]">Status Akhir</th>
                    <th scope="col" class="px-6 py-4 w-[20%]">PIC</th>
                    <th scope="col" class="px-6 py-4 w-[10%] text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($prokers as $proker)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900 mb-1">{{ $proker->name }}</div>
                            <div class="text-xs text-slate-500 line-clamp-1">{{ $proker->description ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-xs font-medium text-slate-600">
                            {{ $proker->start_date ? \Carbon\Carbon::parse($proker->start_date)->translatedFormat('d M Y') : '-' }}<br>
                            s/d<br>
                            {{ $proker->end_date ? \Carbon\Carbon::parse($proker->end_date)->translatedFormat('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($proker->status === 'completed')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-green-50 text-green-700 border border-green-200">
                                    <i class="ph-bold ph-check-circle mr-1"></i>Selesai
                                </span>
                            @elseif($proker->status === 'cancelled')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-red-50 text-red-700 border border-red-200">
                                    <i class="ph-bold ph-x-circle mr-1"></i>Dibatalkan
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                    <i class="ph-bold ph-minus-circle mr-1"></i>Ditutup (Tidak Selesai)
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($proker->pic)
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center font-bold text-[10px] shrink-0">
                                        {{ strtoupper(substr($proker->pic->name, 0, 1)) }}
                                    </div>
                                    <span class="text-xs font-bold text-slate-700 truncate max-w-[120px]">{{ $proker->pic->name }}</span>
                                </div>
                            @else
                                <span class="text-xs text-slate-400 italic">Belum ditentukan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('super_admin.periods.performance.proker', ['period' => $period->id, 'proker' => $proker->id]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 hover:border-brand-300 hover:text-brand-600 text-slate-600 text-xs font-bold rounded-lg transition-colors">
                                <span>Detail Tugas</span>
                                <i class="ph-bold ph-arrow-right"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="ph-fill ph-folder-dashed text-2xl text-slate-400"></i>
                            </div>
                            <div class="text-sm font-bold text-slate-900 mb-1">Tidak Ada Program Kerja</div>
                            <div class="text-xs text-slate-500">Divisi ini tidak memiliki catatan program kerja pada periode tersebut.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
