<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $proker->nama_proker }} - Kanban Board
            </h2>
            <a href="{{ route('prokers.index') }}" class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="kanbanBoard()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-sm" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <!-- Struktur Kepanitiaan Section -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl p-6 mb-6 border border-gray-100 dark:border-gray-700" x-data="{ openRecruit: false, recruitMode: 'existing' }">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Struktur Kepanitiaan</h3>
                    @if($isSuperAdmin || $myProjectRole === 'ketupel' || $myProjectRole === 'co')
                    <button @click="openRecruit = !openRecruit" class="text-sm bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-bold px-4 py-2 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition">
                        + Tambah Panitia
                    </button>
                    @endif
                </div>
                
                <!-- Recruitment Form -->
                <div x-show="openRecruit" style="display: none;" class="mb-6 p-4 border border-indigo-100 dark:border-indigo-900/50 rounded-lg bg-indigo-50/50 dark:bg-indigo-900/10">
                    <form action="{{ route('kepanitiaans.store', $proker->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="flex space-x-4 mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                            <button type="button" @click="recruitMode = 'existing'" :class="{'text-indigo-600 font-bold border-b-2 border-indigo-600': recruitMode === 'existing'}" class="pb-2 text-sm text-gray-500">Pilih Pengurus HIMA</button>
                            <button type="button" @click="recruitMode = 'new_volunteer'" :class="{'text-indigo-600 font-bold border-b-2 border-indigo-600': recruitMode === 'new_volunteer'}" class="pb-2 text-sm text-gray-500">Buat Akun Volunteer</button>
                        </div>
                        
                        <input type="hidden" name="user_type" x-model="recruitMode">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Existing User Select -->
                            <div x-show="recruitMode === 'existing'">
                                <x-input-label for="user_id" :value="__('Pilih Pengurus')" />
                                <select name="user_id" id="user_id" class="block mt-1 w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md">
                                    <option value="">-- Pilih --</option>
                                    @foreach($allUsers as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->divisi->nama_divisi ?? 'N/A' }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- New Volunteer Inputs -->
                            <div x-show="recruitMode === 'new_volunteer'" class="space-y-4">
                                <div>
                                    <x-input-label for="name" :value="__('Nama Volunteer')" />
                                    <x-text-input id="name" class="block mt-1 w-full text-sm" type="text" name="name" />
                                </div>
                                <div>
                                    <x-input-label for="email" :value="__('Email Volunteer')" />
                                    <x-text-input id="email" class="block mt-1 w-full text-sm" type="email" name="email" />
                                </div>
                            </div>
                            
                            <!-- Role Assignment -->
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="divisi_kepanitiaan" :value="__('Divisi Acara (misal: Acara, Pubdekdok)')" />
                                    <x-text-input id="divisi_kepanitiaan" class="block mt-1 w-full text-sm" type="text" name="divisi_kepanitiaan" required />
                                </div>
                                <div>
                                    <x-input-label for="project_role" :value="__('Jabatan di Acara')" />
                                    <select name="project_role" id="project_role" class="block mt-1 w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md" required>
                                        @if($isSuperAdmin)
                                        <option value="ketupel">Ketua Pelaksana (Ketupel)</option>
                                        @endif
                                        @if($isSuperAdmin || $myProjectRole === 'ketupel')
                                        <option value="co">Coordinator Divisi</option>
                                        @endif
                                        <option value="anggota">Anggota Divisi</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <x-primary-button>Tambahkan Panitia</x-primary-button>
                        </div>
                    </form>
                </div>

                <!-- List of Panitia -->
                <div class="flex flex-wrap gap-2">
                    @foreach($proker->kepanitiaans as $pan)
                    <div class="flex items-center space-x-2 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-full px-3 py-1">
                        <span class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $pan->user->name }}</span>
                        <span class="text-xs text-gray-500 bg-gray-200 dark:bg-gray-600 px-2 py-0.5 rounded-full">{{ $pan->divisi_kepanitiaan }} ({{ strtoupper($pan->project_role) }})</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- To Do Column -->
                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-md font-bold text-gray-700 dark:text-gray-300 mb-4 flex items-center justify-between">
                        <span>To Do</span>
                        <span class="bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 text-xs py-1 px-2 rounded-full">{{ count($tasks['to_do']) }}</span>
                    </h3>
                    <div class="kanban-col min-h-[500px] space-y-3" data-status="to_do">
                        @foreach($tasks['to_do'] as $task)
                            <x-kanban-card :task="$task" />
                        @endforeach
                    </div>
                </div>

                <!-- Doing Column -->
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-100 dark:border-blue-800/50">
                    <h3 class="text-md font-bold text-blue-800 dark:text-blue-300 mb-4 flex items-center justify-between">
                        <span>Doing</span>
                        <span class="bg-blue-200 text-blue-800 dark:bg-blue-800 dark:text-blue-300 text-xs py-1 px-2 rounded-full">{{ count($tasks['doing']) }}</span>
                    </h3>
                    <div class="kanban-col min-h-[500px] space-y-3" data-status="doing">
                        @foreach($tasks['doing'] as $task)
                            <x-kanban-card :task="$task" />
                        @endforeach
                    </div>
                </div>

                <!-- Done Column -->
                <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 border border-green-100 dark:border-green-800/50">
                    <h3 class="text-md font-bold text-green-800 dark:text-green-300 mb-4 flex items-center justify-between">
                        <span>Done</span>
                        <span class="bg-green-200 text-green-800 dark:bg-green-800 dark:text-green-300 text-xs py-1 px-2 rounded-full">{{ count($tasks['done']) }}</span>
                    </h3>
                    <div class="kanban-col min-h-[500px] space-y-3" data-status="done">
                        @foreach($tasks['done'] as $task)
                            <x-kanban-card :task="$task" />
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Load SortableJS -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        function kanbanBoard() {
            return {
                init() {
                    const columns = document.querySelectorAll('.kanban-col');
                    columns.forEach(col => {
                        new Sortable(col, {
                            group: 'shared',
                            animation: 150,
                            ghostClass: 'opacity-50',
                            onEnd: (evt) => {
                                const taskId = evt.item.dataset.taskId;
                                const newStatus = evt.to.dataset.status;
                                
                                fetch(`/tasks/${taskId}/status`, {
                                    method: 'PATCH',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({ status: newStatus })
                                })
                                .then(response => {
                                    if(response.ok && newStatus === 'done') {
                                        // Optional: Prompt user to upload proof if moved to done
                                        window.location.href = `/tasks/${taskId}/edit`;
                                    }
                                });
                            }
                        });
                    });
                }
            }
        }
    </script>
</x-app-layout>
