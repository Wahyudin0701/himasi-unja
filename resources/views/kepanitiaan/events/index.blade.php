@extends('layouts.dashboard')

@section('title', 'Events')
@section('breadcrumbs')
    <span class="text-slate-300">/</span>
    <span class="text-slate-800">Events</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header Area -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Event & Kepanitiaan</h1>
            <p class="text-slate-500 mt-1">Kelola seluruh event dan kepanitiaan HIMASI</p>
        </div>
        <a href="{{ route('events.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-medium transition-colors shadow-sm shadow-brand-500/20">
            <i class="ph-bold ph-plus"></i>
            Buat Event Baru
        </a>
    </div>

    <!-- Filters -->
    <div class="flex items-center gap-2 pb-2 overflow-x-auto">
        <a href="{{ route('events.index') }}" class="px-4 py-2 rounded-xl text-sm font-medium whitespace-nowrap transition-colors {{ !$status ? 'bg-slate-900 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">Semua Event</a>
        <a href="{{ route('events.index', ['status' => 'planning']) }}" class="px-4 py-2 rounded-xl text-sm font-medium whitespace-nowrap transition-colors {{ $status == 'planning' ? 'bg-amber-500 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">Planning</a>
        <a href="{{ route('events.index', ['status' => 'ongoing']) }}" class="px-4 py-2 rounded-xl text-sm font-medium whitespace-nowrap transition-colors {{ $status == 'ongoing' ? 'bg-brand-500 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">Ongoing</a>
        <a href="{{ route('events.index', ['status' => 'completed']) }}" class="px-4 py-2 rounded-xl text-sm font-medium whitespace-nowrap transition-colors {{ $status == 'completed' ? 'bg-emerald-500 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">Completed</a>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($events as $event)
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:shadow-slate-200/40 transition-all duration-300 group flex flex-col">
                <div class="p-6 flex-1">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 rounded-xl bg-brand-50 flex items-center justify-center text-brand-600 mb-4 group-hover:scale-110 transition-transform">
                            <i class="ph-fill ph-ticket text-2xl"></i>
                        </div>
                        @if($event->status == 'planning')
                            <span class="px-2.5 py-1 rounded-md bg-amber-50 text-amber-600 text-xs font-semibold uppercase tracking-wider border border-amber-200">Planning</span>
                        @elseif($event->status == 'ongoing')
                            <span class="px-2.5 py-1 rounded-md bg-brand-50 text-brand-600 text-xs font-semibold uppercase tracking-wider border border-brand-200">Ongoing</span>
                        @else
                            <span class="px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-600 text-xs font-semibold uppercase tracking-wider border border-emerald-200">Completed</span>
                        @endif
                    </div>
                    
                    <h3 class="text-lg font-bold text-slate-900 leading-tight mb-2 group-hover:text-brand-600 transition-colors line-clamp-2">{{ $event->name }}</h3>
                    <p class="text-sm text-slate-500 line-clamp-2 mb-4">{{ $event->description }}</p>
                    
                    <div class="space-y-2 mt-auto">
                        <div class="flex items-center gap-2 text-sm text-slate-600">
                            <i class="ph ph-calendar-blank text-slate-400"></i>
                            {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d M Y') }}
                            @if($event->end_date)
                             - {{ \Carbon\Carbon::parse($event->end_date)->translatedFormat('d M Y') }}
                            @endif
                        </div>
                        @if($event->location)
                        <div class="flex items-center gap-2 text-sm text-slate-600">
                            <i class="ph ph-map-pin text-slate-400"></i>
                            <span class="truncate">{{ $event->location }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end">
                    <a href="{{ route('events.show', $event->id) }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700 flex items-center gap-1">
                        Lihat Dashboard
                        <i class="ph-bold ph-arrow-right"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-slate-200 border-dashed p-12 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 mb-4">
                    <i class="ph ph-calendar-x text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">Belum ada Event</h3>
                <p class="text-slate-500 max-w-sm mb-6">Mulai rencanakan kegiatan HIMASI dengan membuat event pertama Anda.</p>
                <a href="{{ route('events.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-medium transition-colors">
                    <i class="ph-bold ph-plus"></i>
                    Buat Event Baru
                </a>
            </div>
        @endforelse
    </div>
    
    @if($events->hasPages())
        <div class="mt-6">
            {{ $events->links() }}
        </div>
    @endif
</div>
@endsection
