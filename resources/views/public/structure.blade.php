@extends('layouts.public')

@section('title', 'Struktur Organisasi HIMASI Unja')

@push('styles')
<style>
    /* ── Custom Animations ─────────────────────────────────────── */
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

    /* ── Section labels ────────────────────────────────────────── */
    .section-pill {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 999px;
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
    }
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════════════
     HERO
════════════════════════════════════════════════════════ --}}
<section class="relative pt-24 pb-16 md:pt-32 md:pb-24 overflow-hidden bg-slate-900">
    <div class="absolute inset-0 z-0 opacity-40">
        <img src="{{ asset('pengurus_himasi.png') }}" alt="HIMASI Struktur Background"
             class="w-full h-full object-cover object-center filter grayscale mix-blend-luminosity">
    </div>
    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/80 to-slate-900/50 z-0"></div>
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl z-0"></div>
    <div class="absolute bottom-0 right-1/4 w-72 h-72 bg-violet-600/10 rounded-full blur-3xl z-0"></div>
    <div class="max-w-5xl mx-auto px-6 relative z-10 text-center">
        <div class="inline-block mb-4 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 backdrop-blur-md" data-aos="fade-down">
            <span class="text-sm font-bold text-indigo-300 tracking-wider uppercase">Profil Organisasi</span>
        </div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white tracking-tight mb-0 leading-tight" data-aos="fade-up" data-aos-delay="100">
            Struktur Organisasi<br>
            <span class="shimmer-text">HIMASI Unja</span>
        </h1>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     BAGAN HIERARKI
