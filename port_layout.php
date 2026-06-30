<?php
$f = 'temp_layout.blade.php';
$c = file_get_contents($f);

// 1. Change title and breadcrumbs
$c = preg_replace('/@section\(\'title\', \'Daftar Pengurus\'\)/', '@section(\'title\', \'Buku Direktori\')', $c);
$c = preg_replace('/<a href="{{ route\(\'super_admin.dashboard\'\) }}.*?>Sekretaris<\/a>/', '<span class="text-slate-500">Buku Direktori</span>', $c);
$c = preg_replace('/Data Pengurus HIMASI/', 'Buku Direktori HIMASI', $c);

// 2. Remove "Tambah" buttons
$c = preg_replace('/<a href="{{ route\(\'super_admin\.members\.create[^\n]*<span class="hidden sm:inline">Tambah[^<]*<\/span>\s*<\/a>/s', '', $c);

// 3. Remove "Kelola Bidang" and "Tambah Anggota" action bar for division
$c = preg_replace('/{{-- Action Bar --}}.*?<\/div>/s', '', $c); // Careful with this regex, let's do it safer:
$c = preg_replace('/<div class="p-4 sm:p-5 flex justify-end gap-3 bg-white">.*?<\/div>/s', '', $c);

// 4. Remove "Edit" and "Hapus" buttons for pembina, dp, bph, kadiv, and staff
// Delete forms:
$c = preg_replace('/<form id="delete-form-.*?<\/form>/s', '', $c);
// Edit links:
$c = preg_replace('/<a href="{{ route\(\'super_admin\.members\.edit\'.*?<\/a>/s', '', $c);
// Action column in table:
$c = preg_replace('/<th scope="col" class="px-6 py-3.5 w-\[10%\] text-right">Aksi<\/th>/', '', $c);
$c = preg_replace('/<td class="px-6 py-3 text-right">.*?<\/td>/s', '', $c);
// Remove colspan=6 and make it 5
$c = preg_replace('/colspan="6"/', 'colspan="5"', $c);

// 5. Change Variable names to match DirectoryController
$c = preg_replace('/\$pembinaMembers/', '($pembina ? $pembina->members : collect())', $c);
$c = preg_replace('/\$dpMembers/', '($dp ? $dp->members : collect())', $c);
$c = preg_replace('/\$bphMembers/', '($bph ? $bph->members : collect())', $c);
$c = preg_replace('/\$divisiGrouped/', '$filteredDivisions', $c);

file_put_contents('resources/views/kepengurusan/directory/index.blade.php', $c);
echo "Done!\n";
