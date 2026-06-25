<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col justify-center">
            <h2 class="text-xl font-bold text-slate-800 leading-tight">
                Dashboard Divisi <span class="text-[#2563eb]">{{ auth()->user()->division->name ?? 'N/A' }}</span>
            </h2>
            <span class="text-sm font-medium text-slate-500 mt-0.5">Welcome back, {{ explode(' ', trim(auth()->user()->name))[0] }}</span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Welcome Banner -->
            <div class="mb-8 relative overflow-hidden bg-gradient-to-r from-[#1e3a8a] to-[#2563eb] rounded-3xl p-8 sm:p-10 shadow-[0_8px_30px_rgb(37,99,235,0.2)]">
                <!-- Decorative background elements -->
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white opacity-10 blur-3xl"></div>
                <div class="absolute bottom-0 right-20 -mb-20 w-48 h-48 rounded-full bg-indigo-300 opacity-20 blur-2xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="text-white">
                        <div class="inline-flex items-center px-3 py-1 rounded-full bg-white/10 border border-white/20 text-xs font-semibold text-blue-100 mb-4 backdrop-blur-sm shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Panel Kepala Divisi
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-bold mb-2 tracking-tight">Selamat Datang, {{ explode(' ', trim(auth()->user()->name))[0] }}</h2>
                        <p class="text-blue-100/90 max-w-xl text-sm sm:text-base leading-relaxed">
                            Kelola program kerja divisi {{ auth()->user()->division->name ?? 'Anda' }}, pantau progres anggota, dan pastikan seluruh target himpunan tercapai dengan baik.
                        </p>
                    </div>
                    
                    <div class="flex-shrink-0 md:text-right">
                        <a href="{{ route('prokers.index') }}" class="inline-flex items-center justify-center bg-white text-[#1e3a8a] hover:bg-blue-50 font-bold py-3 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1">
                            <svg class="w-5 h-5 mr-2 text-[#2563eb]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            Kelola Program Kerja
                        </a>
                        <div class="text-blue-200 text-xs mt-4 font-medium tracking-wide">
                            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stat Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Proker -->
                <div class="bg-white rounded-2xl p-6 shadow-[0_2px_12px_-3px_rgba(6,81,237,0.08)] border border-slate-100 hover:-translate-y-1 transition transform duration-300 group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-slate-500 text-sm font-bold mb-2 uppercase tracking-wider">Total Proker Divisi</p>
                            <h3 class="text-4xl font-black text-slate-800">{{ $prokers->count() }}</h3>
                        </div>
                        <div class="p-3.5 bg-blue-50 text-[#2563eb] rounded-xl group-hover:bg-[#2563eb] group-hover:text-white transition-colors duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Proker Aktif -->
                <div class="bg-white rounded-2xl p-6 shadow-[0_2px_12px_-3px_rgba(6,81,237,0.08)] border border-slate-100 hover:-translate-y-1 transition transform duration-300 group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-slate-500 text-sm font-bold mb-2 uppercase tracking-wider">Proker Aktif</p>
                            <h3 class="text-4xl font-black text-slate-800">{{ $prokers->where('status', 'in_progress')->count() }}</h3>
                        </div>
                        <div class="p-3.5 bg-emerald-50 text-emerald-600 rounded-xl group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Total Anggota -->
                <div class="bg-white rounded-2xl p-6 shadow-[0_2px_12px_-3px_rgba(6,81,237,0.08)] border border-slate-100 hover:-translate-y-1 transition transform duration-300 group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-slate-500 text-sm font-bold mb-2 uppercase tracking-wider">Total Anggota</p>
                            <h3 class="text-4xl font-black text-slate-800">{{ $members->count() }}</h3>
                        </div>
                        <div class="p-3.5 bg-amber-50 text-amber-500 rounded-xl group-hover:bg-amber-500 group-hover:text-white transition-colors duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Proker Section -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="flex justify-between items-end mb-4">
                        <div>
                            <h3 class="text-[22px] font-bold text-slate-800">Program Kerja</h3>
                            <p class="text-slate-500 font-medium text-sm mt-1">Daftar program kerja di divisi Anda</p>
                        </div>
                        <a href="{{ route('prokers.create') }}" class="inline-flex items-center justify-center bg-[#2563eb] hover:bg-[#1d4ed8] text-white font-semibold py-2.5 px-5 rounded-xl transition-all duration-200 shadow-[0_4px_12px_rgba(37,99,235,0.2)] hover:shadow-[0_6px_16px_rgba(37,99,235,0.3)] hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Buat Proker Baru
                        </a>
                    </div>

                    <div class="space-y-4 relative">
                    @forelse($prokers as $proker)
                        <div class="group relative bg-white rounded-2xl p-6 shadow-[0_2px_12px_-3px_rgba(6,81,237,0.06)] border border-slate-100 hover:shadow-[0_8px_24px_-4px_rgba(6,81,237,0.12)] hover:border-blue-200 transition-all duration-300">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                                <div class="flex-1">
                                    <div class="flex flex-wrap items-center gap-2 mb-3">
                                        @if($proker->status == 'completed')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Selesai</span>
                                        @elseif($proker->status == 'in_progress')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-[#2563eb] border border-blue-100"><span class="w-1.5 h-1.5 rounded-full bg-[#2563eb] mr-1.5 animate-pulse"></span> Berjalan</span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-600 border border-amber-100"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> Pending</span>
                                        @endif
                                        
                                        <span class="text-xs text-slate-600 font-bold bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200/60 tabular-nums">
                                            Rp {{ number_format($proker->budget_plan, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <h4 class="text-xl font-bold text-slate-800 group-hover:text-[#2563eb] transition-colors">
                                        <a href="{{ route('prokers.show', $proker->id) }}" class="focus:outline-none before:absolute before:inset-0">
                                            {{ $proker->nama_proker }}
                                        </a>
                                    </h4>
                                    <p class="text-sm text-slate-500 font-medium mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ \Carbon\Carbon::parse($proker->start_date)->format('d M Y') }} &mdash; {{ \Carbon\Carbon::parse($proker->end_date)->format('d M Y') }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-[#2563eb] group-hover:text-white group-hover:border-[#2563eb] transition-all duration-300 shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-2xl p-12 text-center border border-dashed border-slate-300 shadow-sm">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4 text-slate-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800">Belum ada Proker</h3>
                            <p class="text-slate-500 font-medium mt-1 max-w-sm mx-auto">Divisi ini belum memiliki program kerja. Klik tombol di atas untuk membuat proker baru.</p>
                        </div>
                    @endforelse
                    </div>
                </div>

                <!-- Members Section -->
                <div>
                    <div class="mb-4">
                        <h3 class="text-[22px] font-bold text-slate-800">Tim Divisi</h3>
                        <p class="text-slate-500 font-medium text-sm mt-1">Anggota dalam divisi Anda</p>
                    </div>
                    
                    <div class="bg-white rounded-2xl shadow-[0_2px_12px_-3px_rgba(6,81,237,0.08)] border border-slate-100 overflow-hidden">
                        <div class="p-4 border-b border-slate-100 bg-slate-50/80 flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total: {{ $members->count() }} Anggota</span>
                        </div>
                        <ul class="divide-y divide-slate-100 max-h-[500px] overflow-y-auto custom-scrollbar">
                            @forelse($members as $member)
                            <li class="p-4 flex items-center space-x-4 hover:bg-slate-50/50 transition duration-200">
                                <div class="flex-shrink-0 relative">
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-200 border border-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-lg shadow-sm">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                    <span class="absolute bottom-0 right-0 block h-3 w-3 rounded-full bg-emerald-500 ring-2 ring-white"></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-slate-800 truncate">
                                        {{ $member->name }}
                                    </p>
                                    <p class="text-xs text-slate-500 font-medium truncate mt-0.5">
                                        {{ $member->email }}
                                    </p>
                                </div>
                            </li>
                            @empty
                            <li class="p-8 text-center text-slate-500 text-sm font-medium">
                                Belum ada anggota lain di divisi ini.
                            </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }
    </style>
</x-app-layout>

