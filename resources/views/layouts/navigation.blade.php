<!-- Sidebar Overlay for mobile -->
<div x-show="sidebarOpen" class="fixed inset-0 z-20 transition-opacity bg-gray-900/60 backdrop-blur-sm lg:hidden" @click="sidebarOpen = false" x-transition.opacity style="display: none;"></div>

<!-- Sidebar content -->
<div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-72 overflow-y-auto transition-transform duration-300 transform bg-white border-r border-gray-200 lg:translate-x-0 lg:static lg:inset-0 lg:flex lg:flex-col shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
    
    <!-- Logo area -->
    <div class="flex items-center justify-center mt-8 mb-6">
        <div class="flex items-center">
            <a href="{{ route('dashboard') }}" class="flex items-center group">
                <img src="{{ asset('logo_himasi.png') }}" alt="HIMASI Logo" class="h-12 w-auto object-contain group-hover:scale-105 transition-transform duration-300">
            </a>
        </div>
    </div>

    <!-- Divider -->
    <div class="mx-6 mb-6 border-b border-gray-100"></div>

    <!-- Navigation Links -->
    <nav class="px-4 space-y-1.5 flex-1">
        
        <div class="px-4 py-2 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
            Utama
        </div>

        <a class="flex items-center px-4 py-3 text-gray-600 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'hover:bg-gray-50 hover:text-gray-900 transition-all duration-200' }} rounded-xl" href="{{ route('dashboard') }}">
            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            <span class="mx-3">Dashboard</span>
        </a>

        @if(auth()->user()->global_role === 'super_admin')
        <a class="flex items-center px-4 py-3 text-gray-600 {{ request()->routeIs('kepengurusan.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'hover:bg-gray-50 hover:text-gray-900 transition-all duration-200' }} rounded-xl" href="{{ route('kepengurusan.index') }}">
            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('kepengurusan.*') ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <span class="mx-3">Kepengurusan</span>
        </a>
        @endif

        @if(in_array(auth()->user()->global_role, ['super_admin', 'kahim', 'kadiv']) || (auth()->user()->divisi && auth()->user()->divisi->nama_divisi === 'BPH'))
        <div class="pt-4 pb-2">
            <div class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Manajemen
            </div>
        </div>
        <a class="flex items-center px-4 py-3 text-gray-600 {{ request()->routeIs('prokers.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'hover:bg-gray-50 hover:text-gray-900 transition-all duration-200' }} rounded-xl" href="{{ route('prokers.index') }}">
            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('prokers.*') ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <span class="mx-3">Kelola Proker</span>
        </a>
        @endif
    </nav>

    <!-- User Profile Badge at Bottom of Sidebar -->
    <div class="p-4 mt-auto">
        <div class="bg-gray-50 rounded-2xl p-4 flex items-center gap-3 border border-gray-100 hover:border-gray-200 hover:shadow-sm transition-all cursor-pointer group">
            <div class="relative">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-200 flex items-center justify-center text-indigo-700 font-bold border border-indigo-50">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-gray-900 truncate group-hover:text-indigo-600 transition-colors">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ ucfirst(str_replace('_', ' ', Auth::user()->global_role)) }}</p>
            </div>
        </div>
    </div>
</div>
