# MakeCode - QR Code & Barcode Generator

A comprehensive web application for generating and managing QR codes and barcodes with user authentication, advanced customization options, and a professional 4-step workflow. Built with PHP, MySQL, JavaScript, and featuring batch processing capabilities.

**Version:** 1.0.0  
**License:** MIT  
**Author:** Development Team

---

## 🎯 Features

### 🔐 User Authentication & Dashboard

- **User Registration** - Create account with email and password (hashed with `password_hash()`)
- **User Login** - Secure login with credential verification
- **Personal Dashboard** - View all your generated QR codes and barcodes in organized tables
- **Session Management** - Secure session handling with auto-logout

### Multi-Step QR Code Workflow

1. **Step 1: Data Type Selection** - Choose from 9 data types:
   - URL/Website links
   - Email (with CC, BCC, subject, message)
   - Phone numbers
   - SMS messages
   - Plain text
   - Geographic locations (Google Maps)
   - WiFi login credentials
   - Social media links
   - Google Meet meeting URLs

2. **Step 2: Destination** - Enter specific content for the selected data type
3. **Step 3: Style Your QR Code** - Customize appearance:
   - Logo upload (max 5MB, JPEG/PNG)
   - Center text overlay (max 20 chars)
   - Color picker for text
4. **Step 4: Finish & Download** - Download with flexible options

### 📊 Barcode Generation

- **Multiple Symbologies**: Code 128, Code 39, UPC-A, EAN-13
- **Generation Modes**:
  - Single barcode generation
  - Counter mode (auto-sequence with prefix, start, increment, count)
  - File mode (batch upload via CSV/TXT)
- **Batch Processing**: Generate and download multiple barcodes as ZIP
- **Advanced Settings**: Custom size, colors, font size, duplicate prevention
- **Live Preview**: See sample barcode before generation

### 🎨 Core Functionalities

- ✅ Responsive UI with smooth navigation
- ✅ Automatic step progression with back button navigation
- ✅ Real-time QR code generation using chillerlan/php-qrcode
- ✅ File upload preview in input area with "Use File & Style" button
- ✅ Logo upload with live preview in styling section
- ✅ Custom color picker for QR center text
- ✅ Download QR codes in JPEG or PNG format
- ✅ Advanced download settings (size: 100-2000px, DPI: 72-300)
- ✅ Dynamic tracking option for scan statistics
- ✅ Update-later option for QR codes
- ✅ Scan count tracking per QR code
- ✅ User-specific code management (only users see their own codes)

## 📁 Project Structure

```
qr_generator/
├── index.php                    # Main landing page & authenticated dashboard
├── register.php                 # User registration form
├── login.php                    # User login form
├── logout.php                   # Session termination & redirect
│
├── config.php                   # Database & application configuration
├── styles.css                   # All styling (responsive CSS)
├── script.js                    # Frontend logic & interactivity
│
├── Backend Handlers (PHP)
│   ├── register_handler.php     # Process user registration
│   ├── login_handler.php        # Process user login & session setup
│   ├── generate-qr.php          # Generate QR code endpoint
│   ├── generate-barcode.php     # Generate barcode(s) endpoint
│   ├── save-qr.php              # Save QR code metadata to DB
│   ├── get-qr-codes.php         # Retrieve user's QR codes with pagination
│   ├── download-qr.php          # Download QR code in JPEG/PNG
│   ├── download-barcode.php     # Download barcode ZIP batch
│   └── setup-database.php       # Initialize database schema
│
├── Libraries & Dependencies
│   ├── qr_engine/               # chillerlan/php-qrcode library (QR generation)
│   ├── bar_engine/              # Barcode generation library
│   ├── fpdf/                    # PDF export support
│   └── fonts/                   # Font files for barcode rendering
│
├── Upload Directories (auto-created)
│   ├── uploads/qr-codes/        # Generated QR code images
│   ├── uploads/bar-codes/       # Generated barcode images
│   └── uploads/user-files/      # User-uploaded files (optional)
│
├── Database Files
│   ├── qr_generator.sql         # Complete database schema export
│   └── barcode_history.json     # Historical barcode data log
│
└── Documentation
    └── README.md                # This file
```

## 🔄 User Workflows

### Authentication Flow

#### Registration

1. Click "Register" in navbar → `register.php`
2. Fill form: username, email, password, password confirmation
3. Submit → `register_handler.php` validates and creates user
4. Password hashed with `password_hash(PASSWORD_DEFAULT)`
5. Automatic login & redirect to dashboard on success

#### Login

1. Click "Login" in navbar → `login.php`
2. Enter username/email and password
3. Submit → `login_handler.php` verifies credentials with `password_verify()`
4. Session created (`$_SESSION['user_id']`, `$_SESSION['username']`)
5. Redirect to dashboard

#### Dashboard

