<?php
$showContent = file('resources/views/kepengurusan/anggota/proker/show.blade.php', FILE_IGNORE_NEW_LINES);
$progContent = file('resources/views/kepengurusan/anggota/proker/progress.blade.php', FILE_IGNORE_NEW_LINES);

$showStart = -1;
foreach ($showContent as $i => $line) {
    if (strpos($line, '<!-- Riwayat Progres Full -->') !== false) {
        $showStart = $i;
        break;
    }
}
$timelineBlock = array_slice($showContent, $showStart, 87);

$progDelStart = -1;
foreach ($progContent as $i => $line) {
    if (strpos($line, '<!-- Riwayat Jurnal -->') !== false) {
        $progDelStart = $i;
        break;
    }
}
$progDelEnd = -1;
for ($i = $progDelStart; $i < count($progContent); $i++) {
    if (strpos(trim($progContent[$i]), '<!-- Kolom Kanan: Info Ringkas Progres -->') !== false) {
        $progDelEnd = $i - 2;
        break;
    }
}

array_splice($progContent, $progDelStart, $progDelEnd - $progDelStart);

$insertPos = -1;
for ($i = count($progContent) - 1; $i >= 0; $i--) {
    if (trim($progContent[$i]) === '@endsection') {
        $insertPos = $i - 1; // Before @endsection, and the closing divs
        break;
    }
}
// Actually, let's insert before the closing '</div>' of the right column.
// The right column is the last div before @endsection.
for ($i = count($progContent) - 1; $i >= 0; $i--) {
    if (trim($progContent[$i]) === '<!-- Kolom Kanan: Info Ringkas Progres -->') {
        $insertPos = $i + 17; // This is a rough estimation, let's find the closing div of space-y-6
        break;
    }
}

// better way to find the insertion point:
// find '<!-- Kolom Kanan: Info Ringkas Progres -->'
$rightColStart = -1;
foreach ($progContent as $i => $line) {
    if (strpos(trim($line), '<!-- Kolom Kanan: Info Ringkas Progres -->') !== false) {
        $rightColStart = $i;
        break;
    }
}

$insertPos = $rightColStart;
for ($i = $rightColStart; $i < count($progContent); $i++) {
    if (trim($progContent[$i]) === '</div>' && trim($progContent[$i+1]) === '</div>' && trim($progContent[$i+2]) === '@endsection') {
        $insertPos = $i;
        break;
    }
}

array_splice($progContent, $insertPos, 0, $timelineBlock);

file_put_contents('resources/views/kepengurusan/anggota/proker/progress.blade.php', implode("\n", $progContent) . "\n");
echo "Done\n";
