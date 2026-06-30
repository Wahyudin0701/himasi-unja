<?php

// Fix existing database records
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Kepengurusan\Member;

$members = Member::with('user')->get();
$fixedCount = 0;

foreach ($members as $member) {
    if (!$member->user || in_array($member->user->global_role, ['pembina', 'dp', 'super_admin'])) {
        continue;
    }
    
    // We parse the primary position which is before the '&' if it exists
    $parts = explode(' & ', $member->position_title);
    $primaryPosition = $parts[0] ?? '';
    $posLower = strtolower($primaryPosition);
    
    $newRole = 'anggota';
    if (str_contains($posLower, 'ketua himpunan') && !str_contains($posLower, 'wakil')) {
        $newRole = 'kahim';
    } elseif (str_contains($posLower, 'wakil ketua himpunan')) {
        $newRole = 'wakahim';
    } elseif (str_contains($posLower, 'bendahara')) {
        $newRole = 'bendahara';
    } elseif (str_contains($posLower, 'sekretaris')) {
        $newRole = 'sekretaris';
    } elseif (str_contains($posLower, 'ketua divisi') && !str_contains($posLower, 'wakil')) {
        $newRole = 'kadiv';
    } elseif (str_contains($posLower, 'wakil ketua divisi')) {
        $newRole = 'kadiv'; // Wakil also has Kadiv access
    }
    
    if ($member->user->global_role !== $newRole) {
        $member->user->update(['global_role' => $newRole]);
        $fixedCount++;
        echo "Updated " . $member->user->name . " role to " . $newRole . "\n";
    }
}

echo "Database updated. Total users fixed: $fixedCount\n";