- After login, `index.php` shows "Hi, [username]" + Logout button
- How-to section is hidden
- Instead: Two tables display user's QR codes and barcodes
- Table columns: Preview/ID, Type, Content (truncated), Scans/Created date

#### Logout

- Click "Logout" → `logout.php` destroys session
- Redirected to homepage (unauthenticated view)

---

### QR Code Generation Flow (Authenticated User)

#### Step 1: Data Type Selection

- User sees 9 data type buttons + file upload area
- File upload preview shows thumbnails/file names
- "Use File & Style" button appears after selecting files
  - Skips Step 2 and jumps directly to Step 3 (styling)
  - File data stored in `qrCodeData.uploadedFile` as data URL
- Clicking a data type button automatically advances to Step 2

#### Step 2: Destination (Input Screen)

- Shows context-specific input fields for selected data type
- Back button available to return to Step 1
- Input validation before proceeding:
  - URLs: must start with http:// or https://
  - Email: required recipient field
  - SMS: required phone number
  - WiFi: required SSID
  - etc.
- "Style Your QR Code" button advances to Step 3

#### Step 3: Style Your QR Code

- Upload logo: drag-drop or file picker (max 5MB, JPEG/PNG)
  - Live preview shown in upload area
  - Opacity reduced on icon when logo uploaded
- OR add center text (max 20 chars):
  - Color picker for text color (linked hex input)
  - Real-time color sync between picker and hex field
- Back button returns to Step 2
- "Finish & Download" generates QR and advances to Step 4

#### Step 4: Finish & Download

- QR code displayed with metadata
- Account prompt (if not logged in)
- Standard Downloads:
  - JPEG button → downloads standard QR in JPEG
  - PNG button → downloads standard QR in PNG
- Custom Settings (click "Change"):
  - Size: 100-2000px (pixels)
  - DPI: 72-300
  - Optional: Password protection, pause, scan limit toggles
  - "Save Changes" button
- Back button returns to Step 3

#### After Download

- QR code saved to database with `user_id` (if logged in)
- `qr_codes` table updated with:
  - user_id, qr_type='qr', data_type, content, qr_image_path
  - update_later flag, dynamic_tracking flag
  - scan_count initialized to 0
  - created_at timestamp
- Optional file uploads stored in `file_uploads` table

---

### Barcode Generation Flow

#### Step 1: Barcode Setup

- Project/Group name input (optional)
- Symbology selector: Code 128, Code 39, UPC-A, EAN-13
- Data source radio buttons:
  - **Counter Mode**: Generates sequence with prefix + auto-increment
    - Prefix, Start number, Increment, Count inputs
    - Preview shows up to 50 barcodes
  - **File Mode**: Upload .txt or .csv with one value per line
    - Accepts numeric or alphanumeric values
- "Next: Destination" or "Preview Sample" buttons

#### Step 2: Barcode Destination (Preview)

- Shows preview of data to be encoded
- "Style Your Barcode" button advances to Step 3

#### Step 3: Barcode Style & Generate

- Size preset: Standard (440×306px), Small, Large, Basic, or Custom
- Color pickers: Foreground (barcode) and background colors
- Font size for human-readable text
- Duplicate prevention toggle
- "Generate & Download" button → calls `generate-barcode.php`
  - Server generates all barcodes
  - Returns JSON with image URLs and ZIP archive path
- Barcodes displayed as thumbnails on Step 4

#### Step 4: Barcode Download

- Thumbnail preview of all generated barcodes
- "Download Batch Zip" button → downloads complete ZIP archive
- All barcodes saved to `uploads/bar-codes/` directory
- Database entries created with `user_id`, barcode data, dates

## ⚙️ Setup Instructions

### Prerequisites

- **PHP** 7.4 or higher (8.0+ recommended)
- **MySQL** 5.7+ or **MariaDB** 10.3+
- **Apache** or **Nginx** web server with PHP support
- **Composer** package manager (for PHP dependencies)
- **OpenSSL** extension (for password hashing)

### Quick Start (5 minutes)

#### 1. Clone/Download Project

```bash
cd /path/to/xampp/htdocs  # or your web root
git clone https://github.com/your-repo/qr_generator.git
cd qr_generator
```

#### 2. Update Database Credentials

Edit `config.php`:

```php
define('DB_HOST', 'localhost');     // Your MySQL host
define('DB_NAME', 'qr_generator');  // Your database name
define('DB_USER', 'root');          // Your MySQL user
define('DB_PASS', '');              // Your MySQL password
```

#### 3. Create Database

```bash
# Using MySQL CLI
mysql -u root -p
> CREATE DATABASE qr_generator CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
> EXIT;
```

#### 4. Import Database Schema

```bash
# Using MySQL CLI
mysql -u root -p qr_generator < qr_generator.sql

# OR run setup script (optional)
php setup-database.php
```

#### 5. Install Dependencies

```bash
composer install
# This fetches QR code libraries and other PHP packages
```

