<?php

/**
 * Delete QR Code or Barcode Handler
 * Deletes a QR code or barcode entry from the database
 */

require_once 'config.php';

header('Content-Type: application/json');

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Check if user is logged in
if (empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not authenticated']);
    exit;
}

// Get the code ID from POST data
$codeId = isset($_POST['code_id']) ? (int)$_POST['code_id'] : null;

if (!$codeId) {
    echo json_encode(['success' => false, 'error' => 'Code ID is required']);
    exit;
}

try {
    $pdo = getDbConnection();

    if (!$pdo) {
        throw new Exception('Database connection failed');
    }

    // First, verify that this code belongs to the logged-in user
    $stmt = $pdo->prepare('SELECT id, user_id, qr_image_path FROM qr_codes WHERE id = ? LIMIT 1');
    $stmt->execute([$codeId]);
    $code = $stmt->fetch();

    if (!$code) {
        echo json_encode(['success' => false, 'error' => 'Code not found']);
        exit;
    }

    // Verify ownership - security check
    if ($code['user_id'] != $_SESSION['user_id']) {
        echo json_encode(['success' => false, 'error' => 'Unauthorized']);
        exit;
    }

    // Delete the image file if it exists
    if (!empty($code['qr_image_path'])) {
        $filePath = __DIR__ . '/' . ltrim($code['qr_image_path'], '/');
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
    }

    // Delete the code from the database
    $deleteStmt = $pdo->prepare('DELETE FROM qr_codes WHERE id = ? AND user_id = ?');
    $deleteStmt->execute([$codeId, $_SESSION['user_id']]);

    echo json_encode([
        'success' => true,
        'message' => 'Code deleted successfully'
    ]);
} catch (Exception $e) {
    error_log("Error deleting code: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Failed to delete code: ' . $e->getMessage()
    ]);
}
