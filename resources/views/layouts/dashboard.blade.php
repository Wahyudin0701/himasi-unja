<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — HIMASI Management</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800;0,14..32,900;1,14..32,400&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50:  '#eeeeff',
                            100: '#dddeff',
                            200: '#bbb8ff',
                            300: '#9890ff',
                            400: '#7a6fff',
                            500: '#635BFF',
                            600: '#4B45C6',
                            700: '#3730a3',
                            800: '#2d2b7a',
                            900: '#1e1c55',
                        },
                    }
                }
            }
        }
    </script>

    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Active nav item */
        .nav-active {
            background: linear-gradient(135deg, #635BFF, #4B45C6);
            color: #ffffff !important;
            font-weight: 700;
            box-shadow: 0 10px 15px -3px rgba(99, 91, 255, 0.3), 0 4px 6px -4px rgba(99, 91, 255, 0.2);
            transform: translateY(-1px);
        }
        .nav-active .nav-icon { color: #ffffff !important; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

        /* Sidebar link hover */
        .nav-link {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .nav-link:hover:not(.nav-active) {
            background: #f1f5f9;
            color: #635BFF;
            transform: translateX(4px);
        }
        .nav-link:hover:not(.nav-active) .nav-icon { color: #635BFF; }

        /* Card hover */
        .stat-card:hover { transform: translateY(-2px); }
        .stat-card { transition: all 0.2s ease; }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #635BFF, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased" x-data="{ sidebarOpen: false }">

    <!-- Mobile overlay -->
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-linear duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm lg:hidden"
         @click="sidebarOpen = false"></div>

    <!-- ===== SIDEBAR ===== -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-100 flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0 shadow-[4px_0_24px_rgba(0,0,0,0.02)]">

        <!-- Logo -->
        <div class="flex justify-center items-center h-20 px-5 border-b border-slate-100 shrink-0">
            <a href="{{ route('home') }}" class="flex items-center justify-center min-w-0 transition-transform hover:scale-105">
                <img src="{{ asset('logo_himasi.png') }}" alt="HIMASI" class="h-14 w-auto shrink-0">
            </a>
            <button @click="sidebarOpen = false" class="ml-auto lg:hidden p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100">
                <i class="ph ph-x text-lg"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-8">

            <!-- Overview -->
            <div>
                <p class="px-3 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-3">Menu Utama</p>
                <div class="space-y-1.5">
                    <a href="{{ route('dashboard') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-600 {{ request()->routeIs('dashboard', 'kepanitiaan.*') && !request()->routeIs('kepanitiaan.anggota.divisi') && !request()->routeIs('kepanitiaan.co.sprints.*') ? 'nav-active' : '' }}">
                        <i class="ph-fill ph-squares-four nav-icon text-[20px] text-slate-400 shrink-0 transition-colors"></i>
                        Dashboard
                    </a>

                    @if(auth()->user()->hasActiveCORole() || auth()->user()->hasActiveKetupelRole())
                    <a href="{{ route('kepanitiaan.anggota.divisi') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-600 {{ request()->routeIs('kepanitiaan.anggota.divisi') ? 'nav-active' : '' }}">
                        <i class="ph-fill ph-users-three nav-icon text-[20px] text-slate-400 shrink-0 transition-colors"></i>
                        Anggota Divisi
                    </a>
                    @endif

                    @if(auth()->user()->hasActiveCORole())
                    <a href="{{ route('kepanitiaan.co.sprints.index') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-600 {{ request()->routeIs('kepanitiaan.co.sprints.*') ? 'nav-active' : '' }}">
                        <i class="ph-fill ph-calendar-check nav-icon text-[20px] text-slate-400 shrink-0 transition-colors"></i>
                        Pengaturan Sprint
                    </a>
                    @endif
                </div>
            </div>


        </nav>

        <!-- User Profile -->
        <div class="shrink-0 p-4 border-t border-slate-100 bg-white">
            <div class="flex items-center gap-3 w-full">
                <!-- Profile details -->
                <div class="flex-1 flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 shadow-sm ring-2 ring-white overflow-hidden bg-brand-500">
                        <span class="text-base font-bold text-white">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] font-bold text-slate-900 uppercase truncate">{{ auth()->user()->name }}</p>
                        @php
                            $roleName = 'Kepanitiaan';
                            if (auth()->user()->hasActiveKetupelRole()) {
                                $roleName = 'Ketua Pelaksana';
                            } elseif (auth()->user()->hasActiveSekpelBenpelRole()) {
                                $roleName = 'Inti Pelaksana';
                            } elseif (auth()->user()->hasActiveCORole()) {
                                $roleName = 'CO Divisi';
                            } elseif (auth()->user()->hasActiveAnggotaRole()) {
                                $roleName = 'Anggota Divisi';
                            }
                        @endphp
                        <p class="text-[11px] font-medium text-slate-400 truncate">{{ $roleName }}</p>
                    </div>
                </div>
                
                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                    @csrf
                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Keluar">
                        <i class="ph ph-sign-out text-[22px]"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="lg:ml-64 min-h-screen flex flex-col">

        <!-- Topbar -->
        <header class="sticky top-0 z-30 h-20 bg-white/90 backdrop-blur-md border-b border-slate-100 flex items-center justify-between px-5 sm:px-8">
            <div class="flex items-center gap-4">
                <!-- Mobile hamburger -->
                <button @click="sidebarOpen = true" class="lg:hidden p-2 -ml-1 text-slate-500 hover:text-brand-600 rounded-xl hover:bg-brand-50 transition-colors">
                    <i class="ph ph-list text-xl"></i>
                </button>

                <!-- Page title & breadcrumbs -->
                <div class="flex flex-col justify-center">
                    <h2 class="text-[20px] font-bold text-slate-900 leading-tight">@yield('title', 'Dashboard')</h2>
                    <div class="hidden sm:flex items-center gap-1.5 text-[13px] text-slate-500 font-normal">
                        @yield('breadcrumbs', 'Kepanitiaan')
                    </div>
                </div>
            </div>

            <!-- Right actions -->
            <div class="flex items-center gap-3 sm:gap-4">
                <button class="relative p-2 text-slate-400 hover:text-brand-600 rounded-xl hover:bg-brand-50 transition-colors">
                    <i class="ph ph-bell text-xl"></i>
                    <span class="absolute top-2 right-2 w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                </button>
                
                <!-- User avatar with dropdown -->
                <div class="relative" x-data="{ userMenuOpen: false }">
                    <button @click="userMenuOpen = !userMenuOpen" class="flex items-center gap-2.5 focus:outline-none hover:bg-slate-50 p-1.5 -mr-1.5 rounded-xl transition-colors">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 shadow-sm ring-2 ring-slate-100 bg-brand-500">
                            <span class="text-sm font-bold text-white">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        </div>
                        <span class="hidden sm:block text-sm font-semibold text-slate-700">{{ auth()->user()->name }}</span>
                        <i class="ph ph-caret-down text-slate-400 hidden sm:block text-xs"></i>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="userMenuOpen" @click.outside="userMenuOpen = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute right-0 top-full mt-1 w-48 bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden z-50">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-3 text-sm text-rose-600 hover:bg-rose-50 transition-colors font-medium">
                                <i class="ph ph-sign-out text-lg"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-5 sm:p-8">
            <div class="max-w-7xl mx-auto">

                @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
                     class="mb-6 flex items-center gap-3 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800">
                    <i class="ph-fill ph-check-circle text-xl text-emerald-500 shrink-0"></i>
                    <span class="text-sm font-medium flex-1">{{ session('success') }}</span>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600">
                        <i class="ph ph-x"></i>
                    </button>
                </div>
                @endif

                @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition
                     class="mb-6 flex items-center gap-3 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800">
                    <i class="ph-fill ph-warning-circle text-xl text-rose-500 shrink-0"></i>
                    <span class="text-sm font-medium flex-1">{{ session('error') }}</span>
                    <button @click="show = false" class="text-rose-400 hover:text-rose-600">
                        <i class="ph ph-x"></i>
                    </button>
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
