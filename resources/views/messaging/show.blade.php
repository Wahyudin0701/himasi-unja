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

    {{-- Back Navigation --}}
    <div class="flex justify-end mb-2 px-1">
        <a href="{{ route('messages.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-brand-600 transition-colors">
            <i class="ph-bold ph-arrow-left text-xs"></i>
            <span>Kembali</span>
        </a>
    </div>

    {{-- ===== TOP BAR ===== --}}
    <div class="bg-white rounded-t-2xl border border-slate-200 border-b-0 shadow-sm px-5 py-4 shrink-0">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 min-w-0">
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

            {{-- Simple Members Toggle Icon --}}
            <div class="flex items-center gap-2">
                <button @click="showMembers = !showMembers"
                        class="group flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-100 hover:border-brand-200 hover:bg-brand-50 hover:text-brand-600 text-slate-500 transition-all duration-200"
                        title="Lihat Anggota Channel">
                    <i class="ph-fill ph-users text-lg"></i>
                    <span class="text-xs font-semibold">{{ $members->count() }}</span>
                </button>
            </div>
        </div>

        {{-- Members Modal --}}
        <div x-show="showMembers" 
             style="display: none;"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" 
             x-cloak>
            
            {{-- Backdrop --}}
            <div x-show="showMembers" 
                 x-transition.opacity.duration.300ms
                 class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"
                 @click="showMembers = false"></div>
            
            {{-- Modal Content --}}
            <div x-show="showMembers"
                 x-transition.scale.origin.center.duration.300ms
                 class="relative bg-white rounded-2xl shadow-xl w-full max-w-3xl max-h-[85vh] flex flex-col overflow-hidden">
                
                {{-- Modal Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <i class="ph-fill ph-users text-brand-500 text-lg"></i>
                        Anggota Channel ({{ $members->count() }})
                    </h3>
                    <button @click="showMembers = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-200/50 transition-colors">
                        <i class="ph-bold ph-x text-lg"></i>
                    </button>
                </div>

                {{-- Modal Body (Scrollable) --}}
                <div class="p-6 overflow-y-auto">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($members as $member)
                        <div class="flex items-center gap-3 bg-slate-50 rounded-xl p-2.5 border border-transparent hover:border-slate-200 hover:shadow-sm transition-all duration-200">
                            @if($member->avatar)
                                <img src="{{ asset('storage/' . $member->avatar) }}" alt="{{ $member->name }}" class="w-9 h-9 rounded-full object-cover shadow-sm bg-white shrink-0" onerror="this.outerHTML='<div class=\'w-9 h-9 rounded-full bg-brand-100 flex items-center justify-center shrink-0 shadow-sm\'><span class=\'text-[11px] font-bold text-brand-600\'>{{ strtoupper(substr($member->name, 0, 2)) }}</span></div>'">
                            @else
                                <div class="w-9 h-9 rounded-full bg-brand-100 flex items-center justify-center shrink-0 shadow-sm">
                                    <span class="text-[11px] font-bold text-brand-600">{{ strtoupper(substr($member->name, 0, 2)) }}</span>
                                </div>
                            @endif
                            <div class="flex flex-col min-w-0 flex-1">
                                <span class="text-[12px] font-bold text-slate-700 truncate block">{{ $member->name }}</span>
                                <span class="text-[10px] font-semibold text-slate-400 truncate block uppercase tracking-wide">{{ str_replace('_', ' ', $member->global_role) }}</span>
                            </div>
                            @if($member->id === auth()->id())
                                <span class="text-[9px] font-extrabold text-brand-500 bg-brand-50 px-1.5 py-0.5 rounded-md shrink-0">Anda</span>
                            @endif
                        </div>
                    @endforeach
                    </div>
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
                <div class="flex {{ $isOwn ? 'justify-end' : 'justify-start items-start gap-2.5' }} mb-3 group">
                    {{-- Profile Avatar (for others) --}}
                    @if(!$isOwn)
                        <div class="w-8 h-8 rounded-full flex-shrink-0 bg-brand-500 overflow-hidden ring-1 ring-slate-200 shadow-sm flex items-center justify-center mt-1">
                            @if($msg->sender && $msg->sender->avatar)
                                <img src="{{ asset('storage/' . $msg->sender->avatar) }}" alt="" class="w-full h-full object-cover" onerror="this.outerHTML='<span class=\'text-xs font-bold text-white\'>{{ strtoupper(substr($msg->sender->name ?? 'U', 0, 1)) }}</span>'">
                            @else
                                <span class="text-xs font-bold text-white">{{ strtoupper(substr($msg->sender->name ?? 'U', 0, 1)) }}</span>
                            @endif
                        </div>
                    @endif

                    <div class="max-w-[80%] sm:max-w-[70%]">
                        {{-- Sender name (for others) --}}
                        @if(!$isOwn)
                            <p class="text-[11px] font-semibold text-slate-500 mb-1.5 ml-1">{{ $msg->sender->name ?? 'Unknown' }}</p>
                        @endif

                        {{-- Bubble --}}
                        <div class="{{ $isOwn
                                ? 'bg-brand-500 text-white rounded-2xl rounded-br-md'
                                : 'bg-white border border-slate-200 text-slate-800 rounded-2xl rounded-bl-md shadow-sm' }}
                             px-4 py-2.5 transition-all duration-100">

                            {{-- Message body --}}
                            @if($msg->body)
                                @php
                                    $escapedBody = e($msg->body);
                                    // Use dynamic link color based on message ownership (sender vs receiver background)
                                    $linkColorClass = $isOwn ? 'text-white hover:text-brand-100 underline decoration-white/50 hover:decoration-white' : 'text-brand-600 hover:text-brand-800 underline decoration-brand-600/40 hover:decoration-brand-800';
                                    
                                    // Convert http/https URLs into clickable anchor tags
                                    $linkedBody = preg_replace(
                                        '/(https?:\/\/[^\s]+)/i',
                                        '<a href="$1" target="_blank" rel="noopener noreferrer" class="' . $linkColorClass . ' break-all transition-colors">$1</a>',
                                        $escapedBody
                                    );
                                @endphp
                                <p class="text-sm font-medium leading-relaxed whitespace-pre-wrap break-words">{!! $linkedBody !!}</p>
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

                        {{-- Timestamp --}}
                        <div class="flex items-center gap-1.5 mt-1 {{ $isOwn ? 'justify-end mr-1' : 'ml-3' }}">
                            <span class="text-[10px] text-slate-400 font-medium">
                                {{ $msg->created_at->format('H:i') }}
                            </span>
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
              class="flex items-start gap-3">
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
