@extends('layouts.dashboard')

@section('title', 'Kinerja Divisi - ' . $period->name)

@section('breadcrumbs')
    <a href="{{ route('super_admin.dashboard') }}" class="text-slate-500 hover:text-brand-600 transition-colors">Super Admin</a>
    <i class="ph-bold ph-caret-right text-slate-400 text-xs"></i>
    <a href="{{ route('super_admin.periods.index') }}" class="text-slate-500 hover:text-brand-600 transition-colors">Manajemen Periode</a>
    <i class="ph-bold ph-caret-right text-slate-400 text-xs"></i>
    <a href="{{ route('super_admin.periods.show', $period->id) }}" class="text-slate-500 hover:text-brand-600 transition-colors">Periode {{ $period->name }}</a>
    <i class="ph-bold ph-caret-right text-slate-400 text-xs"></i>
    <span class="text-slate-700">Kinerja Divisi</span>
@endsection

@section('content')
<div class="mb-8 flex items-start justify-between">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">Kinerja & Riwayat Proker Divisi</h1>
        <p class="text-sm text-slate-500 mt-2 font-medium leading-relaxed max-w-2xl">
            Pilih divisi di bawah ini untuk melihat daftar Program Kerja (Proker) yang telah mereka laksanakan beserta riwayat penugasan (kanban) di periode {{ $period->name }}.
        </p>
    </div>
    <a href="{{ route('super_admin.periods.show', $period->id) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-brand-600 transition-colors mt-1.5 shrink-0">
        <i class="ph-bold ph-arrow-left"></i>
        Kembali
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($divisions as $div)
    <a href="{{ route('super_admin.periods.performance.division', ['period' => $period->id, 'division' => $div->id]) }}" class="group bg-white rounded-3xl border border-slate-200 shadow-sm hover:border-amber-300 hover:shadow-md transition-all overflow-hidden flex flex-col relative">
        <div class="p-6 sm:p-8 flex-1">
            <div class="w-14 h-14 rounded-2xl bg-slate-50 border border-slate-100 text-slate-600 flex items-center justify-center mb-5 group-hover:bg-amber-50 group-hover:text-amber-600 group-hover:border-amber-200 transition-all">
                <i class="ph-fill ph-users-three text-2xl"></i>
            </div>
            <h3 class="text-lg font-black text-slate-900 mb-1 group-hover:text-brand-600 transition-colors">{{ $div->name }}</h3>
            <p class="text-xs font-medium text-slate-500 line-clamp-2">{{ $div->description ?? 'Tidak ada deskripsi.' }}</p>
        </div>
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 text-xs font-bold text-slate-500 flex items-center justify-between group-hover:bg-amber-50 group-hover:text-amber-700 transition-colors">
            <span>Lihat Riwayat Proker</span>
            <i class="ph-bold ph-arrow-right"></i>
        </div>
    </a>
    @endforeach
</div>

@if($divisions->isEmpty())
<div class="text-center py-16 bg-white border border-slate-200 rounded-3xl shadow-sm">
    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="ph-fill ph-folder-open text-3xl text-slate-400"></i>
    </div>
    <h3 class="text-lg font-black text-slate-900 mb-2">Belum Ada Divisi</h3>
    <p class="text-sm text-slate-500 max-w-sm mx-auto">Tidak ada data divisi yang ditemukan pada periode kepengurusan ini.</p>
</div>
@endif
@endsection
