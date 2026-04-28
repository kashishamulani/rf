<?php

/**
 * Auto-Sync Script for File Updates
 * 
 * This script checks for new files in storage/app/public and copies them to public/storage
 * Run this periodically (daily/weekly) or manually when new files are uploaded
 * 
 * Access via: http://yourdomain.com/sync-storage.php
 * 
 * IMPORTANT: Delete this file after use or protect it with authentication!
 */

// Load Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\File;

echo "<h2>Storage Sync - Check for New Files</h2>";
echo "<hr>";

$sourceDir = storage_path('app/public');
$destDir = public_path('storage');

echo "<p><strong>Source:</strong> {$sourceDir}</p>";
echo "<p><strong>Destination:</strong> {$destDir}</p>";

try {
    // Ensure destination exists
    if (!File::exists($destDir)) {
        File::makeDirectory($destDir, 0755, true, true);
        echo "<p style='color: green;'>✓ Created destination directory</p>";
    }

    // Get all source files
    $sourceFiles = [];
    if (is_dir($sourceDir)) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($sourceDir, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $relativePath = str_replace($sourceDir . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $sourceFiles[$relativePath] = $file->getMTime();
            }
        }
    }

    // Get all destination files
    $destFiles = [];
    if (is_dir($destDir)) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($destDir, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $relativePath = str_replace($destDir . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $destFiles[$relativePath] = $file->getMTime();
            }
        }
    }

    // Find new or modified files
    $filesToCopy = [];
    $skippedFiles = [];
    
    foreach ($sourceFiles as $path => $mtime) {
        if (!isset($destFiles[$path])) {
            // File doesn't exist in destination
            $filesToCopy[] = $path;
        } elseif ($mtime > $destFiles[$path]) {
            // File was modified (newer in source)
            $filesToCopy[] = $path . ' (modified)';
        } else {
            // File is up to date
            $skippedFiles[] = $path;
        }
    }

    // Copy new/modified files
    echo "<hr>";
    echo "<h3>Sync Results:</h3>";
    
    if (empty($filesToCopy)) {
        echo "<p style='color: green;'><strong>✓ All files are up to date! No sync needed.</strong></p>";
    } else {
        echo "<p style='color: blue;'><strong>Found " . count($filesToCopy) . " new/modified file(s)</strong></p>";
        
        $count = 0;
        $errors = [];
        
        foreach ($filesToCopy as $fileInfo) {
            $path = explode(' (', $fileInfo)[0]; // Remove " (modified)" suffix
            $relativePath = $path;
            $destPath = $destDir . DIRECTORY_SEPARATOR . $path;
            $sourcePath = $sourceDir . DIRECTORY_SEPARATOR . $path;
            
            // Create subdirectories if needed
            $destSubDir = dirname($destPath);
            if (!File::exists($destSubDir)) {
                File::makeDirectory($destSubDir, 0755, true, true);
            }
            
            // Copy file
            if (File::copy($sourcePath, $destPath)) {
                $count++;
                echo "<p style='color: green; font-size: 12px;'>✓ Copied: {$relativePath}</p>";
            } else {
                $errors[] = $relativePath;
                echo "<p style='color: red; font-size: 12px;'>✗ Failed to copy: {$relativePath}</p>";
            }
        }
        
        echo "<hr>";
        echo "<p style='color: green;'><strong>✓ Synced {$count} file(s) successfully</strong></p>";
        
        if (!empty($errors)) {
            echo "<p style='color: orange;'>⚠ " . count($errors) . " file(s) failed to copy</p>";
        }
    }

    // Summary
    echo "<hr>";
    echo "<h3>Summary:</h3>";
    echo "<ul>";
    echo "<li>Total source files: " . count($sourceFiles) . "</li>";
    echo "<li>Total destination files: " . count($destFiles) . "</li>";
    echo "<li>New/Modified files: " . count($filesToCopy) . "</li>";
    echo "<li>Up-to-date files: " . count($skippedFiles) . "</li>";
    echo "</ul>";

} catch (\Exception $e) {
    echo "<p style='color: red;'><strong>✗ Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p>Error trace: " . nl2br(htmlspecialchars($e->getTraceAsString())) . "</p>";
}

echo "<hr>";
echo "<h3>Actions:</h3>";
echo "<ul>";
echo "<li><a href='sync-storage.php' style='color: blue;'>↻ Refresh/Sync Again</a></li>";
echo "<li><a href='/' style='color: blue;'>🏠 Go to Homepage</a></li>";
echo "<li><strong style='color: red;'>Remember to delete this file after setup!</strong></li>";
echo "</ul>";

echo "<p style='color: #666; font-size: 12px;'><em>Last sync: " . date('Y-m-d H:i:s') . "</em></p>";
