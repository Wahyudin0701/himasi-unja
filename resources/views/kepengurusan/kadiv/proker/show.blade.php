@extends('layouts.dashboard')

@section('title', 'Detail Program Kerja')

@section('breadcrumbs')
    <span class="text-slate-700">Kepengurusan</span>
@endsection

@section('content')

{{-- ===== HEADER ===== --}}
<div class="mb-8 flex flex-row items-start justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">
            Detail Program Kerja
        </h1>
        <p class="text-sm text-slate-500 mt-1.5 font-medium">Informasi lengkap terkait program kerja divisi.</p>
    </div>
    <div class="flex items-center shrink-0">
        <a href="{{ route('kepengurusan.kadiv.proker.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-brand-600 transition-colors mt-1">
            <i class="ph-bold ph-arrow-left"></i>
            Kembali
        </a>
    </div>
</div>

{{-- ===== DETAIL PROKER ===== --}}
<div class="w-full space-y-6 mb-6">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        {{-- Card Header --}}
        <div class="p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
            <div class="flex items-center gap-4 flex-wrap">
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">{{ $proker->name }}</h2>
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider
                    @if($proker->status === 'ongoing') bg-emerald-50 text-emerald-700 border border-emerald-200
                    @elseif($proker->status === 'planning') bg-brand-50 text-brand-700 border border-brand-200
                    @elseif($proker->status === 'completed') bg-slate-100 text-slate-600 border border-slate-200
                    @else bg-rose-50 text-rose-700 border border-rose-200
                    @endif">
                    {{ $proker->status }}
                </span>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 shrink-0 w-full sm:w-auto mt-2 sm:mt-0">
                <a href="{{ route('kepengurusan.kadiv.proker.edit', $proker->id) }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 transition-all text-sm font-bold shadow-sm w-full sm:w-auto">
                    <i class="ph-bold ph-pencil-simple mr-1"></i>
                    Edit Proker
                </a>
                <a href="{{ route('kepengurusan.kadiv.proker.progress', $proker->id) }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white transition-all text-sm font-bold shadow-sm w-full sm:w-auto">
                    <i class="ph-bold ph-chart-line-up text-base"></i>
                    Progres Proker
                </a>
            </div>
        </div>
        
        {{-- Card Body --}}
        <div class="p-6 sm:p-8 space-y-8">
            {{-- Info Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1 flex items-center gap-1.5">
                        <i class="ph-bold ph-calendar text-sm"></i>
                        Pelaksanaan
                    </h3>
                    <p class="text-sm font-bold text-slate-900">
                        @if($proker->start_date && $proker->end_date)
                            {{ $proker->start_date->translatedFormat('d F Y') }} - {{ $proker->end_date->translatedFormat('d F Y') }}
                        @elseif($proker->start_date)
                            Mulai {{ $proker->start_date->translatedFormat('d F Y') }}
                        @else
                            Menunggu Jadwal
                        @endif
                    </p>
                </div>
                
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1 flex items-center gap-1.5">
                        @if($proker->type === 'event')
                            <i class="ph-bold ph-confetti text-sm"></i>
                        @else
                            <i class="ph-bold ph-briefcase text-sm"></i>
                        @endif
                        Jenis Program
                    </h3>
                    <p class="text-sm font-bold text-slate-900">
                        {{ $proker->type === 'event' ? 'Event Kepanitiaan' : 'Non-Event (Rutin/Internal)' }}
                    </p>
                </div>
            </div>

            {{-- PJ / Panitia Inti Section --}}
            <div>
                @if($proker->type === 'event' && class_exists('\App\Models\Kepanitiaan\Event'))
                    @php
                        $event = \App\Models\Kepanitiaan\Event::where('work_program_id', $proker->id)
                                    ->with(['committees.user', 'committees.role'])
                                    ->first();
                        $inti = [];
                        if($event) {
                            $intiRoles = \App\Models\Kepanitiaan\CommitteeRole::where('scope', 'inti')->orderBy('level')->get();
                            foreach($intiRoles as $role) {
                                $inti[$role->slug] = [
                                    'name' => $role->name,
                                    'committee' => $event->committees->where('committee_role_id', $role->id)->first()
                                ];
                            }
                        }
                    @endphp
                    
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                        <i class="ph-bold ph-users-three text-sm"></i>
                        Susunan Panitia Inti
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        @if($event)
                            @foreach($inti as $slug => $data)
                                <div class="flex items-center gap-3 p-3.5 bg-slate-50/50 border border-slate-100 rounded-xl">
                                    <div class="min-w-0 w-full">
                                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">
                                            {{ $data['name'] }}
                                        </span>
                                        <p class="text-sm font-bold {{ !$data['committee'] ? 'text-slate-400 italic' : 'text-slate-900' }} truncate">
                                            {{ $data['committee']->user->name ?? 'Belum di set' }}
                                        </p>
                                        @if($data['committee'] && $data['committee']->user)
                                            <p class="text-xs text-slate-500 font-medium truncate mt-0.5">
                                                NIM {{ $data['committee']->user->nim }} ({{ $data['committee']->user->angkatan }})
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-sm text-slate-500 font-medium">Event belum terbentuk.</p>
                        @endif
                    </div>
                @else
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                        <i class="ph-bold ph-user-circle text-sm"></i>
                        Penanggung Jawab (PJ)
                    </h3>
                    <div class="flex items-center gap-3 p-3.5 bg-slate-50/50 border border-slate-100 rounded-xl w-full sm:w-max min-w-[300px]">
                        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shrink-0 border border-slate-200 overflow-hidden shadow-sm">
                            @if($proker->pic)
                                <img src="{{ $proker->pic->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($proker->pic->name).'&color=4F46E5&background=EEF2FF' }}" alt="{{ $proker->pic->name }}" class="w-full h-full object-cover">
                            @else
                                <i class="ph-bold ph-user text-slate-300 text-lg"></i>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-900 truncate">{{ $proker->pic->name ?? 'Belum ditentukan' }}</p>
                            @if($proker->pic)
                                <p class="text-xs font-semibold text-slate-400 truncate mt-0.5">{{ $proker->pic->email }}</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- Deskripsi --}}
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                    <i class="ph-bold ph-text-align-left text-sm"></i>
                    Deskripsi Program Kerja
                </h3>
                <p class="text-slate-700 leading-relaxed font-medium bg-slate-50/50 p-4 rounded-xl border border-slate-100/80">
                    {{ $proker->description ?: 'Tidak ada deskripsi yang ditambahkan untuk program kerja ini.' }}
                </p>
            </div>
        </div>
    </div>
