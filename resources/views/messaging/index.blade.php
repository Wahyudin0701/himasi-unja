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

        <div class="flex items-center gap-3">
            @if($totalUnread > 0)
                <div class="hidden sm:flex items-center gap-2 bg-rose-50 text-rose-600 rounded-xl px-4 py-2.5 text-sm font-bold border border-rose-100">
                    <i class="ph-fill ph-envelope-simple text-base"></i>
                    {{ $totalUnread }} Belum Dibaca
                </div>
            @endif

            @if(isset($allUsers) && $allUsers->count() > 0)
                <button onclick="openModal('create-channel-modal')" class="flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white rounded-xl px-4 py-2.5 text-sm font-bold transition-colors shadow-sm">
                    <i class="ph-bold ph-plus text-base"></i>
                    Buat Channel
                </button>
            @endif
        </div>
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
                            @if(auth()->user()->global_role === 'super_admin')
                                <div class="group block bg-white rounded-2xl border border-slate-200 shadow-sm transition-all duration-200 overflow-hidden relative">
                            @else
                                <a href="{{ route('messages.show', $channel->id) }}"
                                   class="group block bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-brand-200 transition-all duration-200 overflow-hidden">
                            @endif
                                <div class="flex items-center gap-4 p-5">
                                    {{-- Channel Icon --}}
                                    <div class="w-12 h-12 rounded-2xl {{ $meta['bg'] }} flex items-center justify-center shrink-0 transition-transform duration-200 {{ auth()->user()->global_role !== 'super_admin' ? 'group-hover:scale-105' : '' }}">
                                        <i class="ph-fill {{ $meta['icon'] }} text-2xl {{ $meta['color'] }}"></i>
                                    </div>

                                    {{-- Channel Info --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h3 class="text-sm font-bold text-slate-900 truncate {{ auth()->user()->global_role !== 'super_admin' ? 'group-hover:text-brand-600 transition-colors' : '' }}">
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
                                            @if($channel->unread_count > 0 && auth()->user()->global_role !== 'super_admin')
                                                <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full bg-rose-500 text-white text-[10px] font-extrabold shadow-sm shadow-rose-500/30">
                                                    {{ $channel->unread_count > 99 ? '99+' : $channel->unread_count }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    {{-- Super Admin Actions --}}
                                    @if(auth()->user()->global_role === 'super_admin')
                                        <div class="flex items-center gap-3 border-l border-slate-100 pl-4 ml-2 shrink-0">
                                            <button onclick="openEditModal({{ $channel->id }}, {{ json_encode($channel->members->pluck('id')) }})" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 text-slate-700 text-sm font-bold rounded-xl transition-colors">
                                                <i class="ph-bold ph-pencil-simple text-base"></i> Ganti
                                            </button>
                                            <button onclick="confirmDelete('Hapus Channel', 'Apakah Anda yakin ingin menghapus grup obrolan ini beserta seluruh isi pesannya?', '{{ route('messages.channel.destroy', $channel->id) }}')" class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-50 border border-rose-200 hover:bg-rose-100 hover:border-rose-300 text-rose-600 text-sm font-bold rounded-xl transition-colors">
                                                <i class="ph-bold ph-trash text-base"></i> Hapus
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @if(auth()->user()->global_role === 'super_admin')
                                </div>
                            @else
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endif

@endsection

@push('scripts')
@if(isset($allUsers) && $allUsers->count() > 0)
{{-- Modal Buat Channel Baru --}}
<div id="create-channel-modal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm modal-backdrop" onclick="closeModal('create-channel-modal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[calc(100%-2rem)] md:w-full max-w-3xl bg-white rounded-2xl shadow-xl z-10 flex flex-col max-h-[90vh]">
        <div class="flex items-center justify-between p-6 border-b border-slate-100 shrink-0">
            <div>
                <h3 class="text-lg font-black text-slate-900">Buat Channel Baru</h3>
                <p class="text-sm text-slate-500 font-medium mt-1">Buat grup percakapan baru dengan pengurus lain.</p>
            </div>
            <button onclick="closeModal('create-channel-modal')" class="w-8 h-8 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-500 flex items-center justify-center transition-colors">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>
        
        <form action="{{ route('messages.channel.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="p-6 space-y-6 overflow-y-auto flex-1">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Channel</label>
                    <input type="text" name="name" required placeholder="Contoh: Evaluasi DP & Kahim" 
                           class="w-full rounded-xl border-slate-200 focus:border-brand-500 focus:ring focus:ring-brand-500/20 shadow-sm text-sm px-4 py-2.5">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Kategori Channel</label>
                    <select name="type" required class="w-full rounded-xl border-slate-200 focus:border-brand-500 focus:ring focus:ring-brand-500/20 shadow-sm text-sm px-4 py-2.5">
                        <option value="pembina_pimpinan">Pembina & Pimpinan</option>
                        <option value="dp_pimpinan">Dewan Penasihat & Pimpinan</option>
                        <option value="pimpinan_kadiv">Pimpinan & Kepala Divisi</option>
                        <option value="kadiv_anggota">Internal Divisi / Kepanitiaan</option>
                    </select>
                </div>

                <div x-data="{ search: '', filterDivisi: '' }">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Anggota</label>
                    <p class="text-xs text-slate-500 mb-3">Pilih satu atau lebih pengguna yang akan diundang ke dalam channel ini. Anda akan otomatis dimasukkan.</p>
                    
                    <div class="flex flex-col sm:flex-row gap-2 mb-3">
                        <input type="text" x-model="search" placeholder="🔍 Cari nama anggota..." 
                               class="flex-1 rounded-xl border-slate-200 focus:border-brand-500 focus:ring focus:ring-brand-500/20 shadow-sm text-sm px-3 py-2">
                        
                        <select x-model="filterDivisi" class="flex-1 rounded-xl border-slate-200 focus:border-brand-500 focus:ring focus:ring-brand-500/20 shadow-sm text-sm px-3 py-2">
                            <option value="">Semua Divisi & Pengurus</option>
                            @if(isset($divisions))
                                @foreach($divisions as $div)
                                    <option value="{{ $div->id }}">{{ $div->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="border border-slate-200 rounded-xl p-3 space-y-2 bg-slate-50">
                        @foreach($allUsers as $u)
                            @if($u->id !== auth()->id())
                            @php
                                $userDiv = $u->memberships->first()->division ?? null;
                                $userDivId = $userDiv ? $userDiv->id : '';
                                $userDivName = $userDiv ? $userDiv->name : 'Non-Divisi';
                            @endphp
                            <label x-show="(search === '' || '{{ strtolower(addslashes($u->name)) }}'.includes(search.toLowerCase())) && (filterDivisi === '' || filterDivisi === '{{ $userDivId }}')"
                                   class="flex items-center gap-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-colors border border-transparent hover:border-slate-200 hover:shadow-sm">
                                <input type="checkbox" name="members[]" value="{{ $u->id }}" class="rounded text-brand-600 focus:ring-brand-500">
                                <div>
                                    <div class="text-sm font-bold text-slate-700">{{ $u->name }}</div>
                                    <div class="text-[11px] font-semibold text-slate-400 uppercase">
                                        {{ $u->global_role }} &bull; <span class="text-brand-500">{{ $userDivName }}</span>
                                    </div>
                                </div>
                            </label>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 bg-slate-50 flex justify-end gap-3 rounded-b-2xl shrink-0">
                <button type="button" onclick="closeModal('create-channel-modal')" class="px-5 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-sm font-bold transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-sm font-bold transition-colors shadow-sm">
                    Buat Channel
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit Anggota Channel --}}
<div id="edit-channel-modal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm modal-backdrop" onclick="closeModal('edit-channel-modal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[calc(100%-2rem)] md:w-full max-w-3xl bg-white rounded-2xl shadow-xl z-10 flex flex-col max-h-[90vh]">
        <div class="flex items-center justify-between p-6 border-b border-slate-100 shrink-0">
            <div>
                <h3 class="text-lg font-black text-slate-900">Edit Anggota Channel</h3>
                <p class="text-sm text-slate-500 font-medium mt-1">Kelola anggota yang berhak masuk ke ruang obrolan ini.</p>
            </div>
            <button onclick="closeModal('edit-channel-modal')" class="w-8 h-8 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-500 flex items-center justify-center transition-colors">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>
        
        <form id="edit-channel-form" action="" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-6 overflow-y-auto flex-1">
                <div x-data="{ search: '', filterDivisi: '' }">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Anggota</label>
                    <p class="text-xs text-slate-500 mb-3">Centang pengguna yang akan diundang ke dalam channel ini.</p>
                    
                    <div class="flex flex-col sm:flex-row gap-2 mb-3">
                        <input type="text" x-model="search" placeholder="🔍 Cari nama anggota..." 
                               class="flex-1 rounded-xl border-slate-200 focus:border-brand-500 focus:ring focus:ring-brand-500/20 shadow-sm text-sm px-3 py-2">
                        
                        <select x-model="filterDivisi" class="flex-1 rounded-xl border-slate-200 focus:border-brand-500 focus:ring focus:ring-brand-500/20 shadow-sm text-sm px-3 py-2">
                            <option value="">Semua Divisi & Pengurus</option>
                            @if(isset($divisions))
                                @foreach($divisions as $div)
                                    <option value="{{ $div->id }}">{{ $div->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="border border-slate-200 rounded-xl p-3 space-y-2 bg-slate-50">
                        @foreach($allUsers as $u)
                            @if($u->id !== auth()->id())
                            @php
                                $userDiv = $u->memberships->first()->division ?? null;
                                $userDivId = $userDiv ? $userDiv->id : '';
                                $userDivName = $userDiv ? $userDiv->name : 'Non-Divisi';
                            @endphp
                            <label x-show="(search === '' || '{{ strtolower(addslashes($u->name)) }}'.includes(search.toLowerCase())) && (filterDivisi === '' || filterDivisi === '{{ $userDivId }}')"
                                   class="flex items-center gap-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-colors border border-transparent hover:border-slate-200 hover:shadow-sm">
                                <input type="checkbox" name="members[]" value="{{ $u->id }}" class="edit-member-checkbox rounded text-brand-600 focus:ring-brand-500">
                                <div>
                                    <div class="text-sm font-bold text-slate-700">{{ $u->name }}</div>
                                    <div class="text-[11px] font-semibold text-slate-400 uppercase">
                                        {{ $u->global_role }} &bull; <span class="text-brand-500">{{ $userDivName }}</span>
                                    </div>
                                </div>
                            </label>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 bg-slate-50 flex justify-end gap-3 rounded-b-2xl shrink-0">
                <button type="button" onclick="closeModal('edit-channel-modal')" class="px-5 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-sm font-bold transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-sm font-bold transition-colors shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(channelId, membersArray) {
        // Set form action
        document.getElementById('edit-channel-form').action = '{{ route('messages.index') }}/channel/' + channelId + '/members';
        
        // Reset all checkboxes
        document.querySelectorAll('.edit-member-checkbox').forEach(function(checkbox) {
            checkbox.checked = false;
        });
        
        // Check checkboxes that match membersArray
        if (membersArray && membersArray.length > 0) {
            document.querySelectorAll('.edit-member-checkbox').forEach(function(checkbox) {
                if (membersArray.includes(parseInt(checkbox.value))) {
                    checkbox.checked = true;
                }
            });
        }
        
        openModal('edit-channel-modal');
    }
</script>
@endif
@endpush
