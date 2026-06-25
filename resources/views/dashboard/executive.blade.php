<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Executive Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Stat Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Proker</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalProker }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Proker Aktif</div>
                    <div class="mt-2 text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $activeProker }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Anggaran</div>
                    <div class="mt-2 text-3xl font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($totalBudget, 0, ',', '.') }}</div>
                </div>
            </div>

            <!-- Recent Prokers -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold mb-4">Proker Terbaru</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-200 text-sm uppercase tracking-wider">
                                    <th class="p-4 font-semibold rounded-tl-lg">Nama Proker</th>
                                    <th class="p-4 font-semibold">Divisi</th>
                                    <th class="p-4 font-semibold">Anggaran</th>
                                    <th class="p-4 font-semibold rounded-tr-lg">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($prokers as $proker)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                    <td class="p-4">
                                        <a href="{{ route('prokers.show', $proker->id) }}" class="font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                            {{ $proker->nama_proker }}
                                        </a>
                                    </td>
                                    <td class="p-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            {{ $proker->division->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="p-4">Rp {{ number_format($proker->budget_plan, 0, ',', '.') }}</td>
                                    <td class="p-4">
                                        @if($proker->status == 'completed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400">Selesai</span>
                                        @elseif($proker->status == 'in_progress')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-400">Berjalan</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-400">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
