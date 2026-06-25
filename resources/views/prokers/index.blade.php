<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-2">
            <div>
                <h2 class="text-[22px] font-bold text-slate-800 tracking-tight">
                    Kelola Proker
                </h2>
                <p class="text-sm text-slate-500 font-medium mt-1">
                    Manajemen Program Kerja Himasi
                </p>
            </div>
            <a href="{{ route('prokers.create') }}" class="inline-flex items-center justify-center bg-[#2563eb] hover:bg-[#1d4ed8] text-white font-semibold py-2.5 px-5 rounded-xl transition-all duration-200 shadow-[0_4px_12px_rgba(37,99,235,0.2)] hover:shadow-[0_6px_16px_rgba(37,99,235,0.3)] hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Buat Proker Baru
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-100 text-emerald-600 px-5 py-4 rounded-xl flex items-center shadow-sm" role="alert">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="block sm:inline font-medium">{{ session('success') }}</span>
            </div>
            @endif

            <!-- Table Container -->
            <div class="bg-white rounded-2xl shadow-[0_2px_12px_-3px_rgba(6,81,237,0.08)] border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100">
                                <th scope="col" class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Proker</th>
                                <th scope="col" class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Divisi</th>
                                <th scope="col" class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Jadwal</th>
                                <th scope="col" class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Anggaran</th>
                                <th scope="col" class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach($prokers as $proker)
                            <tr class="hover:bg-slate-50/50 transition duration-150 group">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-slate-800 group-hover:text-[#2563eb] transition-colors">{{ $proker->nama_proker }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200/60">
                                        {{ $proker->divisi->nama_divisi }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-500 font-medium flex items-center">
                                        <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ \Carbon\Carbon::parse($proker->start_date)->format('d M y') }} - 
                                        {{ \Carbon\Carbon::parse($proker->end_date)->format('d M y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-slate-700 tabular-nums">
                                        Rp {{ number_format($proker->budget_plan, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($proker->status == 'completed')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Selesai
                                        </span>
                                    @elseif($proker->status == 'in_progress')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-[#2563eb] border border-blue-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#2563eb] mr-1.5 animate-pulse"></span> Berjalan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-600 border border-amber-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('prokers.show', $proker->id) }}" class="inline-flex items-center text-[#2563eb] hover:text-[#1d4ed8] text-sm font-bold transition duration-150">
                                        Kanban Board
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    @if($prokers->isEmpty())
                    <div class="p-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4 text-slate-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-800">Tidak ada proker</h3>
                        <p class="mt-1 text-sm text-slate-500">Belum ada program kerja yang ditambahkan.</p>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
