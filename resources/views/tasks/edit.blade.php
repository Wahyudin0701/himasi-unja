<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Task: ') }} {{ $task->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl p-8 border border-gray-100 dark:border-gray-700">
                
                <form action="{{ route('tasks.update', $task->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="title" :value="__('Judul Task')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $task->title)" required autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="description" :value="__('Deskripsi')" />
                        <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', $task->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Upload Bukti Pekerjaan (Opsional)</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Unggah file bukti (PDF, JPG, PNG) untuk menandai task ini selesai secara otomatis.</p>
                        
                        <div>
                            <input type="file" id="proof_file" name="proof_file" class="block w-full text-sm text-gray-500 dark:text-gray-400
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                dark:file:bg-indigo-900/50 dark:file:text-indigo-400
                                hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900
                                transition
                            "/>
                            <x-input-error :messages="$errors->get('proof_file')" class="mt-2" />
                        </div>
                        
                        @if($task->proof_file_url)
                        <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-900 rounded-lg text-sm border border-gray-200 dark:border-gray-700 flex items-center justify-between">
                            <span class="text-gray-600 dark:text-gray-300">Bukti saat ini:</span>
                            <a href="{{ asset('storage/' . $task->proof_file_url) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 font-medium hover:underline">Lihat Dokumen</a>
                        </div>
                        @endif
                    </div>

                    <div class="pt-4 flex items-center justify-end">
                        <a href="{{ route('prokers.show', $task->proker_id) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                            Batal
                        </a>
                        <x-primary-button>
                            {{ __('Simpan Perubahan') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
