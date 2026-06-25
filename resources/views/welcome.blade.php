@extends('layouts.public')

@section('content')
    <!-- Hero Section -->
    <section id="beranda" class="relative min-h-screen flex items-center justify-center pt-20 overflow-hidden -mt-20 bg-slate-900">
        <div class="absolute inset-0 z-0 bg-slate-900 pt-20 lg:pt-0">
            <!-- Mobile Image with Bottom Fade Mask -->
            <img src="{{ asset('pengurus_himasi.png') }}" alt="Pengurus HIMASI" class="w-full h-auto lg:hidden object-top" style="-webkit-mask-image: linear-gradient(to bottom, black 50%, transparent 95%); mask-image: linear-gradient(to bottom, black 50%, transparent 95%);" />
            
            <!-- Desktop Image with Bottom Fade Mask -->
            <img src="{{ asset('pengurus_himasi.png') }}" alt="Pengurus HIMASI" class="hidden lg:block w-full h-full object-cover object-center" style="-webkit-mask-image: linear-gradient(to bottom, black 75%, transparent 100%); mask-image: linear-gradient(to bottom, black 75%, transparent 100%);" />
            
            <!-- Overlay to darken image behind text -->
            <div class="absolute inset-0 bg-slate-900/60 lg:bg-slate-900/75 pointer-events-none"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-6 text-center relative z-10 w-full py-20 -mt-40 lg:mt-0">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white tracking-tight leading-[1.1] mb-6 drop-shadow-lg" data-aos="fade-down" data-aos-duration="1000">
                HIMPUNAN MAHASISWA<br/>
                <span class="text-indigo-400">SISTEM INFORMASI</span><br/>
                <span class="text-orange-500 text-2xl md:text-3xl lg:text-4xl tracking-normal">UNIVERSITAS JAMBI</span>
            </h1>
            
            <p class="text-xl md:text-2xl font-semibold text-slate-200 mb-4 max-w-4xl mx-auto drop-shadow-md" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
                Sinergi, Inovasi, & Kolaborasi Mahasiswa Universitas Jambi.
            </p>
            <p class="text-base md:text-lg text-slate-300 mb-10 max-w-3xl mx-auto leading-relaxed drop-shadow-md" data-aos="fade-up" data-aos-delay="300" data-aos-duration="1000">
                Wadah aspirasi dan pengembangan potensi mahasiswa Sistem Informasi Universitas Jambi melalui berbagai program kerja dan kepanitiaan terpadu.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4" data-aos="fade-up" data-aos-delay="500" data-aos-duration="1000">
                <a href="{{ route('login') }}" class="w-full sm:w-auto bg-blurple hover:bg-blurple-dark text-white font-semibold text-base py-3.5 px-8 rounded-xl transition-all shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 border border-blurple">
                    Masuk Sistem
                </a>
                <a href="{{ route('about') }}" class="w-full sm:w-auto bg-white/10 hover:bg-white/20 text-white backdrop-blur-md border border-white/30 font-semibold text-base py-3.5 px-8 rounded-xl transition-all hover:-translate-y-0.5">
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
    </section>

    <!-- Pimpinan HIMASI -->
    <section id="pimpinan" class="py-24 bg-slate-50 border-b border-slate-200/60 relative overflow-hidden">
        <!-- Decorative Background -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
            <div class="absolute top-10 left-10 w-64 h-64 bg-indigo-300/10 rounded-full filter blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-64 h-64 bg-fuchsia-300/10 rounded-full filter blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16 relative z-10" data-aos="fade-up">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-slate-200 text-slate-600 font-bold text-xs tracking-widest mb-4 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-primary"></span>
                    PIMPINAN HIMASI
                </div>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 mb-6 tracking-tight">Nahkoda Organisasi</h2>
                <p class="text-slate-600 max-w-2xl mx-auto text-lg leading-relaxed">Mengenal lebih dekat pemimpin yang mengarahkan pergerakan dan inovasi Himpunan Mahasiswa Sistem Informasi periode ini.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 lg:gap-16 max-w-4xl mx-auto relative z-10">
                <!-- Ketua -->
                <div class="group relative flex flex-col items-center max-w-[340px] mx-auto w-full" data-aos="fade-up" data-aos-delay="200">
                    <!-- Image Container -->
                    <div class="w-full aspect-[3/4] rounded-2xl overflow-hidden shadow-lg border border-slate-200/60 relative z-0">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10"></div>
                        <img src="{{ asset('pengurus_hima/bph/kahim.webp') }}" onerror="this.src='https://ui-avatars.com/api/?name=Muhammad+Rumii+Firnanditya&background=e2e8f0&color=475569&size=512'" alt="Ketua HIMASI" class="w-full h-full object-cover object-center transform group-hover:scale-105 transition-transform duration-700 ease-out">
                    </div>
                    
                    <!-- Info Card -->
                    <div class="relative z-20 w-[90%] bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.08)] p-6 -mt-16 text-center border border-slate-100 transform group-hover:-translate-y-2 transition-all duration-500">
                        <div class="w-10 h-1 bg-primary rounded-full mx-auto mb-4 transition-all duration-300 group-hover:w-16"></div>
                        <h3 class="text-xl font-bold text-slate-900 mb-1">Muhammad Rumii Firnanditya</h3>
                        <p class="text-primary font-bold text-xs uppercase tracking-wider mb-2">Ketua Umum</p>
                        <p class="text-slate-500 text-[13px] leading-relaxed">
                            Periode Kepengurusan 2025/2026
                        </p>
                    </div>
                </div>

                <!-- Wakil Ketua -->
                <div class="group relative flex flex-col items-center max-w-[340px] mx-auto w-full" data-aos="fade-up" data-aos-delay="400">
                    <!-- Image Container -->
                    <div class="w-full aspect-[3/4] rounded-2xl overflow-hidden shadow-lg border border-slate-200/60 relative z-0">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10"></div>
                        <img src="{{ asset('pengurus_hima/bph/wakahim.webp') }}" onerror="this.src='https://ui-avatars.com/api/?name=Wakil+Ketua&background=e2e8f0&color=475569&size=512'" alt="Wakil Ketua HIMASI" class="w-full h-full object-cover object-center transform group-hover:scale-105 transition-transform duration-700 ease-out">
                    </div>
                    
                    <!-- Info Card -->
                    <div class="relative z-20 w-[90%] bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.08)] p-6 -mt-16 text-center border border-slate-100 transform group-hover:-translate-y-2 transition-all duration-500">
                        <div class="w-10 h-1 bg-fuchsia-500 rounded-full mx-auto mb-4 transition-all duration-300 group-hover:w-16"></div>
                        <h3 class="text-xl font-bold text-slate-900 mb-1">Nama Wakil Ketua</h3>
                        <p class="text-fuchsia-500 font-bold text-xs uppercase tracking-wider mb-2">Wakil Ketua Umum</p>
                        <p class="text-slate-500 text-[13px] leading-relaxed">
                            Periode Kepengurusan 2025/2026
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Kami -->
    <section id="tentang" class="py-24 bg-white overflow-hidden relative">
        <!-- Decorative Background Gradients -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-primary/5 rounded-full mix-blend-multiply filter blur-3xl opacity-70 -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-indigo-300/10 rounded-full mix-blend-multiply filter blur-3xl opacity-70 translate-y-1/3 -translate-x-1/3"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 lg:gap-20 items-center">
                <!-- Image Side -->
                <div class="relative group order-2 lg:order-1 mt-10 lg:mt-0" data-aos="fade-right">
                    <!-- Main Image -->
                    <div class="relative rounded-[2rem] overflow-hidden shadow-2xl shadow-slate-200/50 border-8 border-white">
                        <img src="{{ asset('pengurus_himasi.png') }}" alt="Pengurus HIMASI" class="w-full aspect-[4/3] object-cover object-top transform group-hover:scale-105 transition-transform duration-700 ease-out">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-slate-900/10 to-transparent pointer-events-none"></div>
                    </div>
                    
                    <!-- Floating Badge -->
                    <div class="absolute -bottom-8 -right-4 lg:-right-8 bg-white p-5 lg:p-6 rounded-2xl shadow-[0_20px_50px_rgba(8,_112,_184,_0.1)] border border-slate-50 flex items-center gap-4 hover:-translate-y-2 transition-transform duration-300 z-20" data-aos="fade-up" data-aos-delay="300">
                        <div class="w-12 h-12 lg:w-14 lg:h-14 bg-indigo-50 rounded-full flex items-center justify-center text-primary shrink-0">
                            <svg class="w-6 h-6 lg:w-7 lg:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div>
                            <div class="text-2xl lg:text-3xl font-black text-slate-900">8</div>
                            <div class="text-xs lg:text-sm font-bold tracking-wide text-slate-500 uppercase mt-0.5">Divisi Aktif</div>
                        </div>
                    </div>

                    <!-- Decorative Dots -->
                    <div class="absolute -top-6 -left-6 w-24 h-24 bg-[radial-gradient(#cbd5e1_2px,transparent_2px)] [background-size:12px_12px] -z-10 opacity-70"></div>
                </div>

                <!-- Text Side -->
                <div class="order-1 lg:order-2 lg:pl-6" data-aos="fade-left">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-50/80 border border-indigo-100 text-primary font-extrabold text-xs tracking-widest mb-6 backdrop-blur-sm">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                        </span>
                        TENTANG KAMI
                    </div>
                    
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 leading-[1.15] mb-6 tracking-tight">
                        Wadah Kolaborasi & Inovasi <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-fuchsia-500">Teknologi</span>
                    </h2>
                    
                    <p class="text-base lg:text-lg text-slate-600 leading-relaxed mb-8">
                        HIMASI Universitas Jambi adalah episentrum pengembangan minat, bakat, dan potensi mahasiswa Sistem Informasi untuk melahirkan talenta digital yang kompeten dan siap bersaing di industri kreatif.
                    </p>
                    
                    <!-- Feature List -->
                    <ul class="space-y-4 mb-10">
                        <li class="flex items-start gap-4">
                            <div class="shrink-0 w-7 h-7 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500 mt-0.5 shadow-sm border border-emerald-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <p class="text-slate-700 font-medium">Pengembangan <strong class="text-slate-900">Hard-Skill</strong> & <strong class="text-slate-900">Soft-Skill</strong> secara terpadu.</p>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="shrink-0 w-7 h-7 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500 mt-0.5 shadow-sm border border-emerald-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <p class="text-slate-700 font-medium">Membangun relasi luas dengan alumni dan profesional industri.</p>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="shrink-0 w-7 h-7 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500 mt-0.5 shadow-sm border border-emerald-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <p class="text-slate-700 font-medium">Sinergi aktif membangun ekosistem digital Universitas Jambi.</p>
                        </li>
                    </ul>
                    
                    <a href="{{ route('about') }}" class="group inline-flex items-center justify-center gap-3 bg-slate-900 hover:bg-blurple text-white font-semibold py-4 px-8 rounded-xl transition-all duration-300 shadow-[0_10px_20px_rgba(15,_23,_42,_0.1)] hover:shadow-[0_10px_20px_rgba(99,_91,_255,_0.25)] hover:-translate-y-1">
                        Kenali Profil Lengkap
                        <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Sorotan Divisi -->
    <section id="struktur" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <div class="text-sm font-bold text-blurple uppercase tracking-wider mb-2">Struktur Organisasi</div>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Divisi & Departemen HIMASI</h2>
                <p class="text-slate-500 max-w-2xl mx-auto">Kenali berbagai divisi yang menggerakkan roda organisasi HIMASI Universitas Jambi.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                $divisis = [
                    ['nama' => 'BPH', 'desc' => 'Badan Pengurus Harian', 'slug' => 'bph', 'image' => 'bph/bph_bersama.webp'],
                    ['nama' => 'Humas', 'desc' => 'Hubungan Masyarakat', 'slug' => 'humas', 'image' => 'humas/humas_bersama.webp'],
                    ['nama' => 'Ristek', 'desc' => 'Riset dan Teknologi', 'slug' => 'ristek', 'image' => 'ristek/ristek_bersama.webp'],
                    ['nama' => 'Danus', 'desc' => 'Dana Usaha', 'slug' => 'danus', 'image' => 'danus/danus_bersama.webp'],
                    ['nama' => 'Medinfo', 'desc' => 'Media dan Informasi', 'slug' => 'medinfo', 'image' => 'mediasi/mediasi_bersama.webp'],
                    ['nama' => 'Mikat', 'desc' => 'Minat dan Bakat', 'slug' => 'mikat', 'image' => 'mdb/mdb_bersama.webp'],
                    ['nama' => 'PSDA', 'desc' => 'Pengembangan SDA', 'slug' => 'psda', 'image' => 'psda/psda_bersama.webp'],
                    ['nama' => 'SosAgma', 'desc' => 'Sosial dan Agama', 'slug' => 'sosagma', 'image' => 'sosgam/sosgam_bersama.webp'],
                    ['nama' => 'PPM', 'desc' => 'Pengawasan & Masalah', 'slug' => 'ppm', 'image' => 'ppm/ppm_bersama.webp'],
                ];
                @endphp

                @foreach($divisis as $div)
                <a href="{{ route('division.show', $div['slug']) }}" class="group block bg-white rounded-3xl border border-slate-100 hover:border-primary/30 transition-all duration-300 hover:shadow-xl hover:shadow-indigo-500/10 relative overflow-hidden flex flex-col" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <!-- Image -->
                    <div class="h-48 sm:h-56 w-full relative overflow-hidden bg-slate-100">
                        <img src="{{ asset('pengurus_hima/' . $div['image']) }}" alt="{{ $div['desc'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-60"></div>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-6 sm:p-8 relative z-10 flex flex-col flex-grow bg-white">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-bold tracking-widest text-primary uppercase">{{ $div['nama'] }}</span>
                            <div class="w-8 h-8 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center group-hover:bg-primary group-hover:border-primary group-hover:text-white transition-colors duration-300 text-slate-400 shadow-sm">
                                <svg class="w-4 h-4 transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 leading-snug group-hover:text-primary transition-colors duration-300">{{ $div['desc'] }}</h3>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Berita / Kegiatan Terbaru -->
    <section id="berita" class="py-24 bg-slate-50 border-t border-slate-200/60">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-end justify-between mb-12">
                <div>
                    <div class="text-sm font-bold text-blurple uppercase tracking-wider mb-2">Update Terbaru</div>
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900">Berita & Kegiatan</h2>
                </div>
                <a href="#" class="hidden sm:flex items-center gap-2 text-slate-500 hover:text-slate-900 font-medium transition-colors">
                    Lihat Semua Berita <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 hover:shadow-xl hover:shadow-slate-200/50 transition-all group cursor-pointer">
                    <div class="h-48 bg-slate-200 overflow-hidden relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-400 to-purple-500 group-hover:scale-105 transition-transform duration-500"></div>
                    </div>
                    <div class="p-6">
                        <div class="text-xs font-bold text-blurple uppercase tracking-wider mb-3">Kegiatan Divisi</div>
                        <h3 class="font-bold text-lg text-slate-900 mb-2 line-clamp-2 group-hover:text-blurple transition-colors">Pelatihan Laravel 11 untuk Mahasiswa Sistem Informasi</h3>
                        <p class="text-slate-500 text-sm mb-4 line-clamp-2">Divisi Ristek mengadakan pelatihan intensif framework Laravel untuk meningkatkan skill web development mahasiswa.</p>
                        <div class="text-xs text-slate-400 font-medium">12 Oktober 2026</div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 hover:shadow-xl hover:shadow-slate-200/50 transition-all group cursor-pointer">
                    <div class="h-48 bg-slate-200 overflow-hidden relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-cyan-500 group-hover:scale-105 transition-transform duration-500"></div>
                    </div>
                    <div class="p-6">
                        <div class="text-xs font-bold text-blurple uppercase tracking-wider mb-3">Informasi Internal</div>
                        <h3 class="font-bold text-lg text-slate-900 mb-2 line-clamp-2 group-hover:text-blurple transition-colors">Rapat Kerja HIMASI Periode 2026/2027</h3>
                        <p class="text-slate-500 text-sm mb-4 line-clamp-2">Pembahasan program kerja satu tahun ke depan yang dihadiri oleh seluruh fungsionaris HIMASI.</p>
                        <div class="text-xs text-slate-400 font-medium">05 Oktober 2026</div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 hover:shadow-xl hover:shadow-slate-200/50 transition-all group cursor-pointer">
                    <div class="h-48 bg-slate-200 overflow-hidden relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-400 to-teal-500 group-hover:scale-105 transition-transform duration-500"></div>
                    </div>
                    <div class="p-6">
                        <div class="text-xs font-bold text-blurple uppercase tracking-wider mb-3">Event</div>
                        <h3 class="font-bold text-lg text-slate-900 mb-2 line-clamp-2 group-hover:text-blurple transition-colors">Persiapan Dies Natalis Sistem Informasi ke-10</h3>
                        <p class="text-slate-500 text-sm mb-4 line-clamp-2">Kepanitiaan gabungan dari berbagai angkatan telah resmi dibentuk untuk menyukseskan perayaan tahun ini.</p>
                        <div class="text-xs text-slate-400 font-medium">28 September 2026</div>
                    </div>
                </div>
            </div>
            
            <a href="#" class="sm:hidden mt-8 flex items-center justify-center gap-2 text-slate-600 font-medium">
                Lihat Semua Berita <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
    </section>
@endsection
