<?php

/**
 * Alternative File Copy Script (For hosts that don't support symlinks)
 * 
 * This script copies files from storage/app/public to public/storage
 * instead of using symbolic links. Use this if setup-storage-link.php fails.
 * 
 * Run this file once in your browser: http://yourdomain.com/copy-files.php
 * 
 * IMPORTANT: Delete this file after running it successfully for security reasons!
 */

// Load Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

echo "<h2>File Copy Method (Alternative to Symlinks)</h2>";
echo "<hr>";

$sourceDir = storage_path('app/public');
$destDir = public_path('storage');

echo "<p><strong>Source:</strong> {$sourceDir}</p>";
echo "<p><strong>Destination:</strong> {$destDir}</p>";

try {
    // Create destination directory if it doesn't exist
    if (!File::exists($destDir)) {
        File::makeDirectory($destDir, 0755, true, true);
        echo "<p style='color: green;'>✓ Created destination directory</p>";
    } else {
        echo "<p style='color: green;'>✓ Destination directory already exists</p>";
    }

    // Get .htaccess content for the storage directory
    $htaccessContent = "<IfModule mod_rewrite.c>\n    RewriteEngine On\n    RewriteCond %{REQUEST_FILENAME} !-f\n    RewriteCond %{REQUEST_FILENAME} !-d\n    RewriteRule ^ index.php [L]\n</IfModule>\n";
    
    $htaccessPath = $destDir . '/.htaccess';
    File::put($htaccessPath, $htaccessContent);
    echo "<p style='color: green;'>✓ Created .htaccess file in storage directory</p>";

    // Copy all files recursively
    echo "<hr>";
    echo "<h3>Copying Files...</h3>";
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($sourceDir, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    
    $count = 0;
    $errors = [];
    
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $relativePath = str_replace($sourceDir . DIRECTORY_SEPARATOR, '', $file->getPathname());
            $destPath = $destDir . DIRECTORY_SEPARATOR . $relativePath;
            
            // Create subdirectories if needed
            $destSubDir = dirname($destPath);
            if (!File::exists($destSubDir)) {
                File::makeDirectory($destSubDir, 0755, true, true);
            }
            
            // Copy file
            if (File::copy($file->getPathname(), $destPath)) {
                $count++;
                echo "<p style='color: green; font-size: 12px;'>✓ Copied: {$relativePath}</p>";
            } else {
                $errors[] = $relativePath;
                echo "<p style='color: red; font-size: 12px;'>✗ Failed to copy: {$relativePath}</p>";
            }
        }
    }

    echo "<hr>";
    echo "<h3>Summary:</h3>";
    echo "<p style='color: green;'><strong>✓ Successfully copied {$count} file(s)</strong></p>";
    
    if (!empty($errors)) {
        echo "<p style='color: orange;'>⚠ {$errors} file(s) failed to copy</p>";
    }

    // Verify some files are accessible
    echo "<hr>";
    echo "<h3>Verification:</h3>";
    echo "<p>Testing access to storage directory...</p>";
    
    if (is_readable($destDir)) {
        echo "<p style='color: green;'>✓ Storage directory is readable</p>";
    } else {
        echo "<p style='color: red;'>✗ Storage directory is not readable. Check permissions.</p>";
    }
    
    if (is_writable($destDir)) {
        echo "<p style='color: green;'>✓ Storage directory is writable</p>";
    } else {
        echo "<p style='color: orange;'>⚠ Storage directory is not writable (this may be okay)</p>";
    }

} catch (\Exception $e) {
    echo "<p style='color: red;'><strong>✗ Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p>Please check folder permissions and try again.</p>";
}

echo "<hr>";
echo "<h3>Next Steps:</h3>";
echo "<ol>";
echo "<li>Test accessing documents from your application</li>";
echo "<li><strong style='color: red;'>DELETE THIS FILE</strong> after successful setup for security</li>";
echo "<li>You may need to run this script periodically if new files are uploaded</li>";
echo "</ol>";

echo "<hr>";
echo "<p style='color: #666; font-size: 12px;'><em>Created: " . date('Y-m-d H:i:s') . "</em></p>";
