<?php
/**
 * Save QR Code API Endpoint
 * Saves or updates QR code data in the database
 */

require_once 'config.php';

header('Content-Type: application/json');

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Get JSON input
$input = file_get_contents('php://input');
$qrData = json_decode($input, true);

// Validate input
if (!$qrData) {
    echo json_encode(['success' => false, 'error' => 'Invalid JSON data']);
    exit;
}

$qrCodeId = $qrData['id'] ?? null;
$userId = $_SESSION['user_id'] ?? null;
$content = $qrData['content'] ?? '';
$dataType = $qrData['dataType'] ?? 'url';
$updateLater = $qrData['updateLater'] ?? false;
$dynamicTracking = $qrData['dynamicTracking'] ?? false;

try {
    $pdo = getDbConnection();
    
    if (!$pdo) {
        throw new Exception('Database connection failed');
    }

    if ($qrCodeId) {
        // Update existing QR code
        $stmt = $pdo->prepare("
            UPDATE qr_codes 
            SET content = ?, 
                data_type = ?, 
                update_later = ?, 
                dynamic_tracking = ?,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ? AND user_id = ?
        ");
        
        $stmt->execute([
            $content,
            $dataType,
            $updateLater,
            $dynamicTracking,
            $qrCodeId,
            $userId
        ]);
        
        echo json_encode([
            'success' => true,
            'id' => $qrCodeId,
            'message' => 'QR Code updated successfully'
        ]);
    } else {
        // Create new QR code
        $stmt = $pdo->prepare("
            INSERT INTO qr_codes 
            (user_id, data_type, content, update_later, dynamic_tracking) 
            VALUES 
            (?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $userId,
            $dataType,
            $content,
            $updateLater,
            $dynamicTracking
        ]);
        
        $newId = $pdo->lastInsertId();
        
        echo json_encode([
            'success' => true,
            'id' => $newId,
            'message' => 'QR Code saved successfully'
        ]);
    }

} catch (Exception $e) {
    error_log("Error saving QR code: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Failed to save QR code: ' . $e->getMessage()
    ]);
}

?>
