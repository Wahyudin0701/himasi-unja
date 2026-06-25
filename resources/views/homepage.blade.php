<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistem Informasi Kepanitiaan Dies Natalis Sistem Informasi — Platform terpadu untuk manajemen 10 divisi kepanitiaan secara efisien dan real-time.">
    <title>Dies Natalis Sistem Informasi — Sistem Kepanitiaan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }

        .hero-gradient {
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
        }

        .card-glow:hover {
            box-shadow: 0 0 40px rgba(99, 102, 241, 0.25);
        }

        .badge-glow {
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.5);
        }

        .text-gradient {
            background: linear-gradient(135deg, #818cf8 0%, #c084fc 50%, #f472b6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .grain-overlay::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 1;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }
        .float-anim { animation: float 4s ease-in-out infinite; }

        @keyframes pulse-ring {
            0% { transform: scale(1); opacity: 0.6; }
            100% { transform: scale(2.5); opacity: 0; }
        }
        .pulse-ring { animation: pulse-ring 2.5s cubic-bezier(0.4, 0, 0.6, 1) infinite; }

        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .slide-in { animation: slideInUp 0.7s ease forwards; opacity: 0; }
        .slide-in-1 { animation-delay: 0.1s; }
        .slide-in-2 { animation-delay: 0.25s; }
        .slide-in-3 { animation-delay: 0.4s; }
        .slide-in-4 { animation-delay: 0.55s; }

        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .marquee-track { animation: marquee 25s linear infinite; }
        .marquee-track:hover { animation-play-state: paused; }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.25;
        }
    </style>
</head>

<body class="bg-[#080614] text-white overflow-x-hidden">

    {{-- === NAVBAR === --}}
    <nav class="fixed top-0 left-0 right-0 z-50 px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="font-bold text-white text-lg tracking-tight">Dies Natalis SI</span>
            </div>
            <a href="{{ route('login') }}"
               class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/10 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                Masuk Sistem
            </a>
        </div>
    </nav>

    {{-- === HERO === --}}
    <section class="relative min-h-screen flex items-center justify-center px-6 overflow-hidden hero-gradient grain-overlay">

        {{-- Decorative orbs --}}
        <div class="orb w-96 h-96 bg-indigo-600 top-[-10%] left-[-10%]"></div>
        <div class="orb w-80 h-80 bg-purple-600 bottom-[-5%] right-[-5%]"></div>
        <div class="orb w-64 h-64 bg-pink-500 top-[40%] right-[20%]"></div>

        {{-- Grid pattern --}}
        <div class="absolute inset-0 opacity-10" style="background-image: linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 50px 50px;"></div>

        <div class="relative z-10 max-w-4xl mx-auto text-center pt-20">

            {{-- Badge --}}
            <div class="slide-in slide-in-1 inline-flex items-center gap-2 bg-indigo-500/20 border border-indigo-400/30 rounded-full px-5 py-2 mb-8 badge-glow">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-indigo-500"></span>
                </span>
                <span class="text-indigo-300 text-sm font-semibold">Sistem Kepanitiaan Aktif</span>
            </div>

            {{-- Title --}}
            <h1 class="slide-in slide-in-2 text-5xl md:text-7xl font-black leading-tight mb-6">
                <span class="text-white">Kepanitiaan </span>
                <span class="text-gradient">Dies Natalis</span>
                <br>
                <span class="text-white">Sistem Informasi</span>
            </h1>

            {{-- Subtitle --}}
            <p class="slide-in slide-in-3 text-lg md:text-xl text-slate-400 max-w-2xl mx-auto mb-10 leading-relaxed">
                Platform manajemen kepanitiaan terpadu untuk <strong class="text-white">10 Divisi</strong>.
                Dari rundown acara hingga distribusi konsumsi — semua terkoordinasi dalam satu sistem.
            </p>

            {{-- CTA Buttons --}}
            <div class="slide-in slide-in-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('login') }}"
                   id="btn-masuk-sistem"
                   class="group relative inline-flex items-center gap-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold px-8 py-4 rounded-2xl transition-all duration-300 shadow-2xl shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                    Masuk ke Dasbor
                    <span class="absolute inset-0 rounded-2xl bg-white opacity-0 group-hover:opacity-10 transition-opacity"></span>
                </a>
                <a href="#fitur"
                   class="inline-flex items-center gap-2 text-slate-400 hover:text-white font-semibold px-6 py-4 rounded-2xl border border-white/10 hover:border-white/20 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                    Lihat Fitur
                </a>
            </div>

            {{-- Stats --}}
            <div class="mt-16 grid grid-cols-3 gap-6 max-w-lg mx-auto">
                <div class="text-center">
                    <div class="text-3xl font-black text-white">10</div>
                    <div class="text-xs text-slate-500 mt-1">Divisi Aktif</div>
                </div>
                <div class="text-center border-x border-white/10">
                    <div class="text-3xl font-black text-white">64</div>
                    <div class="text-xs text-slate-500 mt-1">Akun Panitia</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-black text-white">14</div>
                    <div class="text-xs text-slate-500 mt-1">Modul Sistem</div>
                </div>
            </div>
        </div>

        {{-- Scroll indicator --}}
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 opacity-50">
            <span class="text-xs text-slate-500">Gulir ke bawah</span>
            <svg class="w-5 h-5 text-slate-500 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </section>

    {{-- === MARQUEE / TICKER === --}}
    <div class="py-4 bg-indigo-600/10 border-y border-indigo-500/20 overflow-hidden">
        <div class="flex whitespace-nowrap marquee-track">
            @php
            $items = ['Divisi Acara', 'Divisi Kestari', 'Divisi Humas', 'Divisi Logistik', 'Divisi Sponsor', 'Divisi Pubdok', 'Divisi Keamanan', 'Divisi Kesehatan', 'Divisi Konsumsi', 'Divisi Dekorasi', 'Perangkat Inti'];
            @endphp
            @foreach(array_merge($items, $items) as $item)
            <span class="inline-flex items-center gap-3 px-8 text-indigo-300 text-sm font-semibold">
                <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full"></span>
                {{ $item }}
            </span>
            @endforeach
        </div>
    </div>

    {{-- === FITUR SECTION === --}}
    <section id="fitur" class="py-24 px-6 bg-[#080614]">
        <div class="max-w-7xl mx-auto">

            <div class="text-center mb-16">
                <span class="text-indigo-400 text-sm font-bold uppercase tracking-widest">Modul Sistem</span>
                <h2 class="text-4xl md:text-5xl font-black text-white mt-3 mb-4">Fitur Lengkap untuk Setiap Divisi</h2>
                <p class="text-slate-400 max-w-xl mx-auto">Setiap divisi memiliki modul khusus yang dirancang sesuai kebutuhan operasional lapangan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @php
                $features = [
                    ['icon' => '📅', 'div' => 'Divisi Acara', 'title' => 'Rundown Live', 'desc' => 'Manajemen jadwal real-time. PJ dapat menggeser jam acara dan otomatis memicu notifikasi ke seluruh panitia.', 'color' => 'from-blue-500/20 to-indigo-500/20', 'border' => 'border-blue-500/30'],
                    ['icon' => '📋', 'div' => 'Divisi Kestari', 'title' => 'Generator Surat & Absensi', 'desc' => 'Buat draf surat, rekap absensi, dan kelola database peserta untuk keperluan sertifikat secara terpadu.', 'color' => 'from-violet-500/20 to-purple-500/20', 'border' => 'border-violet-500/30'],
                    ['icon' => '🤝', 'div' => 'Divisi Humas', 'title' => 'Eksekutor Dokumen', 'desc' => 'Unggah scan dokumen TTD basah & stempel, guest handling VIP/Dosen, dan distribusi surat digital.', 'color' => 'from-fuchsia-500/20 to-pink-500/20', 'border' => 'border-fuchsia-500/30'],
                    ['icon' => '📦', 'div' => 'Divisi Logistik', 'title' => 'Inventaris & Peminjaman', 'desc' => 'Sistem inventaris interaktif dengan alur Diajukan → Dipinjam → Dikembalikan, lengkap dengan foto kondisi barang.', 'color' => 'from-orange-500/20 to-amber-500/20', 'border' => 'border-orange-500/30'],
                    ['icon' => '💼', 'div' => 'Divisi Sponsor', 'title' => 'Sponsorship Pipeline', 'desc' => 'Papan Kanban status sponsor (Prospect → Deal/Ditolak) untuk mencegah tumpang tindih kontak antar anggota.', 'color' => 'from-green-500/20 to-emerald-500/20', 'border' => 'border-green-500/30'],
                    ['icon' => '🎨', 'div' => 'Divisi Pubdok', 'title' => 'Aset Desain Terpusat', 'desc' => 'Upload dan distribusikan template pamflet, sertifikat, dan ID Card agar bisa diakses oleh seluruh divisi.', 'color' => 'from-cyan-500/20 to-sky-500/20', 'border' => 'border-cyan-500/30'],
                    ['icon' => '🛡️', 'div' => 'Divisi Keamanan', 'title' => 'Gamifikasi Kedisiplinan', 'desc' => 'Catat pelanggaran panitia, sistem otomatis mengurangi poin, dan tampilkan Leaderboard Kedisiplinan.', 'color' => 'from-red-500/20 to-rose-500/20', 'border' => 'border-red-500/30'],
                    ['icon' => '🏥', 'div' => 'Divisi Kesehatan', 'title' => 'Audit Medis P3K', 'desc' => 'Tracker inventory obat dengan alert otomatis untuk obat yang mendekati masa kedaluwarsa.', 'color' => 'from-teal-500/20 to-green-500/20', 'border' => 'border-teal-500/30'],
                    ['icon' => '🍱', 'div' => 'Divisi Konsumsi', 'title' => 'Kalkulasi Distribusi Makanan', 'desc' => 'Hitung otomatis jumlah panitia + tamu hadir, lengkap dengan detail pantangan makan dan alergi.', 'color' => 'from-yellow-500/20 to-orange-500/20', 'border' => 'border-yellow-500/30'],
                ];
                @endphp

                @foreach($features as $f)
                <div class="group relative bg-white/5 hover:bg-white/8 backdrop-blur-sm border {{ $f['border'] }} rounded-2xl p-6 transition-all duration-300 card-glow cursor-default">
                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-br {{ $f['color'] }} opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="text-3xl mb-4">{{ $f['icon'] }}</div>
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">{{ $f['div'] }}</div>
                        <h3 class="text-lg font-bold text-white mb-2">{{ $f['title'] }}</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">{{ $f['desc'] }}</p>
                    </div>
                </div>
                @endforeach

                {{-- Dekorasi card --}}
                <div class="group relative bg-gradient-to-br from-indigo-600/30 to-purple-600/30 border border-indigo-500/40 rounded-2xl p-6 transition-all duration-300 card-glow">
                    <div class="text-3xl mb-4">🏠</div>
                    <div class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-1">Divisi Dekorasi</div>
                    <h3 class="text-lg font-bold text-white mb-2">Task Checklist Sinkronisasi</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">To-do list tersinkronisasi dengan Divisi Logistik & Acara untuk kebutuhan layout dan tata ruang.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- === ALUR TERINTEGRASI === --}}
    <section class="py-24 px-6 bg-white/2">
        <div class="max-w-4xl mx-auto text-center">
            <span class="text-indigo-400 text-sm font-bold uppercase tracking-widest">Alur Terintegrasi</span>
            <h2 class="text-4xl font-black text-white mt-3 mb-4">Sistem Lintas-Divisi yang Terhubung</h2>
            <p class="text-slate-400 mb-16 max-w-2xl mx-auto">Setiap divisi berkolaborasi secara seamless melalui alur kerja yang otomatis dan terstruktur.</p>

            {{-- Alur Surat --}}
            <div class="bg-white/5 border border-white/10 rounded-3xl p-8 mb-6">
                <h3 class="text-lg font-bold text-white mb-6 flex items-center justify-center gap-2">
                    <span class="text-2xl">✉️</span> Alur Persuratan
                </h3>
                <div class="flex flex-wrap items-center justify-center gap-3">
                    @php
                    $steps = [
                        ['label' => 'Kestari', 'desc' => 'Buat Draf', 'color' => 'bg-violet-500/20 text-violet-300 border-violet-500/40'],
                        ['label' => 'Sekpel', 'desc' => 'Verifikasi & Nomori', 'color' => 'bg-blue-500/20 text-blue-300 border-blue-500/40'],
                        ['label' => 'Humas', 'desc' => 'Print & TTD & Scan', 'color' => 'bg-pink-500/20 text-pink-300 border-pink-500/40'],
                        ['label' => 'Logistik / Sponsor', 'desc' => 'Download PDF', 'color' => 'bg-green-500/20 text-green-300 border-green-500/40'],
                    ];
                    @endphp
                    @foreach($steps as $i => $step)
                    <div class="flex items-center gap-3">
                        <div class="border {{ $step['color'] }} rounded-xl px-4 py-3 text-center min-w-[110px]">
                            <div class="text-xs font-black uppercase tracking-wider mb-1">{{ $step['label'] }}</div>
                            <div class="text-xs opacity-70">{{ $step['desc'] }}</div>
                        </div>
                        @if($i < count($steps) - 1)
                        <svg class="w-5 h-5 text-slate-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Alur Sertifikat --}}
            <div class="bg-white/5 border border-white/10 rounded-3xl p-8">
                <h3 class="text-lg font-bold text-white mb-6 flex items-center justify-center gap-2">
                    <span class="text-2xl">🎓</span> Alur Sertifikat Massal
                </h3>
                <div class="flex flex-wrap items-center justify-center gap-3">
                    @php
                    $steps2 = [
                        ['label' => 'Pubdok', 'desc' => 'Upload Template', 'color' => 'bg-cyan-500/20 text-cyan-300 border-cyan-500/40'],
                        ['label' => 'Kestari', 'desc' => 'Import CSV Nama', 'color' => 'bg-violet-500/20 text-violet-300 border-violet-500/40'],
                        ['label' => 'Humas', 'desc' => 'Scan Stempel TTD', 'color' => 'bg-pink-500/20 text-pink-300 border-pink-500/40'],
                        ['label' => 'Sistem', 'desc' => 'Generate Massal', 'color' => 'bg-indigo-500/20 text-indigo-300 border-indigo-500/40'],
                    ];
                    @endphp
                    @foreach($steps2 as $i => $step)
                    <div class="flex items-center gap-3">
                        <div class="border {{ $step['color'] }} rounded-xl px-4 py-3 text-center min-w-[110px]">
                            <div class="text-xs font-black uppercase tracking-wider mb-1">{{ $step['label'] }}</div>
                            <div class="text-xs opacity-70">{{ $step['desc'] }}</div>
                        </div>
                        @if($i < count($steps2) - 1)
                        <svg class="w-5 h-5 text-slate-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- === CTA FINAL === --}}
    <section class="py-24 px-6">
        <div class="max-w-2xl mx-auto text-center">
            <div class="relative bg-gradient-to-br from-indigo-600/30 to-purple-600/30 border border-indigo-500/30 rounded-3xl p-12 overflow-hidden">
                <div class="orb w-64 h-64 bg-indigo-500 top-[-50%] left-[-20%]" style="opacity:0.15;"></div>
                <div class="orb w-48 h-48 bg-purple-500 bottom-[-30%] right-[-10%]" style="opacity:0.15;"></div>
                <div class="relative z-10">
                    <div class="text-5xl mb-6 float-anim">🚀</div>
                    <h2 class="text-3xl md:text-4xl font-black text-white mb-4">Siap Bertugas?</h2>
                    <p class="text-slate-400 mb-8">Masuk ke sistem untuk mulai mengelola kepanitiaan Dies Natalis bersama divisimu.</p>
                    <a href="{{ route('login') }}"
                       id="btn-cta-login"
                       class="inline-flex items-center gap-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold px-10 py-4 rounded-2xl transition-all duration-300 shadow-2xl shadow-indigo-500/40 hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Masuk Sekarang
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- === FOOTER === --}}
    <footer class="py-8 px-6 border-t border-white/5 text-center">
        <p class="text-slate-600 text-sm">
            &copy; {{ date('Y') }} Sistem Informasi Kepanitiaan Dies Natalis &mdash; Dibuat untuk panitia, oleh panitia.
        </p>
    </footer>

</body>
</html>