#### 6. Create Upload Directories

```bash
mkdir -p uploads/qr-codes
mkdir -p uploads/bar-codes
chmod 755 uploads/qr-codes uploads/bar-codes
```

#### 7. Access Application

```
http://localhost/qr_generator/
```

---

### Detailed Configuration

#### Environment Variables (Optional)

For production, create `.env`:

```
APP_ENV=production
DB_HOST=localhost
DB_NAME=qr_generator
DB_USER=myuser
DB_PASS=mypassword
SESSION_TIMEOUT=3600
```

#### Web Server Configuration

**Apache (.htaccess) - Already Included**

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

**Nginx (nginx.conf)**

```nginx
location /qr_generator/ {
    try_files $uri $uri/ /qr_generator/index.php?$query_string;
}
```

#### PHP Configuration (php.ini)

```ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 60
memory_limit = 256M
session.gc_maxlifetime = 3600
```

---

### Optional: Production Deployment

#### Enable HTTPS

```apache
<VirtualHost *:443>
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
</VirtualHost>
```

#### Add CSRF Protection

Update `config.php` to generate CSRF tokens:

```php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
```

#### Enable Rate Limiting

Add to `config.php` or `.htaccess`:

```php
// Simple rate limiting (1 generate request per user per minute)
if (!isset($_SESSION['last_qr_generate'])) {
    $_SESSION['last_qr_generate'] = 0;
}
if (time() - $_SESSION['last_qr_generate'] < 60) {
    header('HTTP/1.1 429 Too Many Requests');
    exit;
}
$_SESSION['last_qr_generate'] = time();
```

## 📊 Database Schema

### Overview

MakeCode uses 5 main tables with referential integrity via foreign keys. All timestamps default to `CURRENT_TIMESTAMP`.

### Table: `users`

Stores user account information.

| Column                     | Type         | Constraints                                            | Description                                 |
| -------------------------- | ------------ | ------------------------------------------------------ | ------------------------------------------- |
| id                         | INT          | PK, AUTO_INCREMENT                                     | User ID                                     |
| username                   | VARCHAR(50)  | UNIQUE, NOT NULL                                       | Unique login username                       |
| email                      | VARCHAR(100) | UNIQUE, NOT NULL                                       | Unique email address                        |
| password                   | VARCHAR(255) | NOT NULL                                               | Hashed password (PASSWORD_DEFAULT)          |
| created_at                 | TIMESTAMP    | DEFAULT CURRENT_TIMESTAMP                              | Account creation time                       |
| updated_at                 | TIMESTAMP    | DEFAULT CURRENT_TIMESTAMP, ON UPDATE CURRENT_TIMESTAMP | Last profile update                         |
| _Optional upcoming fields_ |              |                                                        | last_login, is_active, email_verified, role |

**Indexes**: `PRIMARY KEY (id)`, `UNIQUE (username)`, `UNIQUE (email)`, `INDEX (email)`, `INDEX (username)`

---

### Table: `qr_codes`

Stores generated QR codes and barcode entries.

| Column           | Type                  | Constraints                                            | Description                                                      |
| ---------------- | --------------------- | ------------------------------------------------------ | ---------------------------------------------------------------- |
| id               | INT                   | PK, AUTO_INCREMENT                                     | QR/Barcode ID                                                    |
| user_id          | INT                   | FK users(id) ON DELETE CASCADE                         | Owner user (NULL = guest)                                        |
| qr_type          | ENUM('qr', 'barcode') | DEFAULT 'qr'                                           | Type of code                                                     |
| data_type        | VARCHAR(50)           | NOT NULL                                               | url, email, phone, sms, text, location, wifi, social, meet, file |
| content          | TEXT                  | NOT NULL                                               | Encoded data/URL                                                 |
| qr_image_path    | VARCHAR(255)          | DEFAULT NULL                                           | Server path to generated image                                   |
| update_later     | TINYINT(1)            | DEFAULT 0                                              | Allow future updates (1=yes, 0=no)                               |
| dynamic_tracking | TINYINT(1)            | DEFAULT 0                                              | Enable scan tracking (1=yes, 0=no)                               |
| scan_count       | INT                   | DEFAULT 0                                              | Number of times scanned                                          |
| is_active        | TINYINT(1)            | DEFAULT 1                                              | Active status (1=active, 0=inactive)                             |
| created_at       | TIMESTAMP             | DEFAULT CURRENT_TIMESTAMP                              | Generation timestamp                                             |
| updated_at       | TIMESTAMP             | DEFAULT CURRENT_TIMESTAMP, ON UPDATE CURRENT_TIMESTAMP | Last modification                                                |

**Indexes**: `PRIMARY KEY (id)`, `FK (user_id)`, `INDEX (data_type)`, `INDEX (created_at)`, `INDEX (is_active)`

**Example Rows**:

