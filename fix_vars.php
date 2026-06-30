<?php
$f = 'resources/views/kepengurusan/directory/index.blade.php';
$c = file_get_contents($f);

$c = str_replace('$pembinaMembers', '($pembina ? $pembina->members : collect())', $c);
$c = str_replace('$dpMembers', '($dp ? $dp->members : collect())', $c);
$c = str_replace('$bphMembers', '($bph ? $bph->members : collect())', $c);
$c = str_replace('$divisiGrouped', '$filteredDivisions', $c);

file_put_contents($f, $c);
echo "Fixed variables!\n";
