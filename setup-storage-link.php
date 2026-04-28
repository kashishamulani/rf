<?php

/**
 * Storage Link Setup Script
 * 
 * This script creates the symbolic link between storage/app/public and public/storage
 * Run this file once in your browser: http://yourdomain.com/setup-storage-link.php
 * 
 * IMPORTANT: Delete this file after running it successfully for security reasons!
 */

// Load Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\File;

echo "<h2>Storage Link Setup</h2>";
echo "<hr>";

$target = storage_path('app/public');
$link = public_path('storage');

echo "<p><strong>Target:</strong> {$target}</p>";
echo "<p><strong>Link:</strong> {$link}</p>";

try {
    // Check if link already exists
    if (File::exists($link)) {
        if (is_link($link)) {
            echo "<p style='color: green;'>✓ Symbolic link already exists!</p>";
        } else {
            echo "<p style='color: orange;'>⚠ A directory/file already exists at {$link}. Removing it...</p>";
            
            // If it's an empty directory or doesn't exist, we can proceed
            if (is_dir($link)) {
                $files = File::files($link);
                if (empty($files)) {
                    File::deleteDirectory($link);
                    echo "<p>Empty directory removed.</p>";
                } else {
                    echo "<p style='color: red;'>✗ Directory is not empty. Please manually remove it first.</p>";
                    echo "<p>Files found: " . count($files) . "</p>";
                    exit;
                }
            } else {
                File::delete($link);
                echo "<p>File removed.</p>";
            }
        }
    }

    // Create the symbolic link
    if (!file_exists($link)) {
        symlink($target, $link);
        echo "<p style='color: green; font-size: 18px;'><strong>✓ Storage link created successfully!</strong></p>";
        
        // Verify the link
        if (is_link($link)) {
            echo "<p style='color: green;'>✓ Verified: Symbolic link is working</p>";
            echo "<p>Link points to: " . readlink($link) . "</p>";
        } else {
            echo "<p style='color: orange;'>⚠ Link created but verification failed. Some hosting providers restrict symlinks.</p>";
            echo "<p><strong>Alternative Solution:</strong> Use the copy-files.php script instead.</p>";
        }
    } else {
        echo "<p style='color: green;'>✓ Link path already exists and is accessible</p>";
    }

} catch (\Exception $e) {
    echo "<p style='color: red;'><strong>✗ Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Troubleshooting:</strong></p>";
    echo "<ul>";
    echo "<li>Some shared hosting providers don't support symbolic links</li>";
    echo "<li>If symlinks are disabled, use the alternative method: <a href='copy-files.php' style='color: blue;'>Copy Files Script</a></li>";
    echo "<li>Or contact Hostinger support to enable symlinks</li>";
    echo "</ul>";
}

echo "<hr>";
echo "<h3>Next Steps:</h3>";
echo "<ol>";
echo "<li>Test accessing a document from your application</li>";
echo "<li><strong style='color: red;'>DELETE THIS FILE</strong> after successful setup for security</li>";
echo "<li>If this didn't work, try the alternative method below</li>";
echo "</ol>";

echo "<hr>";
echo "<h3>Alternative Method (If Symlinks Don't Work):</h3>";
echo "<p>If the above failed or symlinks are not supported, use the file copying method:</p>";
echo "<a href='copy-files.php' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Run Copy Files Script</a>";

echo "<hr>";
echo "<p style='color: #666; font-size: 12px;'><em>Created: " . date('Y-m-d H:i:s') . "</em></p>";
