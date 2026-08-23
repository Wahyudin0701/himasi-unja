<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Secara otomatis salin direktori foto pengurus ke storage (untuk keperluan hosting/deploy)
        $sourcePath = public_path('pengurus_hima');
        $destinationPath = storage_path('app/public/pengurus_hima');
        
        if (\Illuminate\Support\Facades\File::exists($sourcePath)) {
            \Illuminate\Support\Facades\File::copyDirectory($sourcePath, $destinationPath);
        }

        $this->call([
            PeriodSeeder::class,
            OrgPositionSeeder::class,
            CommitteeRoleSeeder::class,
            OrganizationSeeder::class,
            DiesNatalisSeeder::class,
            WorkProgramSeeder::class,
        ]);
    }
}
