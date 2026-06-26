<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kepanitiaan\CommitteeRole;

class CommitteeRoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Ketua Pelaksana', 'slug' => 'ketua-pelaksana', 'level' => 1, 'scope' => 'inti'],
            ['name' => 'Wakil Ketua Pelaksana', 'slug' => 'wakil-ketua-pelaksana', 'level' => 2, 'scope' => 'inti'],
            ['name' => 'Sekretaris Pelaksana', 'slug' => 'sekretaris-pelaksana', 'level' => 3, 'scope' => 'inti'],
            ['name' => 'Bendahara Pelaksana', 'slug' => 'bendahara-pelaksana', 'level' => 3, 'scope' => 'inti'],
            ['name' => 'Koordinator Divisi (CO)', 'slug' => 'co-divisi', 'level' => 4, 'scope' => 'divisi'],
            ['name' => 'Anggota', 'slug' => 'anggota', 'level' => 5, 'scope' => 'divisi'],
            ['name' => 'Pendamping Gugus', 'slug' => 'pendamping-gugus', 'level' => 5, 'scope' => 'divisi'],
        ];

        foreach ($roles as $role) {
            CommitteeRole::create($role);
        }
    }
}
