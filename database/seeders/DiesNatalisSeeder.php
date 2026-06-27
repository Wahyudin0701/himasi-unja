<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kepengurusan\Division;
use App\Models\Kepengurusan\WorkProgram;
use App\Models\Kepanitiaan\Event;
use App\Models\Kepanitiaan\EventDivision;
use App\Models\Kepanitiaan\CommitteeRole;
use App\Models\Kepanitiaan\EventCommittee;
use App\Models\User;

class DiesNatalisSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Cari Divisi PSDA
        $psda = Division::where('slug', 'psda')->first();
        if (!$psda) {
            $this->command->warn("Divisi PSDA tidak ditemukan. Seeder dihentikan.");
            return;
        }

        // 2. Buat Proker Inti (Work Program)
        $proker = WorkProgram::create([
            'division_id' => $psda->id,
            'name' => 'Dies Natalis HIMASI',
            'type' => 'event',
            'description' => 'Perayaan hari jadi Himpunan Mahasiswa Sistem Informasi yang diadakan rutin setiap tahun.',
            'start_date' => now()->addMonths(2),
            'end_date' => now()->addMonths(2)->addDays(3),
            'status' => 'planned',
        ]);

        // 3. Buat Event yang Terhubung dengan Proker
        $admin = User::where('email', 'admin@himasi.unja.ac.id')->first();
        
        $event = Event::create([
            'period_id' => $psda->period_id,
            'work_program_id' => $proker->id,
            'created_by' => $admin->id ?? 1,
            'name' => 'Dies Natalis HIMASI 2026',
            'description' => 'Acara puncak perayaan ulang tahun HIMASI ke-12 dengan berbagai perlombaan dan malam inagurasi.',
            'event_date' => $proker->start_date,
            'end_date' => $proker->end_date,
            'status' => 'planning',
        ]);

        // 4. Buat 10 Divisi Kepanitiaan (Fixed)
        $divisiNames = [
            'Acara', 'Humas', 'Pubdok', 'Logistik', 'Kestari', 
            'Kesehatan', 'Dekorasi', 'Sponsor', 'Keamanan', 'Konsumsi'
        ];

        foreach ($divisiNames as $index => $divName) {
            EventDivision::create([
                'event_id' => $event->id,
                'name' => 'Divisi ' . $divName,
                'slug' => \Illuminate\Support\Str::slug('divisi-' . $divName),
                'sort_order' => $index + 1,
            ]);
        }

        // 5. Assign Ketupel Dummy (Untuk keperluan testing dashboard)
        // Faisal (ID 5) sebagai Ketupel
        $ketupel = User::find(5); 
        $ketupelRole = CommitteeRole::where('slug', 'ketua-pelaksana')->first();
        
        if ($ketupel && $ketupelRole) {
            EventCommittee::create([
                'event_id' => $event->id,
                'user_id' => $ketupel->id,
                'committee_role_id' => $ketupelRole->id,
            ]);
        }

        // 6. Assign Dummy CO & Anggota (Divisi Acara) untuk Testing Dashboard CO
        $divisiAcara = EventDivision::where('event_id', $event->id)->where('slug', 'divisi-acara')->first();
        $coRole = CommitteeRole::where('slug', 'co-divisi')->first();
        $anggotaRole = CommitteeRole::where('slug', 'anggota')->first();

        if ($divisiAcara && $coRole && $anggotaRole) {
            // User 6 jadi CO Acara
            EventCommittee::create([
                'event_id' => $event->id,
                'user_id' => 6, // Rahmat Hidayat
                'committee_role_id' => $coRole->id,
                'event_division_id' => $divisiAcara->id,
            ]);

            // User 7 dan 8 jadi Anggota Acara
            EventCommittee::create([
                'event_id' => $event->id,
                'user_id' => 7, // Dian Pratama
                'committee_role_id' => $anggotaRole->id,
                'event_division_id' => $divisiAcara->id,
            ]);
            EventCommittee::create([
                'event_id' => $event->id,
                'user_id' => 8, // Siti Aminah
                'committee_role_id' => $anggotaRole->id,
                'event_division_id' => $divisiAcara->id,
            ]);
        }

        $this->command->info("Event Dies Natalis & 10 Divisi berhasil dibuat. Ketupel: {$ketupel->name}");
    }
}
