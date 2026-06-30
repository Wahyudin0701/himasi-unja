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

    /* ── Cardless Member Hover Effect ───────────────────────────── */
    .member-item {
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .member-item:hover {
        transform: translateY(-5px);
    }
    .member-avatar-ring {
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        padding: 3px;
        border-radius: 50%;
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), background 0.4s ease, box-shadow 0.4s ease;
    }
    .member-item:hover .member-avatar-ring {
        background: linear-gradient(135deg, #6366f1, #8b5cf6, #06b6d4);
        transform: scale(1.06);
        box-shadow: 0 12px 30px rgba(99,102,241,0.25);
    }
    .member-avatar-ring img,
    .member-avatar-ring .avatar-placeholder {
        border-radius: 50%;
        display: block;
        background: #fff;
    }
    .member-name {
        transition: color 0.3s ease;
    }
    .member-item:hover .member-name {
        color: #4f46e5;
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
    {{-- Background image --}}
    <div class="absolute inset-0 z-0 opacity-40">
        <img src="{{ asset('pengurus_himasi.png') }}" alt="HIMASI Struktur Background"
             class="w-full h-full object-cover object-center filter grayscale mix-blend-luminosity">
    </div>
    {{-- Gradient overlay --}}
    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/80 to-slate-900/50 z-0"></div>
    {{-- Decorative blurs --}}
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

        {{-- Section Header --}}
        <div class="text-center mb-24">
            <div class="section-pill bg-slate-100 text-slate-500 border border-slate-200 mb-4">Hirarki Kepengurusan</div>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight">Bagan Organisasi</h2>
        </div>

        <div class="flex flex-col items-center gap-12">

            {{-- ── PEMBINA ──────────────────────────────────────────── --}}
            <div class="w-full text-center" data-aos="fade-up">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mb-10">Pembina HIMASI</h3>
                <div class="flex flex-wrap justify-center gap-10 md:gap-16">
                    @php
                    $pembinas = [
                        ['nama' => 'Pembina I', 'jabatan' => 'Pembina HIMASI', 'initial' => 'PB', 'color' => 'from-indigo-500 to-violet-500'],
                        ['nama' => 'Pembina II', 'jabatan' => 'Pembina HIMASI', 'initial' => 'PB', 'color' => 'from-indigo-500 to-violet-500'],
                    ];
                    @endphp
                    @foreach($pembinas as $pb)
                    <div class="member-item flex flex-col items-center text-center cursor-pointer hover:-translate-y-1 transition-transform" 
     @click="$dispatch('open-member-modal', { name: '{{ addslashes($member->name) }}', position: '{{ addslashes($member->position_title) }}', nim: '{{ $member->nim }}', angkatan: '{{ $member->angkatan ?? '-' }}', email: '{{ $member->email }}', avatar_url: '{{ $member->avatar_url }}', initial: '{{ strtoupper(substr($member->name, 0, 1)) }}', identifier_type: '{{ str_starts_with(strtolower($member->position_title ?? \"\"), \"pembina\") || str_starts_with(strtolower($member->position_title ?? \"\"), \"dosen\") ? \"NIP\" : \"NIM\" }}', color_class: 'text-indigo-600 bg-indigo-100', text_color_class: 'text-brand-600' })">
                        <div class="member-avatar-ring w-24 h-24 md:w-28 md:h-28 mb-4">
                            <div class="avatar-placeholder w-full h-full flex items-center justify-center bg-gradient-to-br {{ $pb['color'] }} rounded-full">
                                <span class="text-white font-black text-xl md:text-2xl">{{ $pb['initial'] }}</span>
                            </div>
                        </div>
                        <h4 class="member-name font-bold text-slate-800 text-[15px] md:text-base leading-snug tracking-tight max-w-[200px]">{{ $pb['nama'] }}</h4>
                        <span class="text-[11px] font-bold text-indigo-600 tracking-wider uppercase mt-1.5">{{ $pb['jabatan'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Separator Line --}}
            <div class="flex flex-col items-center my-4">
                <div class="w-[2px] h-14 bg-gradient-to-b from-indigo-500/30 to-violet-500/30"></div>
                <div class="w-2 h-2 rounded-full bg-violet-500 -mt-1 shadow-md shadow-violet-500/50"></div>
            </div>

            {{-- ── DEWAN PENGAWAS ───────────────────────────────────── --}}
            <div class="w-full text-center" data-aos="fade-up">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mb-10">Dewan Pengawas (DP)</h3>
                <div class="flex flex-wrap justify-center gap-8 md:gap-12">
                    @for($i = 1; $i <= 5; $i++)
                    <div class="member-item flex flex-col items-center text-center cursor-pointer hover:-translate-y-1 transition-transform" 
     @click="$dispatch('open-member-modal', { name: '{{ addslashes($member->name) }}', position: '{{ addslashes($member->position_title) }}', nim: '{{ $member->nim }}', angkatan: '{{ $member->angkatan ?? '-' }}', email: '{{ $member->email }}', avatar_url: '{{ $member->avatar_url }}', initial: '{{ strtoupper(substr($member->name, 0, 1)) }}', identifier_type: '{{ str_starts_with(strtolower($member->position_title ?? \"\"), \"pembina\") || str_starts_with(strtolower($member->position_title ?? \"\"), \"dosen\") ? \"NIP\" : \"NIM\" }}', color_class: 'text-indigo-600 bg-indigo-100', text_color_class: 'text-brand-600' })">
                        <div class="member-avatar-ring w-24 h-24 md:w-28 md:h-28 mb-4">
                            <div class="avatar-placeholder w-full h-full flex items-center justify-center bg-gradient-to-br from-violet-500 to-purple-500 rounded-full">
                                <span class="text-white font-black text-xl md:text-2xl">DP{{ $i }}</span>
                            </div>
                        </div>
                        <h4 class="member-name font-bold text-slate-800 text-[15px] md:text-base leading-snug tracking-tight max-w-[200px]">Anggota DP {{ $i }}</h4>
                        <span class="text-[11px] font-bold text-violet-600 tracking-wider uppercase mt-1.5">Dewan Pengawas</span>
                    </div>
                    @endfor
                </div>
            </div>

            {{-- Separator Line --}}
            <div class="flex flex-col items-center my-4">
                <div class="w-[2px] h-14 bg-gradient-to-b from-violet-500/30 to-indigo-500/30"></div>
                <div class="w-2 h-2 rounded-full bg-indigo-500 -mt-1 shadow-md shadow-indigo-500/50"></div>
            </div>

            {{-- ── BPH ──────────────────────────────────────────────── --}}
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
                        <div class="member-item flex flex-col items-center cursor-default">
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
                        <div class="member-item flex flex-col items-center cursor-default">
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
                <div class="w-[2px] h-14 bg-gradient-to-b from-violet-500/30 to-indigo-500/30"></div>
                <div class="w-2 h-2 rounded-full bg-indigo-500 -mt-1 shadow-md shadow-indigo-500/50"></div>
            </div>

            {{-- ── BPH ──────────────────────────────────────────────── --}}
            {{-- ── BPH ──────────────────────────────────────────────── --}}
            @if($bphDivision)
            <div class="w-full text-center" data-aos="fade-up">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mb-10">Badan Pengurus Harian (BPH)</h3>
                
                @php
                    $kahim = $bphDivision->users->filter(function($u) {
                        $title = strtolower($u->position_title);
                        return !str_contains($title, 'wakil') && (str_contains($title, 'ketua himpunan') || str_contains($title, 'ketua umum'));
                    });
                    $wakahim = $bphDivision->users->filter(function($u) {
                        return str_contains(strtolower($u->position_title), 'wakil ketua');
                    });
                    $othersBph = $bphDivision->users->reject(function($u) use ($kahim, $wakahim) {
                        return $kahim->contains($u) || $wakahim->contains($u);
                    });
                @endphp

                <div class="flex flex-col items-center gap-10">
                    {{-- Row 1: Ketua & Wakil Ketua --}}
                    <div class="flex flex-wrap justify-center gap-10 md:gap-14">
                        @foreach($kahim->concat($wakahim) as $member)
                        <div class="member-item member-item--lg flex flex-col items-center cursor-default">
                            <div class="member-avatar-ring w-full h-[300px]">
                                <div class="member-card__inner">
                                    @if($member->avatar && file_exists(public_path(ltrim($member->avatar, '/'))))
                                        <img src="{{ asset(ltrim($member->avatar, '/')) }}" alt="{{ $member->name }}" class="member-card__photo">
                                    @else
                                        <div class="member-card__photo flex items-center justify-center bg-gradient-to-br from-indigo-500 to-violet-500">
                                            <span class="text-white font-black text-3xl">{{ substr($member->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div class="member-card__overlay"></div>
                                    <div class="member-card__info">
                                        <div class="info-divider"></div>
                                        <h4 class="info-name text-sm">{{ $member->name }}</h4>
                                        <span class="info-position text-[10px]">{{ $member->position_title }}</span>
                                        @if($member->nim)
                                            <span class="info-meta text-[9px]">{{ $member->nim }} &middot; {{ $member->angkatan }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Row 2: Sekretaris & Bendahara --}}
                    <div class="flex flex-wrap justify-center gap-8 md:gap-10 max-w-4xl mx-auto">
                        @foreach($othersBph as $member)
                        <div class="member-item flex flex-col items-center cursor-default">
                            <div class="member-avatar-ring w-full h-[250px]">
                                <div class="member-card__inner">
                                    @if($member->avatar && file_exists(public_path(ltrim($member->avatar, '/'))))
                                        <img src="{{ asset(ltrim($member->avatar, '/')) }}" alt="{{ $member->name }}" class="member-card__photo">
                                    @else
                                        <div class="member-card__photo flex items-center justify-center bg-gradient-to-br from-indigo-500 to-violet-500">
                                            <span class="text-white font-black text-2xl">{{ substr($member->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div class="member-card__overlay"></div>
                                    <div class="member-card__info">
                                        <div class="info-divider"></div>
                                        <h4 class="info-name text-[13px]">{{ $member->name }}</h4>
                                        <span class="info-position text-[10px]">{{ $member->position_title }}</span>
                                        @if($member->nim)
                                            <span class="info-meta text-[9px]">{{ $member->nim }} &middot; {{ $member->angkatan }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Separator Line --}}
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
                                <div class="member-item member-item--lg flex flex-col items-center cursor-default">
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
                                <div class="member-item flex flex-col items-center cursor-default">
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
@endsection

