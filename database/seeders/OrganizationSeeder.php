<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Division;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password123');
        $roleBph = Role::firstOrCreate(['name' => 'BPH']);
        $roleKadiv = Role::firstOrCreate(['name' => 'Kadiv']);
        $roleAnggota = Role::firstOrCreate(['name' => 'Anggota']);

        $divisis = [
            'bph' => [
                'nama' => 'Badan Pengurus Harian',
                'singkatan' => 'BPH',
                'icon' => '🏛️',
                'color' => 'bg-indigo-50',
                'text' => 'text-indigo-600',
                'desc' => 'Badan Pengurus Harian (BPH) adalah inti pengelolaan organisasi yang berfungsi sebagai pusat koordinasi, pengawasan, dan pengambilan keputusan strategis dalam Himpunan Mahasiswa Sistem Informasi Universitas Jambi. BPH bertanggung jawab untuk memastikan seluruh program kerja berjalan sesuai visi dan misi himpunan, menjaga stabilitas internal organisasi, serta menjadi penghubung utama antara pengurus, anggota, dan pihak eksternal.',
                'pengurus' => [
                    ['nama' => 'Muhammad Rumii Firnanditya', 'jabatan' => 'Ketua Himpunan', 'avatar' => 'pengurus_hima/bph/kahim.webp'],
                    ['nama' => 'Alief Noverdy Chaliandra P.', 'jabatan' => 'Wakil Ketua Himpunan', 'avatar' => 'pengurus_hima/bph/wakahim.webp'],
                    ['nama' => 'Zikra Zana', 'jabatan' => 'Sekretaris I', 'avatar' => 'pengurus_hima/bph/sekretaris_I.webp'],
                    ['nama' => 'Faisal Masri Maulana', 'jabatan' => 'Sekretaris II', 'avatar' => 'pengurus_hima/bph/sekretaris_II.webp'],
                    ['nama' => 'Zahra Zahira', 'jabatan' => 'Bendahara I', 'avatar' => 'pengurus_hima/bph/bendahara_I.webp'],
                    ['nama' => 'Desti Amanda', 'jabatan' => 'Bendahara II', 'avatar' => 'pengurus_hima/bph/bendahara_II.webp'],
                ]
            ],
            'humas' => [
                'nama' => 'Divisi Hubungan Masyarakat',
                'singkatan' => 'Humas',
                'icon' => '🤝',
                'color' => 'bg-blue-50',
                'text' => 'text-blue-600',
                'desc' => 'Divisi Hubungan Masyarakat (HUMAS) berperan sebagai jembatan penghubung antara HIMASI dengan pihak internal maupun eksternal dalam menyampaikan informasi, membangun citra positif, serta menjaga komunikasi yang efektif dan profesional. HUMAS bertanggung jawab dalam pengelolaan publikasi, relasi, dan penyampaian informasi agar kegiatan HIMASI dapat dikenal, dipahami, dan diterima dengan baik oleh mahasiswa, dosen, serta masyarakat luas.',
                'pengurus' => [
                    ['nama' => 'Muhammad Farhan', 'jabatan' => 'Ketua Divisi Hubungan Masyarakat', 'avatar' => 'pengurus_hima/humas/kadiv_humas.webp'],
                    ['nama' => 'Arsilia Zuhruf', 'jabatan' => 'Wakil Ketua Divisi Hubungan Masyarakat & Ketua Bidang Internal', 'avatar' => 'pengurus_hima/humas/wakdiv_humas.webp'],
                    ['nama' => 'Dian Tri Hariyanto', 'jabatan' => 'Sekretaris Divisi', 'avatar' => 'pengurus_hima/humas/sekretaris_humas.webp'],
                    ['nama' => 'M. Ardika Ghathfani', 'jabatan' => 'Ketua Bidang Eksternal', 'avatar' => 'pengurus_hima/humas/kabid_eksternal_humas.webp'],
                    ['nama' => 'Putri Syaqila Amanda S.', 'jabatan' => 'Ketua Bidang Manajemen Sosial Media', 'avatar' => 'pengurus_hima/humas/kabid_manajemen_sosial_media_humas.webp'],
                    ['nama' => 'Intan Sri Ayu Binti Darul Nizsham', 'jabatan' => 'Anggota Eksternal', 'avatar' => 'pengurus_hima/humas/anggota_ekternal_humas.webp'],
                    ['nama' => 'Mahardika Azel Pangestu', 'jabatan' => 'Anggota Internal', 'avatar' => 'pengurus_hima/humas/anggota_internal_humas.webp'],
                    ['nama' => 'Amanda Dwi Maharani', 'jabatan' => 'Anggota Manajemen Sosial Media', 'avatar' => 'pengurus_hima/humas/anggota_manajemen_sosial_media.webp'],
                ]
            ],
            'ristek' => [
                'nama' => 'Divisi Riset dan Teknologi',
                'singkatan' => 'Ristek',
                'icon' => '💻',
                'color' => 'bg-emerald-50',
                'text' => 'text-emerald-600',
                'desc' => 'Divisi Riset dan Teknologi (RISTEK) merupakan divisi yang berfokus pada bidang pengetahuan ilmiah dan pengembangan teknologi. RISTEK juga memfasilitasi pengembangan mahasiswa melalui teknologi informasi dan soft skills.',
                'pengurus' => [
                    ['nama' => 'Damara Rafiandriza Putra', 'jabatan' => 'Ketua Divisi Riset dan Teknologi', 'avatar' => 'pengurus_hima/ristek/kadiv_ristek.webp'],
                    ['nama' => 'Shakila Rama Wulandari', 'jabatan' => 'Wakil Ketua Divisi Riset dan Teknologi', 'avatar' => 'pengurus_hima/ristek/kakadiv_ristek.webp'],
                    ['nama' => 'Fika Faulina', 'jabatan' => 'Sekretaris Divisi', 'avatar' => 'pengurus_hima/ristek/sekretaris_ristek.webp'],
                    ['nama' => 'Maulana Umar Al-Faroq', 'jabatan' => 'Ketua Bidang Riset', 'avatar' => 'pengurus_hima/ristek/kabid_riset.webp'],
                    ['nama' => 'Raihan Sanovra M.G.F', 'jabatan' => 'Ketua Bidang Teknologi', 'avatar' => 'pengurus_hima/ristek/kabid_teknologi.webp'],
                    ['nama' => 'Ahmad Irsanto Hibatullah', 'jabatan' => 'Anggota Riset', 'avatar' => 'pengurus_hima/ristek/anggota_riset.webp'],
                    ['nama' => 'Iqbal Khairullah', 'jabatan' => 'Anggota Riset', 'avatar' => 'pengurus_hima/ristek/anggota_riset_2.webp'],
                    ['nama' => 'Muhammad Andre Kurniawan', 'jabatan' => 'Anggota Teknologi', 'avatar' => 'pengurus_hima/ristek/anggota_teknologi.webp'],
                ]
            ],
            'danus' => [
                'nama' => 'Divisi Dana dan Usaha',
                'singkatan' => 'Danus',
                'icon' => '💰',
                'color' => 'bg-amber-50',
                'text' => 'text-amber-600',
                'desc' => 'Divisi Dana Usaha (Danus) adalah divisi yang bertanggung jawab dalam merancang, mengelola, dan melaksanakan kegiatan usaha organisasi guna memperoleh sumber pendanaan mandiri. Divisi ini berperan sebagai penunjang keuangan himpunan mahasiswa melalui kegiatan kewirausahaan yang kreatif, inovatif, dan berkelanjutan, sehingga dapat mendukung pelaksanaan program kerja serta meningkatkan kemandirian finansial organisasi.',
                'pengurus' => [
                    ['nama' => 'M. Wahyudin', 'jabatan' => 'Ketua Divisi Dana Usaha', 'avatar' => 'pengurus_hima/danus/kadiv_danus.webp'],
                    ['nama' => 'Rio Saputra', 'jabatan' => 'Wakil Ketua Divisi Dana Usaha', 'avatar' => 'pengurus_hima/danus/wakadiv_danus.webp'],
                    ['nama' => 'Laila Nazelita', 'jabatan' => 'Sekretaris', 'avatar' => 'pengurus_hima/danus/sekretaris_danus.webp'],
                    ['nama' => 'Nadia Julia Fika', 'jabatan' => 'Bendahara', 'avatar' => 'pengurus_hima/danus/bendahara_danus.webp'],
                    ['nama' => 'Friska Marchella', 'jabatan' => 'Ketua Bidang Kewirausahaan', 'avatar' => 'pengurus_hima/danus/kabid_kewirausahaan.webp'],
                    ['nama' => 'Wisnu Nugroho', 'jabatan' => 'Ketua Bidang Sosial dan Branding', 'avatar' => 'pengurus_hima/danus/kabid_sosial_branding.webp'],
                    ['nama' => 'Gibran Krisna Athallah', 'jabatan' => 'Anggota Kewirausahaan', 'avatar' => 'pengurus_hima/danus/anggota_kewirausahaan.webp'],
                    ['nama' => 'Hilmy Anandika Indra', 'jabatan' => 'Anggota Sosial dan Branding', 'avatar' => 'pengurus_hima/danus/anggota_sosial_branding.webp'],
                ]
            ],
            'medinfo' => [
                'nama' => 'Divisi Media dan Informasi',
                'singkatan' => 'Mediasi',
                'icon' => '📸',
                'color' => 'bg-fuchsia-50',
                'text' => 'text-fuchsia-600',
                'desc' => 'Divisi Media dan Informasi (MEDIASI) merupakan divisi yang bertanggung jawab dalam pengelolaan, production, dan penyebaran informasi HIMASI UNJA melalui media digital dan visual kreatif. Mediasi berperan sebagai wajah utama HIMASI dalam membangun citra, branding, dan komunikasi kepada pihak internal maupun eksternal, melalui pengelolaan media sosial, desain visual, dokumentasi foto & video, serta arsip digital kegiatan. Mediasi juga memastikan seluruh informasi kegiatan HIMASI tersampaikan secara informatif, menarik, konsisten, dan terdokumentasi dengan baik.',
                'pengurus' => [
                    ['nama' => 'Khafifah Najwa Siregar', 'jabatan' => 'Ketua Divisi Media dan Informasi', 'avatar' => 'pengurus_hima/mediasi/kadiv_mediasi.webp'],
                    ['nama' => 'Juliyando Akbar', 'jabatan' => 'Wakil Ketua Divisi Media dan Informasi', 'avatar' => 'pengurus_hima/mediasi/wakadiv_mediasi.webp'],
                    ['nama' => 'Azia Naura Ramadhani', 'jabatan' => 'Sekretaris', 'avatar' => 'pengurus_hima/mediasi/sekretaris_mediasi.webp'],
                    ['nama' => 'Reza Dian Azzahra', 'jabatan' => 'Ketua Bidang Desain', 'avatar' => 'pengurus_hima/mediasi/kabid_desain.webp'],
                    ['nama' => 'Arfun Ali Yafie', 'jabatan' => 'Ketua Bidang Videografi', 'avatar' => 'pengurus_hima/mediasi/kabid_videografi.webp'],
                    ['nama' => 'Gian Dzrikri Alfarobbi', 'jabatan' => 'Ketua Bidang Fotografi', 'avatar' => 'pengurus_hima/mediasi/kabid_fotografi.webp'],
                    ['nama' => 'Bagas Adinata', 'jabatan' => 'Anggota Desain', 'avatar' => 'pengurus_hima/mediasi/anggota_desain.webp'],
                ]
            ],
            'mikat' => [
                'nama' => 'Divisi Minat dan Bakat',
                'singkatan' => 'MDB',
                'icon' => '🎨',
                'color' => 'bg-rose-50',
                'text' => 'text-rose-600',
                'desc' => 'Divisi Minat dan Bakat (MDB) berperan dalam menggali serta mengembangkan potensi anggota sesuai dengan minat dan bakat yang dimiliki melalui berbagai kegiatan, seperti pelatihan, perlombaan, dan pertunjukan. Melalui kegiatan tersebut, anggota diberikan wadah untuk mengekspresikan diri secara positif sehingga mampu meningkatkan kreativitas, keterampilan, dan kepercayaan diri. Selain itu, MDB juga berfungsi sebagai sarana pembinaan dan pengembangan soft skill anggota agar mampu berprestasi serta berkontribusi secara aktif dalam organisasi.',
                'pengurus' => [
                    ['nama' => 'Rayyan Adam Gunawan', 'jabatan' => 'Ketua Divisi Minat dan Bakat', 'avatar' => 'pengurus_hima/mdb/kadiv_mdb.webp'],
                    ['nama' => 'Octa Rulian Pratama', 'jabatan' => 'Wakil Ketua Divisi Minat dan Bakat', 'avatar' => 'pengurus_hima/mdb/wakadiv_mdb.webp'],
                    ['nama' => 'Lesianda Junita', 'jabatan' => 'Sekretaris', 'avatar' => 'pengurus_hima/mdb/sekretaris_mdb.webp'],
                    ['nama' => 'Laila Khairul Amalia', 'jabatan' => 'Ketua Bidang Seni', 'avatar' => 'pengurus_hima/mdb/kabid_seni.webp'],
                    ['nama' => 'M. Rajendra Fareliansyah', 'jabatan' => 'Ketua Bidang Penyaluran Bakat', 'avatar' => 'pengurus_hima/mdb/kabid_penyaluran_bakat.webp'],
                    ['nama' => 'M. Arif Rahman', 'jabatan' => 'Ketua Bidang Sport & E-Sport', 'avatar' => 'pengurus_hima/mdb/kabid_sport_esport.webp'],
                    ['nama' => 'Fharel Sapvela Dwipa', 'jabatan' => 'Anggota Sport & E-Sport', 'avatar' => 'pengurus_hima/mdb/anggota_sport_esport.webp'],
                ]
            ],
            'psda' => [
                'nama' => 'Divisi Pengembangan Sumber Daya Anggota',
                'singkatan' => 'PSDA',
                'icon' => '🌱',
                'color' => 'bg-teal-50',
                'text' => 'text-teal-600',
                'desc' => 'Divisi Pengembangan Sumber Daya Anggota (PSDA) berfokus pada penguatan kualitas organisasi melalui pengelolaan hubungan, kebersamaan, serta evaluasi kinerja HIMASI. PSDA menjadi wadah pelaksanaan kegiatan keakraban, kolaborasi, serta agenda evaluatif dan apresiatif yang melibatkan pengurus, anggota, dan dosen.',
                'pengurus' => [
                    ['nama' => 'Aisyah Putri Hasbi', 'jabatan' => 'Ketua Divisi PSDA', 'avatar' => 'pengurus_hima/psda/kadiv_psda.webp'],
                    ['nama' => 'Fitri Agustusina', 'jabatan' => 'Wakil Ketua Divisi PSDA', 'avatar' => 'pengurus_hima/psda/wakadiv_psda.webp'],
                    ['nama' => 'Imelia Amanda', 'jabatan' => 'Sekretaris Divisi', 'avatar' => 'pengurus_hima/psda/sekretaris_psda.webp'],
                    ['nama' => 'Akbar Nabil Fikri', 'jabatan' => 'Ketua Bidang Kolaborasi dan Event', 'avatar' => 'pengurus_hima/psda/kabid_kolaborasi_event.webp'],
                    ['nama' => 'Han Leomila Nababan', 'jabatan' => 'Ketua Bidang Pengelolaan & Konsolidasi Anggota', 'avatar' => 'pengurus_hima/psda/kabid_pengelolaan_konsolidasi_anggota.webp'],
                    ['nama' => 'M. Arzis Fadhillah', 'jabatan' => 'Anggota Pengelolaan & Konsolidasi Anggota', 'avatar' => 'pengurus_hima/psda/anggota_pengelolaan_konsolidasi_anggota.webp'],
                    ['nama' => 'Ghaniyyah Fitri Altila', 'jabatan' => 'Anggota Pengelolaan & Konsolidasi Anggota', 'avatar' => 'pengurus_hima/psda/anggota_pengelolaan_konsolidasi_anggota_1.webp'],
                ]
            ],
            'sosagma' => [
                'nama' => 'Divisi Sosial dan Agama',
                'singkatan' => 'Sosgam',
                'icon' => '🙏',
                'color' => 'bg-indigo-50',
                'text' => 'text-indigo-600',
                'desc' => 'Divisi Sosial dan Agama (Sosgam) adalah divisi yang mewadahi berbagai kegiatan sosial mulai dari lingkup mahasiswa Sistem Informasi hingga masyarakat luas. Sosgam membidangi kegiatan keagamaan dalam kehidupan kemahasiswaan, khususnya dalam perayaan hari-hari besar keagamaan, serta menjadi wadah pengembangan potensi mahasiswa di bidang keagamaan.',
                'pengurus' => [
                    ['nama' => 'Frizka Aulia', 'jabatan' => 'Ketua Divisi Sosial dan Agama', 'avatar' => 'pengurus_hima/sosgam/kadiv_sosgam.webp'],
                    ['nama' => 'Bayu Hartanto', 'jabatan' => 'Wakil Ketua Divisi Sosial dan Agama', 'avatar' => 'pengurus_hima/sosgam/wakadiv_sosgam.webp'],
                    ['nama' => 'Ribi Aulia Ladinda', 'jabatan' => 'Sekretaris', 'avatar' => 'pengurus_hima/sosgam/sekretaris_sosgam.webp'],
                    ['nama' => 'Riko Wijaya', 'jabatan' => 'Ketua Bidang Sosial', 'avatar' => 'pengurus_hima/sosgam/kabid_sosial.webp'],
                    ['nama' => 'Olga Wulandari', 'jabatan' => 'Ketua Bidang Agama', 'avatar' => 'pengurus_hima/sosgam/kabid_agama.webp'],
                    ['nama' => 'Melani Fitri Astria', 'jabatan' => 'Anggota Sosial', 'avatar' => 'pengurus_hima/sosgam/anggota_sosial.webp'],
                    ['nama' => 'M. Ridho', 'jabatan' => 'Anggota Agama', 'avatar' => 'pengurus_hima/sosgam/anggota_agama.webp'],
                    ['nama' => 'Rihhadatul Aliya', 'jabatan' => 'Anggota Agama', 'avatar' => 'pengurus_hima/sosgam/anggota_agama_2.webp'],
                ]
            ],
            'ppm' => [
                'nama' => 'Divisi Pengawasan dan Penyelesaian Masalah',
                'singkatan' => 'PPM',
                'icon' => '⚖️',
                'color' => 'bg-slate-200',
                'text' => 'text-slate-700',
                'desc' => 'Divisi Pengawasan dan Penyelesaian Masalah (PPM) berfokus pada pemantauan dan evaluasi kinerja keanggotaan guna menjaga efektivitas serta kepatuhan terhadap standar organisasi. Divisi ini bertugas mengidentifikasi dan menganalisis permasalahan internal dalam lingkup keanggotaan mahasiswa baru HIMASI, serta melaksanakan penyelesaian permasalahan secara objektif dan solutif.',
                'pengurus' => [
                    ['nama' => 'Bintang Aliqa Athilla', 'jabatan' => 'Ketua Divisi PPM', 'avatar' => 'pengurus_hima/ppm/kadiv_ppm.webp'],
                    ['nama' => 'Khozin Sapzidan', 'jabatan' => 'Wakil Ketua Divisi PPM', 'avatar' => 'pengurus_hima/ppm/wakadiv_ppm.webp'],
                    ['nama' => 'Fadhil Rahmadhana', 'jabatan' => 'Sekretaris Divisi', 'avatar' => 'pengurus_hima/ppm/sekretaris_ppm.webp'],
                    ['nama' => 'Nusyaibah', 'jabatan' => 'Ketua Bidang Pengawasan', 'avatar' => 'pengurus_hima/ppm/kabid_pengawasan.webp'],
                    ['nama' => 'Ripa Bagaskara', 'jabatan' => 'Ketua Bidang Pengelolaan Data', 'avatar' => 'pengurus_hima/ppm/kabid_pengelolaan_data.webp'],
                    ['nama' => 'Griyo Sihnugroho', 'jabatan' => 'Anggota Pengawasan', 'avatar' => 'pengurus_hima/ppm/anggota_pengawasan.webp'],
                    ['nama' => 'Qonita Ghina Anbarputri', 'jabatan' => 'Anggota Pengelolaan Data', 'avatar' => 'pengurus_hima/ppm/anggota_pengawasan_data.webp'],
                ]
            ]
        ];

        foreach ($divisis as $slug => $data) {
            $division = Division::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $data['nama'],
                    'singkatan' => $data['singkatan'],
                    'icon' => $data['icon'],
                    'color' => $data['color'],
                    'text_color' => $data['text'],
                    'desc' => $data['desc'],
                    'base_points' => 100,
                ]
            );

            // Update details if they already existed
            $division->update([
                'name' => $data['nama'],
                'singkatan' => $data['singkatan'],
                'icon' => $data['icon'],
                'color' => $data['color'],
                'text_color' => $data['text'],
                'desc' => $data['desc'],
            ]);

            foreach ($data['pengurus'] as $p) {
                // Generate a dummy email
                $emailSlug = Str::slug($p['nama'], '.');
                $email = $emailSlug . '@himasi.unja.ac.id';
                
                // Determine Role
                $role = $roleAnggota;
                $jabatan = strtolower($p['jabatan']);
                
                if ($slug === 'bph' || str_contains($jabatan, 'ketua himpunan') || str_contains($jabatan, 'sekretaris') || str_contains($jabatan, 'bendahara')) {
                    $role = $roleBph;
                } elseif (str_contains($jabatan, 'ketua divisi') || str_contains($jabatan, 'ketua bidang')) {
                    $role = $roleKadiv;
                }

                User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $p['nama'],
                        'password' => $password,
                        'role_id' => $role->id,
                        'division_id' => $division->id,
                        'position_title' => $p['jabatan'],
                        'avatar' => $p['avatar'] ?? null,
                    ]
                );
            }
        }
    }
}
