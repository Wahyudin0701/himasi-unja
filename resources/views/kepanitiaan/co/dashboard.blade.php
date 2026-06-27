@extends('layouts.dashboard')



@section('content')

    <!-- Greeting -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-brand-500 uppercase tracking-widest mb-1">CO Acara</p>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">
                Halo, <span class="gradient-text">{{ explode(' ', $user->name)[0] }}</span>
            </h1>
            <p class="text-sm text-slate-500 mt-1.5 font-medium">Kelola divisi dan pantau tugas sprint anggota Anda.</p>
        </div>
    </div>

    @forelse($divisionsData as $data)
        @php
            $assignment = $data['assignment'];
            $members = $data['members'];
            $groupedTasks = $data['groupedTasks'];
            $totalTasks = $data['totalTasks'];
            $completedTasks = $data['completedTasks'];
            $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        @endphp

        <!-- Division Container -->
        <div class="mb-12" 
             x-data="{ 
                modalOpen: false, 
                editMode: false,
                taskId: null,
                title: '',
                description: '',
                assigned_to: '',
                priority: 'medium',
                due_date: '',
                sprint_number: 1,
                sprint_start_date: '{{ now()->format('Y-m-d') }}',
                sprint_end_date: '{{ now()->addDays(7)->format('Y-m-d') }}',
                status: 'todo',

                openCreateModal() {
                    this.editMode = false;
                    this.title = '';
                    this.description = '';
                    this.assigned_to = '';
                    this.sprint_number = 1;
                    this.modalOpen = true;
                },

                openEditModal(task) {
                    this.editMode = true;
                    this.taskId = task.id;
                    this.title = task.title;
                    this.description = task.description || '';
                    this.assigned_to = task.assigned_to;
                    this.sprint_number = task.sprint_number;
                    this.due_date = task.due_date ? task.due_date.substring(0, 10) : '';
                    this.priority = task.priority;
                    this.status = task.status;
                    this.modalOpen = true;
                },

                getRemainingDays(dateStr) {
                    if (!dateStr) return '-';
                    let due = new Date(dateStr);
                    let today = new Date();
                    today.setHours(0,0,0,0);
                    due.setHours(0,0,0,0);
                    let diffTime = due - today;
                    let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    if(diffDays === 0) return 'Hari ini';
                    if(diffDays === 1) return 'Besok';
                    if(diffDays < 0) return 'Terlewat ' + Math.abs(diffDays) + ' hari';
                    return diffDays + ' Hari lagi';
                }

             }">
             
            <!-- Sprint Tasks Board Header -->
            <div>
                <div class="flex items-center justify-between py-5 border-t border-slate-200">
                    <h3 class="text-xl font-bold text-slate-900 flex items-center gap-2"><i class="ph-fill ph-kanban text-brand-500"></i> Papan Sprint</h3>
                    <a href="{{ route('kepanitiaan.co.tasks.create', ['event_id' => $assignment->event_id, 'event_division_id' => $assignment->event_division_id]) }}" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold rounded-xl shadow-lg transition-all flex items-center gap-2 shadow-brand-500/30">
                        <i class="ph ph-plus"></i> Tambah Tugas
                    </a>
                </div>

                <!-- Division Header -->
                <div class="mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center border border-brand-100 shrink-0">
                                <i class="ph-fill ph-users-three text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-slate-900 leading-tight">{{ $assignment->division->name ?? 'Divisi' }}</h2>
                                <p class="text-xs font-bold text-brand-600 uppercase tracking-widest mt-0.5">{{ $assignment->event->name }}</p>
                            </div>
                        </div>
                        
                        <div class="flex flex-col items-end gap-1">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-500 uppercase">Progress Keseluruhan</span>
                                <span class="text-sm font-bold text-emerald-600">{{ $progress }}%</span>
                            </div>
                            <div class="w-48 h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($groupedTasks->count() > 0)
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                        
                        <!-- Global Table Headers (Desktop Only) -->
                        <div class="hidden lg:flex border-b border-slate-200 bg-slate-50/80">
                            <div class="w-16 shrink-0 border-r border-slate-200"></div>
                            <div class="flex-1 grid grid-cols-3 divide-x divide-slate-200">
                                <div class="p-4 text-center">
                                    <h4 class="text-xs font-extrabold text-slate-700 uppercase tracking-widest flex items-center justify-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-slate-300"></div> TO DO
                                    </h4>
                                </div>
                                <div class="p-4 text-center">
                                    <h4 class="text-xs font-extrabold text-slate-700 uppercase tracking-widest flex items-center justify-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-blue-400"></div> IN PROGRESS
                                    </h4>
                                </div>
                                <div class="p-4 text-center">
                                    <h4 class="text-xs font-extrabold text-slate-700 uppercase tracking-widest flex items-center justify-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-emerald-400"></div> COMPLETED
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <!-- Sprints Data -->
                        <div class="divide-y divide-slate-200 bg-slate-50/20">
                            @foreach($groupedTasks as $sprintNumber => $tasks)
                                <div class="flex flex-col lg:flex-row hover:bg-white transition-colors group/row">
                                    
                                    <!-- Rotated Sprint Header -->
                                    <div class="lg:w-16 bg-brand-600 text-white flex items-center justify-center shrink-0 border-b lg:border-b-0 lg:border-r border-slate-200 py-3 lg:py-0 overflow-hidden">
                                        <div class="flex flex-col items-center justify-center gap-1 lg:transform lg:-rotate-90 whitespace-nowrap">
                                            <h3 class="text-sm font-extrabold tracking-widest uppercase leading-none">SPRINT {{ $sprintNumber }}</h3>
                                            @php
                                                $startDate = $tasks->first()->sprint_start_date ? \Carbon\Carbon::parse($tasks->first()->sprint_start_date)->format('d M') : '';
                                                $endDate = $tasks->first()->sprint_end_date ? \Carbon\Carbon::parse($tasks->first()->sprint_end_date)->format('d M') : '';
                                            @endphp
                                            @if($startDate && $endDate)
                                                <span class="text-[10px] font-medium text-slate-300 text-center">{{ $startDate }} - {{ $endDate }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Columns Container -->
                                    <div class="flex-1 grid grid-cols-1 lg:grid-cols-3 divide-y lg:divide-y-0 lg:divide-x divide-slate-200">
                                        
                                        <!-- Col 1: TO DO -->
                                        <div class="p-4 flex flex-col">
                                            <div class="lg:hidden flex items-center gap-2 mb-4 px-1">
                                                <div class="w-2 h-2 shrink-0 rounded-full bg-slate-300"></div>
                                                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-widest">TO DO</h4>
                                            </div>
                                            <div class="space-y-3 flex-1">
                                                @foreach($tasks->where('status', 'todo') as $task)
                                                <div class="bg-white border border-slate-200 rounded-lg p-3 shadow-sm cursor-pointer hover:border-brand-300 hover:shadow-md transition group relative flex flex-col" onclick="window.location.href='{{ route('kepanitiaan.co.tasks.show', $task->id) }}'" title="Klik untuk edit tugas">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <div class="flex items-start gap-1.5 min-w-0">
                                                            <div class="w-5 h-5 rounded-full bg-slate-100 flex items-center justify-center text-[9px] font-bold text-slate-600 shrink-0">
                                                                {{ strtoupper(substr($task->assignee->name ?? '?', 0, 1)) }}
                                                            </div>
                                                            <span class="text-[10px] font-semibold text-slate-500 whitespace-normal leading-tight">{{ $task->assignee->name ?? 'Unassigned' }}</span>
                                                        </div>
                                                        <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-widest ml-auto shrink-0
                                                            @if($task->priority === 'high') bg-rose-100 text-rose-700
                                                            @elseif($task->priority === 'medium') bg-amber-100 text-amber-700
                                                            @else bg-slate-100 text-slate-600
                                                            @endif">
                                                            {{ $task->priority }}
                                                        </span>
                                                    </div>
                                                    <span class="font-bold text-xs text-slate-800 block leading-tight mt-1">{{ $task->title }}</span>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Col 2: IN PROGRESS -->
                                        <div class="p-4 flex flex-col">
                                            <div class="lg:hidden flex items-center gap-2 mb-4 px-1">
                                                <div class="w-2 h-2 shrink-0 rounded-full bg-blue-400"></div>
                                                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-widest">IN PROGRESS</h4>
                                            </div>
                                            <div class="space-y-3 flex-1">
                                                @foreach($tasks->whereIn('status', ['waiting', 'revisi']) as $task)
                                                <div class="bg-white border border-blue-100 rounded-lg p-3 shadow-sm cursor-pointer hover:border-blue-400 hover:shadow-md transition group relative flex flex-col" onclick="window.location.href='{{ route('kepanitiaan.co.tasks.show', $task->id) }}'" title="Klik untuk edit tugas">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <div class="flex items-start gap-1.5 min-w-0">
                                                            <div class="w-5 h-5 rounded-full bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center text-[9px] font-bold shrink-0">
                                                                {{ strtoupper(substr($task->assignee->name ?? '?', 0, 1)) }}
                                                            </div>
                                                            <span class="text-[10px] font-semibold text-slate-500 whitespace-normal leading-tight">{{ $task->assignee->name ?? 'Unassigned' }}</span>
                                                        </div>
                                                        <div class="flex items-center gap-1 ml-auto shrink-0">
                                                            <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-widest
                                                                @if($task->priority === 'high') bg-rose-100 text-rose-700
                                                                @elseif($task->priority === 'medium') bg-amber-100 text-amber-700
                                                                @else bg-slate-100 text-slate-600
                                                                @endif">
                                                                {{ $task->priority }}
                                                            </span>
                                                            @if($task->status == 'revisi')
                                                                <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-widest bg-amber-100 text-amber-700">REVISI</span>
                                                            @elseif($task->status == 'waiting')
                                                                <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-widest bg-blue-100 text-blue-700">WAITING</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <span class="font-bold text-xs text-slate-800 block leading-tight mt-1">{{ $task->title }}</span>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Col 3: COMPLETED -->
                                        <div class="p-4 flex flex-col">
                                            <div class="lg:hidden flex items-center gap-2 mb-4 px-1">
                                                <div class="w-2 h-2 shrink-0 rounded-full bg-emerald-400"></div>
                                                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-widest">COMPLETED</h4>
                                            </div>
                                            <div class="space-y-3 flex-1">
                                                @foreach($tasks->where('status', 'completed') as $task)
                                                <div class="bg-white border border-emerald-100 rounded-lg p-3 shadow-sm cursor-pointer hover:border-emerald-400 hover:shadow-md transition group relative flex flex-col opacity-80 hover:opacity-100" onclick="window.location.href='{{ route('kepanitiaan.co.tasks.show', $task->id) }}'">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <div class="flex items-start gap-1.5 min-w-0">
                                                            <div class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-[9px] font-bold shrink-0">
                                                                {{ strtoupper(substr($task->assignee->name ?? '?', 0, 1)) }}
                                                            </div>
                                                            <span class="text-[10px] font-semibold text-slate-500 whitespace-normal leading-tight">{{ $task->assignee->name ?? 'Unassigned' }}</span>
                                                        </div>
                                                        <div class="flex items-center gap-1 ml-auto shrink-0">
                                                            <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-700">
                                                                SELESAI
                                                            </span>
                                                            <i class="ph-fill ph-check-circle text-emerald-500"></i>
                                                        </div>
                                                    </div>
                                                    <span class="font-bold text-xs text-slate-600 block leading-tight mt-1 line-through">{{ $task->title }}</span>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            @endforeach
                        </div>
                @else
                    <div class="py-12 text-center bg-slate-50 border border-slate-200 border-dashed rounded-2xl">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm border border-slate-100">
                            <i class="ph ph-clipboard-text text-3xl text-slate-300"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-700">Belum ada tugas</h3>
                        <p class="text-xs text-slate-500 mt-1">Mulai rencanakan sprint dengan menambahkan tugas baru.</p>
                    </div>
                @endif
            </div>

            <!-- Edit / Create Modal -->
            <div x-show="modalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center px-4">
                <div x-show="modalOpen" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="modalOpen = false"></div>
                
                <div x-show="modalOpen" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                     
                     <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 shrink-0">
                         <div>
                             <h3 class="font-bold text-slate-900 text-lg" x-text="editMode ? 'Edit Tugas' : 'Tambah Tugas Baru'"></h3>
                             <p class="text-xs text-slate-500 font-medium mt-0.5">Berikan detail tugas dan deadline yang jelas.</p>
                         </div>
                         <button @click="modalOpen = false" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 transition-colors">
                             <i class="ph ph-x"></i>
                         </button>
                     </div>

                     <div class="p-6 overflow-y-auto">
                         <form :action="editMode ? '{{ url('kepanitiaan/co/tasks') }}/' + taskId : '{{ route('kepanitiaan.co.tasks.store') }}'" method="POST">
                             @csrf
                             <template x-if="editMode"><input type="hidden" name="_method" value="PATCH"></template>
                             
                             <input type="hidden" name="event_id" value="{{ $assignment->event_id }}">
                             <input type="hidden" name="event_division_id" value="{{ $assignment->event_division_id }}">

                             <div class="space-y-5">
                                 <div>
                                     <label class="block text-sm font-bold text-slate-700 mb-2">Judul Tugas <span class="text-rose-500">*</span></label>
                                     <input type="text" name="title" x-model="title" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-all" required>
                                 </div>

                                 <div>
                                     <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi (Opsional)</label>
                                     <textarea name="description" x-model="description" rows="3" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-all"></textarea>
                                 </div>

                                 <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                     <div>
                                         <label class="block text-sm font-bold text-slate-700 mb-2">Ditugaskan Kepada <span class="text-rose-500">*</span></label>
                                         <select name="assigned_to" x-model="assigned_to" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-all" required>
                                             <option value="">-- Pilih Anggota --</option>
                                             @foreach($members as $m)
                                                 <option value="{{ $m->user_id }}">{{ $m->user->name }}</option>
                                             @endforeach
                                         </select>
                                     </div>
                                     <div>
                                         <label class="block text-sm font-bold text-slate-700 mb-2">Tingkat Prioritas <span class="text-rose-500">*</span></label>
                                         <select name="priority" x-model="priority" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-all" required>
                                             <option value="low">Rendah (Low)</option>
                                             <option value="medium">Menengah (Medium)</option>
                                             <option value="high">Tinggi (High)</option>
                                         </select>
                                     </div>
                                 </div>

                                 <!-- Sprint Settings Box -->
                                 <div class="bg-brand-50 border border-brand-100 rounded-xl p-5">
                                     <h4 class="font-bold text-brand-900 text-sm mb-4 flex items-center gap-2"><i class="ph-fill ph-flag-checkered text-brand-500"></i> Pengaturan Sprint</h4>
                                     @if(isset($data['sprints']) && $data['sprints']->isEmpty())
                                         <div class="p-3 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-xs">
                                             <i class="ph-fill ph-warning-circle align-middle"></i> Anda belum mengatur jadwal Sprint. <a href="{{ route('kepanitiaan.co.sprints.index') }}" class="font-bold underline hover:text-amber-800">Atur sekarang</a>
                                         </div>
                                     @else
                                         <div>
                                             <label class="block text-xs font-bold text-brand-700 mb-1.5">Pilih Sprint (Ke-) <span class="text-rose-500">*</span></label>
                                             <select name="sprint_number" x-model="sprint_number" class="w-full bg-white border border-brand-200 text-brand-900 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block p-2.5 transition-all font-bold" required>
                                                 <option value="">-- Pilih Sprint --</option>
                                                 @if(isset($data['sprints']))
                                                     @foreach($data['sprints'] as $sprint)
                                                         <option value="{{ $sprint->sprint_number }}">Sprint {{ $sprint->sprint_number }} ({{ $sprint->start_date->format('d M') }} - {{ $sprint->end_date->format('d M Y') }})</option>
                                                     @endforeach
                                                 @endif
                                             </select>
                                         </div>
                                     @endif
                                 </div>

                                 <!-- Status Field (Only show on Edit) -->
                                 <div x-show="editMode" style="display: none;">
                                     <label class="block text-sm font-bold text-slate-700 mb-2">Status Pengerjaan</label>
                                     <select name="status" x-model="status" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-all">
                                         <option value="todo">To Do</option>
                                         <option value="waiting">Waiting (In Progress)</option>
                                         <option value="revisi">Revisi</option>
                                         <option value="completed">Completed</option>
                                     </select>
                                 </div>
                             </div>

                             <div class="mt-8 pt-5 border-t border-slate-100 flex justify-end gap-3">
                                 <button type="button" @click="modalOpen = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">Batal</button>
                                 <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-brand-600 hover:bg-brand-700 rounded-xl transition-colors shadow-lg shadow-brand-500/30 flex items-center gap-2">
                                     <i class="ph ph-check-circle text-lg"></i> Simpan Tugas
                                 </button>
                             </div>
                         </form>
                     </div>
                </div>
            </div>
        </div>

    @empty
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-12 text-center">
            <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-4 border border-slate-100 shadow-sm">
                <i class="ph-fill ph-flag-banner text-3xl text-slate-300"></i>
            </div>
            <h3 class="font-bold text-slate-900 mb-1 text-lg">Belum Ada Divisi</h3>
            <p class="text-sm text-slate-500">Anda belum ditugaskan sebagai Koordinator Divisi di acara manapun.</p>
        </div>
    @endforelse

@endsection

@push('styles')
<style>
    .gradient-text {
        background: linear-gradient(135deg, #635BFF, #818cf8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>
@endpush
