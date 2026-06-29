<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Messaging\Channel;
use App\Models\Kepengurusan\Period;
use App\Models\Kepengurusan\Division;
use App\Models\Kepengurusan\Member;
use App\Models\User;

class ChannelSeeder extends Seeder
{
    public function run(): void
    {
        $period = Period::where('is_active', true)->first();
        if (!$period) {
            $this->command->warn('No active period found. Skipping channel seeding.');
            return;
        }

        // Get divisions
        $pembinaDivision = Division::where('period_id', $period->id)->where('type', 'pembina')->first();
        $dpDivision = Division::where('period_id', $period->id)->where('type', 'dp')->first();
        $bphDivision = Division::where('period_id', $period->id)->where('type', 'bph')->first();
        $divisiList = Division::where('period_id', $period->id)->where('type', 'divisi')->get();

        // Get Pimpinan users (Kahim & Wakahim)
        $pimpinanUsers = User::where('global_role', 'pimpinan')->pluck('id')->toArray();

        // ========================
        // 1. Channel: Pembina ↔ Pimpinan
        // ========================
        if ($pembinaDivision) {
            $channel = Channel::firstOrCreate(
                ['type' => 'pembina_pimpinan', 'period_id' => $period->id],
                ['name' => 'Pembina & Pimpinan', 'division_id' => $pembinaDivision->id]
            );

            // Add Pembina members
            $pembinaUserIds = Member::where('division_id', $pembinaDivision->id)->pluck('user_id')->toArray();
            // Add Pimpinan users  
            $allMembers = array_unique(array_merge($pembinaUserIds, $pimpinanUsers));

            foreach ($allMembers as $userId) {
                $channel->members()->syncWithoutDetaching([$userId]);
            }

            $this->command->info("Created channel: {$channel->name} with " . count($allMembers) . " members");
        }

        // ========================
        // 2. Channel: DP ↔ Pimpinan
        // ========================
        if ($dpDivision) {
            $channel = Channel::firstOrCreate(
                ['type' => 'dp_pimpinan', 'period_id' => $period->id],
                ['name' => 'Dewan Pengawas & Pimpinan', 'division_id' => $dpDivision->id]
            );

            $dpUserIds = Member::where('division_id', $dpDivision->id)->pluck('user_id')->toArray();
            $allMembers = array_unique(array_merge($dpUserIds, $pimpinanUsers));

            foreach ($allMembers as $userId) {
                $channel->members()->syncWithoutDetaching([$userId]);
            }

            $this->command->info("Created channel: {$channel->name} with " . count($allMembers) . " members");
        }

        // ========================
        // 3. Channels: Pimpinan ↔ Kadiv (1 per divisi)
        // ========================
        foreach ($divisiList as $divisi) {
            $channel = Channel::firstOrCreate(
                ['type' => 'pimpinan_kadiv', 'division_id' => $divisi->id, 'period_id' => $period->id],
                ['name' => 'Pimpinan & Kadiv ' . $divisi->singkatan]
            );

            // Get Kadiv & Wakadiv of this division
            $kadivUserIds = Member::where('division_id', $divisi->id)
                ->whereHas('orgPosition', function($q) {
                    $q->whereIn('slug', ['ketua-divisi', 'wakil-ketua-divisi']);
                })
                ->pluck('user_id')->toArray();

            $allMembers = array_unique(array_merge($kadivUserIds, $pimpinanUsers));

            foreach ($allMembers as $userId) {
                $channel->members()->syncWithoutDetaching([$userId]);
            }

            $this->command->info("Created channel: {$channel->name} with " . count($allMembers) . " members");
        }

        // ========================
        // 4. Channels: Kadiv ↔ Anggota (1 per divisi)
        // ========================
        foreach ($divisiList as $divisi) {
            $channel = Channel::firstOrCreate(
                ['type' => 'kadiv_anggota', 'division_id' => $divisi->id, 'period_id' => $period->id],
                ['name' => 'Divisi ' . $divisi->singkatan]
            );

            // All members of this division
            $divisiUserIds = Member::where('division_id', $divisi->id)->pluck('user_id')->toArray();

            foreach ($divisiUserIds as $userId) {
                $channel->members()->syncWithoutDetaching([$userId]);
            }

            $this->command->info("Created channel: {$channel->name} with " . count($divisiUserIds) . " members");
        }

        $this->command->info('Channel seeding complete!');
    }
}
