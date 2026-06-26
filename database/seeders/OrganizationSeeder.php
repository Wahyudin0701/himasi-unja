<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kepengurusan\Division;
use App\Models\Kepengurusan\Period;
use App\Models\User;
use App\Models\Kepengurusan\Member;
use App\Models\Kepengurusan\OrgPosition;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $period = Period::where('is_active', true)->first();
        if (!$period) {
            $period = Period::create(['name' => '2025-2026', 'start_date' => '2025-01-01', 'end_date' => '2025-12-31', 'is_active' => true]);
        }

        // Admin User
        $admin = User::firstOrCreate(['email' => 'admin@himasi.unja.ac.id'], [
            'name' => 'Admin HIMASI',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        $divisionsData = [
            [
                'name' => 'Badan Pengurus Harian', 'slug' => 'bph', 'singkatan' => 'BPH', 'icon' => '🏛️', 'color' => 'bg-indigo-50', 'text_color' => 'text-indigo-600', 'type' => 'bph',
                'description' => 'Badan Pengurus Harian (BPH) adalah inti pengelolaan organisasi yang berfungsi sebagai pusat koordinasi, pengawasan, dan pengambilan keputusan strategis dalam Himpunan Mahasiswa Sistem Informasi Universitas Jambi. BPH bertanggung jawab untuk memastikan seluruh program kerja berjalan sesuai visi dan misi himpunan, menjaga stabilitas internal organisasi, serta menjadi penghubung utama antara pengurus, anggota, dan pihak eksternal.',
                'members' => [
                    ['name' => 'Muhammad Rumii Firnanditya', 'position' => 'Ketua Himpunan', 'avatar' => 'pengurus_hima/bph/kahim.webp'],
                    ['name' => 'Alief Noverdy Chaliandra P.', 'position' => 'Wakil Ketua Himpunan', 'avatar' => 'pengurus_hima/bph/wakahim.webp'],
                    ['name' => 'Zikra Zana', 'position' => 'Sekretaris I', 'avatar' => 'pengurus_hima/bph/sekretaris_I.webp'],
                    ['name' => 'Faisal Masri Maulana', 'position' => 'Sekretaris II', 'avatar' => 'pengurus_hima/bph/sekretaris_II.webp'],
                    ['name' => 'Zahra Zahira', 'position' => 'Bendahara I', 'avatar' => 'pengurus_hima/bph/bendahara_I.webp'],
                    ['name' => 'Desti Amanda', 'position' => 'Bendahara II', 'avatar' => 'pengurus_hima/bph/bendahara_II.webp'],
                ]
            ],
            [
                'name' => 'Hubungan Masyarakat', 'slug' => 'humas', 'singkatan' => 'HUMAS', 'icon' => '🤝', 'color' => 'bg-blue-50', 'text_color' => 'text-blue-600', 'type' => 'divisi',
                'description' => 'Divisi Hubungan Masyarakat (HUMAS) berperan sebagai jembatan penghubung antara HIMASI dengan pihak internal maupun eksternal dalam menyampaikan informasi, membangun citra positif, serta menjaga komunikasi yang efektif dan profesional. HUMAS bertanggung jawab dalam pengelolaan publikasi, relasi, dan penyampaian informasi agar kegiatan HIMASI dapat dikenal, dipahami, dan diterima dengan baik oleh mahasiswa, dosen, serta masyarakat luas.',
                'members' => [
                    ['name' => 'Muhammad Farhan', 'position' => 'Ketua Divisi Hubungan Masyarakat', 'avatar' => 'pengurus_hima/humas/kadiv_humas.webp'],
                    ['name' => 'Arsilia Zuhruf', 'position' => 'Wakil Ketua Divisi Hubungan Masyarakat & Ketua Bidang Internal', 'avatar' => 'pengurus_hima/humas/wakdiv_humas.webp'],
                    ['name' => 'Dian Tri Hariyanto', 'position' => 'Sekretaris Divisi', 'avatar' => 'pengurus_hima/humas/sekretaris_humas.webp'],
                    ['name' => 'M. Ardika Ghathfani', 'position' => 'Ketua Bidang Eksternal', 'avatar' => 'pengurus_hima/humas/kabid_eksternal_humas.webp'],
                    ['name' => 'Putri Syaqila Amanda S.', 'position' => 'Ketua Bidang Manajemen Sosial Media', 'avatar' => 'pengurus_hima/humas/kabid_manajemen_sosial_media_humas.webp'],
                    ['name' => 'Intan Sri Ayu Binti Darul Nizsham', 'position' => 'Anggota Eksternal', 'avatar' => 'pengurus_hima/humas/anggota_ekternal_humas.webp'],
                    ['name' => 'Mahardika Azel Pangestu', 'position' => 'Anggota Internal', 'avatar' => 'pengurus_hima/humas/anggota_internal_humas.webp'],
                    ['name' => 'Amanda Dwi Maharani', 'position' => 'Anggota Manajemen Sosial Media', 'avatar' => 'pengurus_hima/humas/anggota_manajemen_sosial_media.webp'],
                ]
            ],
            [
                'name' => 'Riset dan Teknologi', 'slug' => 'ristek', 'singkatan' => 'RISTEK', 'icon' => '💻', 'color' => 'bg-purple-50', 'text_color' => 'text-purple-600', 'type' => 'divisi',
                'description' => 'Divisi Riset dan Teknologi (RISTEK) merupakan divisi yang berfokus pada bidang pengetahuan ilmiah dan pengembangan teknologi. RISTEK juga memfasilitasi pengembangan mahasiswa melalui teknologi informasi dan soft skills.',
                'members' => [
                    ['name' => 'Damara Rafiandriza Putra', 'position' => 'Ketua Divisi Riset dan Teknologi', 'avatar' => 'pengurus_hima/ristek/kadiv_ristek.webp'],
                    ['name' => 'Shakila Rama Wulandari', 'position' => 'Wakil Ketua Divisi Riset dan Teknologi', 'avatar' => 'pengurus_hima/ristek/kakadiv_ristek.webp'],
                    ['name' => 'Fika Faulina', 'position' => 'Sekretaris Divisi', 'avatar' => 'pengurus_hima/ristek/sekretaris_ristek.webp'],
                    ['name' => 'Maulana Umar Al-Faroq', 'position' => 'Ketua Bidang Riset', 'avatar' => 'pengurus_hima/ristek/kabid_riset.webp'],
                    ['name' => 'Raihan Sanovra M.G.F', 'position' => 'Ketua Bidang Teknologi', 'avatar' => 'pengurus_hima/ristek/kabid_teknologi.webp'],
                    ['name' => 'Ahmad Irsanto Hibatullah', 'position' => 'Anggota Riset', 'avatar' => 'pengurus_hima/ristek/anggota_riset.webp'],
                    ['name' => 'Iqbal Khairullah', 'position' => 'Anggota Riset', 'avatar' => 'pengurus_hima/ristek/anggota_riset_2.webp'],
                    ['name' => 'Muhammad Andre Kurniawan', 'position' => 'Anggota Teknologi', 'avatar' => 'pengurus_hima/ristek/anggota_teknologi.webp'],
                ]
            ],
            [
                'name' => 'Dana dan Usaha', 'slug' => 'danus', 'singkatan' => 'DANUS', 'icon' => '💰', 'color' => 'bg-yellow-50', 'text_color' => 'text-yellow-600', 'type' => 'divisi',
                'description' => 'Divisi Dana Usaha (Danus) adalah divisi yang bertanggung jawab dalam merancang, mengelola, dan melaksanakan kegiatan usaha organisasi guna memperoleh sumber pendanaan mandiri. Divisi ini berperan sebagai penunjang keuangan himpunan mahasiswa melalui kegiatan kewirausahaan yang kreatif, inovatif, dan berkelanjutan, sehingga dapat mendukung pelaksanaan program kerja serta meningkatkan kemandirian finansial organisasi.',
                'members' => [
                    ['name' => 'M. Wahyudin', 'position' => 'Ketua Divisi Dana Usaha', 'avatar' => 'pengurus_hima/danus/kadiv_danus.webp'],
                    ['name' => 'Rio Saputra', 'position' => 'Wakil Ketua Divisi Dana Usaha', 'avatar' => 'pengurus_hima/danus/wakadiv_danus.webp'],
                    ['name' => 'Laila Nazelita', 'position' => 'Sekretaris', 'avatar' => 'pengurus_hima/danus/sekretaris_danus.webp'],
                    ['name' => 'Nadia Julia Fika', 'position' => 'Bendahara', 'avatar' => 'pengurus_hima/danus/bendahara_danus.webp'],
                    ['name' => 'Friska Marchella', 'position' => 'Ketua Bidang Kewirausahaan', 'avatar' => 'pengurus_hima/danus/kabid_kewirausahaan.webp'],
                    ['name' => 'Wisnu Nugroho', 'position' => 'Ketua Bidang Sosial dan Branding', 'avatar' => 'pengurus_hima/danus/kabid_sosial_branding.webp'],
                    ['name' => 'Gibran Krisna Athallah', 'position' => 'Anggota Kewirausahaan', 'avatar' => 'pengurus_hima/danus/anggota_kewirausahaan.webp'],
                    ['name' => 'Hilmy Anandika Indra', 'position' => 'Anggota Sosial dan Branding', 'avatar' => 'pengurus_hima/danus/anggota_sosial_branding.webp'],
                ]
            ],
            [
                'name' => 'Media dan Informasi', 'slug' => 'mediasi', 'singkatan' => 'MEDIASI', 'icon' => '📣', 'color' => 'bg-sky-50', 'text_color' => 'text-sky-600', 'type' => 'divisi',
                'description' => 'Divisi Media dan Informasi (MEDIASI) merupakan divisi yang bertanggung jawab dalam pengelolaan, production, dan penyebaran informasi HIMASI UNJA melalui media digital dan visual kreatif. Mediasi berperan sebagai wajah utama HIMASI dalam membangun citra, branding, dan komunikasi kepada pihak internal maupun eksternal, melalui pengelolaan media sosial, desain visual, dokumentasi foto & video, serta arsip digital kegiatan. Mediasi juga memastikan seluruh informasi kegiatan HIMASI tersampaikan secara informatif, menarik, konsisten, dan terdokumentasi dengan baik.',
                'members' => [
                    ['name' => 'Khafifah Najwa Siregar', 'position' => 'Ketua Divisi Media dan Informasi', 'avatar' => 'pengurus_hima/mediasi/kadiv_mediasi.webp'],
                    ['name' => 'Juliyando Akbar', 'position' => 'Wakil Ketua Divisi Media dan Informasi', 'avatar' => 'pengurus_hima/mediasi/wakadiv_mediasi.webp'],
                    ['name' => 'Azia Naura Ramadhani', 'position' => 'Sekretaris', 'avatar' => 'pengurus_hima/mediasi/sekretaris_mediasi.webp'],
                    ['name' => 'Reza Dian Azzahra', 'position' => 'Ketua Bidang Desain', 'avatar' => 'pengurus_hima/mediasi/kabid_desain.webp'],
                    ['name' => 'Arfun Ali Yafie', 'position' => 'Ketua Bidang Videografi', 'avatar' => 'pengurus_hima/mediasi/kabid_videografi.webp'],
                    ['name' => 'Gian Dzrikri Alfarobbi', 'position' => 'Ketua Bidang Fotografi', 'avatar' => 'pengurus_hima/mediasi/kabid_fotografi.webp'],
                    ['name' => 'Bagas Adinata', 'position' => 'Anggota Desain', 'avatar' => 'pengurus_hima/mediasi/anggota_desain.webp'],
                ]
            ],
            [
                'name' => 'Minat dan Bakat', 'slug' => 'mikat', 'singkatan' => 'MDB', 'icon' => '🎨', 'color' => 'bg-rose-50', 'text_color' => 'text-rose-600', 'type' => 'divisi',
                'description' => 'Divisi Minat dan Bakat (MDB) berperan dalam menggali serta mengembangkan potensi anggota sesuai dengan minat dan bakat yang dimiliki melalui berbagai kegiatan, seperti pelatihan, perlombaan, dan pertunjukan. Melalui kegiatan tersebut, anggota diberikan wadah untuk mengekspresikan diri secara positif sehingga mampu meningkatkan kreativitas, keterampilan, dan kepercayaan diri. Selain itu, MDB juga berfungsi sebagai sarana pembinaan dan pengembangan soft skill anggota agar mampu berprestasi serta berkontribusi secara aktif dalam organisasi.',
                'members' => [
                    ['name' => 'Rayyan Adam Gunawan', 'position' => 'Ketua Divisi Minat dan Bakat', 'avatar' => 'pengurus_hima/mdb/kadiv_mdb.webp'],
                    ['name' => 'Octa Rulian Pratama', 'position' => 'Wakil Ketua Divisi Minat dan Bakat', 'avatar' => 'pengurus_hima/mdb/wakadiv_mdb.webp'],
                    ['name' => 'Lesianda Junita', 'position' => 'Sekretaris', 'avatar' => 'pengurus_hima/mdb/sekretaris_mdb.webp'],
                    ['name' => 'Laila Khairul Amalia', 'position' => 'Ketua Bidang Seni', 'avatar' => 'pengurus_hima/mdb/kabid_seni.webp'],
                    ['name' => 'M. Rajendra Fareliansyah', 'position' => 'Ketua Bidang Penyaluran Bakat', 'avatar' => 'pengurus_hima/mdb/kabid_penyaluran_bakat.webp'],
                    ['name' => 'M. Arif Rahman', 'position' => 'Ketua Bidang Sport & E-Sport', 'avatar' => 'pengurus_hima/mdb/kabid_sport_esport.webp'],
                    ['name' => 'Fharel Sapvela Dwipa', 'position' => 'Anggota Sport & E-Sport', 'avatar' => 'pengurus_hima/mdb/anggota_sport_esport.webp'],
                ]
            ],
            [
                'name' => 'Pengembangan Sumber Daya Anggota', 'slug' => 'psda', 'singkatan' => 'PSDA', 'icon' => '👥', 'color' => 'bg-emerald-50', 'text_color' => 'text-emerald-600', 'type' => 'divisi',
                'description' => 'Divisi Pengembangan Sumber Daya Anggota (PSDA) berfokus pada penguatan kualitas organisasi melalui pengelolaan hubungan, kebersamaan, serta evaluasi kinerja HIMASI. PSDA menjadi wadah pelaksanaan kegiatan keakraban, kolaborasi, serta agenda evaluatif dan apresiatif yang melibatkan pengurus, anggota, dan dosen.',
                'members' => [
                    ['name' => 'Aisyah Putri Hasbi', 'position' => 'Ketua Divisi PSDA', 'avatar' => 'pengurus_hima/psda/kadiv_psda.webp'],
                    ['name' => 'Fitri Agustusina', 'position' => 'Wakil Ketua Divisi PSDA', 'avatar' => 'pengurus_hima/psda/wakadiv_psda.webp'],
                    ['name' => 'Imelia Amanda', 'position' => 'Sekretaris Divisi', 'avatar' => 'pengurus_hima/psda/sekretaris_psda.webp'],
                    ['name' => 'Akbar Nabil Fikri', 'position' => 'Ketua Bidang Kolaborasi dan Event', 'avatar' => 'pengurus_hima/psda/kabid_kolaborasi_event.webp'],
                    ['name' => 'Han Leomila Nababan', 'position' => 'Ketua Bidang Pengelolaan & Konsolidasi Anggota', 'avatar' => 'pengurus_hima/psda/kabid_pengelolaan_konsolidasi_anggota.webp'],
                    ['name' => 'M. Arzis Fadhillah', 'position' => 'Anggota Pengelolaan & Konsolidasi Anggota', 'avatar' => 'pengurus_hima/psda/anggota_pengelolaan_konsolidasi_anggota.webp'],
                    ['name' => 'Ghaniyyah Fitri Altila', 'position' => 'Anggota Pengelolaan & Konsolidasi Anggota', 'avatar' => 'pengurus_hima/psda/anggota_pengelolaan_konsolidasi_anggota_1.webp'],
                ]
            ],
            [
                'name' => 'Sosial dan Agama', 'slug' => 'sosagma', 'singkatan' => 'SOSGAM', 'icon' => '🕌', 'color' => 'bg-teal-50', 'text_color' => 'text-teal-600', 'type' => 'divisi',
                'description' => 'Divisi Sosial dan Agama (Sosgam) adalah divisi yang mewadahi berbagai kegiatan sosial mulai dari lingkup mahasiswa Sistem Informasi hingga masyarakat luas. Sosgam membidangi kegiatan keagamaan dalam kehidupan kemahasiswaan, khususnya dalam perayaan hari-hari besar keagamaan, serta menjadi wadah pengembangan potensi mahasiswa di bidang keagamaan.',
                'members' => [
                    ['name' => 'Frizka Aulia', 'position' => 'Ketua Divisi Sosial dan Agama', 'avatar' => 'pengurus_hima/sosgam/kadiv_sosgam.webp'],
                    ['name' => 'Bayu Hartanto', 'position' => 'Wakil Ketua Divisi Sosial dan Agama', 'avatar' => 'pengurus_hima/sosgam/wakadiv_sosgam.webp'],
                    ['name' => 'Ribi Aulia Ladinda', 'position' => 'Sekretaris', 'avatar' => 'pengurus_hima/sosgam/sekretaris_sosgam.webp'],
                    ['name' => 'Riko Wijaya', 'position' => 'Ketua Bidang Sosial', 'avatar' => 'pengurus_hima/sosgam/kabid_sosial.webp'],
                    ['name' => 'Olga Wulandari', 'position' => 'Ketua Bidang Agama', 'avatar' => 'pengurus_hima/sosgam/kabid_agama.webp'],
                    ['name' => 'Melani Fitri Astria', 'position' => 'Anggota Sosial', 'avatar' => 'pengurus_hima/sosgam/anggota_sosial.webp'],
                    ['name' => 'M. Ridho', 'position' => 'Anggota Agama', 'avatar' => 'pengurus_hima/sosgam/anggota_agama.webp'],
                    ['name' => 'Rihhadatul Aliya', 'position' => 'Anggota Agama', 'avatar' => 'pengurus_hima/sosgam/anggota_agama_2.webp'],
                ]
            ],
            [
                'name' => 'Pengawasan dan Penyelesaian Masalah', 'slug' => 'ppm', 'singkatan' => 'PPM', 'icon' => '🌍', 'color' => 'bg-orange-50', 'text_color' => 'text-orange-600', 'type' => 'divisi',
                'description' => 'Divisi Pengawasan dan Penyelesaian Masalah (PPM) berfokus pada pemantauan dan evaluasi kinerja keanggotaan guna menjaga efektivitas serta kepatuhan terhadap standar organisasi. Divisi ini bertugas mengidentifikasi dan menganalisis permasalahan internal dalam lingkup keanggotaan mahasiswa baru HIMASI, serta melaksanakan penyelesaian permasalahan secara objektif dan solutif.',
                'members' => [
                    ['name' => 'Bintang Aliqa Athilla', 'position' => 'Ketua Divisi PPM', 'avatar' => 'pengurus_hima/ppm/kadiv_ppm.webp'],
                    ['name' => 'Khozin Sapzidan', 'position' => 'Wakil Ketua Divisi PPM', 'avatar' => 'pengurus_hima/ppm/wakadiv_ppm.webp'],
                    ['name' => 'Fadhil Rahmadhana', 'position' => 'Sekretaris Divisi', 'avatar' => 'pengurus_hima/ppm/sekretaris_ppm.webp'],
                    ['name' => 'Nusyaibah', 'position' => 'Ketua Bidang Pengawasan', 'avatar' => 'pengurus_hima/ppm/kabid_pengawasan.webp'],
                    ['name' => 'Ripa Bagaskara', 'position' => 'Ketua Bidang Pengelolaan Data', 'avatar' => 'pengurus_hima/ppm/kabid_pengelolaan_data.webp'],
                    ['name' => 'Griyo Sihnugroho', 'position' => 'Anggota Pengawasan', 'avatar' => 'pengurus_hima/ppm/anggota_pengawasan.webp'],
                    ['name' => 'Qonita Ghina Anbarputri', 'position' => 'Anggota Pengelolaan Data', 'avatar' => 'pengurus_hima/ppm/anggota_pengawasan_data.webp'],
                ]
            ],
        ];

        // OrgPositions (we will dynamically create or get them based on title if needed, 
        // but let's just use generic IDs for now, or match text)
        $genericKadiv = OrgPosition::firstOrCreate(['slug' => 'ketua-divisi'], ['name' => 'Ketua Divisi', 'level' => 5]);
        $genericWakadiv = OrgPosition::firstOrCreate(['slug' => 'wakil-ketua-divisi'], ['name' => 'Wakil Ketua Divisi', 'level' => 6]);
        $genericAnggota = OrgPosition::firstOrCreate(['slug' => 'anggota-divisi'], ['name' => 'Anggota Divisi', 'level' => 7]);

        foreach ($divisionsData as $divData) {
            $membersData = $divData['members'];
            unset($divData['members']);
            
            $divData['period_id'] = $period->id;
            
            // Base points for gamification (optional)
            $divData['base_points'] = 100;
            
            $division = Division::create($divData);

            foreach ($membersData as $m) {
                // Generate a unique email
                $email = strtolower(explode(' ', $m['name'])[0]) . rand(100, 999) . '@himasi.unja.ac.id';
                
                $user = User::create([
                    'name' => $m['name'],
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'avatar' => $m['avatar']
                ]);

                // Determine position mapping
                $posTitle = strtolower($m['position']);
                $orgPosId = $genericAnggota->id;
                
                if (str_contains($posTitle, 'ketua himpunan') || str_contains($posTitle, 'ketua umum')) {
                    $p = OrgPosition::firstOrCreate(['slug' => 'ketua-himpunan'], ['name' => 'Ketua Himpunan', 'level' => 1]);
                    $orgPosId = $p->id;
                } elseif (str_contains($posTitle, 'wakil ketua himpunan')) {
                    $p = OrgPosition::firstOrCreate(['slug' => 'wakil-ketua-himpunan'], ['name' => 'Wakil Ketua Himpunan', 'level' => 2]);
                    $orgPosId = $p->id;
                } elseif (str_contains($posTitle, 'sekretaris')) {
                    $p = OrgPosition::firstOrCreate(['slug' => 'sekretaris'], ['name' => 'Sekretaris', 'level' => 3]);
                    $orgPosId = $p->id;
                } elseif (str_contains($posTitle, 'bendahara')) {
                    $p = OrgPosition::firstOrCreate(['slug' => 'bendahara'], ['name' => 'Bendahara', 'level' => 4]);
                    $orgPosId = $p->id;
                } elseif (str_contains($posTitle, 'wakil ketua divisi')) {
                    $orgPosId = $genericWakadiv->id;
                } elseif (str_contains($posTitle, 'ketua divisi')) {
                    $orgPosId = $genericKadiv->id;
                } elseif (str_contains($posTitle, 'ketua bidang')) {
                    $p = OrgPosition::firstOrCreate(['slug' => 'ketua-bidang'], ['name' => 'Ketua Bidang', 'level' => 6]);
                    $orgPosId = $p->id;
                }

                Member::create([
                    'user_id' => $user->id,
                    'division_id' => $division->id,
                    'org_position_id' => $orgPosId,
                    'position_title' => $m['position'],
                    'joined_at' => $period->start_date
                ]);
            }
        }
    }
}
