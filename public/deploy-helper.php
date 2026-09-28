<?php
/**
 * HIMASI Management - Deploy Helper
 * Script ini menggantikan perintah artisan yang tidak bisa dijalankan
 * karena InfinityFree tidak memiliki SSH.
 * 
 * ⚠️ HAPUS FILE INI SETELAH DEPLOY SELESAI!
 * 
 * Akses via browser: https://himasiunja.freehosting.dev/deploy-helper.php?key=himasi2026
 */

// Security key (ganti jika perlu)
$secretKey = 'himasi2026';

if (!isset($_GET['key']) || $_GET['key'] !== $secretKey) {
    die('⛔ Unauthorized. Tambahkan ?key=himasi2026 di URL.');
}

echo "<pre>";
echo "============================================\n";
echo "  HIMASI Deploy Helper\n";
echo "============================================\n\n";

// Tentukan base path Laravel
$basePath = realpath(__DIR__ . '/..');
if (file_exists($basePath . '/artisan')) {
    echo "✅ Laravel base path: $basePath\n\n";
} else {
    // Mungkin kita ada di root htdocs
    $basePath = __DIR__;
    if (file_exists($basePath . '/artisan')) {
        echo "✅ Laravel base path: $basePath\n\n";
    } else {
        die("❌ Tidak bisa menemukan file artisan. Pastikan semua file Laravel sudah diupload.\n");
    }
}

$action = $_GET['action'] ?? 'info';

switch ($action) {
    case 'storage-link':
        // Buat symlink storage secara manual (copy directory)
        $target = $basePath . '/storage/app/public';
        $link = $basePath . '/public/storage';
        
        if (is_link($link) || is_dir($link)) {
            echo "⚠️ public/storage sudah ada.\n";
        } else {
            // Coba symlink dulu
            if (@symlink($target, $link)) {
                echo "✅ Symlink berhasil dibuat: public/storage -> storage/app/public\n";
            } else {
                // Jika symlink gagal, buat .htaccess redirect
                $htaccess = "RewriteEngine On\nRewriteRule ^(.*)$ ../storage/app/public/$1 [L]";
                @mkdir($link, 0755, true);
                file_put_contents($link . '/.htaccess', $htaccess);
                echo "✅ Storage redirect dibuat via .htaccess\n";
            }
        }
        break;

    case 'key-generate':
        // Generate APP_KEY
        $key = 'base64:' . base64_encode(random_bytes(32));
        echo "✅ APP_KEY baru: $key\n";
        echo "\nCopy key di atas dan paste ke file .env Anda.\n";
        break;
        
    case 'permissions':
        // Fix permissions
        $dirs = ['storage', 'storage/framework', 'storage/framework/cache', 
                 'storage/framework/sessions', 'storage/framework/views',
                 'storage/logs', 'bootstrap/cache'];
        foreach ($dirs as $dir) {
            $fullPath = $basePath . '/' . $dir;
            if (!is_dir($fullPath)) {
                @mkdir($fullPath, 0755, true);
                echo "📁 Created: $dir\n";
            } else {
                @chmod($fullPath, 0755);
                echo "✅ OK: $dir\n";
            }
        }
        
        // Buat file .gitkeep dan log file jika belum ada
        $logFile = $basePath . '/storage/logs/laravel.log';
        if (!file_exists($logFile)) {
            file_put_contents($logFile, '');
            echo "📄 Created: storage/logs/laravel.log\n";
        }
        echo "\n✅ Permissions fixed!\n";
        break;

    case 'clear-cache':
        // Hapus semua cache
        $cacheDirs = [
            $basePath . '/storage/framework/cache/data',
            $basePath . '/storage/framework/views',
            $basePath . '/storage/framework/sessions',
            $basePath . '/bootstrap/cache',
        ];
        foreach ($cacheDirs as $dir) {
            if (is_dir($dir)) {
                $files = glob($dir . '/*');
                foreach ($files as $file) {
                    if (is_file($file) && basename($file) !== '.gitignore') {
                        @unlink($file);
                    }
                }
                echo "🗑️ Cleared: " . str_replace($basePath, '', $dir) . "\n";
            }
        }
        echo "\n✅ Cache cleared!\n";
        break;

    case 'info':
    default:
        echo "📋 PHP Version: " . phpversion() . "\n";
        echo "📋 Server: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";
        echo "📋 Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
        echo "📋 Base Path: $basePath\n\n";
        
        // Check extensions
        $required = ['pdo_mysql', 'mbstring', 'xml', 'curl', 'gd', 'zip', 'bcmath'];
        echo "=== PHP Extensions ===\n";
        foreach ($required as $ext) {
            echo (extension_loaded($ext) ? "✅" : "❌") . " $ext\n";
        }
        
        echo "\n=== Available Actions ===\n";
        echo "?action=info           → Info ini\n";
        echo "?action=permissions    → Fix folder permissions\n";
        echo "?action=storage-link   → Buat storage link\n";
        echo "?action=key-generate   → Generate APP_KEY baru\n";
        echo "?action=clear-cache    → Hapus semua cache\n";
        break;
}

echo "\n============================================\n";
echo "⚠️  HAPUS FILE INI setelah deploy selesai!\n";
echo "============================================\n";
echo "</pre>";
