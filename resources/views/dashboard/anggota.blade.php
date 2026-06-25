<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Tugas Mendatang</h3>

            <div class="space-y-4">
                @forelse($tasks as $task)
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-1">{{ $task->title }}</h4>
                            <p class="text-sm text-gray-500 mb-3">{{ $task->description }}</p>
                            
                            <div class="flex items-center space-x-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-400">
                                    {{ $task->proker->nama_proker }}
                                </span>
                                <span class="text-xs text-red-500 font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Due: {{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex flex-col items-end space-y-2">
                            @if($task->status == 'to_do')
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">To Do</span>
                            @elseif($task->status == 'doing')
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-400">Doing</span>
                            @endif
                            <a href="{{ route('prokers.show', $task->proker_id) }}" class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">Lihat Board &rarr;</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-10 text-center border border-gray-100 dark:border-gray-700">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-200">Tidak ada tugas</h3>
                    <p class="mt-1 text-sm text-gray-500">Anda tidak memiliki tugas yang mendesak saat ini.</p>
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
