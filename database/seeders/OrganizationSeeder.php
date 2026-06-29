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
            'global_role' => 'super_admin',
        ]);

        $divisionsData = [
            [
                'name' => 'Dewan Pembina', 'slug' => 'pembina', 'singkatan' => 'Pembina', 'icon' => '👑', 'color' => 'bg-slate-50', 'text_color' => 'text-slate-600', 'type' => 'pembina',
                'description' => 'Dewan Pembina Himpunan Mahasiswa Sistem Informasi Universitas Jambi.',
                'sub_divisions' => [],
                'members' => [
                    ['name' => 'Reni Aryani, S.Kom., M.S.I.', 'position' => 'Pembina', 'nim' => '198801222015042003', 'tahun_angkatan' => '2015', 'avatar' => 'pengurus_hima/pembina/reni_aryani.png'],
                    ['name' => 'Willy Bima Alfajri, S.Tr.Kom., M.Kom.', 'position' => 'Pembina', 'nim' => '199610032024061002', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/pembina/willy_bima.png'],
                    ['name' => 'Oki Dahwanu, S.TP., M.Kom.', 'position' => 'Pembina', 'nim' => '199010292025061001', 'tahun_angkatan' => '2025', 'avatar' => 'pengurus_hima/pembina/oke_dahwanu.jpg'],
                ]
            ],
            [
                'name' => 'Dewan Penasehat', 'slug' => 'dp', 'singkatan' => 'DP', 'icon' => '👁️', 'color' => 'bg-slate-50', 'text_color' => 'text-slate-600', 'type' => 'dp',
                'description' => 'Dewan Penasehat Himpunan Mahasiswa Sistem Informasi Universitas Jambi.',
                'sub_divisions' => [],
                'members' => [
                    ['name' => 'Tito Aidil Sanjaya', 'position' => 'Ketua Penasehat', 'nim' => 'F1E122088', 'tahun_angkatan' => '2022', 'avatar' => 'pengurus_hima/dp/dp.png'],
                    ['name' => 'Febri Heryansah', 'position' => 'Penasehat Minat dan Bakat', 'nim' => 'F1E122195', 'tahun_angkatan' => '2022', 'avatar' => 'pengurus_hima/dp/dp.png'],
                    ['name' => 'Ari Setiya Ningsih', 'position' => 'Penasehat Sosial dan Agama', 'nim' => 'F1E122004', 'tahun_angkatan' => '2022', 'avatar' => 'pengurus_hima/dp/dp.png'],
                    ['name' => 'Abelia Maisya Huwaida', 'position' => 'Penasehat Pengembangan Sumber Daya Anggota', 'nim' => 'F1E122009', 'tahun_angkatan' => '2022', 'avatar' => 'pengurus_hima/dp/dp.png'],
                    ['name' => 'Satria Pratama Hutagalung', 'position' => 'Penasehat Pengawasan dan Penyelesaian Masalah', 'nim' => 'F1E122097', 'tahun_angkatan' => '2022', 'avatar' => 'pengurus_hima/dp/dp.png'],
                ]
            ],
            [
                'name' => 'Badan Pengurus Harian', 'slug' => 'bph', 'singkatan' => 'BPH', 'icon' => '🏛️', 'color' => 'bg-indigo-50', 'text_color' => 'text-indigo-600', 'type' => 'bph',
                'description' => 'Badan Pengurus Harian (BPH) adalah inti pengelolaan organisasi yang berfungsi sebagai pusat koordinasi, pengawasan, dan pengambilan keputusan strategis dalam Himpunan Mahasiswa Sistem Informasi Universitas Jambi. BPH bertanggung jawab untuk memastikan seluruh program kerja berjalan sesuai visi dan misi himpunan, menjaga stabilitas internal organisasi, serta menjadi penghubung utama antara pengurus, anggota, dan pihak eksternal.',
                'sub_divisions' => [],
                'members' => [
                    ['name' => 'Muhammad Rumii Firnanditya', 'position' => 'Ketua Himpunan', 'nim' => 'F1E123075', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/bph/kahim.webp'],
                    ['name' => 'Alief Noverdy Chaliandra Putra', 'position' => 'Wakil Ketua Himpunan', 'nim' => 'F1E123041', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/bph/wakahim.webp'],
                    ['name' => 'Zikra Zana', 'position' => 'Sekretaris I', 'nim' => 'F1E123014', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/bph/sekretaris_I.webp'],
                    ['name' => 'Faisal Masri Maulana', 'position' => 'Sekretaris II', 'nim' => 'F1E123085', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/bph/sekretaris_II.webp'],
                    ['name' => 'Zahra Zahira', 'position' => 'Bendahara I', 'nim' => 'F1E123089', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/bph/bendahara_I.webp'],
                    ['name' => 'Desti Amanda', 'position' => 'Bendahara II', 'nim' => 'F1E123021', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/bph/bendahara_II.webp'],
                ]
            ],
            [
                'name' => 'Hubungan Masyarakat', 'slug' => 'humas', 'singkatan' => 'HUMAS', 'icon' => '🤝', 'color' => 'bg-blue-50', 'text_color' => 'text-blue-600', 'type' => 'divisi',
                'description' => 'Divisi Hubungan Masyarakat (HUMAS) berperan sebagai jembatan penghubung antara HIMASI dengan pihak internal maupun eksternal dalam menyampaikan informasi, membangun citra positif, serta menjaga komunikasi yang efektif dan profesional. HUMAS bertanggung jawab dalam pengelolaan publikasi, relasi, dan penyampaian informasi agar kegiatan HIMASI dapat dikenal, dipahami, dan diterima dengan baik oleh mahasiswa, dosen, serta masyarakat luas.',
                'sub_divisions' => ['Eksternal', 'Internal', 'Manajemen Sosial Media'],
                'members' => [
                    ['name' => 'Muhammad Farhan', 'position' => 'Ketua Divisi Hubungan Masyarakat', 'nim' => 'F1E123071', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/humas/kadiv_humas.webp'],
                    ['name' => 'Arsilia Zuhruf', 'position' => 'Wakil Ketua Divisi Hubungan Masyarakat & Ketua Bidang Internal', 'nim' => 'F1E124061', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/humas/wakdiv_humas.webp'],
                    ['name' => 'Dian Tri Hariyanto', 'position' => 'Sekretaris Divisi', 'nim' => 'F1E124008', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/humas/sekretaris_humas.webp'],
                    ['name' => 'Muhammad Ardika Ghathfani', 'position' => 'Ketua Bidang Eksternal', 'nim' => 'F1E124042', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/humas/kabid_eksternal_humas.webp'],
                    ['name' => 'Putri Syaqila Amanda.S', 'position' => 'Ketua Bidang Manajemen Sosial Media', 'nim' => 'F1E124005', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/humas/kabid_manajemen_sosial_media_humas.webp'],
                    ['name' => 'Intan Sri Ayu Binti Darul Nizsham', 'position' => 'Anggota Eksternal', 'nim' => 'F1E124053', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/humas/anggota_ekternal_humas.webp'],
                    ['name' => 'Mahardika Azel Pangestu', 'position' => 'Anggota Internal', 'nim' => 'F1E124035', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/humas/anggota_internal_humas.webp'],
                    ['name' => 'Amanda Dwi Maharani', 'position' => 'Anggota Manajemen Sosial Media', 'nim' => 'F1E124003', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/humas/anggota_manajemen_sosial_media.webp'],
                ]
            ],
            [
                'name' => 'Riset dan Teknologi', 'slug' => 'ristek', 'singkatan' => 'RISTEK', 'icon' => '💻', 'color' => 'bg-purple-50', 'text_color' => 'text-purple-600', 'type' => 'divisi',
                'description' => 'Divisi Riset dan Teknologi (RISTEK) merupakan divisi yang berfokus pada bidang pengetahuan ilmiah dan pengembangan teknologi. RISTEK juga memfasilitasi pengembangan mahasiswa melalui teknologi informasi dan soft skills.',
                'sub_divisions' => ['Riset', 'Teknologi'],
                'members' => [
                    ['name' => 'Damara Rafiandriza Putra', 'position' => 'Ketua Divisi Riset dan Teknologi', 'nim' => 'F1E123031', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/ristek/kadiv_ristek.webp'],
                    ['name' => 'Shakila Rama Wulandari', 'position' => 'Wakil Ketua Divisi Riset dan Teknologi', 'nim' => 'F1E123028', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/ristek/kakadiv_ristek.webp'],
                    ['name' => 'Fika Faulina', 'position' => 'Sekretaris Divisi', 'nim' => 'F1E124051', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/ristek/sekretaris_ristek.webp'],
                    ['name' => 'Maulana Umar Al-Faroq', 'position' => 'Ketua Bidang Riset', 'nim' => 'F1E124010', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/ristek/kabid_riset.webp'],
                    ['name' => 'Raihan Sanovra Marzuq Gerdo Fiero', 'position' => 'Ketua Bidang Teknologi', 'nim' => 'F1E123066', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/ristek/kabid_teknologi.webp'],
                    ['name' => 'Ahmad Irsanto Hibatullah', 'position' => 'Anggota Riset', 'nim' => 'F1E124020', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/ristek/anggota_riset.webp'],
                    ['name' => 'Iqbal Khairullah', 'position' => 'Anggota Riset', 'nim' => 'F1E124013', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/ristek/anggota_riset_2.webp'],
                    ['name' => 'Muhammad Andre Kurniawan', 'position' => 'Anggota Teknologi', 'nim' => 'F1E124029', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/ristek/anggota_teknologi.webp'],
                ]
            ],
            [
                'name' => 'Dana dan Usaha', 'slug' => 'danus', 'singkatan' => 'DANUS', 'icon' => '💰', 'color' => 'bg-yellow-50', 'text_color' => 'text-yellow-600', 'type' => 'divisi',
                'description' => 'Divisi Dana Usaha (Danus) adalah divisi yang bertanggung jawab dalam merancang, mengelola, dan melaksanakan kegiatan usaha organisasi guna memperoleh sumber pendanaan mandiri. Divisi ini berperan sebagai penunjang keuangan himpunan mahasiswa melalui kegiatan kewirausahaan yang kreatif, inovatif, dan berkelanjutan, sehingga dapat mendukung pelaksanaan program kerja serta meningkatkan kemandirian finansial organisasi.',
                'sub_divisions' => ['Sosial dan Branding', 'Kewirausahaan'],
                'members' => [
                    ['name' => 'M. Wahyudin', 'position' => 'Ketua Divisi Dana Usaha', 'nim' => 'F1E123063', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/danus/kadiv_danus.webp'],
                    ['name' => 'Rio Saputra', 'position' => 'Wakil Ketua Divisi Dana Usaha', 'nim' => 'F1E123101', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/danus/wakadiv_danus.webp'],
                    ['name' => 'Laila Nazelita', 'position' => 'Sekretaris', 'nim' => 'F1E123003', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/danus/sekretaris_danus.webp'],
                    ['name' => 'Nadia Julia Fika', 'position' => 'Bendahara', 'nim' => 'F1E124018', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/danus/bendahara_danus.webp'],
                    ['name' => 'Friska Marchella', 'position' => 'Ketua Bidang Kewirausahaan', 'nim' => 'F1E124069', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/danus/kabid_kewirausahaan.webp'],
                    ['name' => 'Wisnu Nugroho', 'position' => 'Ketua Bidang Sosial dan Branding', 'nim' => 'F1E124032', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/danus/kabid_sosial_branding.webp'],
                    ['name' => 'Gibran Krisna Athallah', 'position' => 'Anggota Kewirausahaan', 'nim' => 'F1E123034', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/danus/anggota_kewirausahaan.webp'],
                    ['name' => 'Hilmy Anandika Indra', 'position' => 'Anggota Sosial dan Branding', 'nim' => 'F1E123005', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/danus/anggota_sosial_branding.webp'],
                ]
            ],
            [
                'name' => 'Media dan Informasi', 'slug' => 'mediasi', 'singkatan' => 'MEDIASI', 'icon' => '📣', 'color' => 'bg-sky-50', 'text_color' => 'text-sky-600', 'type' => 'divisi',
                'description' => 'Divisi Media dan Informasi (MEDIASI) merupakan divisi yang bertanggung jawab dalam pengelolaan, produksi, dan penyebaran informasi HIMASI UNJA melalui media digital dan visual kreatif. Mediasi berperan sebagai wajah utama HIMASI dalam membangun citra, branding, dan komunikasi kepada pihak internal maupun eksternal, melalui pengelolaan media sosial, desain visual, dokumentasi foto & video, serta arsip digital kegiatan. Mediasi juga memastikan seluruh informasi kegiatan HIMASI tersampaikan secara informatif, menarik, konsisten, dan terdokumentasi dengan baik.',
                'sub_divisions' => ['Desain', 'Videografi', 'Fotografi'],
                'members' => [
                    ['name' => 'Khafifah Najwa Siregar', 'position' => 'Ketua Divisi Media dan Informasi', 'nim' => 'F1E123044', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/mediasi/kadiv_mediasi.webp'],
                    ['name' => 'Juliyando Akbar', 'position' => 'Wakil Ketua Divisi Media dan Informasi', 'nim' => 'F1E123029', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/mediasi/wakadiv_mediasi.webp'],
                    ['name' => 'Azia Naura Ramadhani', 'position' => 'Sekretaris', 'nim' => 'F1E123064', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/mediasi/sekretaris_mediasi.webp'],
                    ['name' => 'Reza Dian Azzahra', 'position' => 'Ketua Bidang Desain', 'nim' => 'F1E124072', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/mediasi/kabid_desain.webp'],
                    ['name' => 'Arfun Ali Yafie', 'position' => 'Ketua Bidang Videografi', 'nim' => 'F1E123070', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/mediasi/kabid_videografi.webp'],
                    ['name' => 'Gian Dzikri Alfarobbi', 'position' => 'Ketua Bidang Fotografi', 'nim' => 'F1E124024', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/mediasi/kabid_fotografi.webp'],
                    ['name' => 'Bagas Adinata', 'position' => 'Anggota Desain', 'nim' => 'F1E124016', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/mediasi/anggota_desain.webp'],
                ]
            ],
            [
                'name' => 'Minat dan Bakat', 'slug' => 'mikat', 'singkatan' => 'MDB', 'icon' => '🎨', 'color' => 'bg-rose-50', 'text_color' => 'text-rose-600', 'type' => 'divisi',
                'description' => 'Divisi Minat dan Bakat (MDB) berperan dalam menggali serta mengembangkan potensi anggota sesuai dengan minat dan bakat yang dimiliki melalui berbagai kegiatan, seperti pelatihan, perlombaan, dan pertunjukan. Melalui kegiatan tersebut, anggota diberikan wadah untuk mengekspresikan diri secara positif sehingga mampu meningkatkan kreativitas, keterampilan, dan kepercayaan diri. Selain itu, MDB juga berfungsi sebagai sarana pembinaan dan pengembangan soft skill anggota agar mampu berprestasi serta berkontribusi secara aktif dalam organisasi.',
                'sub_divisions' => ['Seni', 'Penyaluran bakat', 'Sport & E-sport'],
                'members' => [
                    ['name' => 'Rayyan Adam Gunawan', 'position' => 'Ketua Divisi Minat dan Bakat', 'nim' => 'F1E123002', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/mdb/kadiv_mdb.webp'],
                    ['name' => 'Octa Rulian Pratama', 'position' => 'Wakil Ketua Divisi Minat dan Bakat', 'nim' => 'F1E124009', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/mdb/wakadiv_mdb.webp'],
                    ['name' => 'Lesianda Junitia', 'position' => 'Sekretaris', 'nim' => 'F1E123096', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/mdb/sekretaris_mdb.webp'],
                    ['name' => 'Laila Khairul Amalia', 'position' => 'Ketua Bidang Seni', 'nim' => 'F1E124030', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/mdb/kabid_seni.webp'],
                    ['name' => 'Muhammad Rajendra Fareliansyah', 'position' => 'Ketua Bidang Penyaluran Bakat', 'nim' => 'F1E123072', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/mdb/kabid_penyaluran_bakat.webp'],
                    ['name' => 'M.Arif Rahman', 'position' => 'Ketua Bidang Sport & E-Sport', 'nim' => 'F1E124065', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/mdb/kabid_sport_esport.webp'],
                    ['name' => 'Fharel Sapvela Dwipa', 'position' => 'Anggota Sport & E-Sport', 'nim' => 'F1E124048', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/mdb/anggota_sport_esport.webp'],
                ]
            ],
            [
                'name' => 'Pengembangan Sumber Daya Anggota', 'slug' => 'psda', 'singkatan' => 'PSDA', 'icon' => '👥', 'color' => 'bg-emerald-50', 'text_color' => 'text-emerald-600', 'type' => 'divisi',
                'description' => 'Divisi Pengembangan Sumber Daya Anggota (PSDA) berfokus pada penguatan kualitas organisasi melalui pengelolaan hubungan, kebersamaan, serta evaluasi kinerja HIMASI. PSDA menjadi wadah pelaksanaan kegiatan keakraban, kolaborasi, serta agenda evaluatif dan apresiatif yang melibatkan pengurus, anggota, dan dosen.',
                'sub_divisions' => ['Kolaborasi Event', 'Pengelolaan & Konsolidasi Anggota'],
                'members' => [
                    ['name' => 'Aisyah Putri Hasbi', 'position' => 'Ketua Divisi PSDA', 'nim' => 'F1E123097', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/psda/kadiv_psda.webp'],
                    ['name' => 'Fitri Agustina', 'position' => 'Wakil Ketua Divisi PSDA', 'nim' => 'F1E123091', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/psda/wakadiv_psda.webp'],
                    ['name' => 'Imelia Amanda', 'position' => 'Sekretaris Divisi', 'nim' => 'F1E123083', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/psda/sekretaris_psda.webp'],
                    ['name' => 'Akbar Nabil Fikri', 'position' => 'Ketua Bidang Kolaborasi dan Event', 'nim' => 'F1E124058', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/psda/kabid_kolaborasi_event.webp'],
                    ['name' => 'Han Leomila Nababan', 'position' => 'Ketua Bidang Pengelolaan & Konsolidasi Anggota', 'nim' => 'F1E124057', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/psda/kabid_pengelolaan_konsolidasi_anggota.webp'],
                    ['name' => 'Ahmad Fauzan', 'position' => 'Anggota Kolaborasi dan Event', 'nim' => 'F1E124023', 'tahun_angkatan' => '2024', 'avatar' => null],
                    ['name' => 'M. Arzis Fadhillah', 'position' => 'Anggota Pengelolaan & Konsolidasi Anggota', 'nim' => 'F1E124050', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/psda/anggota_pengelolaan_konsolidasi_anggota.webp'],
                    ['name' => 'Ghaniyyah Fitri Altila', 'position' => 'Anggota Pengelolaan & Konsolidasi Anggota', 'nim' => 'F1E124028', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/psda/anggota_pengelolaan_konsolidasi_anggota_1.webp'],
                ]
            ],
            [
                'name' => 'Sosial dan Agama', 'slug' => 'sosagma', 'singkatan' => 'SOSGAM', 'icon' => '🕌', 'color' => 'bg-teal-50', 'text_color' => 'text-teal-600', 'type' => 'divisi',
                'description' => 'Divisi Sosial dan Agama (Sosgam) adalah divisi yang mewadahi berbagai kegiatan sosial mulai dari lingkup mahasiswa Sistem Informasi hingga masyarakat luas. Sosgam membidangi kegiatan keagamaan dalam kehidupan kemahasiswaan, khususnya dalam perayaan hari-hari besar keagamaan, serta menjadi wadah pengembangan potensi mahasiswa di bidang keagamaan.',
                'sub_divisions' => ['Sosial', 'Agama'],
                'members' => [
                    ['name' => 'Frizka Aulia', 'position' => 'Ketua Divisi Sosial dan Agama', 'nim' => 'F1E123112', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/sosgam/kadiv_sosgam.webp'],
                    ['name' => 'Bayu Hartanto', 'position' => 'Wakil Ketua Divisi Sosial dan Agama', 'nim' => 'F1E124045', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/sosgam/wakadiv_sosgam.webp'],
                    ['name' => 'Ribi Aulia Ladinda', 'position' => 'Sekretaris', 'nim' => 'F1E124036', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/sosgam/sekretaris_sosgam.webp'],
                    ['name' => 'Riko Wijaya', 'position' => 'Ketua Bidang Sosial', 'nim' => 'F1E124052', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/sosgam/kabid_sosial.webp'],
                    ['name' => 'Olga Wulandari', 'position' => 'Ketua Bidang Agama', 'nim' => 'F1E124055', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/sosgam/kabid_agama.webp'],
                    ['name' => 'Melani Fitri Astria', 'position' => 'Anggota Sosial', 'nim' => 'F1E123074', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/sosgam/anggota_sosial.webp'],
                    ['name' => 'M. Ridho', 'position' => 'Anggota Agama', 'nim' => 'F1E123068', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/sosgam/anggota_agama.webp'],
                    ['name' => 'Rihhadatul Aliya', 'position' => 'Anggota Agama', 'nim' => 'F1E123104', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/sosgam/anggota_agama_2.webp'],
                ]
            ],
            [
                'name' => 'Pengawasan dan Penyelesaian Masalah', 'slug' => 'ppm', 'singkatan' => 'PPM', 'icon' => '🌍', 'color' => 'bg-orange-50', 'text_color' => 'text-orange-600', 'type' => 'divisi',
                'description' => 'Divisi Pengawasan dan Penyelesaian Masalah (PPM) berfokus pada pemantauan dan evaluasi kinerja keanggotaan guna menjaga efektivitas serta kepatuhan terhadap standar organisasi. Divisi ini bertugas mengidentifikasi dan menganalisis permasalahan internal dalam lingkup keanggotaan mahasiswa baru HIMASI, serta melaksanakan penyelesaian permasalahan secara objektif dan solutif.',
                'sub_divisions' => ['Pengelolaan Data', 'Pengawasan'],
                'members' => [
                    ['name' => 'Bintang Aliqa Athilla', 'position' => 'Ketua Divisi PPM', 'nim' => 'F1E123055', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/ppm/kadiv_ppm.webp'],
                    ['name' => 'Khozin Sapzidan', 'position' => 'Wakil Ketua Divisi PPM', 'nim' => 'F1E123073', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/ppm/wakadiv_ppm.webp'],
                    ['name' => 'Fadhil Rahmadhana', 'position' => 'Sekretaris Divisi', 'nim' => 'F1E124059', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/ppm/sekretaris_ppm.webp'],
                    ['name' => 'Nusyaibah', 'position' => 'Ketua Bidang Pengawasan', 'nim' => 'F1E124037', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/ppm/kabid_pengawasan.webp'],
                    ['name' => 'Ripa Bagaskara', 'position' => 'Ketua Bidang Pengelolaan Data', 'nim' => 'F1E124025', 'tahun_angkatan' => '2024', 'avatar' => 'pengurus_hima/ppm/kabid_pengelolaan_data.webp'],
                    ['name' => 'Griyo Sihnugroho', 'position' => 'Anggota Pengawasan', 'nim' => 'F1E123019', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/ppm/anggota_pengawasan.webp'],
                    ['name' => 'Qonita Ghina Anbarputri', 'position' => 'Anggota Pengelolaan Data', 'nim' => 'F1E123033', 'tahun_angkatan' => '2023', 'avatar' => 'pengurus_hima/ppm/anggota_pengawasan_data.webp'],
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
            $subDivsData = $divData['sub_divisions'] ?? [];
            unset($divData['members']);
            unset($divData['sub_divisions']);
            
            $divData['period_id'] = $period->id;
            
            // Base points for gamification (optional)
            $divData['base_points'] = 100;
            
            // Use updateOrCreate so we don't duplicate divisions in the active period
            $division = Division::updateOrCreate(
                ['slug' => $divData['slug'], 'period_id' => $period->id],
                $divData
            );
            
            // Clear placeholders or existing members in this division to prevent duplicates
            $division->members()->delete();
            $division->subDivisions()->delete();

            // Insert sub divisions
            foreach ($subDivsData as $subName) {
                \App\Models\Kepengurusan\SubDivision::create([
                    'division_id' => $division->id,
                    'name' => $subName,
                    'slug' => \Illuminate\Support\Str::slug($subName) . '-' . time(),
                ]);
            }

            static $emailCounter = 100;
            foreach ($membersData as $m) {
                // Generate a unique email
                $firstName = preg_replace('/[^a-zA-Z0-9]/', '', strtolower(explode(' ', $m['name'])[0]));
                $email = $firstName . ($emailCounter++) . '@himasi.unja.ac.id';
                
                // Use provided NIM and Angkatan or fallback
                $angkatanList = ['2021', '2022', '2023'];
                $angkatan = $m['tahun_angkatan'] ?? $angkatanList[array_rand($angkatanList)];
                $nim = $m['nim'] ?? ('F1E1' . substr($angkatan, 2) . str_pad($emailCounter, 3, '0', STR_PAD_LEFT));
                
                // Tentukan global_role berdasarkan posisi jabatan
                $posLower = strtolower($m['position']);
                $role = 'anggota';
                
                if (str_contains($posLower, 'ketua himpunan') && !str_contains($posLower, 'wakil')) {
                    $role = 'kahim';
                } elseif (str_contains($posLower, 'wakil ketua himpunan')) {
                    $role = 'wakahim';
                } elseif (str_contains($posLower, 'sekretaris')) {
                    $role = 'sekretaris';
                } elseif (str_contains($posLower, 'bendahara')) {
                    $role = 'bendahara';
                } elseif (str_contains($posLower, 'ketua divisi') || str_contains($posLower, 'wakil ketua divisi')) {
                    $role = 'kadiv';
                } elseif (str_contains($posLower, 'pembina')) {
                    $role = 'pembina';
                } elseif (str_contains($posLower, 'penasehat')) {
                    $role = 'dp';
                }

                $user = User::create([
                    'name' => $m['name'],
                    'email' => $email,
                    'nim' => $nim,
                    'angkatan' => $angkatan,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'avatar' => $m['avatar'] ?? null,
                    'global_role' => $role,
                ]);

                // Determine position mapping
                $posTitle = strtolower($m['position']);
                $orgPosId = $genericAnggota->id;
                
                if (str_contains($posTitle, 'wakil ketua himpunan')) {
                    $p = OrgPosition::firstOrCreate(['slug' => 'wakil-ketua-himpunan'], ['name' => 'Wakil Ketua Himpunan', 'level' => 2]);
                    $orgPosId = $p->id;
                } elseif (str_contains($posTitle, 'ketua himpunan') || str_contains($posTitle, 'ketua umum')) {
                    $p = OrgPosition::firstOrCreate(['slug' => 'ketua-himpunan'], ['name' => 'Ketua Himpunan', 'level' => 1]);
                    $orgPosId = $p->id;
                } elseif (str_contains($posTitle, 'sekretaris i') && !str_contains($posTitle, 'sekretaris ii')) {
                    $p = OrgPosition::firstOrCreate(['slug' => 'sekretaris-1'], ['name' => 'Sekretaris I', 'level' => 3]);
                    $orgPosId = $p->id;
                } elseif (str_contains($posTitle, 'sekretaris ii')) {
                    $p = OrgPosition::firstOrCreate(['slug' => 'sekretaris-2'], ['name' => 'Sekretaris II', 'level' => 3]);
                    $orgPosId = $p->id;
                } elseif (str_contains($posTitle, 'sekretaris')) {
                    $p = OrgPosition::firstOrCreate(['slug' => 'sekretaris'], ['name' => 'Sekretaris', 'level' => 3]);
                    $orgPosId = $p->id;
                } elseif (str_contains($posTitle, 'bendahara i') && !str_contains($posTitle, 'bendahara ii')) {
                    $p = OrgPosition::firstOrCreate(['slug' => 'bendahara-1'], ['name' => 'Bendahara I', 'level' => 4]);
                    $orgPosId = $p->id;
                } elseif (str_contains($posTitle, 'bendahara ii')) {
                    $p = OrgPosition::firstOrCreate(['slug' => 'bendahara-2'], ['name' => 'Bendahara II', 'level' => 4]);
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
