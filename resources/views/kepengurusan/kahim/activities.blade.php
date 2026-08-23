@extends('layouts.dashboard')

@section('title', 'Aktivitas Divisi')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Timeline Aktivitas Divisi</h1>
        <p class="text-sm text-slate-500 mt-1">Pantau seluruh pembaruan tugas dan kinerja dari staf kepengurusan.</p>
    </div>
    
    <form action="{{ route('kepengurusan.kahim.activities') }}" method="GET" class="flex items-center gap-2">
        <select name="division_id" onchange="this.form.submit()" class="text-sm rounded-xl border-slate-200 focus:border-brand-500 focus:ring focus:ring-brand-500/20 shadow-sm py-2 px-3">
            <option value="">-- Semua Divisi --</option>
            @foreach($divisions as $div)
                <option value="{{ $div->id }}" {{ request('division_id') == $div->id ? 'selected' : '' }}>{{ $div->name }}</option>
            @endforeach
        </select>
        @if(request()->filled('division_id'))
        <a href="{{ route('kepengurusan.kahim.activities') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-bold shadow-sm transition-colors">
            <i class="ph-bold ph-x"></i>
        </a>
        @endif
    </form>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-8 w-full">
    
    <div class="space-y-8">
        @forelse($activities as $log)
            <div class="flex gap-4 group">
                <div class="shrink-0 relative">
                    <div class="w-12 h-12 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center font-bold text-lg z-10 relative group-hover:scale-110 transition-transform overflow-hidden border-2 border-white shadow-sm">
                        @if($log->author->avatar)
                            @php
                                $avatarPath = ltrim($log->author->avatar, '/');
                                $avatarUrl = $avatarPath ? asset('storage/' . $avatarPath) : null;
                            @endphp
                            @if($avatarUrl)
                                <img src="{{ $avatarUrl }}" alt="{{ $log->author->name }}" class="w-full h-full object-cover">
                            @else
                                {{ substr($log->author->name, 0, 1) }}
                            @endif
                        @else
                            {{ substr($log->author->name, 0, 1) }}
                        @endif
                    </div>
                    @if(!$loop->last)
                    <div class="absolute top-12 left-1/2 -translate-x-1/2 w-0.5 h-[calc(100%+2rem)] bg-slate-100"></div>
                    @endif
                </div>
                <div class="pb-4 w-full">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2">
                        <div>
                            <p class="text-base text-slate-800">
                                <span class="font-bold text-slate-900">{{ $log->author->name }}</span> 
                                memperbarui progres pada proker <span class="font-bold text-slate-900">"{{ $log->workProgram->name }}"</span>
                            </p>
                            <p class="text-xs font-bold text-slate-400 mt-1">
                                <span class="text-brand-600">{{ $log->workProgram->division->name }}</span> • {{ $log->created_at->translatedFormat('d M Y, H:i') }} ({{ $log->created_at->diffForHumans() }})
                            </p>
                        </div>
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-sm font-black whitespace-nowrap self-start">Progres: {{ $log->progress_update }}%</span>
                    </div>
                    
                    <div class="mt-3 p-4 bg-slate-50 rounded-xl border border-slate-100 text-sm text-slate-700 shadow-inner">
                        {!! nl2br(e($log->content)) !!}
                    </div>

                    @if($log->attachment || $log->link)
                    <div class="mt-3 flex flex-wrap gap-2">
                        @if($log->attachment)
                        <a href="{{ Storage::url($log->attachment) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-600 hover:text-brand-600 hover:border-brand-300 transition-colors">
                            <i class="ph-bold ph-paperclip"></i> Lampiran File
                        </a>
                        @endif
                        
                        @if($log->link)
                        <a href="{{ $log->link }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-600 hover:text-brand-600 hover:border-brand-300 transition-colors">
                            <i class="ph-bold ph-link"></i> Tautan Terkait
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ph-bold ph-clock text-3xl text-slate-400"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-700">Belum Ada Aktivitas</h3>
                <p class="text-sm font-medium text-slate-500 mt-1">Pembaruan proker dari staf akan muncul di sini.</p>
            </div>
        @endforelse
    </div>

    @if($activities->hasPages())
    <div class="mt-8 pt-6 border-t border-slate-100">
        {{ $activities->links() }}
    </div>
    @endif
</div>
@endsection
