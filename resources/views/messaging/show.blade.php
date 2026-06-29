@extends('layouts.dashboard')

@section('title', $channel->name)

@section('breadcrumbs')
    <a href="{{ route('messages.index') }}" class="text-slate-500 hover:text-brand-600 transition-colors">Pesan</a>
    <i class="ph-bold ph-caret-right text-slate-400 text-xs"></i>
    <span class="text-slate-700">{{ $channel->name }}</span>
@endsection

@section('content')

@php
    $typeLabels = [
        'pembina_pimpinan' => ['label' => 'Pembina & Pimpinan', 'icon' => 'ph-chalkboard-teacher', 'color' => 'text-amber-600', 'bg' => 'bg-amber-50', 'border' => 'border-amber-200'],
        'dp_pimpinan'      => ['label' => 'Dewan Pengawas & Pimpinan', 'icon' => 'ph-shield-check', 'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50', 'border' => 'border-emerald-200'],
        'pimpinan_kadiv'   => ['label' => 'Pimpinan & Kepala Divisi', 'icon' => 'ph-crown', 'color' => 'text-brand-600', 'bg' => 'bg-brand-50', 'border' => 'border-brand-200'],
        'kadiv_anggota'    => ['label' => 'Divisi', 'icon' => 'ph-users-three', 'color' => 'text-sky-600', 'bg' => 'bg-sky-50', 'border' => 'border-sky-200'],
    ];
    $typeMeta = $typeLabels[$channel->type] ?? ['label' => 'Channel', 'icon' => 'ph-chat-circle', 'color' => 'text-slate-600', 'bg' => 'bg-slate-50', 'border' => 'border-slate-200'];
@endphp