```sql
-- QR for URL (user 1)
INSERT INTO qr_codes (user_id, qr_type, data_type, content, qr_image_path, dynamic_tracking)
VALUES (1, 'qr', 'url', 'https://example.com', 'uploads/qr-codes/qr_abc123.png', 1);

-- Barcode for product (user 1)
INSERT INTO qr_codes (user_id, qr_type, data_type, content, qr_image_path)
VALUES (1, 'barcode', 'product', 'PROD-001-2024', 'uploads/bar-codes/barcode_001.png', 0);
```

---

### Table: `qr_customizations`

Stores styling/customization for QR codes.

| Column           | Type         | Constraints                       | Description                         |
| ---------------- | ------------ | --------------------------------- | ----------------------------------- |
| id               | INT          | PK, AUTO_INCREMENT                | Customization ID                    |
| qr_code_id       | INT          | FK qr_codes(id) ON DELETE CASCADE | Associated QR code                  |
| foreground_color | VARCHAR(7)   | DEFAULT '#000000'                 | QR module color (hex)               |
| background_color | VARCHAR(7)   | DEFAULT '#FFFFFF'                 | QR background color (hex)           |
| logo_path        | VARCHAR(255) | DEFAULT NULL                      | Path to embedded logo image         |
| size             | INT          | DEFAULT 300                       | QR code size (px)                   |
| error_correction | VARCHAR(1)   | DEFAULT 'M'                       | Error correction level (L, M, Q, H) |

**Indexes**: `PRIMARY KEY (id)`, `FK (qr_code_id)`

---

### Table: `qr_scans`

Tracks QR code scan events for analytics.

| Column     | Type         | Constraints                       | Description                             |
| ---------- | ------------ | --------------------------------- | --------------------------------------- |
| id         | INT          | PK, AUTO_INCREMENT                | Scan event ID                           |
| qr_code_id | INT          | FK qr_codes(id) ON DELETE CASCADE | Scanned QR code                         |
| ip_address | VARCHAR(45)  | DEFAULT NULL                      | Visitor IP (IPv4 or IPv6)               |
| user_agent | TEXT         | DEFAULT NULL                      | Browser/device info                     |
| location   | VARCHAR(100) | DEFAULT NULL                      | Geographic location (optional, from IP) |
| scanned_at | TIMESTAMP    | DEFAULT CURRENT_TIMESTAMP         | Scan timestamp                          |

**Indexes**: `PRIMARY KEY (id)`, `FK (qr_code_id)`, `INDEX (scanned_at)`

---

### Table: `file_uploads`

Stores files uploaded by users (embedded in QR codes or attachments).

| Column            | Type         | Constraints                       | Description               |
| ----------------- | ------------ | --------------------------------- | ------------------------- |
| id                | INT          | PK, AUTO_INCREMENT                | Upload ID                 |
| qr_code_id        | INT          | FK qr_codes(id) ON DELETE CASCADE | Associated QR code        |
| original_filename | VARCHAR(255) | NOT NULL                          | Original file name        |
| stored_filename   | VARCHAR(255) | NOT NULL                          | Sanitized server filename |
| file_path         | VARCHAR(255) | NOT NULL                          | Full server path to file  |
| file_type         | VARCHAR(50)  | DEFAULT NULL                      | Detected MIME type        |
| file_size         | INT          | DEFAULT NULL                      | File size in bytes        |
| uploaded_at       | TIMESTAMP    | DEFAULT CURRENT_TIMESTAMP         | Upload timestamp          |

**Indexes**: `PRIMARY KEY (id)`, `FK (qr_code_id)`

---

### SQL to Add Recommended Fields (Production)

For enhanced functionality, run these migrations:

```sql
-- Add user authentication fields
ALTER TABLE users
  ADD COLUMN last_login DATETIME NULL,
  ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1,
  ADD COLUMN email_verified TINYINT(1) NOT NULL DEFAULT 0,
  ADD INDEX idx_is_active (is_active),
  ADD INDEX idx_last_login (last_login);

-- Add role-based access (optional)
ALTER TABLE users
  ADD COLUMN role VARCHAR(20) NOT NULL DEFAULT 'user',
  ADD INDEX idx_role (role);

-- Add more qr_codes fields
ALTER TABLE qr_codes
  ADD COLUMN title VARCHAR(255) DEFAULT NULL,
  ADD COLUMN description TEXT DEFAULT NULL,
  ADD INDEX idx_user_id_created (user_id, created_at);
```

---

### Sample Database Creation Script

```php
<?php
$sql = <<<SQL
-- Recreate all tables
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_email (email),
  INDEX idx_username (username)
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE qr_codes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT DEFAULT NULL,
  qr_type ENUM('qr', 'barcode') DEFAULT 'qr',
  data_type VARCHAR(50) NOT NULL,
  content TEXT NOT NULL,
  qr_image_path VARCHAR(255) DEFAULT NULL,
  update_later TINYINT(1) DEFAULT 0,
  dynamic_tracking TINYINT(1) DEFAULT 0,
  scan_count INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_id (user_id),
  INDEX idx_data_type (data_type),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ... (similar for other tables)
SQL;

// Execute all at once
// $pdo->exec($sql);
?>
```

