<?php

/**
 * Generate QR Code API Endpoint
 * Handles QR code generation and saves to database
 * Uses chillerlan/php-qrcode library
 */

require_once 'config.php';
require_once __DIR__ . '/qr_engine/autoload.php';

// Import QR Code classes
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Common\Version;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Output\QRGdImagePNG;

header('Content-Type: application/json');

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Get POST data
$data = $_POST['data'] ?? '';
$type = $_POST['type'] ?? 'url';
$updateLater = isset($_POST['updateLater']) ? (bool)$_POST['updateLater'] : false;
$dynamicTracking = isset($_POST['dynamicTracking']) ? (bool)$_POST['dynamicTracking'] : false;
$centerText = $_POST['centerText'] ?? '';
$centerTextColor = $_POST['centerTextColor'] ?? '#000000';
$userId = $_SESSION['user_id'] ?? null;

// Validate input
if (empty($data)) {
    echo json_encode(['success' => false, 'error' => 'No data provided']);
    exit;
}

try {
    $pdo = getDbConnection();

    if (!$pdo) {
        throw new Exception('Database connection failed');
    }

    // Generate unique filename
    $filename = 'qr_' . uniqid() . '.png';
    $filepath = QR_CODE_UPLOAD_DIR . $filename;

    // Create QR Code with options
    $qrOptions = [
        'version'      => Version::AUTO,
        'eccLevel'     => EccLevel::M,
        'outputInterface' => QRGdImagePNG::class,
        'scale'        => 5,
        'margin'       => 1,
        'drawLightModules' => true,
        'imageTransparent' => false,
        'returnResource' => true,
    ];

    // Generate QR code - pass array directly to constructor
    $qrcode = new QRCode($qrOptions);
    $image = $qrcode->render($data);

    if (!$image) {
        throw new Exception('Failed to generate QR code');
    }

    // Save the image
    imagepng($image, $filepath);

    // Reload image for compositing (logo / center text)
    $image = imagecreatefrompng($filepath);
    if ($image === false) {
        throw new Exception('Failed to load QR code image for compositing');
    }

    $imageWidth = imagesx($image);
    $imageHeight = imagesy($image);

    // If a logo (data URL) was uploaded, composite it in the center
    $logoData = $_POST['logo'] ?? '';
    if (!empty($logoData)) {
        // extract base64
        if (preg_match('/^data:image\/([a-zA-Z]+);base64,/', $logoData)) {
            $base64 = preg_replace('/^data:image\/[a-zA-Z]+;base64,/', '', $logoData);
            $decoded = base64_decode($base64);
        } else {
            $decoded = base64_decode($logoData);
        }

        if ($decoded !== false) {
            $logoImg = @imagecreatefromstring($decoded);
            if ($logoImg !== false) {
                // determine target box (25% of smallest dimension) and padding
                $logoBox = (int)floor(min($imageWidth, $imageHeight) * 0.25);
                $padding = 10; // px padding inside the box
                $innerMax = max(1, $logoBox - 2 * $padding);

                $logoW = imagesx($logoImg);
                $logoH = imagesy($logoImg);

                // compute resized dimensions preserving aspect ratio
                if ($logoW > $logoH) {
                    $newW = $innerMax;
                    $newH = (int)floor($logoH * $newW / $logoW);
                } else {
                    $newH = $innerMax;
                    $newW = (int)floor($logoW * $newH / $logoH);
                }

                // center coordinates for the background box
                $bgX = (int)floor(($imageWidth - $logoBox) / 2);
                $bgY = (int)floor(($imageHeight - $logoBox) / 2);

                // draw white background rectangle (with padding)
                $white = imagecolorallocate($image, 255, 255, 255);
                imagefilledrectangle($image, $bgX, $bgY, $bgX + $logoBox, $bgY + $logoBox, $white);

                // destination coordinates for the resized logo (inside padding)
                $dstX = $bgX + $padding + (int)floor(($innerMax - $newW) / 2);
                $dstY = $bgY + $padding + (int)floor(($innerMax - $newH) / 2);

                // preserve transparency
                $resizedLogo = imagecreatetruecolor($newW, $newH);
                imagealphablending($resizedLogo, false);
                imagesavealpha($resizedLogo, true);
                $transparent = imagecolorallocatealpha($resizedLogo, 0, 0, 0, 127);
                imagefilledrectangle($resizedLogo, 0, 0, $newW, $newH, $transparent);

                imagecopyresampled($resizedLogo, $logoImg, 0, 0, 0, 0, $newW, $newH, $logoW, $logoH);

                // composite onto QR image
                imagecopy($image, $resizedLogo, $dstX, $dstY, 0, 0, $newW, $newH);

                imagedestroy($logoImg);
                imagedestroy($resizedLogo);
            }
        }
    }

    // If there's a center text, add it to the image (white background, padding, bold-ish)
    if (!empty($centerText)) {
        // Parse color (expect format like #RRGGBB)
        $colorHex = ltrim($centerTextColor, '#');
        if (strlen($colorHex) === 6) {
            $r = hexdec(substr($colorHex, 0, 2));
            $g = hexdec(substr($colorHex, 2, 2));
            $b = hexdec(substr($colorHex, 4, 2));
        } else {
            $r = $g = $b = 0;
        }

        // pick a TTF font if available (prefer system Arial on Windows)
        $fontPath = null;
        $candidates = [
            'C:\\Windows\\Fonts\\arial.ttf',
            __DIR__ . '/fonts/arial.ttf',
            __DIR__ . '/fonts/DejaVuSans.ttf'
        ];
        foreach ($candidates as $c) {
            if (file_exists($c)) {
                $fontPath = $c;
                break;
            }
        }

        $padding = 10; // padding around text background

        if ($fontPath !== null) {
            // determine font size so text fits within ~70% width
            $maxWidth = (int)floor($imageWidth * 0.7) - 2 * $padding;
            $fontSize = (int)floor($imageHeight * 0.06);
            if ($fontSize < 8) $fontSize = 8;
            // adjust down until fits
            while ($fontSize > 6) {
                $bbox = imagettfbbox($fontSize, 0, $fontPath, $centerText);
                $textW = abs($bbox[2] - $bbox[0]);
                $textH = abs($bbox[5] - $bbox[1]);
                if ($textW <= $maxWidth) break;
                $fontSize--;
            }

            $bgW = $textW + 2 * $padding;
            $bgH = $textH + 2 * $padding;
            $bgX = (int)floor(($imageWidth - $bgW) / 2);
            $bgY = (int)floor(($imageHeight - $bgH) / 2);

            // draw white background
            $white = imagecolorallocate($image, 255, 255, 255);
            imagefilledrectangle($image, $bgX, $bgY, $bgX + $bgW, $bgY + $bgH, $white);

            // draw text centered; emulate bold by drawing twice
            $textX = (int)($imageWidth / 2 - $textW / 2);
            $textY = (int)($imageHeight / 2 + $textH / 2 - $padding / 2);
            $textColor = imagecolorallocate($image, $r, $g, $b);
            imagettftext($image, $fontSize, 0, $textX, $textY, $textColor, $fontPath, $centerText);
            // emulate heavier weight
            imagettftext($image, $fontSize, 0, $textX + 1, $textY, $textColor, $fontPath, $centerText);
        } else {
            // fallback to built-in font
            $font = 5; // built-in
            $textWidth = strlen($centerText) * imagefontwidth($font);
            $textHeight = imagefontheight($font);
            $bgW = $textWidth + 2 * $padding;
            $bgH = $textHeight + 2 * $padding;
            $bgX = (int)floor(($imageWidth - $bgW) / 2);
            $bgY = (int)floor(($imageHeight - $bgH) / 2);
            $white = imagecolorallocate($image, 255, 255, 255);
            imagefilledrectangle($image, $bgX, $bgY, $bgX + $bgW, $bgY + $bgH, $white);
            $textColor = imagecolorallocate($image, $r, $g, $b);
            $textX = max(0, (int)($imageWidth / 2 - ($textWidth / 2)));
            $textY = max(0, (int)($imageHeight / 2 - ($textHeight / 2)));
            // draw twice to emphasize weight
            imagestring($image, $font, $textX, $textY, $centerText, $textColor);
            imagestring($image, $font, $textX + 1, $textY, $centerText, $textColor);
        }

        // save and free
        imagepng($image, $filepath);
        imagedestroy($image);
    } else {
        // no center text; just clean up
        imagepng($image, $filepath);
        imagedestroy($image);
    }

    // Use relative path for browser access
    $qrCodeUrl = 'uploads/qr-codes/' . $filename;

    // Save to database
    $stmt = $pdo->prepare("
        INSERT INTO qr_codes 
        (user_id, qr_type, data_type, content, qr_image_path, update_later, dynamic_tracking) 
        VALUES 
        (?, 'qr', ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $userId,
        $type,
        $data,
        $qrCodeUrl,
        $updateLater ? 1 : 0,
        $dynamicTracking ? 1 : 0
    ]);

    $qrCodeId = $pdo->lastInsertId();

    // Return success response
    echo json_encode([
        'success' => true,
        'qrCodeId' => $qrCodeId,
        'qrCodeUrl' => $qrCodeUrl,
        'message' => 'QR Code generated successfully'
    ]);
} catch (Exception $e) {
    error_log("Error generating QR code: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Failed to generate QR code: ' . $e->getMessage()
    ]);
}
