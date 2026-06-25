<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = [
            'Inti', 'Acara', 'Kestari', 'Humas', 'Logistik', 
            'Sponsor', 'Pubdok', 'Keamanan', 'Kesehatan', 'Konsumsi', 'Dekorasi'
        ];

        foreach ($divisions as $div) {
            Division::create([
                'name' => $div,
                'base_points' => 100
            ]);
        }
    }
}
