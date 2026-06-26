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
    <!-- Berita Hero Section -->
    <section class="relative pt-24 pb-16 md:pt-32 md:pb-24 overflow-hidden bg-slate-900 border-b border-slate-800">
        <!-- Decorative Background -->
        <div class="absolute inset-0 z-0 opacity-40">
            <img src="{{ asset('pengurus_himasi.png') }}" alt="Berita Background" class="w-full h-full object-cover object-center filter grayscale mix-blend-luminosity">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/80 to-slate-900/50 z-0"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
            <div class="inline-block mb-4 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 backdrop-blur-md" data-aos="fade-down">
                <span class="text-sm font-bold text-indigo-300 tracking-wider uppercase">Kabar Terkini</span>
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white tracking-tight leading-tight" data-aos="fade-up" data-aos-delay="100">
                Berita & Inovasi <br class="hidden md:block" />
                <span class="shimmer-text">HIMASI Unja</span>
            </h1>
        </div>
    </section>

    <!-- News Section -->
    <section class="py-20 bg-slate-50 relative">
        <div class="max-w-7xl mx-auto px-6">
            
            <div class="text-center mb-12" data-aos="fade-up">
                <p class="text-lg md:text-xl text-slate-600 max-w-2xl mx-auto leading-relaxed">
                    Ikuti terus perkembangan terbaru, kegiatan mahasiswa, dan inovasi teknologi dari keluarga besar Sistem Informasi.
                </p>
            </div>
            
            <!-- Filter Categories -->
            <div class="flex flex-wrap items-center justify-center gap-3 mb-16" data-aos="fade-up">
                <button class="px-6 py-2.5 rounded-full bg-blurple text-white font-medium text-sm shadow-md shadow-indigo-500/20 transition-all">Semua Berita</button>
                <button class="px-6 py-2.5 rounded-full bg-white text-slate-600 hover:text-blurple font-medium text-sm border border-slate-200 hover:border-indigo-300 shadow-sm transition-all">Akademik</button>
                <button class="px-6 py-2.5 rounded-full bg-white text-slate-600 hover:text-blurple font-medium text-sm border border-slate-200 hover:border-indigo-300 shadow-sm transition-all">Organisasi</button>
                <button class="px-6 py-2.5 rounded-full bg-white text-slate-600 hover:text-blurple font-medium text-sm border border-slate-200 hover:border-indigo-300 shadow-sm transition-all">Teknologi</button>
                <button class="px-6 py-2.5 rounded-full bg-white text-slate-600 hover:text-blurple font-medium text-sm border border-slate-200 hover:border-indigo-300 shadow-sm transition-all">Prestasi</button>
            </div>

            <!-- News Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- News Card 1 (Featured/Latest) -->
                <article class="group bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-200/60 hover:shadow-2xl hover:shadow-indigo-500/10 transition-all duration-300 hover:-translate-y-2 flex flex-col" data-aos="fade-up" data-aos-delay="100">
                    <div class="relative aspect-video overflow-hidden bg-slate-200">
                        <div class="absolute inset-0 bg-gradient-to-tr from-indigo-500 to-purple-600 opacity-90 transition-transform duration-700 group-hover:scale-110"></div>
                        <div class="absolute inset-0 flex items-center justify-center text-white/50">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                        </div>
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-indigo-600 text-xs font-bold rounded-full shadow-sm">Organisasi</span>
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex items-center gap-4 text-xs text-slate-500 mb-4 font-medium">
                            <span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> 24 Jun 2026</span>
                            <span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> Divisi Medinfo</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-blurple transition-colors line-clamp-2">
                            <a href="#">Peluncuran Sistem Informasi Manajemen HIMASI Terbaru</a>
                        </h3>
                        <p class="text-slate-600 text-sm leading-relaxed mb-6 line-clamp-3">
                            HIMASI Universitas Jambi resmi meluncurkan platform digital terbaru untuk mempermudah manajemen administrasi dan penyebaran informasi kepada seluruh anggota himpunan.
                        </p>
                        <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                            <a href="#" class="text-blurple font-semibold text-sm hover:text-indigo-700 transition-colors flex items-center gap-1 group/link">
                                Baca Selengkapnya 
                                <svg class="w-4 h-4 transform transition-transform group-hover/link:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </article>

                <!-- News Card 2 -->
                <article class="group bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-200/60 hover:shadow-2xl hover:shadow-indigo-500/10 transition-all duration-300 hover:-translate-y-2 flex flex-col" data-aos="fade-up" data-aos-delay="200">
                    <div class="relative aspect-video overflow-hidden bg-slate-200">
                        <div class="absolute inset-0 bg-gradient-to-tr from-cyan-500 to-blue-600 opacity-90 transition-transform duration-700 group-hover:scale-110"></div>
                        <div class="absolute inset-0 flex items-center justify-center text-white/50">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-blue-600 text-xs font-bold rounded-full shadow-sm">Akademik</span>
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex items-center gap-4 text-xs text-slate-500 mb-4 font-medium">
                            <span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> 20 Jun 2026</span>
                            <span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> Divisi Ristek</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-blurple transition-colors line-clamp-2">
                            <a href="#">Webinar Nasional: Tantangan AI di Masa Depan</a>
                        </h3>
                        <p class="text-slate-600 text-sm leading-relaxed mb-6 line-clamp-3">
                            Ratusan mahasiswa dari berbagai universitas turut serta dalam webinar nasional yang membahas peluang dan ancaman kecerdasan buatan bagi talenta muda IT Indonesia.
                        </p>
                        <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                            <a href="#" class="text-blurple font-semibold text-sm hover:text-indigo-700 transition-colors flex items-center gap-1 group/link">
                                Baca Selengkapnya 
                                <svg class="w-4 h-4 transform transition-transform group-hover/link:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </article>

                <!-- News Card 3 -->
                <article class="group bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-200/60 hover:shadow-2xl hover:shadow-indigo-500/10 transition-all duration-300 hover:-translate-y-2 flex flex-col" data-aos="fade-up" data-aos-delay="300">
                    <div class="relative aspect-video overflow-hidden bg-slate-200">
                        <div class="absolute inset-0 bg-gradient-to-tr from-emerald-500 to-teal-600 opacity-90 transition-transform duration-700 group-hover:scale-110"></div>
                        <div class="absolute inset-0 flex items-center justify-center text-white/50">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                        </div>
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-emerald-600 text-xs font-bold rounded-full shadow-sm">Prestasi</span>
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex items-center gap-4 text-xs text-slate-500 mb-4 font-medium">
                            <span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> 15 Jun 2026</span>
                            <span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> BPH HIMASI</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-blurple transition-colors line-clamp-2">
                            <a href="#">Juara 1 Lomba UI/UX Design Tingkat Nasional</a>
                        </h3>
                        <p class="text-slate-600 text-sm leading-relaxed mb-6 line-clamp-3">
                            Tim delegasi mahasiswa Sistem Informasi Universitas Jambi berhasil membawa pulang piala kemenangan dalam ajang perlombaan desain antarmuka bergengsi tahun ini.
                        </p>
                        <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                            <a href="#" class="text-blurple font-semibold text-sm hover:text-indigo-700 transition-colors flex items-center gap-1 group/link">
                                Baca Selengkapnya 
                                <svg class="w-4 h-4 transform transition-transform group-hover/link:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </article>

            </div>

            <!-- Pagination (Mockup) -->
            <div class="mt-16 flex justify-center" data-aos="fade-up">
                <nav class="flex items-center gap-2">
                    <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-blurple hover:border-indigo-300 shadow-sm transition-all cursor-not-allowed opacity-50" disabled>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-blurple text-white font-bold shadow-md shadow-indigo-500/20 transition-all">1</button>
                    <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-blurple hover:border-indigo-300 hover:bg-slate-50 shadow-sm transition-all font-medium">2</button>
                    <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-blurple hover:border-indigo-300 hover:bg-slate-50 shadow-sm transition-all font-medium">3</button>
                    <span class="px-2 text-slate-400">...</span>
                    <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-blurple hover:border-indigo-300 hover:bg-slate-50 shadow-sm transition-all font-medium">8</button>
                    <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-blurple hover:border-indigo-300 hover:bg-slate-50 shadow-sm transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </nav>
            </div>

        </div>
    </section>
@endsection
