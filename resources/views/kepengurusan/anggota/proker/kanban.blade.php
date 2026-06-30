@extends('layouts.dashboard')

@section('title', 'Proker Tanggung Jawab Saya')

@section('breadcrumbs')
    <span class="text-slate-700">Program Kerja Non-Event</span>
@endsection

@section('content')

    <!-- Greeting -->
    <div class="space-y-6">
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-brand-500 uppercase tracking-widest mb-1">Anggota Divisi</p>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">
                Tanggung Jawab <span class="gradient-text">Proker</span>
            </h1>
            <p class="text-sm text-slate-500 mt-1.5 font-medium">Pantau dan kelola laporan progres program kerja yang menjadi tanggung jawab Anda.</p>
        </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center">
                    <i class="ph-bold ph-folder text-brand-600 text-xl"></i>
                </div>
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Proker</h3>
            </div>
            <p class="text-3xl font-black text-slate-900">{{ $totalProkers }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center">
                    <i class="ph-bold ph-calendar-blank text-slate-600 text-xl"></i>
                </div>
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Planning</h3>
            </div>
            <p class="text-3xl font-black text-slate-900">{{ $planningProkers }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <i class="ph-bold ph-spinner text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Ongoing</h3>
            </div>
            <p class="text-3xl font-black text-slate-900">{{ $ongoingProkers }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <i class="ph-bold ph-check-circle text-emerald-600 text-xl"></i>
                </div>
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Completed</h3>
            </div>
            <p class="text-3xl font-black text-slate-900">{{ $completedProkers }}</p>
        </div>
    </div>

    <!-- Daftar Proker -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden w-full mb-6">
        <div class="p-6 sm:p-8 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                <i class="ph-bold ph-list-dashes text-brand-500"></i>
                Daftar Program Kerja
            </h3>
        </div>
        <div class="p-6 sm:p-8 bg-slate-50">
            @if(isset($prokers) && $prokers->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($prokers as $proker)
                        <div class="bg-white border border-slate-200 rounded-2xl p-5 hover:border-brand-500 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 cursor-pointer flex flex-col justify-between h-full group" onclick="window.location.href='{{ route('kepengurusan.anggota.proker.show', $proker->id) }}'">
                            <div>
                                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                        @if($proker->status == 'planning') bg-slate-100 text-slate-600
                                        @elseif($proker->status == 'ongoing') bg-blue-100 text-blue-600
                                        @elseif($proker->status == 'completed') bg-emerald-100 text-emerald-600
                                        @elseif($proker->status == 'cancelled') bg-rose-100 text-rose-600
                                        @endif
                                    ">
                                        {{ $proker->status }}
                                    </span>
                                </div>
                                <h4 class="text-base font-black text-slate-900 mb-2 group-hover:text-brand-600 transition-colors">{{ $proker->name }}</h4>
                                <p class="text-xs text-slate-500 line-clamp-2 mb-4">{{ $proker->description }}</p>
                            </div>
                            
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Progres</span>
                                    <span class="text-xs font-black {{ $proker->progress_percentage == 100 ? 'text-emerald-500' : 'text-brand-600' }}">
                                        {{ $proker->progress_percentage }}%
                                    </span>
                                </div>
                                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full {{ $proker->progress_percentage == 100 ? 'bg-emerald-500' : 'bg-brand-500' }} rounded-full" style="width: {{ $proker->progress_percentage }}%"></div>
                                </div>
                                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                    <span class="text-[10px] font-bold text-slate-400 flex items-center gap-1">
                                        <i class="ph-bold ph-calendar-blank"></i>
                                        {{ $proker->start_date ? \Carbon\Carbon::parse($proker->start_date)->format('d M Y') : '-' }}
                                    </span>
                                    <i class="ph-bold ph-arrow-right text-slate-300 group-hover:text-brand-500 group-hover:translate-x-1 transition-all"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 px-4 bg-white rounded-2xl border border-dashed border-slate-200">
                    <div class="w-16 h-16 rounded-full bg-slate-50 border border-slate-100 shadow-sm flex items-center justify-center mx-auto mb-4">
                        <i class="ph-fill ph-folder-dashed text-slate-300 text-3xl"></i>
                    </div>
                    <h4 class="text-base font-bold text-slate-900 mb-2">Belum Ada Proker</h4>
                    <p class="text-sm text-slate-500 font-medium max-w-sm mx-auto">Anda belum ditugaskan sebagai Penanggung Jawab (PIC) untuk program kerja non-event apapun.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
