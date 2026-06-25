@extends('layouts.public')

@section('title', 'Tentang HIMASI Unja')

@section('content')
<!-- Hero Section -->
<section class="relative pt-24 pb-16 md:pt-32 md:pb-24 overflow-hidden bg-slate-900">
    <div class="absolute inset-0 z-0 opacity-40">
        <img src="{{ asset('pengurus_himasi.png') }}" alt="HIMASI Background" class="w-full h-full object-cover object-center filter grayscale mix-blend-luminosity">
    </div>
    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/80 to-slate-900/50 z-0"></div>
    
    <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
        <div class="inline-block mb-4 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 backdrop-blur-md" data-aos="fade-down">
            <span class="text-sm font-bold text-indigo-300 tracking-wider uppercase">Tentang Himpunan</span>
        </div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white tracking-tight leading-tight" data-aos="fade-up" data-aos-delay="100">
            Himpunan Mahasiswa<br class="hidden md:block"/> Sistem Informasi <span class="text-indigo-400">(HIMASI)</span>
        </h1>
    </div>
</section>

<!-- Description Section -->
<section class="pt-20 pb-8 bg-white">
    <div class="max-w-4xl mx-auto px-6 text-center" data-aos="fade-up">
        <p class="text-lg md:text-xl text-slate-600 leading-relaxed">
            <strong class="text-slate-900 font-bold">Himpunan Mahasiswa Sistem Informasi (HIMASI)</strong> adalah salah satu HMJ (Himpunan Mahasiswa Jurusan) yang ada di Fakultas Sains dan Teknologi Universitas Jambi yang berfokus pada kegiataan kemahasiswaan yang membangun dan memajukan fakultas dan jurusan.
        </p>
    </div>
</section>

<!-- Vision & Mission -->
<section class="pt-12 pb-24 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid md:grid-cols-2 gap-16 items-start">
            <!-- Visi -->
            <div data-aos="fade-right">
                <h2 class="text-3xl font-bold text-slate-900 mb-6 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-primary text-white flex items-center justify-center shadow-lg shadow-indigo-500/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                    Visi Kami
                </h2>
                <p class="text-lg text-slate-700 leading-relaxed font-medium">
                    "Mengembangkan Sumber Daya Manusia yang Beriman dan Bertakwa kepada Tuhan Yang Maha Esa dan Berbudi Pekerti Luhur, Memiliki Pengetahuan dan Keterampilan, Kesehatan Jasmani dan Rohani yang Mantap dan Mandiri serta Rasa Tanggung Jawab Kemasyarakatan dan Kebangsaan."
                </p>
            </div>

            <!-- Misi -->
            <div data-aos="fade-left">
                <h2 class="text-3xl font-bold text-slate-900 mb-8 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-orange-500 text-white flex items-center justify-center shadow-lg shadow-orange-500/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    Misi Kami
                </h2>
                <ul class="space-y-6">
                    @php
                        $misiList = [
                            "Membangun Organisasi Program Studi yang Berorientasi Akademik dengan atas a/Pengembangan Softskill yang Optimal.",
                            "Membangun Organisasi Program Studi dengan Dilatarbelakangi oleh Pola Pengkaderan yang Jelas dan Berkesinambungan.",
                            "Memberikan Pelayanan Kemahasiswaaan yang Baik.",
                            "Mengadakan Pelatihan-pelatihan Keteknologian untuk Membentuk Mahasiswa Program Studi yang Berkualitas.",
                            "Membangun Hubungan dan Kerjasama yang Baik dengan Pihak Birokrasi.",
                            "Memperkenalkan Sistem Informasi ke Masyarakat Luas."
                        ];
                    @endphp
                    @foreach($misiList as $index => $misi)
                    <li class="flex gap-5 group">
                        <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-500 flex-shrink-0 flex items-center justify-center font-bold text-sm group-hover:bg-primary group-hover:text-white transition-colors duration-300 border border-slate-200 group-hover:border-primary shadow-sm">
                            {{ $index + 1 }}
                        </div>
                        <p class="text-slate-600 leading-relaxed pt-2 group-hover:text-slate-900 transition-colors duration-300">{{ $misi }}</p>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Tujuan Section -->
<section class="py-24 bg-slate-50 border-t border-slate-100 relative overflow-hidden">
    <!-- Decorative elements -->
    <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-96 h-96 bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4 tracking-tight" data-aos="fade-up">Tujuan HIMASI</h2>
        <p class="text-slate-500 max-w-2xl mx-auto mb-16 text-lg" data-aos="fade-up" data-aos-delay="100">Pilar utama yang menjadi arah dan sasaran dari setiap program kerja serta pergerakan organisasi kami.</p>
        
        <div class="grid md:grid-cols-2 gap-6 lg:gap-8">
            @php
                $tujuanList = [
                    [
                        "judul" => "Membangun Jaringan dan Kolaborasi",
                        "deskripsi" => "Menjalin hubungan yang kuat antar mahasiswa serta mitra eksternal guna menciptakan kolaborasi yang produktif dalam pengembangan akademik dan teknologi.",
                        "icon" => "M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z",
                        "color" => "indigo"
                    ],
                    [
                        "judul" => "Berbagi Pengetahuan dan Pengalaman",
                        "deskripsi" => "Mewadahi pertukaran ilmu, ide, dan pengalaman antar anggota untuk menumbuhkan budaya belajar, inovasi, serta pengembangan diri yang berkelanjutan.",
                        "icon" => "M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253",
                        "color" => "blue"
                    ],
                    [
                        "judul" => "Pengembangan Profesionalisme",
                        "deskripsi" => "Meningkatkan kualitas mahasiswa Sistem Informasi melalui penguatan soft skill, hard skill, serta pembinaan karakter agar siap bersaing di dunia akademik maupun profesional.",
                        "icon" => "M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z",
                        "color" => "emerald"
                    ],
                    [
                        "judul" => "Mengembangkan Proyek Bersama",
                        "deskripsi" => "Mendorong terciptanya karya dan proyek kolaboratif sebagai bentuk implementasi keilmuan Sistem Informasi yang memberikan manfaat nyata bagi kampus dan masyarakat.",
                        "icon" => "M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4",
                        "color" => "purple"
                    ]
                ];
            @endphp

            @foreach($tujuanList as $index => $tujuan)
            <div class="group relative bg-white p-8 md:p-10 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-{{ $tujuan['color'] }}-500/10 transition-all duration-500 overflow-hidden z-10 hover:-translate-y-1" data-aos="fade-up" data-aos-delay="{{ 100 * ($index + 1) }}">
                <!-- Soft glow in background on hover -->
                <div class="absolute top-0 right-0 -mt-16 -mr-16 w-40 h-40 bg-{{ $tujuan['color'] }}-100 rounded-full opacity-0 group-hover:opacity-50 transition-opacity duration-700 -z-10 blur-3xl"></div>
                
                <div class="flex flex-col sm:flex-row gap-6 lg:gap-8 items-start text-left">
                    <div class="w-16 h-16 shrink-0 rounded-2xl bg-{{ $tujuan['color'] }}-50 text-{{ $tujuan['color'] }}-600 flex items-center justify-center group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-500 border border-{{ $tujuan['color'] }}-100/50">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tujuan['icon'] }}"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-{{ $tujuan['color'] }}-600 transition-colors duration-300">{{ $tujuan['judul'] }}</h3>
                        <p class="text-slate-600 leading-relaxed">{{ $tujuan['deskripsi'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
