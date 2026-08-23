@extends('layouts.dashboard')

@section('title', 'Agenda Himpunan')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Agenda Himpunan</h1>
        <p class="text-sm text-slate-500 mt-1">Daftar lengkap seluruh agenda kepanitiaan dan program kerja himpunan.</p>
    </div>
    
    <form action="{{ route('kepengurusan.kahim.agendas') }}" method="GET" class="flex items-center gap-2">
        <select name="status" onchange="this.form.submit()" class="text-sm rounded-xl border-slate-200 focus:border-brand-500 focus:ring focus:ring-brand-500/20 shadow-sm py-2 px-3">
            <option value="">-- Semua Status --</option>
            <option value="planning" {{ request('status') == 'planning' ? 'selected' : '' }}>Persiapan (Planning)</option>
            <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Berjalan (Ongoing)</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan (Cancelled)</option>
        </select>
        @if(request()->filled('status'))
        <a href="{{ route('kepengurusan.kahim.agendas') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-bold shadow-sm transition-colors" title="Hapus Filter">
            <i class="ph-bold ph-x"></i>
        </a>
        @endif
    </form>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8 w-full">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-black">
                    <th class="px-6 py-4">Nama Agenda / Acara</th>
                    <th class="px-6 py-4">Tanggal Pelaksanaan</th>
                    <th class="px-6 py-4">Lokasi</th>
                    <th class="px-6 py-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($agendas as $agenda)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $agenda->name }}</div>
                            @if($agenda->description)
                            <div class="text-xs text-slate-500 mt-1 line-clamp-1">{{ $agenda->description }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-slate-700 font-medium">
                            <div class="flex items-center gap-2">
                                <i class="ph-bold ph-calendar-blank text-slate-400"></i>
                                {{ \Carbon\Carbon::parse($agenda->event_date)->translatedFormat('d M Y') }}
                                @if($agenda->end_date)
                                    - {{ \Carbon\Carbon::parse($agenda->end_date)->translatedFormat('d M Y') }}
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-700">
                            @if($agenda->location)
                                <div class="flex items-center gap-2">
                                    <i class="ph-bold ph-map-pin text-slate-400"></i>
                                    <span class="line-clamp-1">{{ $agenda->location }}</span>
                                </div>
                            @else
                                <span class="text-slate-400 italic text-xs">Belum ditentukan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($agenda->status === 'completed')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-black bg-brand-100 text-brand-700 uppercase">
                                    <i class="ph-bold ph-check-circle"></i> Selesai
                                </span>
                            @elseif($agenda->status === 'ongoing')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-black bg-emerald-100 text-emerald-700 uppercase">
                                    <i class="ph-bold ph-spinner-gap animate-spin"></i> Berjalan
                                </span>
                            @elseif($agenda->status === 'cancelled')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-black bg-red-100 text-red-700 uppercase">
                                    <i class="ph-bold ph-x-circle"></i> Batal
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-black bg-amber-100 text-amber-700 uppercase">
                                    <i class="ph-bold ph-clock"></i> Persiapan
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="ph-bold ph-calendar-x text-3xl text-slate-400"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-700">Tidak Ada Agenda</h3>
                            <p class="text-sm font-medium text-slate-500 mt-1">Belum ada acara yang dijadwalkan pada filter ini.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($agendas->hasPages())
    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
        {{ $agendas->links() }}
    </div>
    @endif
</div>
@endsection
