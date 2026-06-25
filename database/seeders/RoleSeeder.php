<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Ketupel'],
            ['name' => 'Waketupel'],
            ['name' => 'Sekpel'],
            ['name' => 'Bendpel'],
            ['name' => 'CO'],
            ['name' => 'Anggota'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
