<?php
$f = 'resources/views/public/structure.blade.php';
$c = file_get_contents($f);
$broken = 'identifier_type: \'{{ str_starts_with(strtolower($member->position_title ?? \\"\\"), \\"pembina\\") || str_starts_with(strtolower($member->position_title ?? \\"\\"), \\"dosen\\") ? \\"NIP\\" : \\"NIM\\" }}\'';
$fixed = 'identifier_type: \'{{ str_starts_with(strtolower($member->position_title ?? ""), "pembina") || str_starts_with(strtolower($member->position_title ?? ""), "dosen") ? "NIP" : "NIM" }}\'';
$c = str_replace($broken, $fixed, $c);
file_put_contents($f, $c);
echo "Fixed!\n";
