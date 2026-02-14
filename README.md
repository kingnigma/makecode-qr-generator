# MakeCode - QR Code & Barcode Generator
## 👨‍💻 Author

**Mathew Kings**
- 🌐 Portfolio: [mkportfolio.crestdigico.com](https://mkportfolio.crestdigico.com)
- 💼 LinkedIn: [linkedin.com/in/mathew-kings](https://linkedin.com/in/mathew-kings)
- 🐦 Twitter: [@mathewkings9](https://twitter.com/mathewkings9)
- 📧 Email: mk@crestdigico.com

A comprehensive web application for generating and managing QR codes and barcodes with user authentication, advanced customization options, and batch processing capabilities.

**Version:** 1.0.0 | **License:** MIT

---

## ✨ Core Features

### 🔐 Authentication
- User registration and secure login (password hashing with `password_hash()`)
- Personal dashboard showing user's generated codes
- Session management with auto-logout

### 📱 QR Code Generation
- **4-step workflow**: Data Type → Content → Styling → Download
- **9 data types**: URL, Email, Phone, SMS, Text, Location, WiFi, Social media, Google Meet
- **Customization**: Logo upload, center text overlay, color picker
- **Download options**: JPEG/PNG formats, adjustable size (100-2000px), DPI (72-300)
- Optional scan tracking and future updates

### 📊 Barcode Generation
- **Symbologies**: Code 128, Code 39, UPC-A, EAN-13
- **Generation modes**: Single, Counter (auto-sequence), File batch (CSV/TXT)
- **Batch processing**: Generate and download multiple barcodes as ZIP
- **Customization**: Size presets, colors, font size, duplicate prevention

---

## 🚀 Quick Start (5 minutes)

### Prerequisites
- PHP 7.4+ (8.0+ recommended)
- MySQL 5.7+ or MariaDB 10.3+
- Composer
- Web server (Apache/Nginx)

### Installation

### Project Structure
makecode-qr-generator/
├── index.php                    # Landing page & dashboard
├── register.php / login.php     # Authentication forms
├── config.php / styles.css / script.js
├── Backend Handlers/
│   ├── register_handler.php / login_handler.php / logout.php
│   ├── generate-qr.php / generate-barcode.php
│   ├── save-qr.php / get-qr-codes.php
│   └── download-qr.php / download-barcode.php
├── Libraries/
│   ├── qr_engine/               # chillerlan/php-qrcode
│   └── bar_engine/              # Barcode library
└── uploads/                      # Generated images & user files

### 📊 Database Schema
Table	               Purpose
users	               User accounts (id, username, email, password)
qr_codes	            Generated codes (user_id, type, content, path, scan_count)
qr_customizations	   Styling options (foreground/background color, logo, size)
qr_scans	            Scan tracking (IP, user_agent, timestamp)
file_uploads	      User-uploaded files (filename, path, type, size)

### 🔌 Key API Endpoints
Endpoint	                  Method	   Purpose
/register_handler.php	   POST	      User registration
/login_handler.php	      POST	      User login
/generate-qr.php	         POST	      Generate QR code
/generate-barcode.php	   POST	      Generate barcode(s)
/download-qr.php	         GET	      Download QR code
/get-qr-codes.php	         GET	      Fetch user's codes


### 🔐 Security Features
- Password hashing with password_hash() 
- Prepared statements for SQL injection prevention
- XSS prevention with htmlspecialchars()
- File type validation (JPEG/PNG max 5MB)
- Session-based authentication

Production recommendations:
- Enable HTTPS
- Add CSRF tokens
- Implement rate limiting
- Use environment variables
- Set secure cookie flags

### 🎨 Customization
1. Add New QR Data Type
2. Add button in Step 1 HTML
3. Add input field in Step 2
4. Add JavaScript handler in script.js
5. Add case in generate-qr.php

### 🐛 Troubleshooting
Issue	                           Solution
Database connection failed	      Check MySQL service + credentials in config.php
QR generation fails	            Verify GD extension enabled + composer installed
File upload issues	            Check php.ini upload limits + directory permissions
Login problems	                  Verify database initialized + user exists
Zip file error                   Verify that zip extention is active in php.ini

```bash
# 1. Clone or download project
cd /path/to/webroot
git clone https://github.com/your-username/makecode-qr-generator.git
cd makecode-qr-generator

# 2. Configure database in config.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'your-database');
define('DB_USER', 'root');
define('DB_PASS', '');

# 3. Create database
mysql -u root -p -e "CREATE DATABASE qr_generator CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 4. Import database schema
mysql -u root -p qr_generator < database.sql or run setup-database.php

# 5. Install PHP dependencies
composer install

# 6. Create upload directories
mkdir -p uploads/qr-codes uploads/bar-codes uploads/user-files
chmod 755 uploads/qr-codes uploads/bar-codes uploads/user-files

# 7. Access application
http://localhost/makecode-qr-generator/
```
