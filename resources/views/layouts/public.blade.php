<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sistem Informasi Kepanitiaan HIMASI')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-blurple { background-color: #635BFF; }
        .text-blurple { color: #635BFF; }
        .hover\:bg-blurple-dark:hover { background-color: #4B45C6; }
        .border-blurple { border-color: #635BFF; }
        
        /* Clean subtle pattern for sections */
        .hero-pattern {
            background-color: #ffffff;
            background-image: radial-gradient(#e2e8f0 1.5px, transparent 1.5px);
            background-size: 32px 32px;
        }
    </style>
</head>
<body class="antialiased text-slate-800 bg-slate-50 flex flex-col min-h-screen">




    


<!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-slate-200 shadow-sm">
        <div class="w-full px-6 lg:px-12 h-20 flex items-center justify-between">
            
            <!-- Left Side: Hamburger (Mobile) + Logo -->
            <div class="flex items-center gap-3">
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="lg:hidden p-1 -ml-1 text-slate-600 hover:text-blurple focus:outline-none transition-transform active:scale-95">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('logo_himasi.png') }}" alt="Logo HIMASI" class="h-10 sm:h-12 w-auto">
                </a>
            </div>

            <!-- Right Side: Navigation + CTA -->
            <div class="flex items-center gap-8">
                <!-- Navigation (Desktop) -->
                <nav class="hidden lg:flex items-center gap-8 text-sm font-semibold text-slate-600">
                    <a href="{{ route('home') }}" class="hover:text-blurple transition-colors {{ request()->routeIs('home') ? 'text-blurple' : '' }}">Beranda</a>
                    <a href="{{ route('about') }}" class="hover:text-blurple transition-colors {{ request()->routeIs('about') ? 'text-blurple' : '' }}">Tentang HIMASI</a>
                    
                    <!-- Dropdown Struktur -->
                    <div class="relative group">
                        <button class="flex items-center gap-1 hover:text-blurple transition-colors {{ request()->is('struktur*') ? 'text-blurple' : '' }}">
                            Struktur Organisasi 
                            <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute top-full left-0 mt-4 w-56 bg-white rounded-xl shadow-lg shadow-slate-200/50 border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform translate-y-2 group-hover:translate-y-0">
                            <!-- Invisible bridge to prevent hover loss -->
                            <div class="absolute -top-4 left-0 w-full h-4"></div>
                            
                            <div class="py-2">
                                <a href="{{ route('division.show', 'bph') }}" class="block px-4 py-2.5 text-sm font-medium text-slate-600 hover:text-blurple hover:bg-slate-50 transition-colors">BPH</a>
                                <a href="{{ route('division.show', 'humas') }}" class="block px-4 py-2.5 text-sm font-medium text-slate-600 hover:text-blurple hover:bg-slate-50 transition-colors">Hubungan Masyarakat</a>
                                <a href="{{ route('division.show', 'ristek') }}" class="block px-4 py-2.5 text-sm font-medium text-slate-600 hover:text-blurple hover:bg-slate-50 transition-colors">Riset dan Teknologi</a>
                                <a href="{{ route('division.show', 'danus') }}" class="block px-4 py-2.5 text-sm font-medium text-slate-600 hover:text-blurple hover:bg-slate-50 transition-colors">Dana Usaha</a>
                                <a href="{{ route('division.show', 'medinfo') }}" class="block px-4 py-2.5 text-sm font-medium text-slate-600 hover:text-blurple hover:bg-slate-50 transition-colors">Media dan Informasi</a>
                                <a href="{{ route('division.show', 'mikat') }}" class="block px-4 py-2.5 text-sm font-medium text-slate-600 hover:text-blurple hover:bg-slate-50 transition-colors">Minat dan Bakat</a>
                                <a href="{{ route('division.show', 'psda') }}" class="block px-4 py-2.5 text-sm font-medium text-slate-600 hover:text-blurple hover:bg-slate-50 transition-colors">Pengembangan SDA</a>
                                <a href="{{ route('division.show', 'sosagma') }}" class="block px-4 py-2.5 text-sm font-medium text-slate-600 hover:text-blurple hover:bg-slate-50 transition-colors">Sosial dan Agama</a>
                                <a href="{{ route('division.show', 'ppm') }}" class="block px-4 py-2.5 text-sm font-medium text-slate-600 hover:text-blurple hover:bg-slate-50 transition-colors">PPM</a>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('berita') }}" class="hover:text-blurple transition-colors {{ request()->routeIs('berita') ? 'text-blurple' : '' }}">Berita</a>
                    <a href="{{ route('galeri') }}" class="hover:text-blurple transition-colors {{ request()->routeIs('galeri') ? 'text-blurple' : '' }}">Galeri</a>
                </nav>

                <!-- CTA -->
                <a href="{{ route('login') }}" class="bg-blurple hover:bg-blurple-dark text-white text-xs sm:text-sm font-semibold py-2 sm:py-2.5 px-4 sm:px-6 rounded-xl transition-colors flex items-center gap-1 sm:gap-2 shadow-md shadow-indigo-500/20">
                    Login
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow pt-20">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 border-t border-slate-800 pt-16 pb-8 mt-auto relative overflow-hidden">
        <!-- Optional decorative element -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 lg:gap-12 mb-16">
                <div class="md:col-span-12 lg:col-span-4">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 mb-6">
                        <img src="{{ asset('logo_himasi.png') }}" alt="Logo HIMASI" class="h-9 w-auto">
                        <span class="font-bold text-2xl text-white tracking-tight">HIMASI <span class="text-indigo-400">Unja</span></span>
                    </a>
                    <p class="text-slate-400 text-[15px] leading-relaxed max-w-md mb-8">
                        Wadah aspirasi dan pengembangan potensi mahasiswa Sistem Informasi Universitas Jambi melalui inovasi, kreativitas, dan kolaborasi tanpa batas.
                    </p>
                    <div class="flex items-center gap-4">
                        <a href="https://www.instagram.com/himasiunja/" target="_blank" class="w-11 h-11 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-300 hover:bg-gradient-to-tr hover:from-orange-500 hover:via-pink-500 hover:to-purple-500 hover:text-white hover:border-transparent transition-all duration-300 shadow-sm hover:shadow-pink-500/25">
                            <span class="sr-only">Instagram</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="https://www.youtube.com/@sisteminformasiuniversitas5901/" target="_blank" class="w-11 h-11 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-300 hover:bg-red-600 hover:text-white hover:border-transparent transition-all duration-300 shadow-sm hover:shadow-red-600/25">
                            <span class="sr-only">YouTube</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 0 1-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 0 1-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 0 1 1.768-1.768C5.744 5 11.998 5 11.998 5s6.255 0 7.814.418ZM15.194 12 10 15V9l5.194 3Z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <div class="md:col-span-4 lg:col-span-2">
                    <h4 class="font-bold text-white mb-6 uppercase tracking-wider text-sm">Tautan Cepat</h4>
                    <ul class="space-y-3.5">
                        <li>
                            <a href="{{ route('home') }}" class="text-slate-400 hover:text-indigo-400 text-[15px] transition-colors flex items-center gap-2 group">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-700 group-hover:bg-indigo-400 transition-colors"></span>
                                Beranda
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('about') }}" class="text-slate-400 hover:text-indigo-400 text-[15px] transition-colors flex items-center gap-2 group">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-700 group-hover:bg-indigo-400 transition-colors"></span>
                                Tentang HIMASI
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('berita') }}" class="text-slate-400 hover:text-indigo-400 text-[15px] transition-colors flex items-center gap-2 group {{ request()->routeIs('berita') ? 'text-indigo-400' : '' }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-700 group-hover:bg-indigo-400 transition-colors {{ request()->routeIs('berita') ? 'bg-indigo-400' : '' }}"></span>
                                Berita & Kegiatan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('galeri') }}" class="text-slate-400 hover:text-indigo-400 text-[15px] transition-colors flex items-center gap-2 group {{ request()->routeIs('galeri') ? 'text-indigo-400' : '' }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-700 group-hover:bg-indigo-400 transition-colors {{ request()->routeIs('galeri') ? 'bg-indigo-400' : '' }}"></span>
                                Galeri
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="md:col-span-4 lg:col-span-3">
                    <h4 class="font-bold text-white mb-6 uppercase tracking-wider text-sm">Struktur Organisasi</h4>
                    <ul class="space-y-3.5">
                        <li>
                            <a href="{{ route('division.show', 'bph') }}" class="text-slate-400 hover:text-indigo-400 text-[15px] transition-colors flex items-center gap-2 group">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-700 group-hover:bg-indigo-400 transition-colors"></span>
                                Badan Pengurus Harian
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('division.show', 'humas') }}" class="text-slate-400 hover:text-indigo-400 text-[15px] transition-colors flex items-center gap-2 group">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-700 group-hover:bg-indigo-400 transition-colors"></span>
                                Hubungan Masyarakat
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('division.show', 'ristek') }}" class="text-slate-400 hover:text-indigo-400 text-[15px] transition-colors flex items-center gap-2 group">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-700 group-hover:bg-indigo-400 transition-colors"></span>
                                Riset dan Teknologi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('division.show', 'danus') }}" class="text-slate-400 hover:text-indigo-400 text-[15px] transition-colors flex items-center gap-2 group">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-700 group-hover:bg-indigo-400 transition-colors"></span>
                                Dana Usaha
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('division.show', 'medinfo') }}" class="text-slate-400 hover:text-indigo-400 text-[15px] transition-colors flex items-center gap-2 group">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-700 group-hover:bg-indigo-400 transition-colors"></span>
                                Media dan Informasi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('division.show', 'mikat') }}" class="text-slate-400 hover:text-indigo-400 text-[15px] transition-colors flex items-center gap-2 group">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-700 group-hover:bg-indigo-400 transition-colors"></span>
                                Minat dan Bakat
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('division.show', 'psda') }}" class="text-slate-400 hover:text-indigo-400 text-[15px] transition-colors flex items-center gap-2 group">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-700 group-hover:bg-indigo-400 transition-colors"></span>
                                Pengembangan SDA
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('division.show', 'sosagma') }}" class="text-slate-400 hover:text-indigo-400 text-[15px] transition-colors flex items-center gap-2 group">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-700 group-hover:bg-indigo-400 transition-colors"></span>
                                Sosial dan Agama
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('division.show', 'ppm') }}" class="text-slate-400 hover:text-indigo-400 text-[15px] transition-colors flex items-center gap-2 group">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-700 group-hover:bg-indigo-400 transition-colors"></span>
                                PPM
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="md:col-span-4 lg:col-span-3">
                    <h4 class="font-bold text-white mb-6 uppercase tracking-wider text-sm">Sekretariat</h4>
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-indigo-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <address class="text-slate-400 text-[15px] not-italic leading-relaxed">
                            <strong class="text-slate-300 font-semibold">Gedung Fakultas Sains dan Teknologi</strong><br/>
                            Universitas Jambi Kampus Mendalo<br/>
                            Jl. Raya Jambi - Muara Bulian KM. 15<br/>
                            Mendalo Darat, Jambi Luar Kota
                        </address>
                    </div>
                </div>
            </div>
            
            <div class="pt-8 border-t border-slate-800/60 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="text-sm text-slate-500 text-center md:text-left">
                    &copy; {{ date('Y') }} <span class="text-slate-400 font-medium">HIMASI Universitas Jambi</span>. All rights reserved.
                </div>
                <div class="flex items-center gap-6 text-sm text-slate-500">
                    <a href="#" class="hover:text-indigo-400 transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-indigo-400 transition-colors">Syarat Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Backdrop -->
    <div id="mobile-menu-backdrop" class="fixed inset-0 bg-slate-900/60 z-[60] hidden md:hidden backdrop-blur-sm transition-opacity opacity-0 duration-300"></div>

    <!-- Mobile Menu Sidebar -->
    <div id="mobile-menu-sidebar" class="fixed top-0 left-0 bottom-0 w-[280px] bg-white z-[70] transform -translate-x-full transition-transform duration-300 md:hidden flex flex-col shadow-2xl">
        <!-- Sidebar Header -->
        <div class="h-20 px-6 flex items-center justify-between border-b border-slate-100">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="{{ asset('logo_himasi.png') }}" alt="Logo HIMASI" class="h-10 w-auto">
            </a>
            <!-- Close Button -->
            <button id="mobile-menu-close" class="p-2 -mr-2 text-slate-400 hover:text-slate-800 bg-slate-50 hover:bg-slate-100 rounded-full transition-colors focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Sidebar Links -->
        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
            <a href="{{ route('home') }}" class="block px-4 py-3.5 rounded-xl text-[15px] transition-colors {{ request()->routeIs('home') ? 'bg-indigo-50 text-blurple font-bold' : 'text-slate-700 font-semibold hover:bg-slate-50 hover:text-slate-900' }}">Beranda</a>
            <a href="{{ route('about') }}" class="block px-4 py-3.5 rounded-xl text-[15px] transition-colors {{ request()->routeIs('about') ? 'bg-indigo-50 text-blurple font-bold' : 'text-slate-700 font-semibold hover:bg-slate-50 hover:text-slate-900' }}">Tentang HIMASI</a>
            
            <!-- Mobile Accordion -->
            <div>
                <button id="mobile-struktur-btn" class="w-full flex items-center justify-between px-4 py-3.5 rounded-xl text-[15px] transition-colors {{ request()->is('struktur*') ? 'bg-indigo-50 text-blurple font-bold' : 'text-slate-700 font-semibold hover:bg-slate-50' }}">
                    Struktur Organisasi
                    <svg id="mobile-struktur-icon" class="w-4 h-4 transition-transform {{ request()->is('struktur*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div id="mobile-struktur-menu" class="{{ request()->is('struktur*') ? 'block' : 'hidden' }} pl-6 pr-4 py-2 space-y-1">
                    <a href="{{ route('division.show', 'bph') }}" class="block px-4 py-2.5 text-[14px] text-slate-600 hover:text-blurple font-medium rounded-lg hover:bg-slate-50">BPH</a>
                    <a href="{{ route('division.show', 'humas') }}" class="block px-4 py-2.5 text-[14px] text-slate-600 hover:text-blurple font-medium rounded-lg hover:bg-slate-50">Hubungan Masyarakat</a>
                    <a href="{{ route('division.show', 'ristek') }}" class="block px-4 py-2.5 text-[14px] text-slate-600 hover:text-blurple font-medium rounded-lg hover:bg-slate-50">Riset dan Teknologi</a>
                    <a href="{{ route('division.show', 'danus') }}" class="block px-4 py-2.5 text-[14px] text-slate-600 hover:text-blurple font-medium rounded-lg hover:bg-slate-50">Dana Usaha</a>
                    <a href="{{ route('division.show', 'medinfo') }}" class="block px-4 py-2.5 text-[14px] text-slate-600 hover:text-blurple font-medium rounded-lg hover:bg-slate-50">Media dan Informasi</a>
                    <a href="{{ route('division.show', 'mikat') }}" class="block px-4 py-2.5 text-[14px] text-slate-600 hover:text-blurple font-medium rounded-lg hover:bg-slate-50">Minat dan Bakat</a>
                    <a href="{{ route('division.show', 'psda') }}" class="block px-4 py-2.5 text-[14px] text-slate-600 hover:text-blurple font-medium rounded-lg hover:bg-slate-50">Pengembangan SDA</a>
                    <a href="{{ route('division.show', 'sosagma') }}" class="block px-4 py-2.5 text-[14px] text-slate-600 hover:text-blurple font-medium rounded-lg hover:bg-slate-50">Sosial dan Agama</a>
                    <a href="{{ route('division.show', 'ppm') }}" class="block px-4 py-2.5 text-[14px] text-slate-600 hover:text-blurple font-medium rounded-lg hover:bg-slate-50">PPM</a>
                </div>
            </div>

            <a href="{{ route('home') }}#berita" class="block px-4 py-3.5 rounded-xl text-slate-700 font-semibold text-[15px] hover:bg-slate-50 hover:text-slate-900 transition-colors">Berita</a>
            <a href="{{ route('home') }}#galeri" class="block px-4 py-3.5 rounded-xl text-slate-700 font-semibold text-[15px] hover:bg-slate-50 hover:text-slate-900 transition-colors">Galeri</a>
        </nav>
    </div>

    <!-- Mobile Menu Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnOpen = document.getElementById('mobile-menu-btn');
            const btnClose = document.getElementById('mobile-menu-close');
            const sidebar = document.getElementById('mobile-menu-sidebar');
            const backdrop = document.getElementById('mobile-menu-backdrop');
            
            function openMenu() {
                // Show backdrop and sidebar block
                backdrop.classList.remove('hidden');
                
                // Allow browser to render display:block before applying transition
                setTimeout(() => {
                    backdrop.classList.remove('opacity-0');
                    sidebar.classList.remove('-translate-x-full');
                }, 10);
                
                // Prevent body scroll
                document.body.classList.add('overflow-hidden');
            }

            function closeMenu() {
                // Start hiding transitions
                backdrop.classList.add('opacity-0');
                sidebar.classList.add('-translate-x-full');
                
                // Wait for transition to finish before hiding block
                setTimeout(() => {
                    backdrop.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }, 300);
            }

            if(btnOpen && btnClose && sidebar && backdrop) {
                btnOpen.addEventListener('click', openMenu);
                btnClose.addEventListener('click', closeMenu);
                backdrop.addEventListener('click', closeMenu); // clicking outside closes menu

                // Accordion for mobile menu
                const strukturBtn = document.getElementById('mobile-struktur-btn');
                const strukturMenu = document.getElementById('mobile-struktur-menu');
                const strukturIcon = document.getElementById('mobile-struktur-icon');
                
                if (strukturBtn) {
                    strukturBtn.addEventListener('click', () => {
                        strukturMenu.classList.toggle('hidden');
                        strukturIcon.classList.toggle('rotate-180');
                    });
                }

                // Close menu when clicking any link that is not the accordion toggle
                const mobileLinks = sidebar.querySelectorAll('a');
                mobileLinks.forEach(link => {
                    link.addEventListener('click', closeMenu);
                });
            }
        });
    </script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 50,
        });
    </script>
</body>
</html>

