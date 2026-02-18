<?php
require_once __DIR__ . '/config.php';

// Fetch logged-in user and their codes
$currentUser = null;
$userQRCodes = [];
$userBarcodes = [];

if (!empty($_SESSION['user_id'])) {
    $pdo = getDbConnection();
    if ($pdo) {
        $stmt = $pdo->prepare('SELECT id, username, email FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$_SESSION['user_id']]);
        $currentUser = $stmt->fetch();

        // Fetch QR and barcode entries for this user
        $stmt2 = $pdo->prepare('SELECT id, qr_type, data_type, content, qr_image_path, scan_count, created_at FROM qr_codes WHERE user_id = ? ORDER BY created_at DESC');
        $stmt2->execute([$_SESSION['user_id']]);
        $codes = $stmt2->fetchAll();
        foreach ($codes as $c) {
            if ($c['qr_type'] === 'barcode') {
                $userBarcodes[] = $c;
            } else {
                $userQRCodes[] = $c;
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Primary Meta Tags -->
    <title>Free QR Code & Barcode Generator Online | MakeCode - Create Custom QR Codes</title>
    <meta name="title" content="Free QR Code & Barcode Generator Online | MakeCode - Create Custom QR Codes">
    <meta name="description" content="Generate free QR codes and barcodes online instantly. Create custom QR codes for URLs, WiFi, vCard, email, SMS, location, and more. Download high-resolution codes in PNG/JPEG. 100% free with no signup required.">
    <meta name="keywords" content="QR code generator, free QR code, barcode generator, create QR code, custom QR code, QR code maker, online QR generator, WiFi QR code, vCard QR code, URL QR code, QR code with logo, batch barcode generator, Code 128, EAN-13, UPC-A">
    <meta name="author" content="Mathew Kings">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://yourdomain.com/">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://yourdomain.com/">
    <meta property="og:title" content="Free QR Code & Barcode Generator Online | MakeCode">
    <meta property="og:description" content="Generate free QR codes and barcodes online instantly. Create custom QR codes for URLs, WiFi, vCard, email, SMS, and more. Download high-resolution codes in PNG/JPEG.">
    <meta property="og:image" content="https://yourdomain.com/images/og-image.jpg">
    <meta property="og:site_name" content="MakeCode">
    <meta property="og:locale" content="en_US">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="https://yourdomain.com/">
    <meta name="twitter:title" content="Free QR Code & Barcode Generator Online | MakeCode">
    <meta name="twitter:description" content="Generate free QR codes and barcodes online instantly. Create custom QR codes for URLs, WiFi, vCard, email, SMS, and more.">
    <meta name="twitter:image" content="https://yourdomain.com/images/twitter-card.jpg">
    <meta name="twitter:creator" content="@mathewkings9">
    
    <!-- Structured Data / Schema.org -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebApplication",
      "name": "MakeCode QR Code & Barcode Generator",
      "description": "Free online QR code and barcode generator with customization options. Create QR codes for URLs, WiFi, vCard, email, SMS, location, and more.",
      "url": "https://yourdomain.com/",
      "applicationCategory": "UtilityApplication",
      "operatingSystem": "Web Browser",
      "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "USD"
      },
      "author": {
        "@type": "Person",
        "name": "Mathew Kings",
        "url": "https://mkportfolio.crestdigico.com",
        "sameAs": [
          "https://linkedin.com/in/mathew-kings",
          "https://twitter.com/mathewkings9"
        ]
      },
      "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "4.8",
        "ratingCount": "1250"
      },
      "featureList": "QR Code Generation, Barcode Generation, Custom Logo Upload, Color Customization, Batch Processing, WiFi QR Codes, vCard QR Codes, URL Shortening, Scan Tracking"
    }
    </script>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    
    <!-- Fonts & Styles -->
    <link href="https://fonts.cdnfonts.com/css/bignoodletitling" rel="stylesheet">
    <style>
        @import url('https://fonts.cdnfonts.com/css/bignoodletitling');
    </style>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container nav-container">
            <!-- Logo -->
            <div class="logo">
                <img src="images/logo.png" alt="MakeCode logo" width="80px">
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <!-- Navigation Links -->
            <div class="nav-links" id="navLinks">
                <a href="#" class="nav-link" id="whatIsQRBtn">What is a QR Code</a>
                <a href="#" class="nav-link" id="howToUseBtn">How to use</a>
                <a href="#" class="nav-link">Scan Code</a>
                <?php if (!$currentUser): ?>
                    <a href="register.php" class="nav-link">Register</a>
                    <a href="login.php" class="btn btn-login">Login</a>
                <?php else: ?>
                    <span class="nav-link">Hi, <?php echo htmlspecialchars($currentUser['username']); ?></span>
                    <a href="logout.php" class="btn btn-login">Logout</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-container">
            <h1 class="hero-title bignoodletitling">
                CREATE CUSTOM QR CODES<br>
                AND BARCODES IN SECONDS.
            </h1>

            <p class="hero-subtitle">
                Generate high-resolution, scannable codes for products, links, Wi-Fi, and more.
            </p>
            <p class="hero-subtitle">
                <span class="text-green">100%</span> free and fully customizable.
            </p>

            <div class="hero-buttons">
                <button class="btn btn-primary" id="startCreatingBtn">Start Creating for Free</button>
                <button class="btn btn-secondary" id="discoverFeaturesBtn">Discover Features</button>
            </div>
        </div>
    </section>

    <!-- QR Code Generator Section -->
    <section class="generator-section">
        <div class="generator-container">
            <!-- Tabs -->
            <div class="tabs">
                <button class="tab tab-active" data-tab="qr">QR CODE</button>
                <div class="tab-divider">|</div>
                <button class="tab" data-tab="bar">BAR CODE</button>
            </div>

            <!-- Main Content Area -->
            <div class="generator-grid">
                <!-- Left Panel - Form -->
                <div class="generator-form">
                    <!-- Tab Headers -->
                    <div class="form-tabs">
                        <button class="form-tab form-tab-active" data-step="1">Data Type</button>
                        <button class="form-tab" data-step="2">Destination</button>
                        <button class="form-tab" data-step="3">Style</button>
                        <button class="form-tab" data-step="4">Finish</button>
                    </div>

                    <!-- STEP 1: Data Type Selection -->
                    <div class="form-content" id="step1" style="display: block;">
                        <!-- QR CODE MODE CONTENT -->
                        <div class="qr-mode">
                            <h2>QR Code Creator</h2>
                            <p class="form-description">Select the type of data you want to store in the QR Code</p>

                            <div class="data-type-grid">
                                <button class="data-type-btn" data-type="url">
                                    <span class="data-icon">🔗</span>
                                    <span>URL</span>
                                </button>
                                <button class="data-type-btn" data-type="email">
                                    <span class="data-icon">✉️</span>
                                    <span>Email</span>
                                </button>
                                <button class="data-type-btn" data-type="phone">
                                    <span class="data-icon">📞</span>
                                    <span>Phone</span>
                                </button>
                                <button class="data-type-btn" data-type="sms">
                                    <span class="data-icon">💬</span>
                                    <span>SMS</span>
                                </button>
                                <button class="data-type-btn" data-type="text">
                                    <span class="data-icon">📝</span>
                                    <span>Plain Text</span>
                                </button>
                                <button class="data-type-btn" data-type="location">
                                    <span class="data-icon">📍</span>
                                    <span>Location</span>
                                </button>
                                <button class="data-type-btn" data-type="wifi">
                                    <span class="data-icon">📶</span>
                                    <span>Wifi Login</span>
                                </button>
                                <button class="data-type-btn" data-type="social">
                                    <span class="data-icon">🔗</span>
                                    <span>Social Link</span>
                                </button>
                                <button class="data-type-btn" data-type="meet">
                                    <span class="data-icon">📹</span>
                                    <span>Google Meet</span>
                                </button>
                            </div>

                            <!-- Bottom Options -->
                            <div class="form-options">
                                <div class="option-group">
                                    <input type="checkbox" id="updateLater" class="checkbox">
                                    <label for="updateLater" class="option-label">Update later</label>
                                    <button class="info-btn" title="Information">
                                        <svg class="info-icon" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="option-group">
                                    <input type="checkbox" id="dynamicTracking" class="checkbox">
                                    <label for="dynamicTracking" class="option-label">Dynamic tracking</label>
                                    <button class="info-btn" title="Information">
                                        <svg class="info-icon" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- BARCODE MODE CONTENT -->
                        <div class="barcode-mode" style="display: none;">
                            <h2>Bar Code Creator</h2>
                            <p class="form-description">Create a barcode project / batch</p>

                            <label class="input-field-label">Project / Group Name</label>
                            <input type="text" id="barcodeProjectName" class="text-input"
                                placeholder="e.g. Product-Run-Summer-2024">

                            <label class="input-field-label" style="margin-top:8px;">Barcode Symbology</label>
                            <select id="barcodeSymbology" class="text-input">
                                <option value="code128">Code 128 (recommended)</option>
                                <option value="code39">Code 39</option>
                                <option value="upc-a">UPC-A</option>
                                <option value="ean13">EAN-13</option>
                            </select>

                            <p class="form-description" style="margin-top:12px;">Data Source</p>
                            <div style="display:flex;gap:12px;align-items:center;">
                                <label><input type="radio" name="barcodeSourceMode" value="counter" checked> Counter
                                    (sequence)</label>
                                <label><input type="radio" name="barcodeSourceMode" value="file"> File (upload .txt or
                                    .csv)</label>
                            </div>

                            <div id="barcodeCounterMode" style="margin-top:12px;">
                                <label class="input-field-label">Prefix</label>
                                <input type="text" id="barcodePrefix" class="text-input" placeholder="e.g. PRD">

                                <div style="display:flex;gap:8px;margin-top:8px;">
                                    <div style="flex:1;">
                                        <label class="input-field-label">Start</label>
                                        <input type="number" id="barcodeStart" class="text-input" value="1001">
                                    </div>
                                    <div style="flex:1;">
                                        <label class="input-field-label">Increment</label>
                                        <input type="number" id="barcodeIncrement" class="text-input" value="1">
                                    </div>
                                    <div style="flex:1;">
                                        <label class="input-field-label">Count</label>
                                        <input type="number" id="barcodeCount" class="text-input" value="10" min="1">
                                    </div>
                                </div>
                            </div>

                            <div id="barcodeFileMode" style="display:none;margin-top:12px;">
                                <label class="input-field-label">Upload data file (.txt, .csv)</label>
                                <input type="file" id="barcodeDataFile" class="file-input" accept=".txt,.csv">
                                <p class="input-hint">One data value per line. For UPC/EAN provide numeric values (11/12
                                    digits).</p>
                            </div>

                            <div style="margin-top:12px;display:flex;gap:8px;">
                                <button class="btn btn-primary" id="barcodeToDestinationBtn">Next: Destination</button>
                                <button class="btn" id="barcodePreviewSamplesBtn">Preview Sample</button>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: Destination (URL Input) -->
                    <div class="form-content" id="step2" style="display: none;">
                        <!-- QR CODE DESTINATION -->
                        <div class="qr-mode">
                            <div class="step-header">
                                <button class="back-btn" id="backToDataType">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Data Type</span>
                                </button>
                            </div>

                            <div class="destination-form">
                                <!-- URL -->
                                <div class="dest-block" id="dest-url" style="display:block;">
                                    <label class="input-field-label">URL <span class="required">*</span></label>
                                    <input type="text" id="urlInput" class="text-input" placeholder="https://" value="">
                                    <p class="input-hint">Insert a URL to a Website or a Page. e.g. https://makecode.com</p>
                                </div>

                                <!-- Email -->
                                <div class="dest-block" id="dest-email" style="display:none;">
                                    <label class="input-field-label">To (Email) <span class="required">*</span></label>
                                    <input type="email" id="emailTo" class="text-input" placeholder="recipient@example.com">

                                    <label class="input-field-label">Cc</label>
                                    <input type="text" id="emailCc" class="text-input" placeholder="cc@example.com, another@example.com">

                                    <label class="input-field-label">Bcc</label>
                                    <input type="text" id="emailBcc" class="text-input" placeholder="bcc@example.com">

                                    <label class="input-field-label">Subject (max 200 chars)</label>
                                    <input type="text" id="emailSubject" class="text-input" maxlength="200">

                                    <label class="input-field-label">Message (max 1000 chars)</label>
                                    <textarea id="emailBody" class="text-input" maxlength="1000" rows="6"></textarea>
                                </div>

                                <!-- Phone -->
                                <div class="dest-block" id="dest-phone" style="display:none;">
                                    <label class="input-field-label">Phone Number <span class="required">*</span></label>
                                    <input type="tel" id="phoneNumber" class="text-input" placeholder="+1234567890">
                                </div>

                                <!-- SMS -->
                                <div class="dest-block" id="dest-sms" style="display:none;">
                                    <label class="input-field-label">Phone Number <span class="required">*</span></label>
                                    <input type="tel" id="smsNumber" class="text-input" placeholder="+1234567890">

                                    <label class="input-field-label">Message (max 1000 chars)</label>
                                    <textarea id="smsBody" class="text-input" maxlength="1000" rows="4"></textarea>
                                </div>

                                <!-- Plain Text -->
                                <div class="dest-block" id="dest-text" style="display:none;">
                                    <label class="input-field-label">Plain Text <span class="required">*</span></label>
                                    <textarea id="plainText" class="text-input" rows="6"></textarea>
                                </div>

                                <!-- Location (URL) -->
                                <div class="dest-block" id="dest-location" style="display:none;">
                                    <label class="input-field-label">Google Maps URL <span class="required">*</span></label>
                                    <input type="text" id="locationUrl" class="text-input" placeholder="https://maps.google.com/...?q=...">
                                </div>

                                <!-- Wifi -->
                                <div class="dest-block" id="dest-wifi" style="display:none;">
                                    <label class="input-field-label">SSID <span class="required">*</span></label>
                                    <input type="text" id="wifiSsid" class="text-input">

                                    <label class="input-field-label">Visibility</label>
                                    <select id="wifiHidden" class="text-input">
                                        <option value="false">Visible</option>
                                        <option value="true">Hidden</option>
                                    </select>

                                    <label class="input-field-label">Password</label>
                                    <input type="text" id="wifiPassword" class="text-input">

                                    <label class="input-field-label">Security</label>
                                    <select id="wifiSecurity" class="text-input">
                                        <option value="WPA">WPA</option>
                                        <option value="WPA2">WPA2</option>
                                        <option value="WEP">WEP</option>
                                        <option value="nopass">No encryption</option>
                                    </select>
                                </div>

                                <!-- Social -->
                                <div class="dest-block" id="dest-social" style="display:none;">
                                    <label class="input-field-label">Page Name</label>
                                    <input type="text" id="socialName" class="text-input" placeholder="e.g. My Page">

                                    <label class="input-field-label">URL <span class="required">*</span></label>
                                    <input type="text" id="socialUrl" class="text-input" placeholder="https://facebook.com/yourpage">
                                </div>

                                <!-- Google Meet -->
                                <div class="dest-block" id="dest-meet" style="display:none;">
                                    <label class="input-field-label">Google Meet URL <span class="required">*</span></label>
                                    <input type="text" id="meetUrl" class="text-input" placeholder="https://meet.google.com/xxx-xxxx-xxx">
                                </div>

                                <button class="btn btn-primary btn-full" style="margin-top: 10px;" id="styleYourQRBtn">Style Your QR Code</button>
                            </div>
                        </div>

                        <!-- BARCODE DESTINATION -->
                        <div class="barcode-mode" style="display:none;">
                            <div class="step-header">
                                <button class="back-btn" id="barcodeBackToDataType">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Data Type</span>
                                </button>
                            </div>

                            <p class="form-description">Preview data for this batch</p>
                            <div id="barcodeDataPreview"
                                style="background:#fafafa;border:1px solid #eee;padding:8px;max-height:160px;overflow:auto;">
                                No data selected yet.</div>

                            <div style="margin-top:12px;display:flex;gap:8px;">
                                <button class="btn btn-primary btn-full" id="styleYourBarcodeBtn">Style Your
                                    Barcode</button>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3: Style Your QR Code -->
                    <div class="form-content" id="step3" style="display: none;">
                        <!-- QR CODE STYLE -->
                        <div class="qr-mode">
                            <div class="step-header">
                                <button class="back-btn" id="backToDestination">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Style Your QR Code</span>
                                </button>
                            </div>

                            <p class="form-description">Customize QR Code with colors, logos & shapes</p>

                            <div class="style-section">
                                <h3 class="style-section-title">Add Your Logo</h3>
                                <p class="style-section-desc">This will appear in the center of your QR Code</p>
                                <p class="style-section-note">File size can only be upto 5mb.</p>

                                <div class="logo-upload-area" style="position:relative;">
                                    <!-- Replace SVG with PNG icon -->
                                    <img id="styleUploadIcon" src="images/check_files.png" alt="upload icon" style="width:72px;height:72px;object-fit:contain;opacity:0.9;">
                                    <div style="display:block;margin-top:8px;">
                                        <p class="upload-text">Drag & Drop your file here</p>
                                        <p class="upload-subtext">JPEG, PNG file accepted</p>
                                    </div>
                                    <input type="file" id="logoUpload" class="file-input" accept="image/jpeg,image/png">
                                    <!-- Preview for uploaded logo -->
                                    <img id="logoUploadPreview" src="" alt="logo preview" style="display:none;position:absolute;top:8px;right:8px;width:80px;height:80px;object-fit:contain;border-radius:6px;border:1px solid #eee;background:#fff;padding:4px;">
                                </div>
                                <p class="style-note">* Your logo will be automatically centered and optimally re-sized.</p>

                                <div class="divider-text">OR</div>

                                <h3 class="style-section-title">Add a Center Text</h3>
                                <p class="style-section-desc">This will appear in the center of your QR Code</p>

                                <input type="text" id="centerText" class="text-input" placeholder="" maxlength="20">

                                <div class="color-picker-section">
                                    <label class="color-label">Center Text Color</label>
                                    <div class="color-input-group">
                                        <input type="color" id="centerTextColor" class="color-picker" value="#00d084">
                                        <input type="text" id="centerTextColorHex" class="color-hex-input" value="#000000"
                                            maxlength="7">
                                    </div>
                                </div>

                                <button class="btn btn-primary btn-full" id="finishDownloadBtn">Finish & Download</button>
                            </div>
                        </div>

                        <!-- BARCODE STYLE -->
                        <div class="barcode-mode" style="display:none;">
                            <div class="step-header">
                                <button class="back-btn" id="backToBarcodeDestination">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Destination</span>
                                </button>
                            </div>

                            <p class="form-description">Customize barcode appearance</p>

                            <div class="setting-group">
                                <label class="setting-label">Barcode Size Preset</label>
                                <select id="barcodeSizePreset" class="text-input">
                                    <option value="basic_size">Standard (16.5mm × 11mm / ~222×122px)</option>
                                    <option value="standard">Standard (37mm × 26mm / ~440×306px)</option>
                                    <option value="small">Small (25mm × 18mm / ~295×212px)</option>
                                    <option value="large">Large (50mm × 35mm / ~590×413px)</option>
                                    <option value="custom">Custom Size</option>
                                </select>
                            </div>

                            <div class="setting-group" id="customSizeInputs" style="display:none;">
                                <label class="setting-label">Custom Size (width x height)</label>
                                <div style="display:flex;gap:8px;">
                                    <input type="number" id="barcodeWidth" class="size-input" value="222" min="100">
                                    <input type="number" id="barcodeHeight" class="size-input" value="122" min="20">
                                </div>
                            </div>

                            <div class="setting-group">
                                <label class="setting-label">Colors</label>
                                <div style="display:flex;gap:8px;align-items:center;">
                                    <input type="color" id="barcodeFgColor" value="#000000">
                                    <input type="color" id="barcodeBgColor" value="#ffffff">
                                </div>
                            </div>

                            <div class="setting-group">
                                <label class="setting-label">Human-readable text size</label>
                                <input type="number" id="barcodeFontSize" class="size-input" value="12" min="8"
                                    max="36">
                            </div>

                            <div class="setting-group">
                                <label class="setting-label">Duplicate prevention</label>
                                <div class="toggle-option">
                                    <input type="checkbox" id="barcodePreventDup" class="toggle-checkbox">
                                    <label for="barcodePreventDup" class="toggle-label">Prevent duplicates across
                                        history</label>
                                </div>
                            </div>

                            <div style="display:flex;gap:8px;margin-top:12px;">
                                <button class="btn btn-primary btn-full" id="finishGenerateBarBtn">Generate &
                                    Download</button>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 4: Finish & Download -->
                    <div class="form-content" id="step4" style="display: none;">
                        <!-- QR CODE FINISH -->
                        <div class="qr-mode">
                            <div class="step-header">
                                <button class="back-btn" id="backToStyle">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Finish & Download</span>
                                </button>
                            </div>

                            <p class="form-description">You can now download your QR Code</p>

                            <div class="account-prompt">
                                <p>Keep track of QR Codes and Bar Codes easily in one place via a free account you can get
                                    start by creating a free account here, <a href="#" class="link-green">create account</a>
                                </p>
                            </div>

                            <!-- Standard Download View -->
                            <div id="standardDownload" style="display: block;">
                                <div class="download-settings-header">
                                    <h3 class="settings-title">QR Code Settings</h3>
                                    <p class="settings-subtitle">Standard Download Settings</p>
                                    <button class="change-link" id="showCustomSettings">Change</button>
                                </div>

                                <div class="download-buttons">
                                    <button class="btn btn-download" id="downloadJPEG">Download JPEG</button>
                                    <button class="btn btn-download" id="downloadPNG">Download PNG</button>
                                </div>
                            </div>

                            <!-- Custom Download Settings -->
                            <div id="customDownload" style="display: none;">
                                <h3 class="settings-title">QR Code Settings</h3>
                                <p class="settings-subtitle">Custom Download Settings</p>

                                <div class="custom-settings">
                                    <div class="setting-group">
                                        <label class="setting-label">Size & Resolution</label>
                                        <div class="size-inputs">
                                            <input type="number" id="qrSize" class="size-input" value="400" min="100"
                                                max="2000">
                                            <span class="size-unit">px</span>
                                            <input type="number" id="qrDPI" class="size-input" value="72" min="72"
                                                max="300">
                                            <span class="size-unit">dpi</span>
                                        </div>
                                    </div>

                                    <div class="setting-group">
                                        <label class="setting-label">Password</label>
                                        <div class="toggle-option">
                                            <input type="checkbox" id="enablePassword" class="toggle-checkbox">
                                            <label for="enablePassword" class="toggle-label">Enable Password
                                                Protection</label>
                                        </div>
                                    </div>

                                    <div class="setting-group">
                                        <label class="setting-label">Pause</label>
                                        <div class="toggle-option">
                                            <input type="checkbox" id="enablePause" class="toggle-checkbox">
                                            <label for="enablePause" class="toggle-label">Enable Pause</label>
                                        </div>
                                    </div>

                                    <div class="setting-group">
                                        <label class="setting-label">Scan Limit</label>
                                        <div class="toggle-option">
                                            <input type="checkbox" id="enforceScanLimit" class="toggle-checkbox">
                                            <label for="enforceScanLimit" class="toggle-label">Enforce Scan Limit</label>
                                        </div>
                                    </div>

                                    <button class="save-changes-btn">Save Changes</button>
                                </div>

                                <div class="download-buttons">
                                    <button class="btn btn-download" id="downloadJPEGCustom">Download JPEG</button>
                                    <button class="btn btn-download" id="downloadPNGCustom">Download PNG</button>
                                </div>
                            </div>
                        </div>

                        <!-- BARCODE FINISH -->
                        <div class="barcode-mode" style="display:none;">
                            <div class="step-header">
                                <button class="back-btn" id="backToBarcodeStyle">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Style</span>
                                </button>
                            </div>

                            <h3 class="settings-title">Barcode Output</h3>
                            <p class="settings-subtitle">Preview and download your barcode(s)</p>

                            <div style="display:flex;gap:8px;">
                                <button class="btn btn-download" id="downloadBarcodePNG">Download Batch Zip</button>
                                <a href="#" id="downloadBarcodeZipLink" style="display:none; text-align: center;"
                                    class="btn btn-download">Download ZIP</a>
                            </div>

                            <div id="barcodeFinishPreview" class="barcode-thumbs" style="margin:8px 0;">
                                <!-- image(s) will be inserted here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Panel - QR Code Preview -->
                <div class="qr-preview">
                    <div class="qr-code" id="qrCode">
                        <svg viewBox="0 0 100 100" class="qr-svg">
                            <rect x="0" y="0" width="25" height="25" fill="black" />
                            <rect x="5" y="5" width="15" height="15" fill="white" />
                            <rect x="75" y="0" width="25" height="25" fill="black" />
                            <rect x="80" y="5" width="15" height="15" fill="white" />
                            <rect x="0" y="75" width="25" height="25" fill="black" />
                            <rect x="5" y="80" width="15" height="15" fill="white" />

                            <!-- Simplified QR pattern -->
                            <rect x="30" y="5" width="5" height="5" fill="black" />
                            <rect x="40" y="5" width="5" height="5" fill="black" />
                            <rect x="35" y="10" width="5" height="5" fill="black" />
                            <rect x="45" y="10" width="5" height="5" fill="black" />
                            <rect x="30" y="15" width="10" height="5" fill="black" />
                            <rect x="50" y="15" width="5" height="5" fill="black" />
                            <rect x="60" y="10" width="5" height="5" fill="black" />
                            <rect x="65" y="15" width="5" height="5" fill="black" />

                            <rect x="5" y="30" width="5" height="5" fill="black" />
                            <rect x="15" y="35" width="5" height="5" fill="black" />
                            <rect x="10" y="40" width="10" height="5" fill="black" />
                            <rect x="5" y="50" width="5" height="10" fill="black" />
                            <rect x="15" y="55" width="5" height="5" fill="black" />

                            <rect x="30" y="30" width="40" height="40" fill="black" />
                            <rect x="35" y="35" width="10" height="10" fill="white" />
                            <rect x="55" y="35" width="10" height="10" fill="white" />
                            <rect x="45" y="50" width="10" height="10" fill="white" />
                            <rect x="35" y="55" width="5" height="5" fill="black" />
                            <rect x="60" y="60" width="5" height="5" fill="black" />

                            <rect x="80" y="30" width="5" height="5" fill="black" />
                            <rect x="85" y="35" width="5" height="10" fill="black" />
                            <rect x="75" y="45" width="10" height="5" fill="black" />
                            <rect x="90" y="50" width="5" height="5" fill="black" />
                            <rect x="80" y="55" width="5" height="10" fill="black" />

                            <rect x="30" y="80" width="5" height="5" fill="black" />
                            <rect x="40" y="75" width="10" height="5" fill="black" />
                            <rect x="35" y="85" width="5" height="5" fill="black" />
                            <rect x="45" y="90" width="10" height="5" fill="black" />
                            <rect x="60" y="80" width="5" height="10" fill="black" />
                            <rect x="70" y="85" width="5" height="5" fill="black" />
                            <rect x="75" y="75" width="10" height="5" fill="black" />
                            <rect x="85" y="80" width="5" height="5" fill="black" />
                            <rect x="90" y="90" width="5" height="5" fill="black" />
                        </svg>
                    </div>

                    <!-- Barcode preview (shown when BAR tab active) -->
                    <div id="barcodePreview" style="display:none; text-align:center; padding:16px;">
                        <div id="barcodePreviewCanvas">No barcode yet</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if (!$currentUser): ?>
        <!-- How To Section -->
        <section class="howto-section">
            <div class="howto-container">
                <h2 class="howto-title bignoodletitling">
                    HOW TO CREATE A SCAN CODE WITH MAKECODE
                </h2>

                <p class="howto-subtitle">
                    It only takes 4 steps to create a QR code with our online generator,<br>
                    making it one of the fastest and easiest to use.
                </p>

                <!-- Steps -->
                <div class="steps">
                    <div class="step">
                        <h3 class="step-title">STEP 1</h3>
                        <p class="step-description">Select the type of QR or Bar code you want to create</p>
                    </div>

                    <div class="step">
                        <h3 class="step-title">STEP 2</h3>
                        <p class="step-description">Add the information on the type of QR or Bar code you want to generate
                        </p>
                    </div>

                    <div class="step">
                        <h3 class="step-title">STEP 3</h3>
                        <p class="step-description">Personalize your QR or Bar Code</p>
                    </div>

                    <div class="step step-last">
                        <h3 class="step-title">STEP 4</h3>
                        <p class="step-description">Download and share your QR or Bar code</p>
                    </div>
                </div>
            </div>
        </section>
    <?php else: ?>
        <!-- User Dashboard: show user's QR and Barcodes -->
        <section class="howto-section">
            <div class="howto-container">
                <h2 class="howto-title bignoodletitling">Your Codes</h2>

                <p class="howto-subtitle">Below are your generated QR codes and barcodes.</p>

                <h3>QR Codes</h3>
                <?php if (count($userQRCodes) === 0): ?>
                    <p>No QR codes yet.</p>
                <?php else: ?>
                    <table style="width:100%;border-collapse:collapse;margin-bottom:16px;">
                        <thead>
                            <tr style="background:#f5f5f5;">
                                <th style="padding:8px;border:1px solid #ddd;text-align:left;">Preview</th>
                                <th style="padding:8px;border:1px solid #ddd;text-align:left;">Type</th>
                                <th style="padding:8px;border:1px solid #ddd;text-align:left;">Content</th>
                                <th style="padding:8px;border:1px solid #ddd;text-align:left;">Scans</th>
                                <th style="padding:8px;border:1px solid #ddd;text-align:left;">Created</th>
                                <th style="padding:8px;border:1px solid #ddd;text-align:left;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($userQRCodes as $qr): ?>
                                <tr>
                                    <td style="padding:8px;border:1px solid #ddd;"><?php if (!empty($qr['qr_image_path'])): ?>
                                            <img src="<?php echo htmlspecialchars($qr['qr_image_path']); ?>" alt="qr" style="height:48px;">
                                            <?php else: ?>N/A<?php endif; ?>
                                    </td>
                                    <td style="padding:8px;border:1px solid #ddd;"><?php echo htmlspecialchars($qr['data_type']); ?></td>
                                    <td style="padding:8px;border:1px solid #ddd;max-width:360px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;"><?php echo htmlspecialchars($qr['content']); ?></td>
                                    <td style="padding:8px;border:1px solid #ddd;"><?php echo (int)$qr['scan_count']; ?></td>
                                    <td style="padding:8px;border:1px solid #ddd;"><?php echo htmlspecialchars($qr['created_at']); ?></td>
                                    <td style="padding:8px;border:1px solid #ddd;text-align:center;">
                                        <button class="btn-delete-code" data-code-id="<?php echo (int)$qr['id']; ?>" style="padding:6px 12px;background-color:#dc3545;color:white;border:none;border-radius:4px;cursor:pointer;font-size:12px;">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <h3>Barcodes</h3>
                <?php if (count($userBarcodes) === 0): ?>
                    <p>No barcodes yet.</p>
                <?php else: ?>
                    <table style="width:100%;border-collapse:collapse;margin-bottom:16px;">
                        <thead>
                            <tr style="background:#f5f5f5;">
                                <th style="padding:8px;border:1px solid #ddd;text-align:left;">ID</th>
                                <th style="padding:8px;border:1px solid #ddd;text-align:left;">Type</th>
                                <th style="padding:8px;border:1px solid #ddd;text-align:left;">Content</th>
                                <th style="padding:8px;border:1px solid #ddd;text-align:left;">Created</th>
                                <th style="padding:8px;border:1px solid #ddd;text-align:left;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($userBarcodes as $b): ?>
                                <tr>
                                    <td style="padding:8px;border:1px solid #ddd;"><?php echo (int)$b['id']; ?></td>
                                    <td style="padding:8px;border:1px solid #ddd;"><?php echo htmlspecialchars($b['data_type']); ?></td>
                                    <td style="padding:8px;border:1px solid #ddd;max-width:360px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;"><?php echo htmlspecialchars($b['content']); ?></td>
                                    <td style="padding:8px;border:1px solid #ddd;"><?php echo htmlspecialchars($b['created_at']); ?></td>
                                    <td style="padding:8px;border:1px solid #ddd;text-align:center;">
                                        <button class="btn-delete-code" data-code-id="<?php echo (int)$b['id']; ?>" style="padding:6px 12px;background-color:#dc3545;color:white;border:none;border-radius:4px;cursor:pointer;font-size:12px;">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- Popup Modals -->
    <div id="whatIsQRModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" data-modal="whatIsQRModal">&times;</span>
            <h2>What is a QR Code & Barcode?</h2>
            <p><strong>QR Code (Quick Response Code)</strong> is a two-dimensional barcode that can store various types of data including URLs, text, contact information, WiFi credentials, and more. When scanned with a smartphone camera or QR reader, it instantly provides access to the encoded information.</p>
            <p><strong>Barcode</strong> is a one-dimensional code consisting of parallel lines of varying widths. Common types include Code 128, Code 39, EAN-13, and UPC-A. Barcodes are widely used in retail, inventory management, and product identification.</p>
            <p><strong>Key Differences:</strong></p>
            <ul>
                <li>QR codes can store more data (up to 4,296 characters)</li>
                <li>Barcodes are simpler and store numeric/alphanumeric data</li>
                <li>QR codes can be scanned from any angle</li>
                <li>Barcodes require horizontal scanning</li>
            </ul>
        </div>
    </div>

    <div id="howToUseModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" data-modal="howToUseModal">&times;</span>
            <h2>How to Use This System</h2>
            <h3>For QR Codes:</h3>
            <ol>
                <li><strong>Step 1 - Data Type:</strong> Select the type of data (URL, Email, Phone, SMS, Text, Location, WiFi, Social Link, or Google Meet)</li>
                <li><strong>Step 2 - Destination:</strong> Enter the content you want to encode (e.g., website URL, email address, phone number)</li>
                <li><strong>Step 3 - Style:</strong> Customize your QR code by uploading a logo or adding center text with color</li>
                <li><strong>Step 4 - Download:</strong> Download your QR code in JPEG or PNG format with standard or custom settings</li>
            </ol>
            <h3>For Barcodes:</h3>
            <ol>
                <li><strong>Step 1 - Setup:</strong> Choose barcode symbology (Code 128, Code 39, UPC-A, or EAN-13) and data source (Counter or File upload)</li>
                <li><strong>Step 2 - Preview:</strong> Review the data that will be encoded in your barcodes</li>
                <li><strong>Step 3 - Style:</strong> Select size preset (Standard, Small, Large, or Custom) and customize colors</li>
                <li><strong>Step 4 - Download:</strong> Generate and download your barcodes as individual files or batch ZIP</li>
            </ol>
        </div>
    </div>

    <div id="featuresModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" data-modal="featuresModal">&times;</span>
            <h2>Discover Our Unique Features</h2>
            <p>MakeCode is a professional QR code and barcode generator that stands out with its comprehensive features, making it an invaluable tool for individuals and agencies alike—all completely free!</p>
            <h3>Unique Features That Set Us Apart:</h3>
            <ul>
                <li><strong>Versatile Code Generation:</strong> Create both QR codes and barcodes with support for multiple data types including URLs, emails, phone numbers, SMS, plain text, locations, WiFi credentials, social media links, and Google Meet invites.</li>
                <li><strong>Advanced Customization:</strong> Personalize your codes with logos, center text, custom colors, and high-resolution outputs up to 2000px with adjustable DPI.</li>
                <li><strong>Batch Processing:</strong> Generate multiple barcodes at once using counter sequences or uploaded data files, perfect for inventory and large-scale projects.</li>
                <li><strong>File Upload Support:</strong> Embed files directly into QR codes or use data files for barcode generation.</li>
                <li><strong>Dynamic Tracking:</strong> Optional dynamic tracking for monitoring scan statistics and performance.</li>
                <li><strong>Professional Quality:</strong> High-resolution, scannable codes suitable for print and digital use.</li>
                <li><strong>User-Friendly Interface:</strong> Intuitive 4-step process with real-time previews and easy navigation.</li>
            </ul>
            <h3>Benefits for Individuals:</h3>
            <p>As a free tool, individuals can effortlessly create custom QR codes for personal branding, sharing contact information, linking to portfolios, or connecting to social media—all without any cost or registration barriers.</p>
            <h3>Benefits for Agencies:</h3>
            <p>Agencies can leverage our platform for marketing campaigns, inventory management, event ticketing, and product labeling. The batch generation and customization options enable professional-grade outputs that enhance brand presence and operational efficiency.</p>
            <p><strong>100% Free:</strong> No hidden fees, no premium tiers—just unlimited access to powerful QR and barcode generation tools.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script src="script.js"></script>
</body>

</html>