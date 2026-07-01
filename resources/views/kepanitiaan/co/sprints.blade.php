@extends('layouts.dashboard')

@section('title', 'Pengaturan Sprint')

@section('content')
<div class="w-full pb-10">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800">Pengaturan Sprint</h1>
            <p class="text-slate-500 mt-1 text-sm">Kelola jadwal sprint untuk divisi yang Anda koordinasikan.</p>
        </div>
        <div>
            <a href="{{ route('kepanitiaan.co.dashboard', ['event' => $event->id, 'division' => $division->id]) }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-slate-500 hover:text-brand-600 transition-colors">
                <i class="ph-bold ph-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @foreach($divisionsData as $data)
        @php
            $assignment = $data['assignment'];
            $sprints = $data['sprints'];
        @endphp

        <div class="bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm mb-8" x-data="sprintManager()">
            <div class="flex items-center justify-between gap-4 mb-6 pb-6 border-b border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                        <i class="ph-fill ph-flag-checkered text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg text-slate-800">{{ $assignment->division->name }}</h2>
                        <p class="text-xs font-semibold text-brand-600 mt-0.5">{{ $assignment->event->name }}</p>
                    </div>
                </div>
                
                <button @click="openCreateModal()" class="px-4 py-2.5 text-sm font-bold text-white bg-brand-600 hover:bg-brand-700 rounded-xl transition-colors shadow-lg shadow-brand-500/30 flex items-center gap-2">
                    <i class="ph ph-plus-circle text-lg"></i> Tambah Sprint
                </button>
            </div>

            <!-- List Sprints -->
            @if($sprints->isEmpty())
                <div class="py-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm border border-slate-100">
                        <i class="ph ph-calendar-blank text-2xl text-slate-300"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-700">Belum ada sprint</h3>
                    <p class="text-xs text-slate-500 mt-1">Silakan tambah sprint baru untuk divisi ini.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($sprints as $sprint)
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-4 rounded-xl border border-slate-200 bg-white hover:border-brand-300 transition-colors">
                        <div class="flex items-center gap-4">
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">Sprint {{ $sprint->sprint_number }}</h4>
                                <p class="text-xs text-slate-500 mt-0.5 flex items-center gap-1.5">
                                    <i class="ph ph-calendar-blank text-slate-400"></i> 
                                    {{ $sprint->start_date->format('d M Y') }} 
                                    <i class="ph ph-arrow-right text-slate-300 mx-1"></i> 
                                    {{ $sprint->end_date->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="openEditModal({{ $sprint->id }}, {{ $sprint->sprint_number }}, '{{ $sprint->start_date->format('Y-m-d') }}', '{{ $sprint->end_date->format('Y-m-d') }}')" class="p-2 text-slate-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition-colors" title="Edit">
                                <i class="ph ph-pencil-simple text-lg"></i>
                            </button>
                            
                            <form id="form-delete-sprint-{{ $sprint->id }}" action="{{ route('kepanitiaan.co.sprints.destroy', $sprint->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDeleteSprint('form-delete-sprint-{{ $sprint->id }}')" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">
                                    <i class="ph ph-trash text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif

            <!-- MODAL ADD/EDIT SPRINT -->
            <div x-show="modalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center px-4">
                <div x-show="modalOpen" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="modalOpen = false"></div>
                
                <div x-show="modalOpen" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col">
                     
                     <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 shrink-0">
                         <div>
                             <h3 class="font-bold text-slate-900 text-lg" x-text="editMode ? 'Edit Sprint' : 'Tambah Sprint'"></h3>
                             <p class="text-xs text-slate-500 font-medium mt-0.5">Tentukan sprint ke berapa beserta rentang waktunya.</p>
                         </div>
                         <button @click="modalOpen = false" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 transition-colors">
                             <i class="ph ph-x"></i>
                         </button>
                     </div>

                     <div class="p-6 overflow-y-auto">
                         <form :action="formAction" method="POST">
                             @csrf
                             <template x-if="editMode">
                                 <input type="hidden" name="_method" value="PUT">
                             </template>

                             <input type="hidden" name="event_division_id" value="{{ $assignment->event_division_id }}">

                             <div class="space-y-4">
                                 <div>
                                     <label class="block text-sm font-bold text-slate-700 mb-2">Sprint Ke- <span class="text-rose-500">*</span></label>
                                     <input type="number" min="1" name="sprint_number" x-model="sprint_number" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-all font-bold" required>
                                 </div>
                                 <div class="grid grid-cols-2 gap-4">
                                     <div>
                                         <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Mulai <span class="text-rose-500">*</span></label>
                                         <input type="date" name="start_date" x-model="start_date" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-all" required>
                                     </div>
                                     <div>
                                         <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Selesai <span class="text-rose-500">*</span></label>
                                         <input type="date" name="end_date" x-model="end_date" :min="start_date" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 transition-all" required>
                                     </div>
                                 </div>
                             </div>

                             <div class="mt-8 pt-5 border-t border-slate-100 flex justify-end gap-3">
                                 <button type="button" @click="modalOpen = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">Batal</button>
                                 <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-brand-600 hover:bg-brand-700 rounded-xl transition-colors shadow-lg shadow-brand-500/30 flex items-center gap-2">
                                     <i class="ph ph-check-circle text-lg"></i> Simpan
                                 </button>
                             </div>
                         </form>
                     </div>
                </div>
            </div>
            
        </div>
    @endforeach
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('sprintManager', () => ({
            modalOpen: false,
            editMode: false,
            formAction: '',
            sprint_number: 1,
            start_date: '',
            end_date: '',

            openCreateModal() {
                this.editMode = false;
                this.formAction = '{{ route('kepanitiaan.co.sprints.store') }}';
                
                this.sprint_number = 1;
                this.start_date = '';
                this.end_date = '';
                
                this.modalOpen = true;
            },

            openEditModal(id, number, start, end) {
                this.editMode = true;
                let baseUrl = '{{ route('kepanitiaan.co.sprints.update', ':id') }}';
                this.formAction = baseUrl.replace(':id', id);
                
                this.sprint_number = number;
                this.start_date = start;
                this.end_date = end;
                
                this.modalOpen = true;
            }
        }))
    });

    function confirmDeleteSprint(formId) {
        Swal.fire({
            title: 'Hapus Sprint?',
            text: "Apakah Anda yakin ingin menghapus sprint ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#635BFF',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-3xl'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        })
    }
</script>
@endpush
