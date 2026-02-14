<?php

/**
 * Database Configuration
 * Update these values with your MySQL database credentials
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'qr_generator');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

/**
 * Create Database Connection
 */
function getDbConnection()
{
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        return null;
    }
}

/**
 * Application Settings
 */
define('QR_CODE_UPLOAD_DIR', __DIR__ . '/uploads/qr-codes/');
define('BARCODE_UPLOAD_DIR', __DIR__ . '/uploads/bar-codes/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'pdf', 'docx', 'mp3', 'mp4']);

/**
 * Create necessary directories if they don't exist
 */
if (!file_exists(QR_CODE_UPLOAD_DIR)) {
    mkdir(QR_CODE_UPLOAD_DIR, 0755, true);
}

if (!file_exists(BARCODE_UPLOAD_DIR)) {
    mkdir(BARCODE_UPLOAD_DIR, 0755, true);
}

/**
 * Session Configuration
 */
session_start();

/**
 * Error Reporting (Disable in production)
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Timezone
 */
date_default_timezone_set('UTC');
