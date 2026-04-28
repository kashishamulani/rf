<?php

/**
 * Protected Storage Sync Script
 * 
 * This is a password-protected version of the sync script.
 * You can keep this on your server safely for ongoing maintenance.
 * 
 * Default password: admin123
 * CHANGE THE PASSWORD BELOW before uploading to your server!
 */

// ============= CONFIGURATION =============
$PASSWORD = 'admin123'; // ⚠️ CHANGE THIS to your own password!
// =========================================

session_start();

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: protected-sync.php');
    exit;
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    if ($_POST['password'] === $PASSWORD) {
        $_SESSION['authenticated'] = true;
    } else {
        $error = "Invalid password!";
    }
}

// Check authentication
$isAuthenticated = isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Protected Storage Sync</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 { color: #333; }
        .login-form {
            max-width: 400px;
            margin: 50px auto;
        }
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #0056b3;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .warning {
            background: #fff3cd;
            color: #856404;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        pre {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
        }
        .file-item {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        .file-success { color: #28a745; }
        .file-error { color: #dc3545; }
        .nav {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .nav a {
            color: #007bff;
            text-decoration: none;
            margin-right: 15px;
        }
        .nav a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <?php if (!$isAuthenticated): ?>
            <!-- Login Form -->
            <div class="login-form">
                <h1>🔐 Storage Sync Login</h1>
                <p>Enter password to access storage sync tool.</p>
                
                <?php if (isset($error)): ?>
                    <div class="error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <label for="password"><strong>Password:</strong></label>
                    <input type="password" id="password" name="password" required autofocus>
                    <button type="submit">Login</button>
                </form>
                
                <p style="margin-top: 30px; font-size: 14px; color: #666;">
                    ⚠️ <strong>Note:</strong> Change the default password in the file before deploying!
                </p>
            </div>
            
        <?php else: ?>
            <!-- Authenticated Sync Interface -->
            <h1>🔄 Storage Sync Tool</h1>
            
            <div class="nav">
                <a href="?logout=1">🚪 Logout</a>
                <a href="/">🏠 Homepage</a>
                <a href="sync-storage.php">📊 View Public Sync (Unprotected)</a>
            </div>
            
            <hr style="margin: 20px 0;">
            
            <?php
            // Load Laravel and run sync
            require __DIR__ . '/vendor/autoload.php';
            $app = require_once __DIR__ . '/bootstrap/app.php';
            $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
            $kernel->bootstrap();

            // File operations will be done inline

            $sourceDir = storage_path('app/public');
            $destDir = public_path('storage');

            echo "<p><strong>Source:</strong> {$sourceDir}</p>";
            echo "<p><strong>Destination:</strong> {$destDir}</p>";

            try {
                // Ensure destination exists
                if (!file_exists($destDir)) {
                    mkdir($destDir, 0755, true);
                    echo "<div class='success'>✓ Created destination directory</div>";
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
                        $filesToCopy[] = $path;
                    } elseif ($mtime > $destFiles[$path]) {
                        $filesToCopy[] = $path . ' (modified)';
                    } else {
                        $skippedFiles[] = $path;
                    }
                }

                // Copy new/modified files
                echo "<h3>Sync Results:</h3>";
                
                if (empty($filesToCopy)) {
                    echo "<div class='success'><strong>✓ All files are up to date! No sync needed.</strong></div>";
                } else {
                    echo "<div class='warning'><strong>Found " . count($filesToCopy) . " new/modified file(s)</strong></div>";
                    
                    $count = 0;
                    $errors = [];
                    
                    foreach ($filesToCopy as $fileInfo) {
                        $path = explode(' (', $fileInfo)[0];
                        $relativePath = $path;
                        $destPath = $destDir . DIRECTORY_SEPARATOR . $path;
                        $sourcePath = $sourceDir . DIRECTORY_SEPARATOR . $path;
                        
                        $destSubDir = dirname($destPath);
                        if (!file_exists($destSubDir)) {
                            mkdir($destSubDir, 0755, true);
                        }
                        
                        if (copy($sourcePath, $destPath)) {
                            $count++;
                            echo "<div class='file-item file-success'>✓ Copied: " . htmlspecialchars($relativePath) . "</div>";
                        } else {
                            $errors[] = $relativePath;
                            echo "<div class='file-item file-error'>✗ Failed: " . htmlspecialchars($relativePath) . "</div>";
                        }
                    }
                    
                    echo "<div class='success'><strong>✓ Synced {$count} file(s) successfully</strong></div>";
                    
                    if (!empty($errors)) {
                        echo "<div class='warning'>⚠ " . count($errors) . " file(s) failed to copy</div>";
                    }
                }

                // Summary
                echo "<h3>Summary:</h3>";
                echo "<pre>";
                echo "Total source files:      " . count($sourceFiles) . "\n";
                echo "Total destination files: " . count($destFiles) . "\n";
                echo "New/Modified files:      " . count($filesToCopy) . "\n";
                echo "Up-to-date files:        " . count($skippedFiles) . "\n";
                echo "</pre>";

            } catch (\Exception $e) {
                echo "<div class='error'><strong>✗ Error:</strong> " . htmlspecialchars($e->getMessage()) . "</div>";
            }
            ?>
            
            <hr style="margin: 20px 0;">
            
            <h3>Instructions:</h3>
            <ol>
                <li>This tool syncs files from <code>storage/app/public</code> to <code>public/storage</code></li>
                <li>Run this whenever you upload new documents</li>
                <li>Only NEW or MODIFIED files will be copied</li>
                <li><strong>Keep this file on your server</strong> for easy maintenance (it's password-protected)</li>
            </ol>
            
            <div class="warning">
                <strong>⚠️ Security Reminder:</strong>
                <ul>
                    <li>Change the default password in this file!</li>
                    <li>Delete other setup files: <code>setup-storage-link.php</code> and <code>copy-files.php</code></li>
                </ul>
            </div>
            
            <div class="nav">
                <a href="?logout=1">🚪 Logout</a>
                <a href="/">🏠 Homepage</a>
            </div>
            
            <p style="margin-top: 30px; font-size: 12px; color: #666; text-align: center;">
                Last sync: <?php echo date('Y-m-d H:i:s'); ?>
            </p>
        <?php endif; ?>
        
    </div>
</body>
</html>
