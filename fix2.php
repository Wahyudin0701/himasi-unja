<?php
$lines = file('resources/views/kepengurusan/anggota/proker/progress.blade.php', FILE_IGNORE_NEW_LINES);

$start = -1;
for ($i = 0; $i < count($lines); $i++) {
    if (trim($lines[$i]) === '<!-- Riwayat Progres Full -->') {
        $start = $i;
        break;
    }
}

$end = -1;
for ($i = $start; $i < count($lines); $i++) {
    if (trim($lines[$i]) === '<!-- Kolom Kanan: Info Ringkas Progres -->') {
        $end = $i;
        break;
    }
}

$block = array_splice($lines, $start, $end - $start);

// Find where to insert it (after the Pencapaian Saat Ini card)
$insertPos = -1;
for ($i = 0; $i < count($lines); $i++) {
    if (trim($lines[$i]) === '<!-- Kolom Kanan: Info Ringkas Progres -->') {
        // the space-y-6 div is at $i + 1
        // the Pencapaian Saat Ini card is inside
        // find the first </div> that aligns with the card
        $openDivs = 0;
        for ($j = $i + 1; $j < count($lines); $j++) {
            $line = trim($lines[$j]);
            if (strpos($line, '<div') !== false && strpos($line, '</div>') === false) {
                $openDivs++;
            } elseif (strpos($line, '<div') !== false && strpos($line, '</div>') !== false) {
                // both in same line, no net change
            } elseif (strpos($line, '</form>') !== false || strpos($line, '</a>') !== false) {
                // ignore
            } elseif (strpos($line, '</div>') !== false) {
                $openDivs--;
                if ($openDivs === 0) { // closing of space-y-6
                    $insertPos = $j;
                    break 2;
                }
            }
        }
    }
}

if ($insertPos !== -1) {
    array_splice($lines, $insertPos, 0, $block);
} else {
    // fallback, insert before last two divs
    for ($i = count($lines) - 1; $i >= 0; $i--) {
        if (trim($lines[$i]) === '@endsection') {
            array_splice($lines, $i - 1, 0, $block);
            break;
        }
    }
}

file_put_contents('resources/views/kepengurusan/anggota/proker/progress.blade.php', implode("\n", $lines) . "\n");
echo "Done\n";
