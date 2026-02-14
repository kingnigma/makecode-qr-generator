<?php
/**
 * Get QR Codes API Endpoint
 * Retrieves QR codes for a specific user
 */

require_once 'config.php';

header('Content-Type: application/json');

// Check if request method is GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Get user ID from session or query parameter
$userId = $_GET['user_id'] ?? $_SESSION['user_id'] ?? null;

if (!$userId) {
    echo json_encode(['success' => false, 'error' => 'User ID is required']);
    exit;
}

try {
    $pdo = getDbConnection();
    
    if (!$pdo) {
        throw new Exception('Database connection failed');
    }

    // Get pagination parameters
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;

    // Get filter parameters
    $dataType = $_GET['data_type'] ?? null;
    $isActive = isset($_GET['is_active']) ? (bool)$_GET['is_active'] : null;

    // Build query
    $query = "SELECT 
                qr.id,
                qr.qr_type,
                qr.data_type,
                qr.content,
                qr.qr_image_path,
                qr.update_later,
                qr.dynamic_tracking,
                qr.scan_count,
                qr.is_active,
                qr.created_at,
                qr.updated_at
              FROM qr_codes qr
              WHERE qr.user_id = ?";

    $params = [$userId];

    // Add filters
    if ($dataType) {
        $query .= " AND qr.data_type = ?";
        $params[] = $dataType;
    }

    if ($isActive !== null) {
        $query .= " AND qr.is_active = ?";
        $params[] = $isActive;
    }

    // Add ordering and pagination
    $query .= " ORDER BY qr.created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $qrCodes = $stmt->fetchAll();

    // Normalize image paths so they are relative (remove leading slash if present)
    foreach ($qrCodes as &$c) {
        if (!empty($c['qr_image_path'])) {
            $c['qr_image_path'] = ltrim($c['qr_image_path'], '/');
        }
    }
    unset($c);

    // Get total count
    $countQuery = "SELECT COUNT(*) as total FROM qr_codes WHERE user_id = ?";
    $countParams = [$userId];

    if ($dataType) {
        $countQuery .= " AND data_type = ?";
        $countParams[] = $dataType;
    }

    if ($isActive !== null) {
        $countQuery .= " AND is_active = ?";
        $countParams[] = $isActive;
    }

    $countStmt = $pdo->prepare($countQuery);
    $countStmt->execute($countParams);
    $totalCount = $countStmt->fetch()['total'];

    // Return success response
    echo json_encode([
        'success' => true,
        'qrCodes' => $qrCodes,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => $totalCount,
            'totalPages' => ceil($totalCount / $limit)
        ]
    ]);

} catch (Exception $e) {
    error_log("Error retrieving QR codes: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Failed to retrieve QR codes: ' . $e->getMessage()
    ]);
}

?>
