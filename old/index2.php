<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MakeCode - QR Code Generator</title>
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

            <!-- Navigation Links -->
            <div class="nav-links">
                <a href="#" class="nav-link">What is a QR Code</a>
                <a href="#" class="nav-link">How to use</a>
                <a href="#" class="nav-link">Scan Code</a>
                <a href="#" class="nav-link">Register</a>
                <button class="btn btn-login">Login</button>
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
                <button class="btn btn-primary">Start Creating for Free</button>
                <button class="btn btn-secondary">Discover Features</button>
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

                        <!-- BARCODE mode (hidden unless BAR tab is selected) -->
                        <div class="barcode-mode" style="display: none; margin-top: 16px;">
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

                        <!-- Input Area -->
                        <div class="input-area">
                            <p class="input-label">Enter your website link, text or or drop a file here</p>
                            <p class="input-sublabel">(Your QR code will be generated automatically)</p>

                            <div class="file-upload">
                                <svg class="upload-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M8 16A8 8 0 108 0a8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L5.707 7.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L9 8.586V5z" />
                                </svg>
                                <span>Upload any file (jpg, png, pdf, docx, mp3, mp4)</span>
                                <input type="file" id="fileUpload" class="file-input" multiple>
                            </div>
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

                    <!-- STEP 2: Destination (URL Input) -->
                    <div class="form-content" id="step2" style="display: none;">
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
                            <label class="input-field-label">URL <span class="required">*</span></label>
                            <input type="text" id="urlInput" class="text-input" placeholder="https://" value="">
                            <p class="input-hint">Insert a URL to a Website or a Page. e.g. https://makecode.com</p>

                            <button class="btn btn-primary btn-full" id="styleYourQRBtn">Style Your QR Code</button>
                        </div>

                        <!-- BARCODE destination (hidden unless BAR tab active) -->
                        <div class="barcode-mode" id="barcodeDestination" style="display:none; margin-top:8px;">
                            <p class="form-description">Preview data for this batch</p>
                            <div id="barcodeDataPreview"
                                style="background:#fafafa;border:1px solid #eee;padding:8px;max-height:160px;overflow:auto;">
                                No data selected yet.</div>

                            <div style="margin-top:12px;display:flex;gap:8px;">
                                <button class="btn btn-primary btn-full" id="styleYourBarcodeBtn">Style Your
                                    Barcode</button>
                                <button class="btn" id="barcodeBackToDataType">Back</button>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3: Style Your QR Code -->
                    <div class="form-content" id="step3" style="display: none;">
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

                            <div class="logo-upload-area">
                                <svg class="upload-icon-large" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M8 16A8 8 0 108 0a8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L5.707 7.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L9 8.586V5z" />
                                </svg>
                                <p class="upload-text">Drag & Drop your file here</p>
                                <p class="upload-subtext">JPEG, PNG file accepted</p>
                                <input type="file" id="logoUpload" class="file-input" accept="image/jpeg,image/png">
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

                        <!-- BARCODE Style options -->
                        <div class="barcode-mode" id="barcodeStyle" style="display:none; margin-top:12px;">
                            <p class="form-description">Customize barcode appearance</p>

                            <div class="setting-group">
                                <label class="setting-label">Size (width x height)</label>
                                <div style="display:flex;gap:8px;">
                                    <input type="number" id="barcodeWidth" class="size-input" value="600" min="100">
                                    <input type="number" id="barcodeHeight" class="size-input" value="120" min="20">
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
                                <button class="btn" id="backToBarcodeDestination">Back</button>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 4: Finish & Download -->
                    <div class="form-content" id="step4" style="display: none;">
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

                        <!-- BARCODE Download View -->
                        <div class="barcode-mode" id="barcodeFinish" style="display:none; margin-top:12px;">
                            <h3 class="settings-title">Barcode Output</h3>
                            <p class="settings-subtitle">Preview and download your barcode(s)</p>

                            <div id="barcodeFinishPreview" style="margin:8px 0;">
                                <!-- image(s) will be inserted here -->
                            </div>

                            <div style="display:flex;gap:8px;">
                                <button class="btn btn-download" id="downloadBarcodePNG">Download PNG</button>
                                <a href="#" id="downloadBarcodeZipLink" style="display:none;"
                                    class="btn btn-download">Download ZIP</a>
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

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script src="script.js"></script>
</body>

</html>