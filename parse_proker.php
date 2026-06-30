<?php
$lines = file('skema_proker.md', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$divisions = [
    'Humas' => 'humas',
    'Ristek' => 'ristek',
    'MDB' => 'mdb',
    'Mediasi' => 'mediasi',
    'Sosgam' => 'sosgam',
    'PSDA' => 'psda',
    'PPM' => 'ppm',
    'Danus' => 'danus'
];

$prokers = [];
$currentDivision = '';
$currentProker = null;

foreach ($lines as $line) {
    // Detect Division
    if (preg_match('/^Divisi (.+)$/i', trim($line), $matches)) {
        $divName = trim($matches[1]);
        if (isset($divisions[$divName])) {
            $currentDivision = $divisions[$divName];
        }
        continue;
    }
    
    // Detect Proker Number (e.g. "1. Roadshow Beasiswa")
    if (preg_match('/^(\d+)\.\s+(.+)$/', trim($line), $matches)) {
        if ($currentProker) {
            $prokers[] = $currentProker;
        }
        $currentProker = [
            'name' => trim($matches[2]),
            'division_slug' => $currentDivision,
            'type' => 'non_event',
            'budget' => 0,
            'description' => '',
            'pic' => ''
        ];
        continue;
    }
    
    if ($currentProker) {
        $line = trim($line);
        if (strpos($line, '•') === 0 || strpos($line, 'o') === 0) {
            $line = ltrim($line, '•o ');
            $line = trim($line);
            
            if (stripos($line, 'Sifat & Jenis:') === 0 || stripos($line, 'Jenis & Sifat Kegiatan:') === 0) {
                $val = strtolower(explode(':', $line)[1]);
                if (strpos($val, 'event') !== false) {
                    $currentProker['type'] = 'event';
                } else {
                    $currentProker['type'] = 'non_event';
                }
            } elseif (stripos($line, 'Deskripsi:') === 0 || stripos($line, 'Deskripsi Kegiatan:') === 0) {
                $currentProker['description'] = trim(explode(':', $line, 2)[1]);
            } elseif (stripos($line, 'Penanggung Jawab') === 0) {
                $pic = explode(':', $line, 2)[1] ?? '';
                $currentProker['pic'] = trim($pic);
            } elseif (stripos($line, 'Anggaran & Sumber Dana') === 0 || stripos($line, 'Rencana Dana & Sumber') === 0 || stripos($line, 'Rencana Anggaran Dana') === 0 || stripos($line, 'Rencana Anggaran') === 0 || stripos($line, 'Sumber Dana') === 0) {
                $val = explode(':', $line, 2)[1] ?? '';
                if (preg_match_all('/\d+/', str_replace(['Rp', '.', ',', ' '], '', $val), $numMatches)) {
                    if (isset($numMatches[0][0]) && $numMatches[0][0] > 0) {
                        $currentProker['budget'] = (int) $numMatches[0][0];
                    }
                }
            }
        }
    }
}
if ($currentProker) {
    $prokers[] = $currentProker;
}

$output = "<?php\n\nnamespace Database\\Seeders;\n\nuse Illuminate\\Database\\Seeder;\nuse App\\Models\\Kepengurusan\\WorkProgram;\nuse App\\Models\\Kepengurusan\\Division;\nuse App\\Models\\User;\n\nclass WorkProgramSeeder extends Seeder\n{\n    public function run(): void\n    {\n";
$output .= "        \$divisions = Division::all()->keyBy('slug');\n";
$output .= "        \$users = User::all();\n\n";

foreach ($prokers as $p) {
    $slug = $p['division_slug'];
    $name = addslashes($p['name']);
    $desc = addslashes($p['description']);
    $type = $p['type'];
    $budget = $p['budget'];
    
    // Parse PIC to just first name
    $picName = trim(explode(',', explode('&', explode('(', $p['pic'])[0])[0])[0]);
    $picName = addslashes(trim(explode(' ', $picName)[0]));
    
    $output .= "        if (isset(\$divisions['$slug'])) {\n";
    if (!empty($picName)) {
        $output .= "            \$pic = \$users->first(function (\$u) { return stripos(\$u->name, '$picName') !== false; });\n";
    } else {
        $output .= "            \$pic = null;\n";
    }
    $output .= "            WorkProgram::create([\n";
    $output .= "                'division_id' => \$divisions['$slug']->id,\n";
    $output .= "                'name' => '$name',\n";
    $output .= "                'description' => '$desc',\n";
    $output .= "                'type' => '$type',\n";
    $output .= "                'status' => 'planning',\n";
    $output .= "                'budget_plan' => $budget,\n";
    if (!empty($picName)) {
        $output .= "                'pic_id' => \$pic ? \$pic->id : null,\n";
    } else {
        $output .= "                'pic_id' => null,\n";
    }
    $output .= "            ]);\n";
    $output .= "        }\n\n";
}

$output .= "    }\n}\n";

file_put_contents('database/seeders/WorkProgramSeeder.php', $output);
echo "Seeder generated successfully with " . count($prokers) . " prokers.\n";