## 🔌 API Endpoints

### Authentication Endpoints

#### Register User

**POST** `/register_handler.php`

**Form Data (application/x-www-form-urlencoded):**

```
username=john_doe&email=john@example.com&password=securepass123&password_confirm=securepass123
```

**Response:** Redirect to `index.php` on success or `register.php?error=...` on failure

**Validation:**

- Username: required, max 50 chars
- Email: required, valid format, unique
- Password: required, min 6 chars, must match confirmation

---

#### Login User

**POST** `/login_handler.php`

**Form Data:**

```
user=john_doe&password=securepass123
# or
user=john@example.com&password=securepass123
```

**Response:** Redirect to `index.php` on success or `login.php?error=...` on failure

**Session Created:**

- `$_SESSION['user_id']` - User ID
- `$_SESSION['username']` - Username

---

#### Logout User

**GET** `/logout.php`

**Response:** Destroys session, redirects to `index.php`

---

### QR Code Endpoints

#### Generate QR Code

**POST** `/generate-qr.php`

**Form Data (multipart/form-data):**

```
data=https://example.com
type=url
updateLater=false
dynamicTracking=true
centerText=Hello
centerTextColor=%23000000
logo=(optional file)
uploaded_file=(optional data URL for file embeds)
```

**Response (JSON):**

```json
{
  "success": true,
  "qrCodeId": 123,
  "qrCodeUrl": "/uploads/qr-codes/qr_abc123xyz.png",
  "message": "QR Code generated successfully"
}
```

**Error Response:**

```json
{
  "success": false,
  "error": "Invalid URL format"
}
```

**Data Types Supported:**
| Type | Required Fields | Example |
|------|-----------------|---------|
| url | data (valid URL) | https://example.com |
| email | data (email address) | user@example.com |
| phone | data (phone number) | +1234567890 |
| sms | data (phone), optional message | +1234567890 with message |
| text | data (any text) | Any plain text |
| location | data (Google Maps URL) | https://maps.google.com/... |
| wifi | data (WIFI URI) | WIFI:T:WPA;S:SSID;P:password;; |
| social | data (social URL) | https://facebook.com/mypage |
| meet | data (Google Meet URL) | https://meet.google.com/xxx |
| file | data (file name), uploaded_file | document.pdf |

---

#### Download QR Code

**GET** `/download-qr.php`

**Query Parameters:**

```
?qrCodeId=123&format=png&size=400&dpi=72
```

| Parameter | Values    | Default  |
| --------- | --------- | -------- |
| qrCodeId  | integer   | required |
| format    | png, jpeg | png      |
| size      | 100-2000  | 400      |
| dpi       | 72-300    | 72       |

**Response:** Downloaded file (image/png or image/jpeg)

---

### Barcode Endpoints

#### Generate Barcode(s)

**POST** `/generate-barcode.php`

**Counter Mode (sequence generation):**

```
mode=counter
projectName=Product-Batch-2024
symbology=code128
prefix=PRD
start=1001
increment=1
count=10
width=220
height=120
fgColor=%23000000
bgColor=%23ffffff
fontSize=12
preventDuplicates=0
```

**File Mode (upload list):**

```
mode=file
projectName=Inventory-Scan
symbology=code128
dataList=["CODE001","CODE002","CODE003"]
width=220
height=120
fgColor=%23000000
bgColor=%23ffffff
fontSize=12
preventDuplicates=0
```

**Response (JSON - Counter Mode):**

```json
{
  "success": true,
  "generated": [
    { "data": "PRD1001", "url": "/uploads/bar-codes/barcode_prd1001_xxx.png" },
    { "data": "PRD1002", "url": "/uploads/bar-codes/barcode_prd1002_xxx.png" }
  ],
  "zipUrl": "/uploads/bar-codes/barcodes_batch_xxx.zip"
}
```

**Response (JSON - File Mode):**

```json
{
  "success": true,
  "zipUrl": "/uploads/bar-codes/barcodes_batch_xxx.zip",
  "generated": [...]
}
```

**Symbologies:** `code128`, `code39`, `ean13`, `upc-a`

---

#### Save QR/Barcode Metadata

**POST** `/save-qr.php`

**JSON Body:**

```json
{
  "id": 123,
  "content": "https://example.com",
  "dataType": "url",
  "updateLater": false,
  "dynamicTracking": true
}
```

**Response:**

```json
{
  "success": true,
  "id": 123,
  "message": "QR Code saved successfully"
}
```

---

#### Get User's QR Codes

**GET** `/get-qr-codes.php`

**Query Parameters:**

```
?user_id=1&page=1&limit=10&data_type=url&is_active=1
```

