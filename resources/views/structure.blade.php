@extends('layouts.public')

@section('title', 'Struktur Organisasi HIMASI Unja')

@section('content')
<!-- Hero Section -->
<section class="relative pt-24 pb-16 md:pt-32 md:pb-24 overflow-hidden bg-slate-900">
    <div class="absolute inset-0 z-0 opacity-40">
        <img src="{{ asset('pengurus_himasi.png') }}" alt="HIMASI Struktur Background" class="w-full h-full object-cover object-center filter grayscale mix-blend-luminosity">
    </div>
    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/80 to-slate-900/50 z-0"></div>
    
    <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
        <div class="inline-block mb-4 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 backdrop-blur-md">
            <span class="text-sm font-bold text-indigo-300 tracking-wider uppercase">Profil Organisasi</span>
        </div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white tracking-tight mb-6">
            Struktur Organisasi<br class="hidden md:block"/> <span class="text-indigo-400">HIMASI Unja</span>
        </h1>
        <p class="text-lg md:text-xl text-slate-300 max-w-3xl mx-auto leading-relaxed">
            Mengenal lebih dekat Badan Pengurus Harian dan kedelapan divisi yang menjadi motor penggerak Himpunan Mahasiswa Sistem Informasi.
        </p>
    </div>
</section>

<!-- BPH Section (Badan Pengurus Harian) -->
<section class="py-16 md:py-24 bg-white border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-6">
        <div class="bg-indigo-50/50 rounded-[2.5rem] p-8 md:p-16 border border-indigo-100/50 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-16 opacity-10 pointer-events-none">
                <div class="text-[150px]">🏛️</div>
            </div>
            <div class="relative z-10 max-w-3xl">
                <div class="text-indigo-600 font-bold tracking-wider uppercase text-sm mb-4">Inti Organisasi</div>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-6">Badan Pengurus Harian (BPH)</h2>
                <p class="text-lg text-slate-600 leading-relaxed mb-8">
                    Badan Pengurus Harian adalah elemen inti yang bertugas memimpin, merumuskan kebijakan, dan mengawasi seluruh kegiatan operasional HIMASI Universitas Jambi. BPH memastikan seluruh divisi bekerja secara sinergis sesuai dengan visi dan misi organisasi.
                </p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div>
                        <div class="font-bold text-slate-900">Ketua Umum</div>
                        <div class="text-sm text-slate-500">Pemimpin Organisasi</div>
                    </div>
                    <div>
                        <div class="font-bold text-slate-900">Wakil Ketua</div>
                        <div class="text-sm text-slate-500">Wakil Pemimpin</div>
                    </div>
                    <div>
                        <div class="font-bold text-slate-900">Sekretaris</div>
                        <div class="text-sm text-slate-500">Administrasi & Surat</div>
                    </div>
                    <div>
                        <div class="font-bold text-slate-900">Bendahara</div>
                        <div class="text-sm text-slate-500">Manajemen Keuangan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Divisi List Section -->
<section class="py-16 md:py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Kedelapan Divisi HIMASI</h2>
            <p class="text-slate-500 max-w-2xl mx-auto text-lg">Pilar-pilar pelaksana program kerja yang menopang keberhasilan organisasi.</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            @php
            $divisis = [
                [
                    'nama' => 'Hubungan Masyarakat',
                    'singkatan' => 'Humas',
                    'icon' => '🤝',
                    'desc' => 'Menjadi jembatan komunikasi antara HIMASI dengan pihak eksternal, baik mahasiswa, alumni, maupun instansi di luar kampus. Divisi ini bertanggung jawab atas citra publik organisasi.',
                    'color' => 'bg-blue-50',
                    'text' => 'text-blue-600'
                ],
                [
                    'nama' => 'Riset dan Teknologi',
                    'singkatan' => 'Ristek',
                    'icon' => '💻',
                    'desc' => 'Fokus pada pengembangan hard skill mahasiswa dalam bidang IT, mencakup pelatihan programming, jaringan, hingga kompetisi teknologi, guna memastikan anggota melek teknologi terkini.',
                    'color' => 'bg-emerald-50',
                    'text' => 'text-emerald-600'
                ],
                [
                    'nama' => 'Dana Usaha',
                    'singkatan' => 'Danus',
                    'icon' => '💰',
                    'desc' => 'Motor penggerak finansial mandiri organisasi. Divisi ini merancang dan mengeksekusi kegiatan wirausaha (merchandise, event berbayar) untuk mendanai operasional HIMASI.',
                    'color' => 'bg-amber-50',
                    'text' => 'text-amber-600'
                ],
                [
                    'nama' => 'Media dan Informasi',
                    'singkatan' => 'Medinfo',
                    'icon' => '📸',
                    'desc' => 'Pusat kreatif HIMASI yang mengelola seluruh platform sosial media, desain grafis, publikasi informasi, dan dokumentasi setiap kegiatan himpunan.',
                    'color' => 'bg-fuchsia-50',
                    'text' => 'text-fuchsia-600'
                ],
                [
                    'nama' => 'Minat dan Bakat',
                    'singkatan' => 'Mikat',
                    'icon' => '🎨',
                    'desc' => 'Mewadahi dan mengembangkan potensi non-akademik mahasiswa Sistem Informasi di bidang seni, olahraga, dan kreativitas melalui berbagai event dan klub rutin.',
                    'color' => 'bg-rose-50',
                    'text' => 'text-rose-600'
                ],
                [
                    'nama' => 'Pengembangan Sumber Daya Anggota',
                    'singkatan' => 'PSDA',
                    'icon' => '🌱',
                    'desc' => 'Bertanggung jawab atas kaderisasi, pengakraban, dan peningkatan kualitas kepemimpinan (soft skill) seluruh anggota internal HIMASI.',
                    'color' => 'bg-teal-50',
                    'text' => 'text-teal-600'
                ],
                [
                    'nama' => 'Sosial dan Agama',
                    'singkatan' => 'SosAgma',
                    'icon' => '🙏',
                    'desc' => 'Memfasilitasi kegiatan spiritual dan sosial kemasyarakatan. Menumbuhkan rasa empati anggota melalui program donasi, pengabdian masyarakat, dan perayaan hari besar.',
                    'color' => 'bg-indigo-50',
                    'text' => 'text-indigo-600'
                ],
                [
                    'nama' => 'Pengawasan dan Penyelesaian Masalah',
                    'singkatan' => 'PPM',
                    'icon' => '⚖️',
                    'desc' => 'Divisi yang mengawasi kinerja pengurus, menjaga kedisiplinan, serta menjadi mediator objektif dalam menyelesaikan konflik atau dinamika internal organisasi.',
                    'color' => 'bg-slate-200',
                    'text' => 'text-slate-700'
                ]
            ];
            @endphp

            @foreach($divisis as $div)
            <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-start gap-6">
                    <div class="w-16 h-16 rounded-2xl flex-shrink-0 flex items-center justify-center text-3xl {{ $div['color'] }}">
                        {{ $div['icon'] }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <h3 class="text-xl font-bold text-slate-900">{{ $div['nama'] }}</h3>
                        </div>
                        <span class="inline-block px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-full mb-4 tracking-wider uppercase">Divisi {{ $div['singkatan'] }}</span>
                        <p class="text-slate-600 leading-relaxed text-sm md:text-base">
                            {{ $div['desc'] }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