</div>

@if($proker->type === 'non_event')
{{-- ===== DAFTAR TUGAS (NON-EVENT) ===== --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden w-full mb-6">
    <div class="p-6 sm:p-8 border-b border-slate-100 flex items-center justify-between">
        <h3 class="text-xl font-bold text-slate-900 flex items-center gap-2">
            <i class="ph-bold ph-check-square-offset text-brand-500"></i>
            Daftar Tugas (Kanban)
        </h3>
        <button onclick="openTaskModal('add')" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white transition-all text-xs font-bold shadow-sm">
            <i class="ph-bold ph-plus"></i>
            Tambah Tugas
        </button>
    </div>
    <div class="p-6 sm:p-8 bg-slate-50">
        @if(isset($tasks) && $tasks->count() > 0)
            <div class="space-y-4">
                @foreach($tasks as $task)
                    <div class="bg-white p-4 rounded-xl border border-slate-200 flex items-start justify-between gap-4 shadow-sm hover:border-brand-300 transition-colors">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                @if($task->priority == 'high')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-600">HIGH</span>
                                @elseif($task->priority == 'medium')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-600">MEDIUM</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500">LOW</span>
                                @endif
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    @if($task->status == 'todo') bg-slate-100 text-slate-600
                                    @elseif($task->status == 'waiting') bg-blue-100 text-blue-600
                                    @elseif(in_array($task->status, ['revisi','revision'])) bg-amber-100 text-amber-600
                                    @elseif($task->status == 'completed') bg-emerald-100 text-emerald-600
                                    @endif
                                ">
                                    {{ $task->status }}
                                </span>
                            </div>
                            <h4 class="text-sm font-bold text-slate-900 mb-1">{{ $task->title }}</h4>
                            <p class="text-[11px] text-slate-500 line-clamp-2 mb-2">{{ $task->description }}</p>
                            <div class="flex items-center gap-3 text-[10px] text-slate-400 font-semibold">
                                <span class="flex items-center gap-1">
                                    <i class="ph-bold ph-user text-xs"></i>
                                    {{ $task->assignee->name ?? 'User dihapus' }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <i class="ph-bold ph-calendar-blank text-xs"></i>
                                    {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : 'Tanpa Tenggat' }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick='editTask(@json($task))' class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-brand-600 hover:border-brand-200 hover:bg-brand-50 transition-colors" title="Edit">
                                <i class="ph-bold ph-pencil-simple"></i>
                            </button>
                            <button onclick="deleteTask('{{ $task->id }}')" class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition-colors" title="Hapus">
                                <i class="ph-bold ph-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-10 px-4 bg-white rounded-2xl border border-dashed border-slate-200">
                <div class="w-14 h-14 rounded-full bg-slate-50 border border-slate-100 shadow-sm flex items-center justify-center mx-auto mb-3">
                    <i class="ph-fill ph-kanban text-slate-300 text-2xl"></i>
                </div>
                <h4 class="text-sm font-bold text-slate-900 mb-1">Belum Ada Tugas</h4>
                <p class="text-xs text-slate-500 font-medium max-w-sm mx-auto">Silakan tambahkan tugas dan assign ke anggota divisi Anda.</p>
            </div>
        @endif
    </div>
</div>

{{-- MODAL TAMBAH/EDIT TUGAS --}}
<div id="modal-task" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:block sm:p-0">
        <div onclick="closeTaskModal()" class="fixed inset-0 bg-slate-900/60 transition-opacity backdrop-blur-sm" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-middle w-full max-w-[92%] sm:max-w-md sm:w-full bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle border border-slate-200">
            <form id="taskForm" action="{{ route('kepengurusan.kadiv.proker.tasks.store', $proker->id) }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="taskMethod" value="POST">
                <div class="bg-white px-6 pt-6 pb-4 sm:p-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-base font-black text-slate-900 uppercase tracking-wide flex items-center gap-2" id="modal-task-title">
                        <i class="ph-bold ph-plus text-brand-500"></i>
                        Tambah Tugas Baru
                    </h3>
                    <button type="button" onclick="closeTaskModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <i class="ph-bold ph-x text-lg"></i>
                    </button>
                </div>
                <div class="px-6 py-6 space-y-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Judul Tugas <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" id="taskTitle" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition-all text-sm font-semibold text-slate-900" placeholder="Contoh: Membuat Proposal Kegiatan">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi Tugas</label>
                        <textarea name="description" id="taskDescription" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition-all text-sm font-medium text-slate-900" placeholder="Jelaskan detail tugas ini..."></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Prioritas <span class="text-rose-500">*</span></label>
                            <select name="priority" id="taskPriority" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition-all text-sm font-semibold text-slate-900 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2394a3b8%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-no-repeat bg-[length:10px_10px] bg-[right_1rem_center]">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Tenggat Waktu</label>
                            <input type="date" name="due_date" id="taskDueDate" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition-all text-sm font-semibold text-slate-900">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Tugaskan Kepada <span class="text-rose-500">*</span></label>
                        <select name="assigned_to" id="taskAssignedTo" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition-all text-sm font-semibold text-slate-900 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2394a3b8%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-no-repeat bg-[length:10px_10px] bg-[right_1rem_center]">
                            <option value="">-- Pilih Anggota Divisi --</option>
                            @if(isset($divisionMembers))
                                @foreach($divisionMembers as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" onclick="closeTaskModal()" class="px-5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors shadow-sm">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-sm font-bold transition-all shadow-sm">
                        Simpan Tugas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL HAPUS TUGAS --}}
<div id="modal-delete-task" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:block sm:p-0">
        <div onclick="closeDeleteTaskModal()" class="fixed inset-0 bg-slate-900/60 transition-opacity backdrop-blur-sm" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-middle w-full max-w-[92%] sm:max-w-md sm:w-full bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle border border-slate-200">
            <div class="px-6 py-6 text-center">
                <div class="w-16 h-16 rounded-full bg-rose-100 flex items-center justify-center mx-auto mb-4">
                    <i class="ph-bold ph-trash text-2xl text-rose-600"></i>
                </div>
                <h3 class="text-lg font-black text-slate-900 mb-2">Hapus Tugas?</h3>
                <p class="text-sm text-slate-500 font-medium">Tugas yang dihapus tidak dapat dikembalikan lagi. Anda yakin?</p>
            </div>
            <div class="bg-slate-50 px-6 py-4 flex justify-center gap-3 border-t border-slate-100">
                <button onclick="closeDeleteTaskModal()" class="flex-1 px-5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors shadow-sm">
                    Batal
                </button>
                <form id="deleteTaskForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-bold transition-all shadow-sm">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@if($proker->type === 'event' && isset($event))
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden w-full mb-6">
    <div class="p-6 sm:p-8 border-b border-slate-100 flex items-center justify-between">
        <h3 class="text-xl font-bold text-slate-900 flex items-center gap-2">
            <i class="ph-bold ph-users text-brand-500"></i>
            Daftar Divisi Kepanitiaan
        </h3>
    </div>
    <div class="p-6 sm:p-8 bg-slate-50">
        @if($event->divisions && $event->divisions->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($event->divisions as $div)
                    @php
                        $co = $div->committees->where('role.slug', 'co-divisi')->first();
                        $anggota = $div->committees->where('role.slug', 'anggota');
                    @endphp
                    <div onclick="openDivisionModal('{{ $div->id }}')" class="bg-white border border-slate-200 rounded-2xl p-5 hover:border-brand-500 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 cursor-pointer flex flex-col justify-between h-full">
                        <div>
                            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                                <h4 class="text-sm font-black text-slate-900 uppercase tracking-wider">{{ $div->name }}</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-slate-100 text-xs font-black text-slate-500">
                                    {{ $anggota->count() + ($co ? 1 : 0) }} Anggota
                                </span>
                            </div>
                            
                            <div class="space-y-1">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Koordinator (CO)</span>
                                <span class="block text-sm font-bold {{ $co ? 'text-slate-800' : 'text-slate-400 italic font-medium' }}">
                                    {{ $co->user->name ?? 'Belum di set' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Modals for Division Details --}}
            @foreach($event->divisions as $div)
                @php
                    $co = $div->committees->where('role.slug', 'co-divisi')->first();
                    $anggota = $div->committees->where('role.slug', 'anggota');
                    
                    $coInitials = '';
                    if ($co && $co->user) {
                        $coWords = explode(' ', $co->user->name);
                        $coInitials = count($coWords) >= 2 
                            ? strtoupper(substr($coWords[0], 0, 1) . substr($coWords[1], 0, 1))
                            : strtoupper(substr($co->user->name, 0, 2));
                    }
                @endphp
                <div id="modal-div-{{ $div->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:block sm:p-0">
                        <div onclick="closeDivisionModal('{{ $div->id }}')" class="fixed inset-0 bg-slate-900/60 transition-opacity backdrop-blur-sm" aria-hidden="true"></div>

                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        <div class="inline-block align-middle w-full max-w-[92%] sm:max-w-lg sm:w-full bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle border border-slate-200">
                            <div class="bg-white px-6 pt-6 pb-4 sm:p-6 border-b border-slate-100 flex items-center justify-between">
                                <h3 class="text-base font-black text-slate-900 uppercase tracking-wide flex items-center gap-2" id="modal-title">
                                    <i class="ph-bold ph-users text-brand-500"></i>
                                    Detail {{ $div->name }}
                                </h3>
                                <button onclick="closeDivisionModal('{{ $div->id }}')" class="text-slate-400 hover:text-slate-600 transition-colors">
                                    <i class="ph-bold ph-x text-lg"></i>
                                </button>
                            </div>
                            
                            <div class="px-6 py-6 space-y-6">
                                {{-- Koordinator Divisi --}}
                                <div>
                                    <h5 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                                        <i class="ph-bold ph-star text-brand-400"></i>
                                        Koordinator (CO)
                                    </h5>
                                    @if($co && $co->user)
                                        <div class="flex items-center gap-3 bg-brand-50/50 p-3 rounded-xl border border-brand-100/50">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 font-black text-xs shadow-sm bg-brand-100 text-brand-700 border border-brand-200">
                                                {{ $coInitials }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm font-bold text-slate-900">{{ $co->user->name }}</p>
                                                <p class="text-xs font-medium text-slate-500 mt-0.5">{{ $co->user->email }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 border-dashed bg-slate-50/50">
                                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center border border-white shadow-sm">
                                                <i class="ph-bold ph-user text-slate-300 text-sm"></i>
                                            </div>
                                            <p class="text-sm font-bold text-slate-400">Belum di set</p>
                                        </div>
                                    @endif
                                </div>

                                {{-- Anggota --}}
                                <div>
                                    <h5 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                                        <i class="ph-bold ph-users text-slate-400"></i>
                                        Anggota Divisi ({{ $anggota->count() }})
                                    </h5>
                                    @if($anggota->count() > 0)
                                        <div class="space-y-2.5 max-h-60 overflow-y-auto pr-1">
                                            @foreach($anggota as $member)
                                                @php
                                                    $memberInitials = '';
                                                    if ($member->user) {
                                                        $memberWords = explode(' ', $member->user->name);
                                                        $memberInitials = count($memberWords) >= 2 
                                                            ? strtoupper(substr($memberWords[0], 0, 1) . substr($memberWords[1], 0, 1))
                                                            : strtoupper(substr($member->user->name, 0, 2));
                                                    }
                                                @endphp
                                                <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 font-black text-xs shadow-sm bg-slate-200 text-slate-600 border border-slate-300">
                                                        {{ $memberInitials ?: '?' }}
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <p class="text-xs font-bold text-slate-900">{{ $member->user->name ?? 'User tidak ditemukan' }}</p>
                                                        <p class="text-[10px] font-medium text-slate-500 mt-0.5">{{ $member->user->email ?? '-' }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="py-6 text-center rounded-xl border border-dashed border-slate-200 bg-slate-50/50">
                                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Belum ada anggota divisi</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="bg-slate-50 px-6 py-4 flex justify-end">
                                <button onclick="closeDivisionModal('{{ $div->id }}')" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors shadow-sm">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-10 px-4">
                <div class="w-16 h-16 rounded-full bg-white border border-slate-200 shadow-sm flex items-center justify-center mx-auto mb-4">
                    <i class="ph-fill ph-users-three text-slate-300 text-2xl"></i>
                </div>
                <h4 class="text-base font-black text-slate-900 mb-1">Divisi Belum Terbentuk</h4>
                <p class="text-sm text-slate-500 font-medium max-w-sm mx-auto">Event ini belum memiliki susunan divisi kepanitiaan. Divisi dapat dibentuk oleh Ketua Pelaksana melalui Dashboard Event.</p>
            </div>
        @endif
    </div>
</div>
@endif

@push('scripts')
<script>
    function openDivisionModal(id) {
        const modal = document.getElementById('modal-div-' + id);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }
    
    function closeDivisionModal(id) {
        const modal = document.getElementById('modal-div-' + id);
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }
</script>
<script>
    function openTaskModal(type, task = null) {
        const modal = document.getElementById('modal-task');
        const form = document.getElementById('taskForm');
        const title = document.getElementById('modal-task-title');
        const method = document.getElementById('taskMethod');
        
        if (type === 'add') {
            title.innerHTML = '<i class="ph-bold ph-plus text-brand-500"></i> Tambah Tugas Baru';
            form.action = "{{ route('kepengurusan.kadiv.proker.tasks.store', $proker->id) }}";
            method.value = 'POST';
            form.reset();
        } else if (type === 'edit' && task) {
            title.innerHTML = '<i class="ph-bold ph-pencil-simple text-brand-500"></i> Edit Tugas';
            form.action = `/kepengurusan/kadiv/proker/{{ $proker->id }}/tasks/${task.id}`;
            method.value = 'PUT';
            
            document.getElementById('taskTitle').value = task.title;
            document.getElementById('taskDescription').value = task.description || '';
            document.getElementById('taskPriority').value = task.priority;
            if(task.due_date) {
                document.getElementById('taskDueDate').value = task.due_date.split('T')[0];
            }
            document.getElementById('taskAssignedTo').value = task.assigned_to;
        }
        
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    
    function closeTaskModal() {
        document.getElementById('modal-task').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    
    function editTask(task) {
        openTaskModal('edit', task);
    }
    
    function deleteTask(id) {
        const modal = document.getElementById('modal-delete-task');
        const form = document.getElementById('deleteTaskForm');
        form.action = `/kepengurusan/kadiv/proker/{{ $proker->id }}/tasks/${id}`;
        
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    
    function closeDeleteTaskModal() {
        document.getElementById('modal-delete-task').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
</script>
@endpush

@endsection