════════════════════════════════════════════════════════ --}}
<section class="py-20 md:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-24">
            <div class="section-pill bg-slate-100 text-slate-500 border border-slate-200 mb-4">Hirarki Kepengurusan</div>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight">Bagan Organisasi</h2>
        </div>

        {{-- Reusable macro: renders one member card --}}
        {{-- Structure: .member-item > .member-avatar-ring > .member-card__inner(photo+overlay) + .member-card__info --}}

        <div class="flex flex-col items-center gap-12">

            {{-- ── PEMBINA ──────────────────────────────────────────── --}}
            <div class="w-full text-center" data-aos="fade-up">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mb-10">Pembina HIMASI</h3>
                <div class="flex flex-wrap justify-center gap-8 md:gap-12">
                    @if($pembinaDivision && $pembinaDivision->users)
                        @foreach($pembinaDivision->users as $member)
                        <div class="member-item flex flex-col items-center cursor-pointer hover:-translate-y-1 transition-transform" 
     @click="$dispatch('open-member-modal', { name: '{{ addslashes($member->name) }}', position: '{{ addslashes($member->position_title) }}', nim: '{{ $member->nim }}', angkatan: '{{ $member->angkatan ?? '-' }}', email: '{{ $member->email }}', avatar_url: '{{ $member->avatar_url }}', initial: '{{ strtoupper(substr($member->name, 0, 1)) }}', identifier_type: '{{ str_starts_with(strtolower($member->position_title ?? ""), "pembina") || str_starts_with(strtolower($member->position_title ?? ""), "dosen") ? "NIP" : "NIM" }}', color_class: 'text-indigo-600 bg-indigo-100', text_color_class: 'text-brand-600' })">
                            <div class="member-avatar-ring w-full aspect-[3/4]">
                                <div class="member-card__inner">
                                    @if($member->avatar_url)
                                        <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="member-card__photo">
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
                                <div class="info-divider"></div>
                                <h4 class="info-name text-[13px]">{{ $member->name }}</h4>
                                <span class="info-position text-[9px]">{{ $member->position_title }}</span>
                                @if($member->nim)
                                    <span class="info-meta text-[9px]">NIP. {{ $member->nim }}</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- Separator --}}
            <div class="flex flex-col items-center my-4">
                <div class="w-[2px] h-14 bg-gradient-to-b from-indigo-500/30 to-violet-500/30"></div>
                <div class="w-2 h-2 rounded-full bg-violet-500 -mt-1 shadow-md shadow-violet-500/50"></div>
            </div>

            {{-- ── DEWAN PENGAWAS ───────────────────────────────────── --}}
            <div class="w-full text-center" data-aos="fade-up">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mb-10">Dewan Pengawas (DP)</h3>
                <div class="flex flex-wrap justify-center gap-8 md:gap-10">
                    @if($dpDivision && $dpDivision->users)
                        @foreach($dpDivision->users as $member)
                        <div class="member-item flex flex-col items-center cursor-pointer hover:-translate-y-1 transition-transform" 
     @click="$dispatch('open-member-modal', { name: '{{ addslashes($member->name) }}', position: '{{ addslashes($member->position_title) }}', nim: '{{ $member->nim }}', angkatan: '{{ $member->angkatan ?? '-' }}', email: '{{ $member->email }}', avatar_url: '{{ $member->avatar_url }}', initial: '{{ strtoupper(substr($member->name, 0, 1)) }}', identifier_type: '{{ str_starts_with(strtolower($member->position_title ?? ""), "pembina") || str_starts_with(strtolower($member->position_title ?? ""), "dosen") ? "NIP" : "NIM" }}', color_class: 'text-indigo-600 bg-indigo-100', text_color_class: 'text-brand-600' })">
                            <div class="member-avatar-ring w-full aspect-[3/4]">
                                <div class="member-card__inner">
                                    @if($member->avatar_url)
                                        <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="member-card__photo">
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
                                <div class="info-divider"></div>
                                <h4 class="info-name text-[13px]">{{ $member->name }}</h4>
                                <span class="info-position text-[9px]" style="color:#7c3aed">{{ $member->position_title }}</span>
                                @if($member->nim)
                                    <span class="info-meta text-[9px]">{{ $member->nim }} &middot; {{ $member->angkatan }}</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- Separator --}}
            <div class="flex flex-col items-center my-4">
                <div class="w-[2px] h-14 bg-gradient-to-b from-violet-500/30 to-indigo-500/30"></div>
                <div class="w-2 h-2 rounded-full bg-indigo-500 -mt-1 shadow-md shadow-indigo-500/50"></div>
            </div>

            {{-- ── BPH ──────────────────────────────────────────────── --}}
            @if($bphDivision)
            <div class="w-full text-center" data-aos="fade-up">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mb-10">Badan Pengurus Harian (BPH)</h3>
                @php
                    $kahim = $bphDivision->users->filter(fn($u) =>
                        !str_contains(strtolower($u->position_title), 'wakil') &&
                        (str_contains(strtolower($u->position_title), 'ketua himpunan') || str_contains(strtolower($u->position_title), 'ketua umum'))
                    );
                    $wakahim = $bphDivision->users->filter(fn($u) =>
                        str_contains(strtolower($u->position_title), 'wakil ketua')
                    );
                    $othersBph = $bphDivision->users->reject(fn($u) =>
                        $kahim->contains($u) || $wakahim->contains($u)
                    );
                @endphp
                <div class="flex flex-col items-center gap-10">
                    {{-- Row 1: Ketua & Wakil --}}
                    <div class="flex flex-wrap justify-center gap-10 md:gap-14">
                        @foreach($kahim->concat($wakahim) as $member)
                        <div class="member-item member-item--lg flex flex-col items-center cursor-pointer hover:-translate-y-1 transition-transform" 
     @click="$dispatch('open-member-modal', { name: '{{ addslashes($member->name) }}', position: '{{ addslashes($member->position_title) }}', nim: '{{ $member->nim }}', angkatan: '{{ $member->angkatan ?? '-' }}', email: '{{ $member->email }}', avatar_url: '{{ $member->avatar_url }}', initial: '{{ strtoupper(substr($member->name, 0, 1)) }}', identifier_type: '{{ str_starts_with(strtolower($member->position_title ?? ""), "pembina") || str_starts_with(strtolower($member->position_title ?? ""), "dosen") ? "NIP" : "NIM" }}', color_class: 'text-indigo-600 bg-indigo-100', text_color_class: 'text-brand-600' })">
                            <div class="member-avatar-ring w-full aspect-[3/4]">
                                <div class="member-card__inner">
                                    @if($member->avatar_url)
                                        <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="member-card__photo">
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
                                <div class="info-divider"></div>
                                <h4 class="info-name text-sm">{{ $member->name }}</h4>
                                <span class="info-position text-[10px]">{{ $member->position_title }}</span>
                                @if($member->nim)
                                    <span class="info-meta text-[9px]">{{ $member->nim }} &middot; {{ $member->angkatan }}</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    {{-- Row 2: Sekretaris & Bendahara --}}
                    <div class="flex flex-wrap justify-center gap-8 md:gap-10 max-w-4xl mx-auto">
                        @foreach($othersBph as $member)
                        <div class="member-item flex flex-col items-center cursor-pointer hover:-translate-y-1 transition-transform" 
     @click="$dispatch('open-member-modal', { name: '{{ addslashes($member->name) }}', position: '{{ addslashes($member->position_title) }}', nim: '{{ $member->nim }}', angkatan: '{{ $member->angkatan ?? '-' }}', email: '{{ $member->email }}', avatar_url: '{{ $member->avatar_url }}', initial: '{{ strtoupper(substr($member->name, 0, 1)) }}', identifier_type: '{{ str_starts_with(strtolower($member->position_title ?? ""), "pembina") || str_starts_with(strtolower($member->position_title ?? ""), "dosen") ? "NIP" : "NIM" }}', color_class: 'text-indigo-600 bg-indigo-100', text_color_class: 'text-brand-600' })">
                            <div class="member-avatar-ring w-full aspect-[3/4]">
                                <div class="member-card__inner">
                                    @if($member->avatar_url)
                                        <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="member-card__photo">
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
                                <div class="info-divider"></div>
                                <h4 class="info-name text-[13px]">{{ $member->name }}</h4>
                                <span class="info-position text-[10px]">{{ $member->position_title }}</span>
                                @if($member->nim)
                                    <span class="info-meta text-[9px]">{{ $member->nim }} &middot; {{ $member->angkatan }}</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Separator --}}
            <div class="flex flex-col items-center my-6">
                <div class="w-[2px] h-16 bg-gradient-to-b from-indigo-500/30 to-violet-500/30"></div>
                <div class="w-2.5 h-2.5 rounded-full bg-violet-600 -mt-1.5 shadow-lg shadow-violet-600/50"></div>
            </div>

            {{-- ── DIVISI KEPENGURUSAN ─────────────────────────────── --}}
            <div class="w-full text-center" data-aos="fade-up">
                <h3 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight mb-20">Divisi Kepengurusan</h3>
                <div class="space-y-24">
                    @foreach($divisions as $div)
                    <div class="text-center" data-aos="fade-up">
                        <div class="flex flex-col items-center justify-center gap-2.5 mb-12">
                            <h4 class="text-lg md:text-xl font-extrabold text-slate-800 tracking-wider uppercase">{{ $div->name }} ({{ $div->singkatan }})</h4>
                            <div class="w-12 h-1 bg-gradient-to-r from-indigo-500 to-violet-500 rounded-full"></div>
                        </div>
                        @php
                            $kadiv = $div->users->filter(fn($u) =>
                                !str_contains(strtolower($u->position_title), 'wakil') &&
                                (str_contains(strtolower($u->position_title), 'ketua divisi') || str_contains(strtolower($u->position_title), 'ketua umum'))
                            );
                            $wakadiv = $div->users->filter(fn($u) =>
                                str_contains(strtolower($u->position_title), 'wakil ketua divisi') ||
                                str_contains(strtolower($u->position_title), 'wakil ketua')
                            );
                            $officers = $div->users->filter(function($u) use ($kadiv, $wakadiv) {
                                if ($kadiv->contains($u) || $wakadiv->contains($u)) return false;
                                $t = strtolower($u->position_title);
                                return str_contains($t,'sekretaris') || str_contains($t,'bendahara') ||
                                       str_contains($t,'ketua bidang') || str_contains($t,'kabid');
                            });
                            $anggota = $div->users->reject(fn($u) =>
                                $kadiv->contains($u) || $wakadiv->contains($u) || $officers->contains($u)
                            );
                            $color = '6366f1';
                            if (str_contains($div->color,'blue'))    $color = '3b82f6';
                            if (str_contains($div->color,'emerald')) $color = '10b981';
                            if (str_contains($div->color,'amber'))   $color = 'f59e0b';
                            if (str_contains($div->color,'fuchsia')) $color = 'd946ef';
                            if (str_contains($div->color,'rose'))    $color = 'f43f5e';
                            if (str_contains($div->color,'teal'))    $color = '14b8a6';
                            if (str_contains($div->color,'slate'))   $color = '64748b';
                        @endphp
                        <div class="flex flex-col items-center gap-10 max-w-6xl mx-auto">

                            {{-- Row 1: Kadiv & Wakadiv --}}
                            @if($kadiv->count() > 0 || $wakadiv->count() > 0)
                            <div class="flex flex-wrap justify-center gap-8 md:gap-12">
                                @foreach($kadiv->concat($wakadiv) as $member)
                                <div class="member-item member-item--lg flex flex-col items-center cursor-pointer hover:-translate-y-1 transition-transform" 
     @click="$dispatch('open-member-modal', { name: '{{ addslashes($member->name) }}', position: '{{ addslashes($member->position_title) }}', nim: '{{ $member->nim }}', angkatan: '{{ $member->angkatan ?? '-' }}', email: '{{ $member->email }}', avatar_url: '{{ $member->avatar_url }}', initial: '{{ strtoupper(substr($member->name, 0, 1)) }}', identifier_type: '{{ str_starts_with(strtolower($member->position_title ?? ""), "pembina") || str_starts_with(strtolower($member->position_title ?? ""), "dosen") ? "NIP" : "NIM" }}', color_class: 'text-indigo-600 bg-indigo-100', text_color_class: 'text-brand-600' })">
                                    <div class="member-avatar-ring w-full aspect-[3/4]">
                                        <div class="member-card__inner">
                                            @if($member->avatar_url)
                                                <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="member-card__photo">
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
                                        <div class="info-divider"></div>
                                        <h4 class="info-name text-sm">{{ $member->name }}</h4>
                                        <span class="info-position text-[10px]">{{ $member->position_title }}</span>
                                        @if($member->nim)
                                            <span class="info-meta text-[9px]">{{ $member->nim }} &middot; {{ $member->angkatan }}</span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            {{-- Row 2: Officers --}}
                            @if($officers->count() > 0)
                            <div class="flex flex-wrap justify-center gap-6 md:gap-8 max-w-5xl">
                                @foreach($officers as $member)
                                <div class="member-item flex flex-col items-center cursor-pointer hover:-translate-y-1 transition-transform" 
     @click="$dispatch('open-member-modal', { name: '{{ addslashes($member->name) }}', position: '{{ addslashes($member->position_title) }}', nim: '{{ $member->nim }}', angkatan: '{{ $member->angkatan ?? '-' }}', email: '{{ $member->email }}', avatar_url: '{{ $member->avatar_url }}', initial: '{{ strtoupper(substr($member->name, 0, 1)) }}', identifier_type: '{{ str_starts_with(strtolower($member->position_title ?? ""), "pembina") || str_starts_with(strtolower($member->position_title ?? ""), "dosen") ? "NIP" : "NIM" }}', color_class: 'text-indigo-600 bg-indigo-100', text_color_class: 'text-brand-600' })">
                                    <div class="member-avatar-ring w-full aspect-[3/4]">
                                        <div class="member-card__inner">
                                            @if($member->avatar_url)
                                                <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="member-card__photo">
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
                                        <div class="info-divider"></div>
                                        <h4 class="info-name text-[13px]">{{ $member->name }}</h4>
                                        <span class="info-position text-[9px]">{{ $member->position_title }}</span>
                                        @if($member->nim)
                                            <span class="info-meta text-[8px]">{{ $member->nim }} &middot; {{ $member->angkatan }}</span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            {{-- Row 3: Anggota --}}
                            @if($anggota->count() > 0)
                            <div class="flex flex-wrap justify-center gap-5 md:gap-8 max-w-5xl">
                                @foreach($anggota as $member)
                                <div class="member-item member-item--sm flex flex-col items-center cursor-default">
                                    <div class="member-avatar-ring w-full aspect-[3/4]">
                                        <div class="member-card__inner">
                                            @if($member->avatar_url)
                                                <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="member-card__photo">
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
                                        <div class="info-divider"></div>
                                        <h4 class="info-name text-xs">{{ $member->name }}</h4>
                                        <span class="info-position text-[8px]">{{ $member->position_title }}</span>
                                        @if($member->nim)
                                            <span class="info-meta text-[8px]">{{ $member->nim }} &middot; {{ $member->angkatan }}</span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif

                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>{{-- end flex-col --}}
    </div>