**Response (JSON):**

```json
{
  "success": true,
  "qrCodes": [
    {
      "id": 1,
      "user_id": 1,
      "qr_type": "qr",
      "data_type": "url",
      "content": "https://example.com",
      "qr_image_path": "/uploads/qr-codes/qr_abc.png",
      "scan_count": 5,
      "created_at": "2026-02-14 10:30:00"
    }
  ],
  "total": 25,
  "page": 1,
  "pages": 3
}
```

---

### Dashboard Endpoints

#### Display User Dashboard

**GET** `/index.php` (while logged in)

**Server-side Logic:**

1. Checks `if (!empty($_SESSION['user_id']))`
2. Fetches user from `users` table
3. Loads all `qr_codes` rows where `user_id` matches
4. Separates into `qr_type='qr'` and `qr_type='barcode'`
5. Renders two tables with user's codes

**Response:** HTML displaying user profile and code tables

## 🎨 Frontend Architecture

### HTML Structure (`index.php`)

- **Navigation Bar**: Logo, menu links, auth buttons
- **Hero Section**: CTA buttons to start creating
- **Generator Section**: Two-column layout (form + live preview)
- **Dashboard Section**: Tables for logged-in users
- **Modal Dialogs**: Info about QR codes, how-to guide, features
- **Footer**: Scripts and async loading

### Styling (`styles.css`)

- **Responsive Design**: Mobile-first approach (320px to 1920px+)
- **Color Scheme**:
  - Primary green: `#00d084` or `#86efac`
  - Neutral grays: `#f5f5f5`, `#ddd`, `#999`
  - Text dark: `#333` or `#000`
- **Components**: Buttons, inputs, tabs, modals, tables
- **Animations**: Smooth transitions, hover effects
- **Font Family**: System stack with fallbacks

### JavaScript Functions (`script.js`)

#### State Management

```javascript
let currentStep = 1;            // Current form step (1-4)
let currentMode = "qr";         // 'qr' or 'bar' mode
let selectedDataType = "";      // Current QR data type
let qrCodeData = {...};         // QR metadata object
let lastBarcodeResult = null;   // Latest barcode generation result
```

#### Core Functions

- `initializeEventListeners()` - Attach all event handlers on page load
- `goToStep(step)` - Navigate between form steps (1-4)
- `generateQRCode()` - POST to `/generate-qr.php`, store result
- `displayQRCode(url)` - Show generated QR in preview area
- `downloadQRCode(format, useCustom)` - Trigger download via `/download-qr.php`
- `saveQRCode()` - POST metadata to `/save-qr.php` (optional)
- `toggleMainTab(tabType)` - Switch between QR and Barcode modes
- `isValidURL(string)` - Validate URL format (http/https)
- `escapeSemicolons(string)` - Escape WiFi SSID special chars

#### Event Handlers

- Data type buttons → select type, auto-advance to Step 2
- Form tab navigation → go to completed step
- Logo upload → preview in styling area, store in `qrCodeData.logo`
- File upload → preview in input area, show "Use File & Style" button
- Color picker → linked hex input updates
- Back buttons → go to previous step
- Download buttons → call `downloadQRCode()` with format
- Barcode preview → RenderJS barcode preview using JsBarcode library

#### AJAX Calls

- `POST /generate-qr.php` - Generate QR, receive image URL
- `POST /generate-barcode.php` - Generate batch barcodes, receive ZIP URL
- `POST /save-qr.php` - Save metadata to database (optional)
- `GET /get-qr-codes.php` - Fetch user's code list

#### Form Validation

- URL: must match `^https?://`
- Email: required recipient field
- Phone: required number
- SMS: required phone + optional message
- WiFi: required SSID, password, security type
- File: required file selection with preview

---

## 🔐 Security Features

### Authentication & Sessions

- **Password Hashing**: `password_hash($pass, PASSWORD_DEFAULT)` (bcrypt)
- **Verification**: `password_verify($input, $hash)`
- **Session Tokens**: Uses PHP native `$_SESSION` superglobal
- **Prepared Statements**: All DB queries use parameterized queries (PDO)

### Input Validation

- URL validation: `filter_var($url, FILTER_VALIDATE_URL)`
- Email validation: `filter_var($email, FILTER_VALIDATE_EMAIL)`
- File type whitelist: Only `.jpg, .png, .pdf, .docx, .mp3, .mp4`
- File size limits: Max 5MB for logos, 10MB for uploads
- String sanitization: `htmlspecialchars()`, `trim()` on user input

### Data Protection

- **SQL Injection Prevention**: Prepared statements with placeholders
- **XSS Prevention**: Output escaping with `htmlspecialchars()`
- **CSRF Tokens**: (Optional, can be added) `$_SESSION['csrf_token']`
- **HTTPS**: Recommended for production deployments

### File Security