<div class="flex flex-col h-[calc(100vh-180px)] min-h-[400px]" x-data="chatRoom()">

    {{-- ===== TOP BAR ===== --}}
    <div class="bg-white rounded-t-2xl border border-slate-200 border-b-0 shadow-sm px-5 py-4 shrink-0">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl {{ $typeMeta['bg'] }} flex items-center justify-center shrink-0">
                    <i class="ph-fill {{ $typeMeta['icon'] }} text-xl {{ $typeMeta['color'] }}"></i>
                </div>
                <div class="min-w-0">
                    <h2 class="text-base font-bold text-slate-900 truncate">{{ $channel->name }}</h2>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $typeMeta['bg'] }} {{ $typeMeta['color'] }} {{ $typeMeta['border'] }} border">
                            <i class="ph-bold {{ $typeMeta['icon'] }} text-[10px]"></i>
                            {{ $typeMeta['label'] }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Members Toggle --}}
            <button @click="showMembers = !showMembers"
                    class="flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-brand-600 hover:bg-brand-50 px-3 py-2 rounded-xl transition-all duration-200">
                <i class="ph-bold ph-users text-base"></i>
                <span>{{ $members->count() }}</span>
                <i class="ph-bold ph-caret-down text-xs transition-transform duration-200" :class="showMembers && 'rotate-180'"></i>
            </button>
        </div>

        {{-- Members List (collapsible) --}}
        <div x-show="showMembers" x-collapse x-cloak>
            <div class="mt-4 pt-4 border-t border-slate-100">
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-3">Anggota Channel</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($members as $member)
                        <div class="inline-flex items-center gap-2 bg-slate-50 rounded-xl px-3 py-2 border border-slate-100">
                            <div class="w-7 h-7 rounded-full bg-brand-500 flex items-center justify-center shrink-0">
                                <span class="text-[10px] font-bold text-white">{{ strtoupper(substr($member->name, 0, 2)) }}</span>
                            </div>
                            <span class="text-xs font-semibold text-slate-700">{{ $member->name }}</span>
                            @if($member->id === auth()->id())
                                <span class="text-[9px] font-bold text-brand-500 bg-brand-50 px-1.5 py-0.5 rounded-full">Anda</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ===== MESSAGES AREA ===== --}}
    <div id="messages-container"
         class="flex-1 overflow-y-auto bg-slate-50 border-x border-slate-200 px-4 sm:px-6 py-6 space-y-1"
         style="scroll-behavior: smooth;">

        @if($messages->isEmpty())
            <div class="flex flex-col items-center justify-center h-full">
                <div class="w-16 h-16 rounded-full bg-white border border-slate-200 flex items-center justify-center mb-4">
                    <i class="ph-fill ph-chat-circle-dots text-3xl text-slate-300"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-700 mb-1">Belum ada pesan</h3>
                <p class="text-xs text-slate-400 font-medium">Mulai percakapan dengan mengirim pesan pertama.</p>
            </div>
        @else
            @php $lastDate = null; @endphp

            @foreach($messages as $msg)
                @php
                    $currentDate = $msg->created_at->format('Y-m-d');
                    $isOwn = $msg->sender_id === auth()->id();
                @endphp

                {{-- Date Separator --}}
                @if($currentDate !== $lastDate)
                    <div class="flex items-center gap-3 py-4">
                        <div class="flex-1 h-px bg-slate-200"></div>
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap px-1">
                            @if($msg->created_at->isToday())
                                Hari Ini
                            @elseif($msg->created_at->isYesterday())
                                Kemarin
                            @else
                                {{ $msg->created_at->translatedFormat('d F Y') }}
                            @endif
                        </span>
                        <div class="flex-1 h-px bg-slate-200"></div>
                    </div>
                    @php $lastDate = $currentDate; @endphp
                @endif

                {{-- Message Bubble --}}
                <div class="flex {{ $isOwn ? 'justify-end' : 'justify-start' }} mb-1 group">
                    <div class="max-w-[80%] sm:max-w-[70%]">
                        {{-- Sender name (for others) --}}
                        @if(!$isOwn)
                            <p class="text-[11px] font-semibold text-slate-500 mb-1 ml-3">{{ $msg->sender->name ?? 'Unknown' }}</p>
                        @endif

                        {{-- Bubble --}}
                        <div class="{{ $isOwn
                                ? 'bg-brand-500 text-white rounded-2xl rounded-br-md'
                                : 'bg-white border border-slate-200 text-slate-800 rounded-2xl rounded-bl-md shadow-sm' }}
                             px-4 py-2.5 transition-all duration-100">

                            {{-- Message body --}}
                            @if($msg->body)
                                <p class="text-sm font-medium leading-relaxed whitespace-pre-wrap break-words">{{ $msg->body }}</p>
                            @endif

                            {{-- Attachment --}}
                            @if($msg->attachment_path)
                                <a href="{{ route('messages.download', $msg->id) }}"
                                   class="mt-2 flex items-center gap-2 px-3 py-2 rounded-xl transition-colors
                                          {{ $isOwn
                                              ? 'bg-brand-600/40 hover:bg-brand-600/60 text-white'
                                              : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200' }}">
                                    <i class="ph-fill ph-file-arrow-down text-lg shrink-0"></i>
                                    <span class="text-xs font-semibold truncate">{{ $msg->attachment_name ?? 'File lampiran' }}</span>
                                </a>
                            @endif
                        </div>

                        {{-- Timestamp & Read receipt --}}
                        <div class="flex items-center gap-1.5 mt-1 {{ $isOwn ? 'justify-end mr-1' : 'ml-3' }}">
                            <span class="text-[10px] text-slate-400 font-medium">
                                {{ $msg->created_at->format('H:i') }}
                            </span>
                            @if($isOwn)
                                @if($msg->reads && $msg->reads->count() > 0)
                                    <span class="text-[10px] text-brand-400 font-semibold flex items-center gap-0.5">
                                        <i class="ph-fill ph-checks text-xs"></i>
                                        Dibaca {{ $msg->reads->count() }} orang
                                    </span>
                                @else
                                    <span class="text-[10px] text-slate-300 flex items-center gap-0.5">
                                        <i class="ph-bold ph-check text-xs"></i>
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- ===== MESSAGE INPUT FORM ===== --}}
    <div class="bg-white rounded-b-2xl border border-slate-200 border-t-0 shadow-sm px-4 sm:px-5 py-4 shrink-0">
        @if($errors->any())
            <div class="mb-3 p-3 rounded-xl bg-rose-50 border border-rose-200">
                <p class="text-xs font-semibold text-rose-600">
                    <i class="ph-fill ph-warning-circle"></i>
                    {{ $errors->first() }}
                </p>
            </div>
        @endif

        <form action="{{ route('messages.store', $channel->id) }}" method="POST" enctype="multipart/form-data"
              class="flex items-end gap-3">
            @csrf

            {{-- Attachment button --}}
            <div class="shrink-0 relative">
                <input type="file" name="attachment" id="file-attachment" class="hidden"
                       @change="handleFileSelect($event)">
                <button type="button"
                        @click="$refs.fileInput ? $refs.fileInput.click() : document.getElementById('file-attachment').click()"
                        class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-brand-600 flex items-center justify-center transition-all duration-200"
                        title="Lampirkan file">
                    <i class="ph-bold ph-paperclip text-lg"></i>
                </button>
            </div>

            {{-- Text area --}}
            <div class="flex-1 min-w-0">
                {{-- Filename preview --}}
                <div x-show="fileName" x-cloak
                     class="mb-2 flex items-center gap-2 text-xs font-semibold text-brand-600 bg-brand-50 border border-brand-100 rounded-lg px-3 py-1.5">
                    <i class="ph-fill ph-file text-sm"></i>
                    <span class="truncate" x-text="fileName"></span>
                    <button type="button" @click="clearFile()" class="ml-auto text-brand-400 hover:text-rose-500 transition-colors">
                        <i class="ph-bold ph-x text-xs"></i>
                    </button>
                </div>

                <textarea name="body"
                          id="message-input"
                          rows="1"
                          placeholder="Ketik pesan..."
                          class="w-full resize-none bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400
                                 focus:outline-none focus:border-brand-400 focus:ring-2 focus:ring-brand-100 transition-all duration-200
                                 max-h-32 overflow-y-auto"
                          @input="autoResize($event)"
                          @keydown.enter.prevent="if (!$event.shiftKey) $event.target.closest('form').submit()"></textarea>
            </div>

            {{-- Send button --}}
            <button type="submit"
                    class="w-10 h-10 rounded-xl bg-brand-500 hover:bg-brand-600 text-white flex items-center justify-center transition-all duration-200 shadow-sm shadow-brand-500/25 hover:shadow-md hover:shadow-brand-500/30 active:scale-95 shrink-0">
                <i class="ph-fill ph-paper-plane-tilt text-lg"></i>
            </button>
        </form>
    </div>
</div>

@endsection

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@push('scripts')
<script>
    function chatRoom() {
        return {
            showMembers: false,
            fileName: '',

            init() {
                this.$nextTick(() => {
                    this.scrollToBottom();
                });
            },

            scrollToBottom() {
                const container = document.getElementById('messages-container');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            },

            autoResize(event) {
                const el = event.target;
                el.style.height = 'auto';
                el.style.height = Math.min(el.scrollHeight, 128) + 'px';
            },

            handleFileSelect(event) {
                const file = event.target.files[0];
                this.fileName = file ? file.name : '';
            },

            clearFile() {
                this.fileName = '';
                const input = document.getElementById('file-attachment');
                if (input) input.value = '';
            }
        }
    }

    // Auto-scroll on page load
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }

        // Focus textarea
        const input = document.getElementById('message-input');
        if (input) input.focus();
    });
</script>
@endpush
