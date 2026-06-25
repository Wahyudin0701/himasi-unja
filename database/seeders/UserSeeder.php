<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Division;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');
        
        $roles = Role::all()->keyBy('name');
        $divisions = Division::all()->keyBy('name');

        // Inti
        $intiRoles = ['Ketupel', 'Waketupel', 'Sekpel', 'Bendpel'];
        foreach ($intiRoles as $r) {
            User::create([
                'name' => $r . ' Name',
                'email' => strtolower($r) . '@himasi.com',
                'password' => $password,
                'role_id' => $roles[$r]->id,
                'division_id' => $divisions['Inti']->id,
            ]);
        }

        // Operasional
        $operasionalDivisions = [
            'Acara', 'Kestari', 'Humas', 'Logistik', 
            'Sponsor', 'Pubdok', 'Keamanan', 'Kesehatan', 'Konsumsi', 'Dekorasi'
        ];

        foreach ($operasionalDivisions as $div) {
            // 1 CO
            User::create([
                'name' => 'CO ' . $div,
                'email' => 'co.' . strtolower($div) . '@himasi.com',
                'password' => $password,
                'role_id' => $roles['CO']->id,
                'division_id' => $divisions[$div]->id,
            ]);

            // 5 Anggota
            for ($i = 1; $i <= 5; $i++) {
                User::create([
                    'name' => 'Anggota ' . $i . ' ' . $div,
                    'email' => 'anggota' . $i . '.' . strtolower($div) . '@himasi.com',
                    'password' => $password,
                    'role_id' => $roles['Anggota']->id,
                    'division_id' => $divisions[$div]->id,
                ]);
            }
        }
    }
}