- Files stored outside web root when possible
- Unique filenames with `uniqid()` to prevent overwrites
- MIME type verification on upload
- Symlink attacks prevented by avoiding predictable paths

### Recommendations for Production

1. **Enable HTTPS** with valid SSL/TLS certificate
2. **Add CSRF Token** to all forms
3. **Implement Rate Limiting** to prevent abuse
4. **Use Environment Variables** for sensitive config
5. **Enable SQL Error Suppression** (log to file instead)
6. **Set Secure Cookie Flags**:
   ```php
   session_set_cookie_params([
       'secure'   => true,   // HTTPS only
       'httponly' => true,   // No JS access
       'samesite' => 'Lax',  // CSRF protection
   ]);
   ```
7. **Add Logging & Monitoring** for security events
8. **Implement CAPTCHA** for registration/sensitive actions
9. **Set up WAF** (Web Application Firewall) rules
10. **Regular Security Audits** and penetration testing

## 🎨 Customization Guide

### Branding & Colors

**Logo:**

- Replace `images/logo.png` with your brand logo
- Suggested size: 80px × 80px (as used in template)
- Update path in `index.php` navbar if filename changes

**Primary Color** (green accent):

```css
/* styles.css - Search and replace all instances */
#00d084   /* Primary green */
#86efac   /* Light green (hover) */
#059669   /* Dark green (active) */
```

**Custom Theme Example**:

```css
/* Replace .btn, .btn-primary, etc. classes with brand colors */
.btn-primary {
  background-color: #your-brand-color;
  color: white;
}
```

### Data Types

To add a new QR data type (e.g., "Event"):

**1. Update HTML** (`index.php` - Step 1):

```html
<button class="data-type-btn" data-type="event">
  <span class="data-icon">📅</span>
  <span>Event</span>
</button>
```

**2. Add Destination Input** (`index.php` - Step 2):

```html
<div class="dest-block" id="dest-event" style="display:none;">
  <label class="input-field-label"
    >Event URL <span class="required">*</span></label
  >
  <input
    type="text"
    id="eventUrl"
    class="text-input"
    placeholder="https://eventbrite.com/..."
  />
</div>
```

**3. Add JavaScript Handler** (`script.js`):

```javascript
case "event": {
  const eventUrl = (document.getElementById("eventUrl") || {}).value || "";
  if (!eventUrl) {
    alert("Please enter event URL");
    return;
  }
  if (!isValidURL(eventUrl)) {
    alert("Please enter a valid URL");
    return;
  }
  content = eventUrl;
  break;
}
```

**4. Add Backend Handler** (`generate-qr.php`):

```php
case "event":
    // Optional: Add event-specific processing
    break;
```

### Email Notifications

Add email alerts for new registrations or QR generations:

```php
// In register_handler.php, after user creation:
use PHPMailer\PHPMailer\PHPMailer;
$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'your-email@gmail.com';
$mail->Password = 'your-app-password';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

$mail->setFrom('noreply@makecode.com');
$mail->addAddress($email);
$mail->Subject = 'Welcome to MakeCode!';
$mail->Body = 'Thanks for signing up...';
$mail->send();
```

### Database Backups

**Automatic Backup (Linux/Bash):**

```bash
#!/bin/bash
BACKUP_DIR="/backups/qr_generator"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")

mkdir -p $BACKUP_DIR

mysqldump -u root -p qr_generator > $BACKUP_DIR/qr_generator_$TIMESTAMP.sql

# Keep only last 7 days
find $BACKUP_DIR -name "qr_generator_*.sql" -mtime +7 -delete
```

**Manual Backup:**

```bash
mysqldump -u root -p qr_generator > qr_generator_backup.sql
```

**Restore from Backup:**

```bash
mysql -u root -p qr_generator < qr_generator_backup.sql
```

---

## 🐛 Troubleshooting

### Database Connection Issues

**Error:** `Database connection failed`

**Solutions:**

1. Verify MySQL is running:

   ```bash
   sudo systemctl status mysql    # Linux
   brew services list              # macOS
   # Windows: Check Services or use XAMPP Control Panel
   ```

2. Check credentials in `config.php`:

   ```bash
   mysql -u root -p -h localhost
   # Test connection, verify username/password
   ```

3. Verify database exists:
   ```sql
   SHOW DATABASES;
   USE qr_generator;
   SHOW TABLES;
   ```

---

### Image Generation Not Working

**Error:** `Failed to generate QR code` or blank QR

**Solutions:**

1. Verify `qr_engine/` library is installed:

   ```bash
   composer install
   ```

2. Check GD extension enabled in PHP:

   ```bash
   php -m | grep gd          # Linux/Mac
   php -i | findstr GD       # Windows
   ```

3. Enable GD in `php.ini`:

   ```ini
   extension=gd              # Uncomment this line
   ; Restart PHP-FPM or Apache
   ```

