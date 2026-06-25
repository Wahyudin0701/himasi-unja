@props(['task'])

<div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 cursor-grab hover:shadow-md transition relative group" data-task-id="{{ $task->id }}">
    <div class="flex justify-between items-start mb-2">
        <h4 class="font-bold text-gray-900 dark:text-white text-sm">{{ $task->title }}</h4>
        <div class="opacity-0 group-hover:opacity-100 transition">
            <a href="{{ route('tasks.edit', $task->id) }}" class="text-gray-400 hover:text-indigo-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
            </a>
        </div>
    </div>
    
    <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 mb-3">{{ $task->description }}</p>
    
    <div class="flex items-center justify-between mt-auto pt-2 border-t border-gray-100 dark:border-gray-700">
        <div class="flex items-center space-x-2 text-xs">
            <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 py-1 px-2 rounded-md font-medium" title="Kepanitiaan">
                {{ substr($task->kepanitiaan->user->name, 0, 10) }}...
            </span>
        </div>
        <div class="flex items-center text-xs {{ \Carbon\Carbon::parse($task->due_date)->isPast() && $task->status != 'done' ? 'text-red-500 font-bold' : 'text-gray-400' }}">
            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            {{ \Carbon\Carbon::parse($task->due_date)->format('d M') }}
        </div>
    </div>
    @if($task->status == 'done' && $task->proof_file_url)
        <div class="mt-2">
            <a href="{{ asset('storage/' . $task->proof_file_url) }}" target="_blank" class="text-xs text-green-600 hover:underline flex items-center">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                Lihat Bukti
            </a>
        </div>
    @endif
</div>
