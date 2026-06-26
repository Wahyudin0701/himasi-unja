<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kepengurusan\OrgPosition;

class OrgPositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            ['name' => 'Ketua Himpunan', 'slug' => 'ketua-himpunan', 'level' => 1],
            ['name' => 'Wakil Ketua Himpunan', 'slug' => 'wakil-ketua-himpunan', 'level' => 2],
            ['name' => 'Sekretaris HIMA', 'slug' => 'sekretaris-hima', 'level' => 3],
            ['name' => 'Wakil Sekretaris HIMA', 'slug' => 'wakil-sekretaris-hima', 'level' => 4],
            ['name' => 'Bendahara HIMA', 'slug' => 'bendahara-hima', 'level' => 3],
            ['name' => 'Wakil Bendahara HIMA', 'slug' => 'wakil-bendahara-hima', 'level' => 4],
            ['name' => 'Ketua Divisi', 'slug' => 'ketua-divisi', 'level' => 5],
            ['name' => 'Ketua Bidang', 'slug' => 'ketua-bidang', 'level' => 6], // if they have bidang inside divisi
            ['name' => 'Anggota Divisi', 'slug' => 'anggota-divisi', 'level' => 7],
        ];

        foreach ($positions as $pos) {
            OrgPosition::create($pos);
        }
    }
}
