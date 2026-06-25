@extends('layouts.public')

@section('title', 'Divisi ' . $divisi->singkatan . ' - HIMASI Unja')

@section('content')


<!-- Hero Section -->
<section class="relative pt-24 pb-16 md:pt-32 md:pb-24 overflow-hidden bg-slate-900" style="z-index:1">
    <div class="absolute inset-0 z-0 opacity-40">
        <img src="{{ asset('pengurus_himasi.png') }}" alt="HIMASI Background" class="w-full h-full object-cover object-center filter grayscale mix-blend-luminosity">
    </div>
    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/80 to-slate-900/50 z-0"></div>
    
    <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
        <div class="inline-flex items-center mb-4 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 backdrop-blur-md" data-aos="fade-down">
            <span class="text-sm font-bold text-indigo-300 tracking-wider uppercase">Divisi HIMASI</span>
        </div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white tracking-tight mb-4" data-aos="fade-up" data-aos-delay="100">
            {{ $divisi->name }}
        </h1>
        <div class="text-2xl font-bold text-indigo-400 mb-2" data-aos="fade-up" data-aos-delay="200">{{ $divisi->singkatan }}</div>
    </div>
</section>

<!-- Division Details -->
<section class="py-24 bg-slate-50/80 relative" style="z-index:1">

    <div class="max-w-5xl mx-auto px-6 text-center mb-20 relative z-10">
        @php
            $slugImages = [
                'bph' => 'bph/bph_bersama.webp',
                'humas' => 'humas/humas_bersama.webp',
                'ristek' => 'ristek/ristek_bersama.webp',
                'danus' => 'danus/danus_bersama.webp',
                'medinfo' => 'mediasi/mediasi_bersama.webp',
                'mikat' => 'mdb/mdb_bersama.webp',
                'psda' => 'psda/psda_bersama.webp',
                'sosagma' => 'sosgam/sosgam_bersama.webp',
                'ppm' => 'ppm/ppm_bersama.webp',
            ];
            $divisiImage = $slugImages[$divisi->slug ?? ''] ?? null;
        @endphp

        @if($divisiImage && file_exists(public_path('pengurus_hima/' . $divisiImage)))
            <div class="w-full rounded-[2rem] overflow-hidden shadow-xl mb-12 relative group border-8 border-white" data-aos="fade-up">
                <img src="{{ asset('pengurus_hima/' . $divisiImage) }}" alt="{{ $divisi->name }}" class="w-full h-auto group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent pointer-events-none"></div>
            </div>
        @endif

        <p class="text-lg md:text-xl text-slate-600 leading-relaxed max-w-4xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            {{ $divisi->desc }}
        </p>
    </div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-4" data-aos="fade-up">Struktur Kepengurusan</h2>
            <p class="text-slate-600 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">Mengenal lebih dekat tim hebat yang menggerakkan program kerja dan visi dari {{ $divisi->name }}.</p>
        </div>

        @php
            $levels = [
                'level1' => collect(), // Ketua Himpunan / Ketua Divisi
                'level2' => collect(), // Wakil Ketua
                'level3' => collect(), // Sekretaris / Bendahara
                'level4' => collect(), // Ketua Bidang
                'level5' => collect(), // Anggota
            ];

            foreach($divisi->users as $u) {
                $jabatan = strtolower($u->position_title);
                
                if (str_contains($jabatan, 'wakil ketua')) {
                    $levels['level2']->push($u);
                } elseif (str_contains($jabatan, 'ketua himpunan') || str_contains($jabatan, 'ketua divisi') || str_contains($jabatan, 'ketua umum')) {
                    $levels['level1']->push($u);
                } elseif (str_contains($jabatan, 'sekretaris') || str_contains($jabatan, 'bendahara')) {
                    $levels['level3']->push($u);
                } elseif (str_contains($jabatan, 'ketua bidang')) {
                    $levels['level4']->push($u);
                } else {
                    $levels['level5']->push($u);
                }
            }

            // Fallback for Ketua if not strictly matched
            if ($levels['level1']->isEmpty()) {
                foreach($levels['level5'] as $key => $u) {
                    if (str_contains(strtolower($u->position_title), 'ketua') && !str_contains(strtolower($u->position_title), 'bidang')) {
                        $levels['level1']->push($u);
                        $levels['level5']->forget($key);
                    }
                }
            }
            
            // Deterministic color based on the division's basic colors or fallback to a standard blurple
            $bg = '635BFF'; 
            if (str_contains($divisi->color, 'blue')) $bg = '3b82f6';
            if (str_contains($divisi->color, 'emerald')) $bg = '10b981';
            if (str_contains($divisi->color, 'amber')) $bg = 'f59e0b';
            if (str_contains($divisi->color, 'fuchsia')) $bg = 'd946ef';
            if (str_contains($divisi->color, 'rose')) $bg = 'f43f5e';
            if (str_contains($divisi->color, 'teal')) $bg = '14b8a6';
            if (str_contains($divisi->color, 'slate')) $bg = '64748b';
        @endphp

        <div class="flex flex-col items-center gap-12 lg:gap-16 relative w-full mt-20">
            <!-- Glowing center connector line -->
            <div class="absolute left-1/2 top-0 bottom-0 w-[2px] bg-gradient-to-b from-transparent via-primary/20 to-transparent -translate-x-1/2 z-0 hidden md:block"></div>

            @foreach(['level1', 'level2', 'level3', 'level4', 'level5'] as $level)
                @if($levels[$level]->isNotEmpty())
                    <div class="flex flex-wrap justify-center gap-6 lg:gap-8 relative z-10 w-full" data-aos="fade-up">
                        @foreach($levels[$level] as $pengurus)
                            <!-- Connection Dot (Desktop Only) -->
                            <div class="absolute left-1/2 top-1/2 w-3 h-3 bg-white border-2 border-primary rounded-full -translate-x-1/2 -translate-y-1/2 z-0 hidden md:block shadow-[0_0_15px_rgba(99,102,241,0.5)]"></div>
                            
                            <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-3 pb-6 text-center shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 hover:-translate-y-2 hover:shadow-2xl hover:shadow-primary/10 transition-all duration-300 w-full sm:w-[260px] relative group z-10">
                                <div class="w-full aspect-[4/5] rounded-2xl overflow-hidden mb-5 relative bg-slate-100 shadow-inner">
                                    @if($pengurus->avatar && file_exists(public_path($pengurus->avatar)))
                                        <img src="{{ asset($pengurus->avatar) }}" alt="{{ $pengurus->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($pengurus->name) }}&background={{ $bg }}&color=fff&size=512&bold=true" alt="{{ $pengurus->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @endif
                                    
                                    <!-- Subtle gradient overlay on hover -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                                </div>
                                
                                <div class="px-3">
                                    <h3 class="text-[17px] font-bold text-slate-800 mb-1.5 leading-tight group-hover:text-slate-900 transition-colors">{{ $pengurus->name }}</h3>
                                    <p class="text-[13px] font-bold tracking-wide uppercase" style="color: #{{ $bg }}">{{ $pengurus->position_title }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach
        </div>
        
        <div class="mt-20 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center text-slate-500 hover:text-slate-900 font-medium transition-colors group">
                <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Beranda
            </a>
        </div>
        </div>
    </div>
</section>
@endsection

