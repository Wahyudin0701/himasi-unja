<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kepengurusan\WorkProgram;
use App\Models\Kepengurusan\Division;
use App\Models\User;

class WorkProgramSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = Division::all()->keyBy('slug');
        $users = User::all();

        if (isset($divisions['humas'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Arsilia') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['humas']->id,
                'name' => 'Roadshow Beasiswa',
                'description' => 'Kegiatan yang dirancang khusus untuk memberikan informasi mendalam mengenai berbagai peluang beasiswa yang tersedia kepada mahasiswa Sistem Informasi.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 50000,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['humas'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Arsilia') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['humas']->id,
                'name' => 'Pendataan Mahasiswa Sistem Informasi',
                'description' => 'Melakukan pendataan secara berkala terhadap mahasiswa aktif Sistem Informasi yang berhasil lulus seleksi beasiswa maupun mahasiswa yang menorehkan prestasi.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['humas'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Mahardika') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['humas']->id,
                'name' => 'SI Teatalk',
                'description' => 'Program adaptasi digital dari kegiatan SI Goes To School (SI GTS) yang sebelumnya dilakukan tatap muka. Kegiatan ini memanfaatkan media digital agar lebih fleksibel dan berkelanjutan dalam membangun citra positif himpunan.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 850000,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['humas'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Muhammad') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['humas']->id,
                'name' => 'Studi Banding',
                'description' => 'Agenda kolaboratif antarorganisasi yang berfungsi sebagai wadah resmi untuk saling bertukar ide, inovasi, dan inspirasi.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['humas'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Muhammad') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['humas']->id,
                'name' => 'Menghadiri Acara/Kegiatan yang Masuk ke Himpunan',
                'description' => 'Menghadiri berbagai undangan acara formal maupun informal dari pihak luar dan menjaga komunikasi yang harmonis dengan alumni.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['humas'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Putri') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['humas']->id,
                'name' => 'Pengenalan Alumni',
                'description' => 'Publikasi konten kreatif di media sosial resmi @himasiunja atau @baloninformasi yang mengulas profil alumni.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['humas'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Putri') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['humas']->id,
                'name' => 'Mengelola Instagram @baloninformasi',
                'description' => 'Mencari, mengurasi, dan mengunggah informasi penting seputar beasiswa, perlombaan, serta memfasilitasi publikasi bagi media partner.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['humas'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Putri') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['humas']->id,
                'name' => 'Narahubung',
                'description' => 'Bertindak sebagai pusat layanan informasi resmi (Contact Person) melalui WhatsApp, Instagram @himasiunja, dan @baloninformasi.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['humas'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Muhammad') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['humas']->id,
                'name' => 'Penulisan Artikel',
                'description' => 'Menyusun artikel informatif mengenai pelaksanaan program kerja HIMASI serta artikel apresiatif yang menyoroti mahasiswa berprestasi.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['ristek'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Santo') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['ristek']->id,
                'name' => 'Thinklab Community',
                'description' => 'Wadah komunitas belajar rutin yang memfasilitasi mahasiswa untuk berdiskusi, mengerjakan tugas, dan mengeksplorasi teknologi bersama. Kegiatan dilaksanakan secara fleksibel (daring dan luring) dengan fokus pada kolaborasi antar-anggota untuk saling membantu mengatasi kesulitan akademik maupun teknis.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 500000,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['ristek'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Umar') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['ristek']->id,
                'name' => 'Study Club Batch 6',
                'description' => 'Kegiatan pembelajaran di mana mahasiswa Sistem Informasi akan difasilitasi oleh pengurus HIMASI UNJA dalam pengajaran materi perkuliahan tertentu sesuai bidang yang relevan bagi mahasiswa Sistem Informasi.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 1000000,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['ristek'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Iqbal') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['ristek']->id,
                'name' => 'Delegasi Lomba',
                'description' => 'Program pendampingan intensif bagi mahasiswa atau tim yang akan mengikuti kompetisi. HIMASI memfasilitasi kebutuhan delegasi mulai dari administrasi pendaftaran, pencarian mentor yang relevan, hingga bantuan informasi pendanaan, agar peserta dapat fokus sepenuhnya pada persiapan lomba.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 500000,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['ristek'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Santo') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['ristek']->id,
                'name' => 'IT Competition',
                'description' => 'Penyelenggaraan kompetisi bidang teknologi informasi berskala regional/nasional yang mewadahi berbagai cabang lomba. Kegiatan mencakup seluruh tahapan manajemen event, mulai dari perumusan tema, seleksi peserta, hingga penjurian final.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 2000000,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['ristek'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Umar') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['ristek']->id,
                'name' => 'Penulisan Artikel SI/TI',
                'description' => 'Media publikasi bagi mahasiswa untuk menuangkan gagasan, opini, atau wawasan seputar teknologi informasi. Tulisan yang masuk akan dikurasi dan dipublikasikan melalui kanal informasi HIMASI (Website/Media Sosial) sebagai bahan bacaan yang bermanfaat bagi mahasiswa umum.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['ristek'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Sanovra') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['ristek']->id,
                'name' => 'Pengembangan dan Maintenance HIMASI Presensi (HIMAPRES)',
                'description' => 'Kegiatan pemeliharaan dan pengembangan berkelanjutan pada sistem presensi digital internal organisasi (HIMAPRES).',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 100000,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['ristek'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Andre') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['ristek']->id,
                'name' => 'Maintenance Website HIMASI',
                'description' => 'Pengelolaan teknis laman resmi organisasi (website HIMASI) secara berkala.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['mdb'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Fharel') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['mdb']->id,
                'name' => 'SI CUP',
                'description' => 'Kompetisi olahraga dan e-sport yang diselenggarakan secara terintegrasi dengan rangkaian acara Dies Natalis.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['mdb'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Laila') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['mdb']->id,
                'name' => 'SI FEST',
                'description' => 'Kompetisi di bidang kesenian yang diadakan bersamaan dengan perayaan Dies Natalis.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['mdb'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'M.') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['mdb']->id,
                'name' => 'SI LEAGUE',
                'description' => 'Kompetisi internal di bidang olahraga dan e-sport yang dikhususkan bagi internal program studi.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 3000000,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['mdb'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'M.') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['mdb']->id,
                'name' => 'SI FUN SPORT',
                'description' => 'Kegiatan olahraga dan e-sport santai yang diadakan secara berkala sebagai sarana menyegarkan pikiran di sela perkuliahan.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['mdb'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Laila') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['mdb']->id,
                'name' => 'LABER SENI (Latihan Bersama Seni)',
                'description' => 'Kegiatan latihan seni bersama yang jadwalnya disesuaikan dengan kebutuhan persiapan penampilan atau proyek seni mendatang.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['mdb'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'M.') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['mdb']->id,
                'name' => 'COLLAB-SI',
                'description' => 'Kegiatan latihan olahraga atau e-sport bersama dengan himpunan mahasiswa lain di ruang lingkup fakultas.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['mdb'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Muhammad') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['mdb']->id,
                'name' => 'PENYALURAN BAKAT',
                'description' => 'Program wadah fasilitasi di mana himpunan membantu mengarahkan dan menyalurkan potensi mahasiswa sesuai dengan bidang keahlian spesifik mereka.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['mediasi'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Reza') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['mediasi']->id,
                'name' => 'Maintenance Media Sosial',
                'description' => 'Pengelolaan dan pemeliharaan akun Instagram, TikTok, dan YouTube HIMASI agar tetap aktif dan konsisten melalui konten desain dan video. Konten meliputi peringatan hari besar, cream days, blue days, HIMASI apresiasi, monthly recap, meme HIMASI, poster kegiatan, dan sejenisnya.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 20000,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['mediasi'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Reza') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['mediasi']->id,
                'name' => 'HIKIS (HIMASI Kuis)',
                'description' => 'Kegiatan kuis interaktif melalui Instagram Story berupa soal atau tebakan setiap dua bulan sekali.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['mediasi'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Bagas') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['mediasi']->id,
                'name' => 'Mading Kampus Digital',
                'description' => 'Pembaruan informasi aktual seputar kegiatan Sistem Informasi (SI) dan Fakultas Sains dan Teknologi (FST) UNJA melalui media sosial.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['mediasi'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Reza') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['mediasi']->id,
                'name' => 'SI TODAY (Program Baru)',
                'description' => 'Pembuatan konten informasi yang aktual, sedang tren, edukatif, dan relevan bagi mahasiswa Sistem Informasi serta lingkungan kampus dengan pendekatan yang ringan.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['mediasi'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Bagas') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['mediasi']->id,
                'name' => 'Desain E-Sertifikat Pengurus',
                'description' => 'Pembuatan desain dan distribusi e-sertifikat resmi bagi seluruh pengurus.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['mediasi'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Gian') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['mediasi']->id,
                'name' => 'Foto Bersama Pengurus',
                'description' => 'Sesi dokumentasi foto resmi seluruh jajaran pengurus HIMASI periode berjalan.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 150000,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['mediasi'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Gian') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['mediasi']->id,
                'name' => 'Dokumentasi Event',
                'description' => 'Mengambil dokumentasi berupa foto dan video di setiap kegiatan yang diselenggarakan oleh HIMASI. Hasil dokumentasi akan direkap dan diarsipkan secara rapi di Google Drive divisi.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['mediasi'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Arfun') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['mediasi']->id,
                'name' => 'Konten Bulanan',
                'description' => 'Pembuatan konten video pendek kreatif seputar dunia Sistem Informasi dan aktivitas perkuliahan sehari-hari dalam format Instagram Reels, TikTok, dan YouTube Shorts.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 25000,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['mediasi'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Arfun') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['mediasi']->id,
                'name' => 'Yearly Recap HIMASI UNJA',
                'description' => 'Pengumpulan, penyuntingan, dan penyusunan seluruh dokumentasi video kegiatan HIMASI yang telah berlangsung sepanjang periode kepengurusan menjadi satu video kilas balik terintegrasi.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['sosgam'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Riko') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['sosgam']->id,
                'name' => 'Harmoni Insani (Bidang Sosial)',
                'description' => 'Program sosial berkelanjutan berupa kunjungan ke panti asuhan dan yayasan disabilitas yang dilaksanakan sebanyak empat kali dalam satu periode. Kegiatan ini disertai dengan penggalangan donasi, kegiatan edukatif, serta permainan interaktif.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 3100000,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['sosgam'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Melani') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['sosgam']->id,
                'name' => 'Himasi Berduka Cita (Bidang Sosial)',
                'description' => 'Program aksi cepat tanggap sebagai bentuk kepedulian HIMASI terhadap keluarga besar Sistem Informasi yang sedang berduka melalui penyampaian informasi, penggalangan donasi, dan penyaluran bantuan dana duka.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['sosgam'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'M.') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['sosgam']->id,
                'name' => 'HIMASI Berbuka (Bidang Agama)',
                'description' => 'Kegiatan buka puasa bersama yang melibatkan seluruh mahasiswa dan dosen Program Studi Sistem Informasi sebagai ajang silaturahmi dan mempererat kebersamaan di bulan Ramadan.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 6500000,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['sosgam'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Mila') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['sosgam']->id,
                'name' => 'Perayaan Paskah (Bidang Agama)',
                'description' => 'Kegiatan perayaan dan refleksi Paskah bagi mahasiswa dan dosen Sistem Informasi yang beragama Kristiani melalui ibadah bersama dan kegiatan kebersamaan.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 1200000,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['sosgam'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Bayu') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['sosgam']->id,
                'name' => 'Peringatan Maulid Nabi Muhammad SAW 1448H (Bidang Agama)',
                'description' => 'Kegiatan keagamaan dalam rangka memperingati Maulid Nabi Muhammad SAW yang diisi dengan rangkaian perlombaan Islami serta acara tausiyah. Kegiatan ini diselenggarakan secara bauran (daring dan luring).',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 1200000,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['psda'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Rayyan') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['psda']->id,
                'name' => 'Dies Natalis ke-13 Prodi Sistem Informasi',
                'description' => 'Peringatan hari lahir Program Studi Sistem Informasi Universitas Jambi yang diselenggarakan setiap tahun oleh HIMASI sebagai wujud rasa syukur atas keberlangsungan dan pencapaian yang telah diraih. Kegiatan ini mencakup 3 tahapan utama, yaitu Opening Ceremony, Event, dan Closing Ceremony.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 4500000000,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['psda'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'M.') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['psda']->id,
                'name' => 'SI FUN DAY 2026',
                'description' => 'Kegiatan malam keakraban (makrab) untuk mahasiswa baru yang dirancang sebagai wadah interaksi antara mahasiswa baru, senior, dan pengurus HIMASI melalui berbagai aktivitas yang menyenangkan, interaktif, dan penuh kreativitas.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 2500000000,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['psda'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Han') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['psda']->id,
                'name' => 'Lepas Sambut',
                'description' => 'Kegiatan kebersamaan yang dilaksanakan pada akhir masa kepengurusan. Acara dikemas secara santai dan kekeluargaan untuk meninggalkan kesan kebersamaan yang mendalam di penghujung periode.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 200000000,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['psda'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Akbar') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['psda']->id,
                'name' => 'Makrab Pengurus: Himasi Friendship Night (HFN)',
                'description' => 'Kegiatan malam keakraban yang dikhususkan bagi internal pengurus aktif HIMASI untuk membangun keakraban dan solidaritas yang kokoh.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 500000000,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['psda'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'M.') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['psda']->id,
                'name' => 'Rapat Pleno',
                'description' => 'Rapat rutin berkala untuk seluruh kepengurusan guna membahas segala keperluan, perkembangan, dan koordinasi terkait himpunan yang diinisiasi oleh Badan Pengurus Harian (BPH).',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['psda'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Ghaniyyah') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['psda']->id,
                'name' => 'Survey Kepuasan Kinerja Pengurus HIMASI',
                'description' => 'Program evaluasi berupa penyebaran kuesioner/survei kepada dosen dan mahasiswa untuk menjaring umpan balik mengenai kualitas kinerja, program kerja, dan pelayanan kepengurusan HIMASI.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['psda'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Han') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['psda']->id,
                'name' => 'Pengurus of The Month',
                'description' => 'Program pemberian apresiasi rutin bulanan kepada pengurus yang dinilai memberikan kontribusi terbaik.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['psda'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Han') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['psda']->id,
                'name' => 'Dosen of The Month',
                'description' => 'Program apresiasi bulanan dari HIMASI yang ditujukan kepada dosen di lingkup program studi yang dinilai berkesan, inspiratif, dan memberikan kontribusi luar biasa.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['ppm'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Nusyaibah') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['ppm']->id,
                'name' => 'SI Harmony (Bidang Pengawasan)',
                'description' => 'Merupakan kegiatan penguatan nilai dan kedisiplinan yang ditujukan bagi mahasiswa baru. Pelaksanaannya dilakukan melalui penyampaian materi, diskusi, tanya jawab, serta refleksi diri untuk menumbuhkan rasa tanggung jawab dan kebersamaan.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['ppm'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Griyo') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['ppm']->id,
                'name' => 'Forum Aspirasi (Bidang Pengawasan)',
                'description' => 'Menyediakan ruang atau wadah digital berupa Google Formulir bagi mahasiswa baru untuk menyampaikan kritik, saran, maupun aspirasi mereka terhadap himpunan. Aspirasi yang masuk kemudian akan disalurkan kepada Badan Pengurus Harian (BPH) untuk ditindaklanjuti.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['ppm'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Ripa') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['ppm']->id,
                'name' => 'Training Dasar Organisasi / TDO (Bidang Pengelolaan Data)',
                'description' => 'Pembekalan dasar-dasar organisasi bagi mahasiswa baru yang dilaksanakan secara tatap muka (luring) selama satu hari penuh. Rangkaian agendanya meliputi pembukaan, penyampaian materi kepemimpinan/organisasi, serta simulasi praktik berorganisasi.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 3800000,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['ppm'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Qonita') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['ppm']->id,
                'name' => 'Pendataan Anggota (Bidang Pengelolaan Data)',
                'description' => 'Melakukan pencatatan kehadiran, partisipasi, dan keterlibatan aktif seluruh anggota dalam setiap kegiatan resmi yang diadakan oleh HIMASI, seperti SI Funday, Training Dasar Organisasi (TDO), Dies Natalis, dan tiga kali rangkaian kegiatan SI Wisuda.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['danus'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Udin') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['danus']->id,
                'name' => 'Pembuatan Jaket, Cocard, dan Lanyard Pengurus HIMASI',
                'description' => 'Kegiatan pembuatan atribut resmi berupa jaket, cocard, dan lanyard untuk pengurus HIMASI periode 2026. Alur pelaksanaannya meliputi perancangan desain, pemilihan vendor konveksi, hingga distribusi produk ke pengurus.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['danus'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Udin') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['danus']->id,
                'name' => 'Pembuatan Baju Prodi Angkatan 25',
                'description' => 'Proses pengadaan baju program studi khusus untuk mahasiswa Sistem Informasi angkatan 2025, mulai dari tahap desain, pemilihan vendor, pendataan ukuran baju, hingga pembagian.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 12600000,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['danus'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Udin') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['danus']->id,
                'name' => 'Pembuatan Baju SI Wisuda',
                'description' => 'Pembuatan baju Sistem Informasi khusus wisuda yang diperuntukkan bagi mahasiswa yang belum memiliki baju prodi (terutama angkatan 2025) guna menghadiri rangkaian arak-arakan wisuda.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 5250000,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['danus'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Nazel') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['danus']->id,
                'name' => 'Penjualan Minuman pada Latihan Arak-arakan Wisuda',
                'description' => 'Aktivitas berjualan minuman ringan kepada mahasiswa dan panitia di lokasi latihan arak-arakan wisuda.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 65500,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['danus'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Nazel') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['danus']->id,
                'name' => 'Program Danus Konsumsi Kegiatan TDO (Training Dasar Organisasi)',
                'description' => 'Penyediaan dan penjualan aneka makanan, minuman, serta merchandise pada saat acara TDO berlangsung. Proker ini juga membuka layanan Jasa Titip (Jastip) makanan bagi peserta yang tidak membawa bekal.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 145500,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['danus'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Gibran') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['danus']->id,
                'name' => 'Penyewaan Peralatan HIMASI',
                'description' => 'Pengelolaan komersialisasi aset inventaris musik milik HIMASI berupa alat musik snar, bass, dan sound system untuk disewakan kepada pihak internal maupun eksternal kampus.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['danus'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Nazel') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['danus']->id,
                'name' => 'Penjualan di Foto Pengurus Himpunan',
                'description' => 'Penyediaan stand makanan dan minuman ringan secara internal pada saat sesi dokumentasi foto pengurus HIMASI berlangsung.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 65500,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['danus'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Frizka.') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['danus']->id,
                'name' => 'Danus PMW (Program Mahasiswa Wirausaha)',
                'description' => 'Partisipasi aktif dan pendampingan dari Divisi DANUS dalam program inkubasi bisnis PMW tingkat universitas.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 6000000,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['danus'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Udin') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['danus']->id,
                'name' => 'Pembuatan Baju SI Funday',
                'description' => 'Pengadaan baju seragam kepanitiaan khusus untuk acara besar \"SI Funday\", mencakup perancangan konsep desain, pemesanan ke vendor, hingga distribusi.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['danus'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Wisnu.') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['danus']->id,
                'name' => 'Penjualan Merchandise pada Setiap Event',
                'description' => 'Penjualan produk-produk pernak-pernik (merchandise) khas HIMASI secara konsisten di setiap acara yang diadakan di dalam maupun di luar lingkungan kampus.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['danus'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Nazel') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['danus']->id,
                'name' => 'Danus di Kantin Perintis',
                'description' => 'Program penjualan produk makanan dan minuman siap saji dengan menitipkan atau membuka lapak di area strategis Kantin Perintis FST.',
                'type' => 'internal',
                'status' => 'planning',
                'budget_plan' => 186000,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['danus'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Nazel.') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['danus']->id,
                'name' => 'Penjualan di SI Funday',
                'description' => 'Membuka booth penjualan makanan, minuman, atau produk komersial lainnya secara langsung kepada para peserta di lokasi acara SI Funday.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['danus'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Hilmy') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['danus']->id,
                'name' => 'SI MARKET DAY',
                'description' => 'Keikutsertaan pengurus DANUS dalam menyewa dan mengelola stand jualan pada festival tahunan \"FST Market Day\", yang mencakup perencanaan menu produk, dekorasi stan, dan strategi penjualan langsung.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['danus'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Gibran') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['danus']->id,
                'name' => 'Takjil Ramadhan',
                'description' => 'Aksi berjualan menu berbuka puasa (takjil) di pinggir jalan atau area publik selama bulan suci Ramadhan dengan sistem manajemen kelompok yang terorganisir.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 0,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

        if (isset($divisions['danus'])) {
            $pic = $users->first(function ($u) { return stripos($u->name, 'Udin') !== false; });
            WorkProgram::create([
                'division_id' => $divisions['danus']->id,
                'name' => 'Pin Angkatan',
                'description' => 'Pengadaan atribut pin angkatan sebagai tanda pengenal sementara bagi mahasiswa baru Sistem Informasi angkatan 2026.',
                'type' => 'event',
                'status' => 'planning',
                'budget_plan' => 2026,
                'pic_id' => $pic ? $pic->id : null,
            ]);
        }

    }
}