4. Verify upload directory exists and is writable:
   ```bash
   chmod 755 uploads/qr-codes
   chmod 755 uploads/bar-codes
   ls -la uploads/           # Check ownership/permissions
   ```

---

### File Upload Issues

**Error:** `File upload failed` or `Invalid file type`

**Solutions:**

1. Check file size limit:

   ```ini
   ; in php.ini
   upload_max_filesize = 10M
   post_max_size = 10M
   ```

2. Verify allowed extensions in `config.php`:

   ```php
   define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'pdf', 'docx', 'mp3', 'mp4']);
   ```

3. Check upload directory permissions:
   ```bash
   touch uploads/test.txt    # Verify writable
   rm uploads/test.txt
   ```

---

### Authentication Problems

**Error:** `Invalid credentials` even with correct password

**Causes & Fixes:**

1. User doesn't exist → Create new account
2. Database not initialized → Run `php setup-database.php`
3. Password altered in database → Delete user and re-register
4. Hashing mismatch → Verify `password_hash()` and `password_verify()` are used

**Check user exists:**

```sql
SELECT id, username, email FROM users WHERE email = 'user@example.com';
```

---

### Session Persists After Logout

**Problem:** User remains logged in after clicking logout

**Solutions:**

1. Check `logout.php` is executing:

   ```php
   session_destroy();
   header('Location: index.php');
   ```

2. Clear browser cookies/cache
3. Check `php.ini` session settings:
   ```ini
   session.gc_divisor = 100      # Garbage collection frequency
   session.gc_maxlifetime = 3600 # Timeout after 1 hour
   ```

---

### Slow Performance

**Symptoms:** Website slow to load, QR generation takes long

**Optimization Tips:**

1. Cache generated QR codes (avoid regenerating)
2. Compress images with `imagefilter()` or third-party tool
3. Paginate dashboard tables (limit 20 per page)
4. Add database indexes:

   ```sql
   CREATE INDEX idx_user_created ON qr_codes(user_id, created_at);
   ```

5. Enable PHP opcode cache (OPcache):

   ```ini
   opcache.enable = 1
   opcache.memory_consumption = 128
   ```

6. Use CDN for static assets (CSS, JS, images)

---

## 📚 Resources

### Official Documentation

- [PHP Official](https://www.php.net/)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [chillerlan/php-qrcode Docs](https://github.com/chillerlan/php-qrcode)
- [JsBarcode](https://github.com/lindell/JsBarcode)

### Tools & Services

- [XAMPP](https://www.apachefriends.org/) - Local development stack
- [Composer](https://getcomposer.org/) - PHP package manager
- [Postman](https://www.postman.com/) - API testing
- [Git](https://git-scm.com/) - Version control

### Learning Resources

- [PHP Security Best Practices](https://www.owasp.org/index.php/PHP_Security_Cheat_Sheet)
- [SQL Injection Prevention](https://cheatsheetseries.owasp.org/cheatsheets/SQL_Injection_Prevention_Cheat_Sheet.html)
- [Web Application Security](https://owasp.org/www-project-top-ten/)

---

## 🚀 Future Roadmap

### Phase 2 (Q1-Q2 2026)

- [ ] QR code analytics dashboard (view scans over time)
- [ ] Batch import of QR data from CSV
- [ ] Scheduled QR code generation (cron jobs)
- [ ] API key system for programmatic access
- [ ] Two-factor authentication (2FA)
- [ ] Integration with Google Analytics

### Phase 3 (Q3-Q4 2026)

- [ ] Mobile app (React Native or Flutter)
- [ ] Real-time QR scanner in app
- [ ] Team collaboration (shared workspaces)
- [ ] Advanced analytics (geographic data, device types)
- [ ] Premium tiers with advanced features
- [ ] White-label solution

### Phase 4 (2027+)

- [ ] AI-powered design suggestions
- [ ] QR code A/B testing
- [ ] AR experiences through QR codes
- [ ] Blockchain verification
- [ ] Enterprise self-hosted deployment

---

## 📝 Version History

| Version | Date         | Changes                                                          |
| ------- | ------------ | ---------------------------------------------------------------- |
| 1.0.0   | Feb 14, 2026 | Initial release with user auth, QR/barcode generation, dashboard |
| 0.9     | Feb 13, 2026 | Beta: File upload preview, auth pages                            |
| 0.8     | Feb 12, 2026 | QR + barcode generation, styling options                         |

---

## 🤝 Contributing

We welcome contributions! To contribute:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## 📄 License

© 2026 MakeCode - All Rights Reserved

This project is provided as-is for educational and commercial use. Modify and distribute as needed.

---

## 🆘 Support & Contact

- **Email**: support@makecode.com
- **Issues**: [GitHub Issues](https://github.com/your-repo/qr_generator/issues)
- **Documentation**: [Full Docs](https://docs.makecode.com)
- **Discord**: Join our community [Discord server](https://discord.gg/makecode)
