@extends('layouts.public')

@section('title', 'Divisi ' . $divisi->singkatan . ' - HIMASI Unja')

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

    /* ── Member Card ──────────────────────────────────────────── */
    .member-item {
        width: 170px;
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .member-item:hover { transform: translateY(-6px); }
    .member-item--lg { width: 200px; }
    .member-item--sm { width: 150px; }

    /* Gradient border ring — wraps only the PHOTO */
    .member-avatar-ring {
        display: flex;
        flex-direction: column;
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        padding: 3px;
        border-radius: 1.25rem;
        transition: background 0.4s ease, box-shadow 0.4s ease;
        position: relative;
        z-index: 0;
    }
    .member-item:hover .member-avatar-ring {
        background: linear-gradient(135deg, #6366f1, #8b5cf6, #06b6d4);
        box-shadow: 0 14px 32px rgba(99,102,241,0.30);
    }

    /* Photo wrapper */
    .member-card__inner {
        position: relative;
        width: 100%;
        height: 100%;
        border-radius: calc(1.25rem - 3px);
        overflow: hidden;
        background: #f1f5f9;
    }

    /* Photo */
    .member-card__photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: flex;
        align-items: center;
        justify-content: center;
        transform: scale(1);
        transition: transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    .member-item:hover .member-card__photo {
        transform: scale(1.06);
    }

    /* Dark overlay on photo hover */
    .member-card__overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(15,23,42,0.45) 0%, transparent 55%);
        opacity: 0;
        transition: opacity 0.5s ease;
        z-index: 1;
        pointer-events: none;
    }
    .member-item:hover .member-card__overlay { opacity: 1; }

    /* Info panel — sits BELOW photo, lifts on hover */
    .member-card__info {
        position: relative;
        z-index: 2;
        background: #ffffff;
        border-radius: 0.9rem;
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        border: 1px solid #f1f5f9;
        padding: 10px 10px 12px;
        text-align: center;
        margin-top: -14px;
        transform: translateY(0);
        transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94), box-shadow 0.4s ease;
    }
    .member-item:hover .member-card__info {
        transform: translateY(-4px);
        box-shadow: 0 14px 40px rgba(99,102,241,0.12);
    }

    /* Accent divider — expands on hover */
    .member-card__info .info-divider {
        width: 1.75rem;
        height: 2px;
        background: linear-gradient(90deg, #6366f1, #8b5cf6);
        border-radius: 999px;
        margin: 0 auto 6px;
        transition: width 0.35s ease;
    }
    .member-item:hover .info-divider { width: 3rem; }

    .member-card__info .info-name {
        font-weight: 700;
        color: #1e293b;
        line-height: 1.3;
        transition: color 0.3s ease;
    }
    .member-item:hover .info-name { color: #4f46e5; }

    .member-card__info .info-position {
        display: block;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #6366f1;
        margin-top: 2px;
    }
    .member-card__info .info-meta {
        display: block;
        font-weight: 600;
        color: #94a3b8;
        margin-top: 1px;
    }
</style>
@endpush

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
        <div class="text-2xl font-bold shimmer-text mb-2" data-aos="fade-up" data-aos-delay="200">{{ $divisi->singkatan }}</div>
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
                    @php
                        $sizeClass = '';
                        if ($level === 'level1' || $level === 'level2') {
                            $sizeClass = 'member-item--lg';
                        } elseif ($level === 'level5') {
                            $sizeClass = 'member-item--sm';
                        }
                    @endphp
                    <div class="flex flex-wrap justify-center gap-6 lg:gap-8 relative z-10 w-full" data-aos="fade-up">
                        @foreach($levels[$level] as $pengurus)
                            <!-- Connection Dot (Desktop Only) -->
                            <div class="absolute left-1/2 top-1/2 w-3 h-3 bg-white border-2 border-primary rounded-full -translate-x-1/2 -translate-y-1/2 z-0 hidden md:block shadow-[0_0_15px_rgba(99,102,241,0.5)]"></div>
                            
                            <div class="member-item {{ $sizeClass }} flex flex-col items-center cursor-default">
                                <div class="member-avatar-ring w-full aspect-[3/4]">
                                    <div class="member-card__inner">
                                        @if($pengurus->avatar_url)
                                            <img src="{{ $pengurus->avatar_url }}" alt="{{ $pengurus->name }}" class="member-card__photo">
                                        @else
                                        <div class="member-card__photo bg-slate-100 flex items-center justify-center">
                                            <svg class="w-1/2 h-1/2 text-slate-300" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                            </svg>
                                        </div>
                                        @endif
                                        <div class="member-card__overlay"></div>
                                    </div>
                                </div>
                                <div class="member-card__info w-[90%]">
                                    <div class="info-divider" style="background: linear-gradient(90deg, #{{ $bg }}, #a5b4fc)"></div>
                                    <h4 class="info-name {{ $level === 'level5' ? 'text-xs' : ($level === 'level3' || $level === 'level4' ? 'text-[13px]' : 'text-sm') }}">{{ $pengurus->name }}</h4>
                                    <span class="info-position {{ $level === 'level5' ? 'text-[8px]' : ($level === 'level3' || $level === 'level4' ? 'text-[9px]' : 'text-[10px]') }}" style="color: #{{ $bg }}">{{ $pengurus->position_title }}</span>
                                    @if($pengurus->nim)
                                        <span class="info-meta {{ $level === 'level5' ? 'text-[8px]' : 'text-[9px]' }}">{{ $pengurus->nim }} &middot; {{ $pengurus->angkatan }}</span>
                                    @endif
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

