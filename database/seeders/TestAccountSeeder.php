<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kepanitiaan\Event;
use App\Models\Kepanitiaan\EventDivision;
use App\Models\Kepanitiaan\EventCommittee;
use App\Models\Kepanitiaan\CommitteeRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestAccountSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        $ketupelUser = User::firstOrCreate(['email' => 'ketupel@test.com'], [
            'name' => 'Akun Ketupel',
            'password' => $password,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
        
        $coUser = User::firstOrCreate(['email' => 'co@test.com'], [
            'name' => 'Akun CO Acara',
            'password' => $password,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
        $anggotaUser = User::firstOrCreate(['email' => 'anggota@test.com'], [
            'name' => 'Akun Anggota Acara',
            'password' => $password,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        $event = Event::first();
        if (!$event) return;

        $divisiAcara = EventDivision::where('event_id', $event->id)->where('slug', 'divisi-acara')->first();
        
        $ketupelRole = CommitteeRole::where('slug', 'ketua-pelaksana')->first();
        $coRole = CommitteeRole::where('slug', 'co-divisi')->first();
        $anggotaRole = CommitteeRole::where('slug', 'anggota')->first();

        if ($ketupelRole) {
            EventCommittee::firstOrCreate([
                'event_id' => $event->id,
                'user_id' => $ketupelUser->id,
            ], [
                'committee_role_id' => $ketupelRole->id,
            ]);
        }

        if ($coRole && $divisiAcara) {
            EventCommittee::firstOrCreate([
                'event_id' => $event->id,
                'user_id' => $coUser->id,
            ], [
                'committee_role_id' => $coRole->id,
                'event_division_id' => $divisiAcara->id,
            ]);
        }

        if ($anggotaRole && $divisiAcara) {
            EventCommittee::firstOrCreate([
                'event_id' => $event->id,
                'user_id' => $anggotaUser->id,
            ], [
                'committee_role_id' => $anggotaRole->id,
                'event_division_id' => $divisiAcara->id,
            ]);
        }
        
        $this->command->info("Akun Test Berhasil Dibuat: ketupel@test.com, co@test.com, anggota@test.com (password: password)");
    }
}
