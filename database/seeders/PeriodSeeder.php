<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kepengurusan\Period;

class PeriodSeeder extends Seeder
{
    public function run(): void
    {
        Period::create([
            'name' => '2025-2026',
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
            'is_active' => true,
        ]);

        Period::create([
            'name' => '2024-2025',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'is_active' => false,
            'archived_at' => '2024-12-31 23:59:59',
        ]);
    }
}
