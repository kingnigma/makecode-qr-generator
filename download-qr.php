<?php

/**
 * Download QR Code API Endpoint
 * Handles QR code downloads in JPEG or PNG format
 */

require_once 'config.php';

// Get parameters
$format = $_GET['format'] ?? 'png';
$size = isset($_GET['size']) ? (int)$_GET['size'] : 400;
$dpi = isset($_GET['dpi']) ? (int)$_GET['dpi'] : 72;
$qrCodeId = $_GET['qrCodeId'] ?? null;

// Validate format
if (!in_array($format, ['png', 'jpeg', 'jpg'])) {
    die('Invalid format');
}

// Normalize format
if ($format === 'jpg') {
    $format = 'jpeg';
}

try {
    $pdo = getDbConnection();

    if (!$pdo) {
        throw new Exception('Database connection failed');
    }

    // Get QR code from database
    if ($qrCodeId) {
        $stmt = $pdo->prepare("SELECT qr_image_path FROM qr_codes WHERE id = ?");
        $stmt->execute([$qrCodeId]);
        $qrCode = $stmt->fetch();

        if (!$qrCode) {
            throw new Exception('QR code not found');
        }

        // Construct file path relative to project root
        $imagePath = __DIR__ . '/' . $qrCode['qr_image_path'];

        if (!file_exists($imagePath)) {
            throw new Exception('QR code image file not found at: ' . $imagePath);
        }

        // Load the image
        $image = imagecreatefrompng($imagePath);

        if (!$image) {
            throw new Exception('Failed to load QR code image');
        }

        // Resize if needed
        if ($size !== 400) {
            $resized = imagescale($image, $size, $size);
            if ($resized) {
                imagedestroy($image);
                $image = $resized;
            }
        }

        // Set filename
        $filename = 'qr_code_' . $qrCodeId . '.' . $format;

        // Set headers for download
        header('Content-Type: image/' . $format);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Output image
        if ($format === 'png') {
            imagepng($image);
        } else {
            imagejpeg($image, null, 90);
        }

        // Clean up
        imagedestroy($image);
    } else {
        throw new Exception('QR code ID is required');
    }
} catch (Exception $e) {
    error_log("Error downloading QR code: " . $e->getMessage());
    header('HTTP/1.1 500 Internal Server Error');
    echo 'Failed to download QR code: ' . $e->getMessage();
}
