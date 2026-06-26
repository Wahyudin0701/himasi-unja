@extends('layouts.public')

@push('styles')
<style>
    @keyframes shimmer { 0%{background-position:200% center} 100%{background-position:-200% center} }
    .shimmer-text {
        background: linear-gradient(90deg,#6366f1,#8b5cf6,#06b6d4,#6366f1);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: shimmer 4s linear infinite;
    }
</style>
@endpush

@section('content')
    <!-- Galeri Hero Section -->
    <section class="relative pt-24 pb-16 md:pt-32 md:pb-24 overflow-hidden bg-slate-900 border-b border-slate-800">
        <!-- Decorative Background -->
        <div class="absolute inset-0 z-0 opacity-40">
            <img src="{{ asset('pengurus_himasi.png') }}" alt="Galeri Background" class="w-full h-full object-cover object-center filter grayscale mix-blend-luminosity">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/80 to-slate-900/50 z-0"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
            <div class="inline-block mb-4 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 backdrop-blur-md" data-aos="fade-down">
                <span class="text-sm font-bold text-indigo-300 tracking-wider uppercase">Galeri Kegiatan</span>
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white tracking-tight leading-tight" data-aos="fade-up" data-aos-delay="100">
                Momen & Kenangan <br class="hidden md:block" />
                <span class="shimmer-text">HIMASI Unja</span>
            </h1>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="py-20 bg-slate-50 relative">
        <div class="max-w-7xl mx-auto px-6">
            
            <div class="text-center mb-12" data-aos="fade-up">
                <p class="text-lg md:text-xl text-slate-600 max-w-2xl mx-auto leading-relaxed">
                    Kumpulan dokumentasi berbagai kegiatan, program kerja, dan kebersamaan keluarga besar Sistem Informasi.
                </p>
            </div>
            
            <!-- Filter Categories -->
            <div class="flex flex-wrap items-center justify-center gap-3 mb-16" data-aos="fade-up">
                <button class="px-6 py-2.5 rounded-full bg-blurple text-white font-medium text-sm shadow-md shadow-indigo-500/20 transition-all">Semua Foto</button>
                <button class="px-6 py-2.5 rounded-full bg-white text-slate-600 hover:text-blurple font-medium text-sm border border-slate-200 hover:border-indigo-300 shadow-sm transition-all">Kepanitiaan</button>
                <button class="px-6 py-2.5 rounded-full bg-white text-slate-600 hover:text-blurple font-medium text-sm border border-slate-200 hover:border-indigo-300 shadow-sm transition-all">Seminar & Workshop</button>
                <button class="px-6 py-2.5 rounded-full bg-white text-slate-600 hover:text-blurple font-medium text-sm border border-slate-200 hover:border-indigo-300 shadow-sm transition-all">Rapat Organisasi</button>
                <button class="px-6 py-2.5 rounded-full bg-white text-slate-600 hover:text-blurple font-medium text-sm border border-slate-200 hover:border-indigo-300 shadow-sm transition-all">Momen Santai</button>
            </div>

            <!-- CSS Masonry Grid -->
            <div class="columns-1 sm:columns-2 lg:columns-3 gap-6 space-y-6">
                
                <!-- Gallery Item 1 -->
                <div class="group relative rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-indigo-500/20 transition-all duration-500 break-inside-avoid" data-aos="fade-up">
                    <!-- Aspect ratio mapping using padding or just auto height for masonry -->
                    <div class="relative w-full aspect-[4/3] bg-gradient-to-tr from-indigo-500 to-purple-600">
                        <!-- Simulated Image/Gradient -->
                        <div class="absolute inset-0 flex items-center justify-center text-white/40 group-hover:scale-110 transition-transform duration-700">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <!-- Content -->
                        <div class="absolute bottom-0 left-0 right-0 p-6 translate-y-4 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <span class="inline-block px-3 py-1 bg-indigo-500/20 backdrop-blur-md text-indigo-200 text-xs font-bold rounded-full mb-2">Seminar & Workshop</span>
                            <h3 class="text-white font-bold text-lg leading-tight">Malam Puncak IT Festival 2026</h3>
                            <p class="text-slate-300 text-sm mt-1">15 April 2026</p>
                        </div>
                    </div>
                </div>

                <!-- Gallery Item 2 -->
                <div class="group relative rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-cyan-500/20 transition-all duration-500 break-inside-avoid" data-aos="fade-up" data-aos-delay="100">
                    <div class="relative w-full aspect-[3/4] bg-gradient-to-tr from-cyan-500 to-blue-600">
                        <div class="absolute inset-0 flex items-center justify-center text-white/40 group-hover:scale-110 transition-transform duration-700">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-6 translate-y-4 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <span class="inline-block px-3 py-1 bg-cyan-500/20 backdrop-blur-md text-cyan-200 text-xs font-bold rounded-full mb-2">Rapat Organisasi</span>
                            <h3 class="text-white font-bold text-lg leading-tight">Musyawarah Besar HIMASI</h3>
                            <p class="text-slate-300 text-sm mt-1">10 Januari 2026</p>
                        </div>
                    </div>
                </div>

                <!-- Gallery Item 3 -->
                <div class="group relative rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-emerald-500/20 transition-all duration-500 break-inside-avoid" data-aos="fade-up" data-aos-delay="200">
                    <div class="relative w-full aspect-square bg-gradient-to-tr from-emerald-400 to-teal-500">
                        <div class="absolute inset-0 flex items-center justify-center text-white/40 group-hover:scale-110 transition-transform duration-700">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-6 translate-y-4 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <span class="inline-block px-3 py-1 bg-emerald-500/20 backdrop-blur-md text-emerald-200 text-xs font-bold rounded-full mb-2">Momen Santai</span>
                            <h3 class="text-white font-bold text-lg leading-tight">Makrab Mahasiswa Baru</h3>
                            <p class="text-slate-300 text-sm mt-1">20 Agustus 2025</p>
                        </div>
                    </div>
                </div>

                <!-- Gallery Item 4 -->
                <div class="group relative rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-orange-500/20 transition-all duration-500 break-inside-avoid" data-aos="fade-up">
                    <div class="relative w-full aspect-[4/5] bg-gradient-to-tr from-orange-400 to-rose-500">
                        <div class="absolute inset-0 flex items-center justify-center text-white/40 group-hover:scale-110 transition-transform duration-700">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-6 translate-y-4 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <span class="inline-block px-3 py-1 bg-orange-500/20 backdrop-blur-md text-orange-200 text-xs font-bold rounded-full mb-2">Kepanitiaan</span>
                            <h3 class="text-white font-bold text-lg leading-tight">Persiapan Lomba Nasional</h3>
                            <p class="text-slate-300 text-sm mt-1">05 November 2025</p>
                        </div>
                    </div>
                </div>

                <!-- Gallery Item 5 -->
                <div class="group relative rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-fuchsia-500/20 transition-all duration-500 break-inside-avoid" data-aos="fade-up" data-aos-delay="100">
                    <div class="relative w-full aspect-[3/2] bg-gradient-to-tr from-fuchsia-500 to-purple-600">
                        <div class="absolute inset-0 flex items-center justify-center text-white/40 group-hover:scale-110 transition-transform duration-700">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-6 translate-y-4 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <span class="inline-block px-3 py-1 bg-fuchsia-500/20 backdrop-blur-md text-fuchsia-200 text-xs font-bold rounded-full mb-2">Seminar & Workshop</span>
                            <h3 class="text-white font-bold text-lg leading-tight">Pelatihan Web Development</h3>
                            <p class="text-slate-300 text-sm mt-1">12 Oktober 2025</p>
                        </div>
                    </div>
                </div>

                <!-- Gallery Item 6 -->
                <div class="group relative rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-blue-500/20 transition-all duration-500 break-inside-avoid" data-aos="fade-up" data-aos-delay="200">
                    <div class="relative w-full aspect-[4/3] bg-gradient-to-tr from-blue-500 to-indigo-600">
                        <div class="absolute inset-0 flex items-center justify-center text-white/40 group-hover:scale-110 transition-transform duration-700">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-6 translate-y-4 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <span class="inline-block px-3 py-1 bg-blue-500/20 backdrop-blur-md text-blue-200 text-xs font-bold rounded-full mb-2">Kepanitiaan</span>
                            <h3 class="text-white font-bold text-lg leading-tight">Rapat Koordinasi BEM</h3>
                            <p class="text-slate-300 text-sm mt-1">02 September 2025</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Load More (Mockup) -->
            <div class="mt-16 flex justify-center" data-aos="fade-up">
                <button class="px-8 py-3 rounded-xl bg-white border-2 border-slate-200 text-slate-600 font-bold hover:border-blurple hover:text-blurple shadow-sm transition-all flex items-center gap-2 group">
                    <svg class="w-5 h-5 animate-spin hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Muat Lebih Banyak
                    <svg class="w-5 h-5 transform group-hover:translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
            </div>

        </div>
    </section>
@endsection
