<?php

/**
 * Database Schema Setup
 * 
 * This script creates the complete database schema for MakeCode QR/Barcode Generator.
 * It creates clean tables with no pre-populated data.
 * 
 * Tables created:
 * - users (user authentication)
 * - qr_codes (QR codes and barcodes)
 * - qr_customizations (styling options)
 * - qr_scans (scan tracking for analytics)
 * - file_uploads (file attachments)
 * 
 * Run once to initialize the database.
 */

require_once 'config.php';

$pdo = getDbConnection();

if (!$pdo) {
    die("Database connection failed. Check your config.php settings.");
}

try {
    echo "<h3>Setting up MakeCode Database...</h3>";
    echo "<hr>";

    // Drop existing tables if they exist (for clean setup)
    // Uncomment the lines below to reset the database tables (WARNING: This will delete all data!)
    /*
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec("DROP TABLE IF EXISTS file_uploads");
    $pdo->exec("DROP TABLE IF EXISTS qr_scans");
    $pdo->exec("DROP TABLE IF EXISTS qr_customizations");
    $pdo->exec("DROP TABLE IF EXISTS qr_codes");
    $pdo->exec("DROP TABLE IF EXISTS users");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    echo "✓ Dropped existing tables<br>";
    */

    // ============================================
    // 1. Users Table
    // ============================================
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            KEY idx_email (email),
            KEY idx_username (username)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✓ Created table: users<br>";

    // ============================================
    // 2. QR Codes Table
    // ============================================
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS qr_codes (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            user_id INT(11) DEFAULT NULL,
            qr_type ENUM('qr','barcode') DEFAULT 'qr',
            data_type VARCHAR(50) NOT NULL,
            content LONGTEXT NOT NULL,
            qr_image_path VARCHAR(255) DEFAULT NULL,
            update_later TINYINT(1) DEFAULT 0,
            dynamic_tracking TINYINT(1) DEFAULT 0,
            scan_count INT(11) DEFAULT 0,
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY idx_user_id (user_id),
            KEY idx_data_type (data_type),
            KEY idx_created_at (created_at),
            CONSTRAINT qr_codes_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✓ Created table: qr_codes<br>";

    // ============================================
    // 3. QR Customizations Table
    // ============================================
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS qr_customizations (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            qr_code_id INT(11) NOT NULL,
            foreground_color VARCHAR(7) DEFAULT '#000000',
            background_color VARCHAR(7) DEFAULT '#FFFFFF',
            logo_path VARCHAR(255) DEFAULT NULL,
            size INT(11) DEFAULT 300,
            error_correction VARCHAR(1) DEFAULT 'M',
            KEY idx_qr_code_id (qr_code_id),
            CONSTRAINT qr_customizations_ibfk_1 FOREIGN KEY (qr_code_id) REFERENCES qr_codes (id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✓ Created table: qr_customizations<br>";

    // ============================================
    // 4. QR Scans Table (Analytics)
    // ============================================
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS qr_scans (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            qr_code_id INT(11) NOT NULL,
            ip_address VARCHAR(45) DEFAULT NULL,
            user_agent LONGTEXT DEFAULT NULL,
            location VARCHAR(100) DEFAULT NULL,
            scanned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            KEY idx_qr_code_id (qr_code_id),
            KEY idx_scanned_at (scanned_at),
            CONSTRAINT qr_scans_ibfk_1 FOREIGN KEY (qr_code_id) REFERENCES qr_codes (id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✓ Created table: qr_scans<br>";

    // ============================================
    // 5. File Uploads Table
    // ============================================
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS file_uploads (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            qr_code_id INT(11) DEFAULT NULL,
            original_filename VARCHAR(255) NOT NULL,
            stored_filename VARCHAR(255) NOT NULL,
            file_path VARCHAR(255) NOT NULL,
            file_type VARCHAR(50) DEFAULT NULL,
            file_size INT(11) DEFAULT NULL,
            uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            KEY idx_qr_code_id (qr_code_id),
            CONSTRAINT file_uploads_ibfk_1 FOREIGN KEY (qr_code_id) REFERENCES qr_codes (id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✓ Created table: file_uploads<br>";

    echo "<hr>";
    echo "<h3 style='color:green;'>✓ Database setup completed successfully!</h3>";
    echo "<p>All 5 tables have been created with clean (empty) data.</p>";
    echo "<p><strong>Tables installed:</strong></p>";
    echo "<ul>";
    echo "<li><strong>users</strong> - User authentication & profiles</li>";
    echo "<li><strong>qr_codes</strong> - QR codes and barcodes storage</li>";
    echo "<li><strong>qr_customizations</strong> - QR code styling options</li>";
    echo "<li><strong>qr_scans</strong> - Scan event tracking for analytics</li>";
    echo "<li><strong>file_uploads</strong> - File attachment metadata</li>";
    echo "</ul>";
    echo "<p><strong>Database:</strong> " . DB_NAME . "</p>";
    echo "<p><strong>Host:</strong> " . DB_HOST . "</p>";
    echo "<hr>";
    echo "<a href='index.php' style='color:blue;'>← Go back to index</a>";
} catch (PDOException $e) {
    echo "<h3 style='color:red;'>Error creating tables:</h3>";
    echo "<p style='color:red;'>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Error code: " . htmlspecialchars($e->getCode()) . "</p>";
    echo "<hr>";
    echo "<a href='index.php' style='color:blue;'>← Go back to index</a>";
} catch (Exception $e) {
    echo "<h3 style='color:red;'>Unexpected error:</h3>";
    echo "<p style='color:red;'>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<hr>";
    echo "<a href='index.php' style='color:blue;'>← Go back to index</a>";
}
