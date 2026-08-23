<?php

$dir = __DIR__ . '/resources/views';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

$count = 0;

foreach ($files as $file) {
    if ($file->isDir()) continue;
    if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') continue;

    $path = $file->getRealPath();
    $content = file_get_contents($path);
    $original = $content;

    // Pattern 1: @if($var->avatar && (file_exists(public_path('storage/' . $var->avatar)) || file_exists(public_path($var->avatar))))
    $content = preg_replace('/ \s*\&\&\s*\(\s*file_exists\(public_path\(\s*\'storage\/\'\s*\.\s*([a-zA-Z0-9_\-\>\$]+)\s*\)\)\s*\|\|\s*file_exists\(public_path\(\s*([a-zA-Z0-9_\-\>\$]+)\s*\)\)\s*\)/', '', $content);

    // Pattern 2: {{ file_exists(public_path('storage/' . $var->avatar)) ? asset('storage/' . $var->avatar) : asset($var->avatar) }}
    $content = preg_replace('/\{\{\s*file_exists\(public_path\(\'storage\/\'\s*\.\s*([a-zA-Z0-9_\-\>\$]+)\)\)\s*\?\s*asset\(\'storage\/\'\s*\.\s*\1\)\s*:\s*asset\(\s*\1\s*\)\s*\}\}/', '{{ asset(\'storage/\' . $1) }}', $content);

    // Pattern 3: avatar_url: '{{ $member->user->avatar ? (file_exists(...)...) : '' }}'
    $content = preg_replace('/\(\s*file_exists\(public_path\(\'storage\/\'\s*\.\s*([a-zA-Z0-9_\-\>\$]+)\)\)\s*\?\s*asset\(\'storage\/\'\s*\.\s*\1\)\s*:\s*asset\(\s*\1\s*\)\s*\)/', 'asset(\'storage/\' . $1)', $content);

    // Pattern 4: $avatarUrl = file_exists(public_path($avatarPath)) ? asset($avatarPath) : (file_exists(public_path('storage/' . $avatarPath)) ? asset('storage/' . $avatarPath) : null);
    $content = preg_replace('/\$avatarUrl\s*=\s*file_exists\(public_path\(\$avatarPath\)\)\s*\?\s*asset\(\$avatarPath\)\s*:\s*\(\s*file_exists\(public_path\(\'storage\/\'\s*\.\s*\$avatarPath\)\)\s*\?\s*asset\(\'storage\/\'\s*\.\s*\$avatarPath\)\s*:\s*null\s*\);/', '$avatarUrl = $avatarPath ? asset(\'storage/\' . $avatarPath) : null;', $content);

    if ($original !== $content) {
        file_put_contents($path, $content);
        $count++;
        echo "Updated: " . str_replace(__DIR__, '', $path) . "\n";
    }
}

echo "Total files updated: $count\n";
