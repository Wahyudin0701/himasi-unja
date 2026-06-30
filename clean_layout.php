<?php
$f = 'resources/views/kepengurusan/directory/index.blade.php';
$c = file_get_contents($f);

// Breadcrumb
$c = str_replace('<a href="{{ route(\'super_admin.dashboard\') }}" class="text-slate-500 hover:text-brand-600 transition-colors">Sekretaris</a>', '<span class="text-slate-500">Buku Direktori</span>', $c);

// Tambah Pembina
$c = preg_replace('/<a href="{{ route\(\'super_admin\.members\.create\.pembina\'\).*?<\/a>/s', '', $c);

// Edit/Delete Pembina
$c = preg_replace('/<div class="flex items-center gap-2 shrink-0" @click\.stop>.*?<\/div>/s', '', $c);

// Tambah DP
$c = preg_replace('/<a href="{{ route\(\'super_admin\.members\.create\.dp\'\).*?<\/a>/s', '', $c);

// Edit BPH
$c = preg_replace('/<a @click\.stop href="{{ route\(\'super_admin\.members\.edit\'.*?<\/a>/s', '', $c);

// Edit Kadiv
$c = preg_replace('/<div class="flex items-center shrink-0" @click\.stop>.*?<\/div>/s', '', $c);

// Action Bar Divisi (Kelola Bidang & Tambah Anggota)
$c = preg_replace('/<div class="p-4 sm:p-5 flex justify-end gap-3 bg-white">.*?<\/div>/s', '', $c);

// Table Actions Column Header
$c = preg_replace('/<th scope="col" class="px-6 py-3\.5 w-\[10%\] text-right">Aksi<\/th>/', '', $c);
// Table Actions Column Data
$c = preg_replace('/<td class="px-6 py-3 text-right">.*?<\/td>/s', '', $c);
// Colspan
$c = str_replace('colspan="6"', 'colspan="5"', $c);

file_put_contents($f, $c);
echo "Cleaned up action buttons.\n";
