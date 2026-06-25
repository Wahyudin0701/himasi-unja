<?php

namespace Database\Seeders;

use App\Models\Guest;
use Illuminate\Database\Seeder;

class GuestSeeder extends Seeder
{
    public function run(): void
    {
        $guests = [
            ['name' => 'Dr. John Doe', 'type' => 'VIP', 'dietary_restrictions' => 'Vegetarian'],
            ['name' => 'Prof. Jane Smith', 'type' => 'Dosen', 'dietary_restrictions' => null],
            ['name' => 'Maba 1', 'type' => 'Peserta', 'dietary_restrictions' => 'Alergi Seafood'],
            ['name' => 'Maba 2', 'type' => 'Peserta', 'dietary_restrictions' => null],
        ];

        foreach ($guests as $g) {
            Guest::create($g);
        }
    }
}