</section>

{{-- CTA --}}
<section class="py-16 bg-[#0a0f1e] relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image:radial-gradient(circle at 25% 50%,#6366f1 0%,transparent 60%),radial-gradient(circle at 75% 50%,#8b5cf6 0%,transparent 60%)"></div>
    <div class="max-w-3xl mx-auto px-6 text-center relative z-10">
        <h2 class="text-2xl md:text-3xl font-black text-white mb-4">Tertarik bergabung dengan HIMASI?</h2>
        <p class="text-slate-400 mb-8">Jadilah bagian dari keluarga besar mahasiswa Sistem Informasi Universitas Jambi.</p>
        <a href="{{ route('about') }}" class="inline-flex items-center gap-2 bg-white text-indigo-700 font-bold px-8 py-3.5 rounded-full hover:bg-indigo-50 transition-colors shadow-xl shadow-indigo-900/30">
            Pelajari Tentang HIMASI
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    </div>
</section>

<!-- MEMBER DETAIL MODAL -->
<div x-data="{ open: false, member: null }" 
     @open-member-modal.window="member = $event.detail; open = true"
     x-show="open" 
     class="relative z-50" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true"
     style="display: none;">
     
    <!-- Backdrop -->
    <div x-show="open" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <!-- Modal Panel -->
            <div x-show="open"
                 @click.away="open = false"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md w-full border border-slate-200">
                
                <!-- Modal Content -->
                <div class="relative">
                    <!-- Header Background (Brand color) -->
                    <div class="h-24 bg-gradient-to-r from-brand-500 to-indigo-600"></div>
                    
                    <!-- Close button -->
                    <button type="button" @click="open = false" class="absolute top-4 right-4 text-white/80 hover:text-white bg-black/10 hover:bg-black/20 rounded-full w-8 h-8 flex items-center justify-center transition-colors">
                        <i class="ph-bold ph-x text-lg"></i>
                    </button>
                    
                    <div class="px-6 pb-6 relative">
                        <!-- Avatar -->
                        <div class="flex justify-center -mt-12 mb-4">
                            <template x-if="member?.avatar_url">
                                <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-md bg-white">
                                    <img :src="member.avatar_url" :alt="member.name" class="w-full h-full object-cover">
                                </div>
                            </template>
                            <template x-if="!member?.avatar_url">
                                <div class="w-24 h-24 rounded-full border-4 border-white shadow-md flex items-center justify-center font-black text-3xl" :class="member?.color_class">
                                    <span x-text="member?.initial"></span>
                                </div>
                            </template>
                        </div>
                        
                        <!-- Text Content -->
                        <div class="text-center mb-6">
                            <h3 class="text-xl font-black text-slate-900 leading-tight" id="modal-title" x-text="member?.name"></h3>
                            <p class="text-sm font-bold mt-1" :class="member?.text_color_class" x-text="member?.position"></p>
                        </div>
                        
                        <!-- Details Grid -->
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 space-y-3">
                            <!-- NIP / NIM -->
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 shrink-0 shadow-sm">
                                    <i class="ph-fill ph-identification-card text-base"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-500 mb-0.5" x-text="member?.identifier_type"></p>
                                    <p class="text-sm font-bold text-slate-900 break-all" x-text="member?.nim"></p>
                                </div>
                            </div>
                            
                            <!-- Email -->
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 shrink-0 shadow-sm">
                                    <i class="ph-fill ph-envelope-simple text-base"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-500 mb-0.5">EMAIL</p>
                                    <p class="text-sm font-bold text-slate-900 break-all" x-text="member?.email"></p>
                                </div>
                            </div>
                            
                            <!-- Angkatan (if exists) -->
                            <template x-if="member?.angkatan && member?.angkatan !== '-'">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 shrink-0 shadow-sm">
                                        <i class="ph-fill ph-graduation-cap text-base"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-[10px] uppercase tracking-wider font-bold text-slate-500 mb-0.5">ANGKATAN</p>
                                        <p class="text-sm font-bold text-slate-900 break-all" x-text="member?.angkatan"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
