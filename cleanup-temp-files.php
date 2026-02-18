<?php
/**
 * Cleanup Temporary Files Script
 * Deletes QR codes, barcodes, and ZIP files not associated with registered users after 30 minutes
 * Can be triggered via HTTP cron job
 */

require_once 'config.php';

// Security: Optional secret key to prevent unauthorized access
define('CLEANUP_SECRET', 'your-secret-key-here'); // Change this!

// Verify secret key if provided in URL
if (isset($_GET['key']) && $_GET['key'] !== CLEANUP_SECRET) {
    http_response_code(403);
    die('Unauthorized');
}

header('Content-Type: application/json');

$results = [
    'success' => true,
    'deleted_files' => 0,
    'deleted_db_records' => 0,
    'errors' => [],
    'timestamp' => date('Y-m-d H:i:s')
];

try {
    $pdo = getDbConnection();
    
    if (!$pdo) {
        throw new Exception('Database connection failed');
    }

    // Calculate cutoff time (30 minutes ago)
    $cutoffTime = date('Y-m-d H:i:s', strtotime('-30 minutes'));

    // Find QR codes without user_id or with user_id = 0/NULL, older than 30 minutes
    $stmt = $pdo->prepare('
        SELECT id, qr_image_path 
        FROM qr_codes 
        WHERE (user_id IS NULL OR user_id = 0) 
        AND created_at < ?
    ');
    $stmt->execute([$cutoffTime]);
    $tempCodes = $stmt->fetchAll();

    // Delete files and database records
    foreach ($tempCodes as $code) {
        // Delete physical file
        if (!empty($code['qr_image_path'])) {
            $filePath = __DIR__ . '/' . ltrim($code['qr_image_path'], '/');
            if (file_exists($filePath)) {
                if (@unlink($filePath)) {
                    $results['deleted_files']++;
                } else {
                    $results['errors'][] = "Failed to delete: {$code['qr_image_path']}";
                }
            }
        }

        // Delete database record
        $deleteStmt = $pdo->prepare('DELETE FROM qr_codes WHERE id = ?');
        if ($deleteStmt->execute([$code['id']])) {
            $results['deleted_db_records']++;
        }
    }

    // Clean up orphaned files in uploads directories
    $directories = [
        'uploads/qr-codes',
        'uploads/bar-codes'
    ];

    foreach ($directories as $dir) {
        $dirPath = __DIR__ . '/' . $dir;
        if (!is_dir($dirPath)) continue;

        $files = glob($dirPath . '/*');
        foreach ($files as $file) {
            if (!is_file($file)) continue;

            // Check if file is older than 30 minutes
            if (filemtime($file) < strtotime('-30 minutes')) {
                $filename = basename($file);
                
                // Check if file is referenced in database
                $checkStmt = $pdo->prepare('
                    SELECT COUNT(*) as count 
                    FROM qr_codes 
                    WHERE qr_image_path LIKE ?
                ');
                $checkStmt->execute(['%' . $filename . '%']);
                $result = $checkStmt->fetch();

                // If not in database, delete it
                if ($result['count'] == 0) {
                    if (@unlink($file)) {
                        $results['deleted_files']++;
                    } else {
                        $results['errors'][] = "Failed to delete orphaned file: $filename";
                    }
                }
            }
        }
    }

    // Clean up ZIP files older than 30 minutes
    $zipDirs = ['uploads/qr-codes', 'uploads/bar-codes'];
    foreach ($zipDirs as $dir) {
        $dirPath = __DIR__ . '/' . $dir;
        if (!is_dir($dirPath)) continue;

        $zipFiles = glob($dirPath . '/*.zip');
        foreach ($zipFiles as $zipFile) {
            if (filemtime($zipFile) < strtotime('-30 minutes')) {
                if (@unlink($zipFile)) {
                    $results['deleted_files']++;
                } else {
                    $results['errors'][] = "Failed to delete ZIP: " . basename($zipFile);
                }
            }
        }
    }

    $results['message'] = "Cleanup completed: {$results['deleted_files']} files and {$results['deleted_db_records']} DB records deleted";

} catch (Exception $e) {
    $results['success'] = false;
    $results['errors'][] = $e->getMessage();
    error_log("Cleanup script error: " . $e->getMessage());
}

echo json_encode($results, JSON_PRETTY_PRINT);
