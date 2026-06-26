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
                    <div class="member-item flex flex-col items-center text-center cursor-default">
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
                    <div class="member-item flex flex-col items-center text-center cursor-default">
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
                    <div class="flex flex-wrap justify-center gap-10 md:gap-16">
                        @foreach($kahim->concat($wakahim) as $member)
                        <div class="member-item flex flex-col items-center text-center cursor-default">
                            <div class="member-avatar-ring w-28 h-28 md:w-32 md:h-32 mb-4">
                                @if($member->avatar && file_exists(public_path(ltrim($member->avatar, '/'))))
                                    <img src="{{ asset(ltrim($member->avatar, '/')) }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="avatar-placeholder w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-500 to-violet-500 rounded-full">
                                        <span class="text-white font-black text-2xl md:text-3xl">{{ substr($member->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <h4 class="member-name font-bold text-slate-800 text-[15px] md:text-base leading-snug tracking-tight max-w-[200px]">{{ $member->name }}</h4>
                            <span class="text-[11px] font-bold text-indigo-600 tracking-wider uppercase mt-1.5">{{ $member->position_title }}</span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Row 2: Sekretaris & Bendahara --}}
                    <div class="flex flex-wrap justify-center gap-8 md:gap-12 max-w-4xl mx-auto">
                        @foreach($othersBph as $member)
                        <div class="member-item flex flex-col items-center text-center cursor-default">
                            <div class="member-avatar-ring w-24 h-24 md:w-28 md:h-28 mb-4">
                                @if($member->avatar && file_exists(public_path(ltrim($member->avatar, '/'))))
                                    <img src="{{ asset(ltrim($member->avatar, '/')) }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="avatar-placeholder w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-500 to-violet-500 rounded-full">
                                        <span class="text-white font-black text-xl md:text-2xl">{{ substr($member->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <h4 class="member-name font-bold text-slate-800 text-[15px] md:text-base leading-snug tracking-tight max-w-[200px]">{{ $member->name }}</h4>
                            <span class="text-[11px] font-bold text-indigo-600 tracking-wider uppercase mt-1.5">{{ $member->position_title }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Separator Line --}}
            <div class="flex flex-col items-center my-6">
                <div class="w-[2px] h-16 bg-gradient-to-b from-indigo-500/30 to-violet-500/30"></div>
                <div class="w-2.5 h-2.5 rounded-full bg-violet-600 -mt-1.5 shadow-lg shadow-violet-600/50"></div>
            </div>

            {{-- ── DIVISI KEPENGURUSAN ────────────────────────────────── --}}
            <div class="w-full text-center" data-aos="fade-up">
                <h3 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight mb-20">Divisi Kepengurusan</h3>

                <div class="space-y-24">
                    @foreach($divisions as $div)
                    <div class="text-center" data-aos="fade-up">
                        {{-- Division Header --}}
                        <div class="flex flex-col items-center justify-center gap-2.5 mb-12">
                            <h4 class="text-lg md:text-xl font-extrabold text-slate-800 tracking-wider uppercase">{{ $div->name }} ({{ $div->singkatan }})</h4>
                            <div class="w-12 h-1 bg-gradient-to-r from-indigo-500 to-violet-500 rounded-full"></div>
                        </div>

                        {{-- Division Members Hierarchical Grid --}}
                        @php
                            $kadiv = $div->users->filter(function($u) {
                                $title = strtolower($u->position_title);
                                return !str_contains($title, 'wakil') && (str_contains($title, 'ketua divisi') || str_contains($title, 'ketua umum'));
                            });
                            $wakadiv = $div->users->filter(function($u) {
                                return str_contains(strtolower($u->position_title), 'wakil ketua divisi') || str_contains(strtolower($u->position_title), 'wakil ketua');
                            });
                            $officers = $div->users->filter(function($u) use ($kadiv, $wakadiv) {
                                if ($kadiv->contains($u) || $wakadiv->contains($u)) return false;
                                return str_contains(strtolower($u->position_title), 'sekretaris') || 
                                       str_contains(strtolower($u->position_title), 'bendahara') || 
                                       str_contains(strtolower($u->position_title), 'ketua bidang') || 
                                       str_contains(strtolower($u->position_title), 'kabid');
                            });
                            $anggota = $div->users->reject(function($u) use ($kadiv, $wakadiv, $officers) {
                                return $kadiv->contains($u) || $wakadiv->contains($u) || $officers->contains($u);
                            });
                        @endphp

                        <div class="flex flex-col items-center gap-10 max-w-6xl mx-auto">
                            {{-- Row 1: Ketua & Wakil Ketua Divisi --}}
                            @if($kadiv->count() > 0 || $wakadiv->count() > 0)
                            <div class="flex flex-wrap justify-center gap-10 md:gap-14">
                                @foreach($kadiv->concat($wakadiv) as $member)
                                <div class="member-item flex flex-col items-center text-center cursor-default">
                                    <div class="member-avatar-ring w-24 h-24 md:w-28 md:h-28 mb-4">
                                        @if($member->avatar && file_exists(public_path(ltrim($member->avatar, '/'))))
                                            <img src="{{ asset(ltrim($member->avatar, '/')) }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                                        @else
                                            @php
                                                $color = '6366f1';
                                                if (str_contains($div->color, 'blue')) $color = '3b82f6';
                                                if (str_contains($div->color, 'emerald')) $color = '10b981';
                                                if (str_contains($div->color, 'amber')) $color = 'f59e0b';
                                                if (str_contains($div->color, 'fuchsia')) $color = 'd946ef';
                                                if (str_contains($div->color, 'rose')) $color = 'f43f5e';
                                                if (str_contains($div->color, 'teal')) $color = '14b8a6';
                                                if (str_contains($div->color, 'slate')) $color = '64748b';
                                            @endphp
                                            <div class="avatar-placeholder w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-200 to-slate-300 rounded-full" style="background-image: linear-gradient(135deg, #{{ $color }} 0%, #a5b4fc 100%)">
                                                <span class="text-white font-black text-xl">{{ substr($member->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <h5 class="member-name font-bold text-slate-800 text-[15px] md:text-base leading-snug tracking-tight max-w-[180px]">{{ $member->name }}</h5>
                                    <span class="text-[11px] font-bold text-indigo-600 tracking-wider uppercase mt-1.5 max-w-[180px]">{{ $member->position_title }}</span>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            {{-- Row 2: Pengurus Harian Divisi (Sekretaris, Bendahara, Kabid) --}}
                            @if($officers->count() > 0)
                            <div class="flex flex-wrap justify-center gap-8 md:gap-12 max-w-5xl">
                                @foreach($officers as $member)
                                <div class="member-item flex flex-col items-center text-center cursor-default">
                                    <div class="member-avatar-ring w-20 h-20 md:w-24 md:h-24 mb-4">
                                        @if($member->avatar && file_exists(public_path(ltrim($member->avatar, '/'))))
                                            <img src="{{ asset(ltrim($member->avatar, '/')) }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                                        @else
                                            @php
                                                $color = '6366f1';
                                                if (str_contains($div->color, 'blue')) $color = '3b82f6';
                                                if (str_contains($div->color, 'emerald')) $color = '10b981';
                                                if (str_contains($div->color, 'amber')) $color = 'f59e0b';
                                                if (str_contains($div->color, 'fuchsia')) $color = 'd946ef';
                                                if (str_contains($div->color, 'rose')) $color = 'f43f5e';
                                                if (str_contains($div->color, 'teal')) $color = '14b8a6';
                                                if (str_contains($div->color, 'slate')) $color = '64748b';
                                            @endphp
                                            <div class="avatar-placeholder w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-200 to-slate-300 rounded-full" style="background-image: linear-gradient(135deg, #{{ $color }} 0%, #a5b4fc 100%)">
                                                <span class="text-white font-black text-lg">{{ substr($member->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <h5 class="member-name font-bold text-slate-800 text-sm leading-snug tracking-tight max-w-[180px]">{{ $member->name }}</h5>
                                    <span class="text-[10px] font-bold text-slate-500 tracking-wider uppercase mt-1.5 max-w-[180px]">{{ $member->position_title }}</span>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            {{-- Row 3: Anggota Divisi --}}
                            @if($anggota->count() > 0)
                            <div class="flex flex-wrap justify-center gap-6 md:gap-10 max-w-4xl">
                                @foreach($anggota as $member)
                                <div class="member-item flex flex-col items-center text-center cursor-default">
                                    <div class="member-avatar-ring w-16 h-16 md:w-20 md:h-20 mb-4">
                                        @if($member->avatar && file_exists(public_path(ltrim($member->avatar, '/'))))
                                            <img src="{{ asset(ltrim($member->avatar, '/')) }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                                        @else
                                            @php
                                                $color = '6366f1';
                                                if (str_contains($div->color, 'blue')) $color = '3b82f6';
                                                if (str_contains($div->color, 'emerald')) $color = '10b981';
                                                if (str_contains($div->color, 'amber')) $color = 'f59e0b';
                                                if (str_contains($div->color, 'fuchsia')) $color = 'd946ef';
                                                if (str_contains($div->color, 'rose')) $color = 'f43f5e';
                                                if (str_contains($div->color, 'teal')) $color = '14b8a6';
                                                if (str_contains($div->color, 'slate')) $color = '64748b';
                                            @endphp
                                            <div class="avatar-placeholder w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-200 to-slate-300 rounded-full" style="background-image: linear-gradient(135deg, #{{ $color }} 0%, #a5b4fc 100%)">
                                                <span class="text-white font-black text-base">{{ substr($member->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <h5 class="member-name font-bold text-slate-800 text-xs md:text-sm leading-snug tracking-tight max-w-[160px]">{{ $member->name }}</h5>
                                    <span class="text-[9px] font-bold text-slate-400 tracking-wider uppercase mt-1.5 max-w-[160px]">{{ $member->position_title }}</span>
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

{{-- ═══════════════════════════════════════════════════════
     CTA BAND
════════════════════════════════════════════════════════ --}}
<section class="py-16 bg-[#0a0f1e] relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image:radial-gradient(circle at 25% 50%, #6366f1 0%, transparent 60%),radial-gradient(circle at 75% 50%, #8b5cf6 0%, transparent 60%)"></div>
    <div class="max-w-3xl mx-auto px-6 text-center relative z-10">
        <h2 class="text-2xl md:text-3xl font-black text-white mb-4">Tertarik bergabung dengan HIMASI?</h2>
        <p class="text-slate-400 mb-8">Jadilah bagian dari keluarga besar mahasiswa Sistem Informasi Universitas Jambi.</p>
        <a href="{{ route('about') }}"
           class="inline-flex items-center gap-2 bg-white text-indigo-700 font-bold px-8 py-3.5 rounded-full hover:bg-indigo-50 transition-colors shadow-xl shadow-indigo-900/30">
            Pelajari Tentang HIMASI
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    </div>
</section>

@endsection
