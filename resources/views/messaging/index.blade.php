@extends('layouts.dashboard')

@section('title', 'Pesan')

@section('breadcrumbs')
    <span class="text-slate-700">Pesan</span>
@endsection

@section('content')

@php
    $totalUnread = $channels->sum('unread_count');

    $channelGroups = [
        'pembina_pimpinan' => [
            'label' => 'Pembina & Pimpinan',
            'icon'  => 'ph-chalkboard-teacher',
            'color' => 'text-amber-500',
            'bg'    => 'bg-amber-50',
        ],
        'dp_pimpinan' => [
            'label' => 'Dewan Pengawas & Pimpinan',
            'icon'  => 'ph-shield-check',
            'color' => 'text-emerald-500',
            'bg'    => 'bg-emerald-50',
        ],
        'pimpinan_kadiv' => [
            'label' => 'Pimpinan & Kepala Divisi',
            'icon'  => 'ph-crown',
            'color' => 'text-brand-500',
            'bg'    => 'bg-brand-50',
        ],
        'kadiv_anggota' => [
            'label' => 'Divisi',
            'icon'  => 'ph-users-three',
            'color' => 'text-sky-500',
            'bg'    => 'bg-sky-50',
        ],
    ];

    $grouped = $channels->groupBy('type');
@endphp

{{-- ===== HEADER ===== --}}
<div class="mb-8">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-xs font-semibold text-brand-500 uppercase tracking-widest mb-1">Komunikasi</p>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">
                Pesan
            </h1>
            <p class="text-sm text-slate-500 mt-1.5 font-medium">
                @if($totalUnread > 0)
                    Anda memiliki <span class="text-brand-600 font-bold">{{ $totalUnread }}</span> pesan belum dibaca.
                @else
                    Semua pesan telah dibaca.
                @endif
            </p>
        </div>

        @if($totalUnread > 0)
            <div class="hidden sm:flex items-center gap-2 bg-rose-50 text-rose-600 rounded-xl px-4 py-2.5 text-sm font-bold border border-rose-100">
                <i class="ph-fill ph-envelope-simple text-base"></i>
                {{ $totalUnread }} Belum Dibaca
            </div>
        @endif
    </div>
</div>

{{-- ===== CHANNEL LIST ===== --}}
@if($channels->isEmpty())
    {{-- Empty State --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm px-6 py-16">
        <div class="flex flex-col items-center justify-center">
            <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mb-5">
                <i class="ph-fill ph-chat-circle-dots text-4xl text-slate-300"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-1.5">Belum Ada Channel</h3>
            <p class="text-sm text-slate-500 font-medium max-w-sm text-center">
                Anda belum tergabung dalam channel pesan apapun. Channel akan muncul sesuai peran Anda di organisasi.
            </p>
        </div>
    </div>
@else
    <div class="space-y-8">
        @foreach($channelGroups as $type => $meta)
            @if($grouped->has($type))
                {{-- Section Header --}}
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl {{ $meta['bg'] }} flex items-center justify-center shrink-0">
                            <i class="ph-bold {{ $meta['icon'] }} text-lg {{ $meta['color'] }}"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-extrabold text-slate-800 uppercase tracking-wide">{{ $meta['label'] }}</h2>
                            <p class="text-xs text-slate-400 font-medium">{{ $grouped[$type]->count() }} channel</p>
                        </div>
                    </div>

                    {{-- Channel Cards --}}
                    <div class="space-y-3">
                        @foreach($grouped[$type] as $channel)
                            <a href="{{ route('messages.show', $channel->id) }}"
                               class="group block bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-brand-200 transition-all duration-200 overflow-hidden">
                                <div class="flex items-center gap-4 p-5">
                                    {{-- Channel Icon --}}
                                    <div class="w-12 h-12 rounded-2xl {{ $meta['bg'] }} flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform duration-200">
                                        <i class="ph-fill {{ $meta['icon'] }} text-2xl {{ $meta['color'] }}"></i>
                                    </div>

                                    {{-- Channel Info --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h3 class="text-sm font-bold text-slate-900 truncate group-hover:text-brand-600 transition-colors">
                                                {{ $channel->name }}
                                            </h3>
                                        </div>

                                        @if($channel->latestMessage)
                                            <p class="text-xs text-slate-500 font-medium truncate">
                                                <span class="font-semibold text-slate-600">{{ $channel->latestMessage->sender->name ?? 'Sistem' }}:</span>
                                                {{ Str::limit($channel->latestMessage->body, 50) }}
                                            </p>
                                        @else
                                            <p class="text-xs text-slate-400 font-medium italic">Belum ada pesan</p>
                                        @endif
                                    </div>

                                    {{-- Right side: timestamp, unread, members --}}
                                    <div class="flex flex-col items-end gap-2 shrink-0">
                                        @if($channel->latestMessage)
                                            <span class="text-[11px] text-slate-400 font-medium whitespace-nowrap">
                                                {{ $channel->latestMessage->created_at->diffForHumans(null, false, true) }}
                                            </span>
                                        @endif

                                        <div class="flex items-center gap-2">
                                            {{-- Members count --}}
                                            <span class="inline-flex items-center gap-1 text-[11px] text-slate-400 font-medium">
                                                <i class="ph-bold ph-users text-xs"></i>
                                                {{ $channel->members_count ?? $channel->members()->count() }}
                                            </span>

                                            {{-- Unread badge --}}
                                            @if($channel->unread_count > 0)
                                                <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full bg-rose-500 text-white text-[10px] font-extrabold shadow-sm shadow-rose-500/30">
                                                    {{ $channel->unread_count > 99 ? '99+' : $channel->unread_count }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endif

@endsection
