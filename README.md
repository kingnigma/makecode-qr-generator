# MakeCode - QR Code & Barcode Generator

## 👨💻 Author

**Mathew Kings**
- 🌐 Portfolio: [mkportfolio.crestdigico.com](https://mkportfolio.crestdigico.com)
- 💼 LinkedIn: [linkedin.com/in/mathew-kings](https://linkedin.com/in/mathew-kings)
- 🐦 Twitter: [@mathewkings9](https://twitter.com/mathewkings9)
- 📧 Email: mk@crestdigico.com

A comprehensive, mobile-optimized web application for generating and managing QR codes and barcodes with user authentication, advanced customization options, batch processing capabilities, and enterprise-grade SEO optimization.

**Version:** 1.2.0 | **License:** MIT | **Repository:** [GitHub](https://github.com/kingnigma/makecode-qr-generator)

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

### 🧹 Automated Cleanup System (NEW)
- **Auto-delete temporary files**: Removes QR codes, barcodes, and ZIP files not associated with registered users after 30 minutes
- **Cron job support**: HTTP-triggered cleanup script for scheduled maintenance
- **Orphaned file detection**: Cleans up files not referenced in database
- **Security**: Secret key authentication for cleanup endpoint
- **Detailed reporting**: JSON response with deletion statistics

### 🔍 SEO Optimization (NEW)
- **Comprehensive meta tags**: Open Graph, Twitter Cards, Schema.org structured data
- **XML sitemap**: Auto-generated sitemap for search engines
- **robots.txt**: Optimized crawler directives
- **SEO-friendly URLs**: Clean URLs with .htaccess rewrite rules
- **Performance optimization**: Gzip compression, browser caching, security headers
- **Mobile-first indexing ready**: Fully optimized for Google's mobile-first approach

### 📱 Mobile Optimization (NEW)
- **Responsive design**: Optimized for all devices (320px - 2560px)
- **Touch-friendly**: Minimum 44x44px tap targets, increased padding
- **Mobile navigation**: Hamburger menu with smooth animations
- **Flexible typography**: Responsive font sizes using clamp()
- **Adaptive layouts**: Single column on mobile, grid on desktop
- **Performance**: Hardware-accelerated transitions, optimized images
- **PWA-ready**: Progressive Web App capabilities

---

## 🚀 Quick Start (5 minutes)

### Prerequisites
- PHP 7.4+ (8.0+ recommended)
- MySQL 5.7+ or MariaDB 10.3+
- Composer
- Web server (Apache/Nginx)
- Git (for cloning repository)

### Installation

```bash
# 1. Clone repository
cd /path/to/webroot
git clone https://github.com/kingnigma/makecode-qr-generator.git
cd makecode-qr-generator

# 2. Configure database in config.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'qr_generator');
define('DB_USER', 'root');
define('DB_PASS', '');

# 3. Create database
mysql -u root -p -e "CREATE DATABASE qr_generator CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 4. Import database schema
mysql -u root -p qr_generator < database.sql
# OR run setup-database.php via browser

# 5. Install PHP dependencies
composer install

# 6. Create upload directories
mkdir -p uploads/qr-codes uploads/bar-codes uploads/user-files
chmod 755 uploads/qr-codes uploads/bar-codes uploads/user-files

# 7. Configure cleanup script (optional)
# Edit cleanup-temp-files.php and change CLEANUP_SECRET

# 8. Setup cron job for cleanup (optional)
# Add to crontab: */30 * * * * curl -s "http://localhost/makecode-qr-generator/cleanup-temp-files.php?key=your-secret-key"

# 9. Access application
http://localhost/makecode-qr-generator/
```

### Project Structure
```
makecode-qr-generator/
├── index.php                       # Landing page & dashboard
├── register.php / login.php        # Authentication forms
├── config.php                      # Database configuration
├── styles.css                      # Mobile-optimized styles
├── script.js                       # Interactive functionality
├── cleanup-temp-files.php          # Automated cleanup script (NEW)
├── sitemap.xml                     # SEO sitemap (NEW)
├── robots.txt                      # Search engine directives (NEW)
├── .htaccess                       # URL rewriting & security (NEW)
├── MOBILE_OPTIMIZATION.md          # Mobile testing guide (NEW)
├── Backend Handlers/
│   ├── register_handler.php        # User registration
│   ├── login_handler.php           # User authentication
│   ├── logout.php                  # Session termination
│   ├── generate-qr.php             # QR code generation
│   ├── generate-barcode.php        # Barcode generation
│   ├── save-qr.php                 # Save to database
│   ├── get-qr-codes.php            # Fetch user codes
│   ├── download-qr.php             # QR download handler
│   ├── download-barcode.php        # Barcode download handler
│   └── delete-code.php             # Code deletion
├── Libraries/
│   ├── qr_engine/                  # chillerlan/php-qrcode
│   └── bar_engine/                 # Barcode library
└── uploads/                        # Generated images & user files
    ├── qr-codes/
    ├── bar-codes/
    └── user-files/
```

---

## 📊 Database Schema

| Table | Purpose |
|-------|---------|
| `users` | User accounts (id, username, email, password) |
| `qr_codes` | Generated codes (user_id, type, content, path, scan_count) |
| `qr_customizations` | Styling options (foreground/background color, logo, size) |
| `qr_scans` | Scan tracking (IP, user_agent, timestamp) |
| `file_uploads` | User-uploaded files (filename, path, type, size) |

---

## 🔌 API Endpoints

| Endpoint | Method | Purpose |
|----------|--------|---------|
| `/register_handler.php` | POST | User registration |
| `/login_handler.php` | POST | User login |
| `/generate-qr.php` | POST | Generate QR code |
| `/generate-barcode.php` | POST | Generate barcode(s) |
| `/download-qr.php` | GET | Download QR code |
| `/download-barcode.php` | GET | Download barcode |
| `/get-qr-codes.php` | GET | Fetch user's codes |
| `/delete-code.php` | POST | Delete code |
| `/cleanup-temp-files.php` | GET | Cleanup temporary files (NEW) |

---

## 🔐 Security Features

- ✅ Password hashing with `password_hash()`
- ✅ Prepared statements for SQL injection prevention
- ✅ XSS prevention with `htmlspecialchars()`
- ✅ File type validation (JPEG/PNG max 5MB)
- ✅ Session-based authentication
- ✅ Security headers (X-Frame-Options, X-XSS-Protection, etc.)
- ✅ CSRF protection ready
- ✅ Secret key authentication for cleanup script

### Production Recommendations:
- Enable HTTPS (uncomment in .htaccess)
- Add CSRF tokens to forms
- Implement rate limiting
- Use environment variables for sensitive data
- Set secure cookie flags
- Regular security audits

---

## 🧹 Automated Cleanup System

### Setup Cron Job

**Linux/macOS:**
```bash
# Edit crontab
crontab -e

# Add this line (runs every 30 minutes)
*/30 * * * * curl -s "http://yourdomain.com/cleanup-temp-files.php?key=your-secret-key-here"
```

**Windows Task Scheduler:**
1. Open Task Scheduler
2. Create Basic Task
3. Trigger: Daily, repeat every 30 minutes
4. Action: Start a program
5. Program: `curl`
6. Arguments: `"http://yourdomain.com/cleanup-temp-files.php?key=your-secret-key-here"`

**Direct PHP Execution:**
```bash
*/30 * * * * php /path/to/cleanup-temp-files.php
```

### Cleanup Features
- Deletes QR/Barcode records without user_id older than 30 minutes
- Removes physical files associated with deleted records
- Cleans orphaned files in uploads directories
- Removes old ZIP files
- Returns JSON report with statistics

---

## 🔍 SEO Configuration

### 1. Update Domain URLs
Replace `https://yourdomain.com/` in:
- `index.php` (meta tags)
- `sitemap.xml`
- `robots.txt`

### 2. Add Social Media Images
Create and upload:
- `images/og-image.jpg` (1200x630px) - Open Graph
- `images/twitter-card.jpg` (1200x600px) - Twitter
- `images/favicon-32x32.png`
- `images/favicon-16x16.png`
- `images/apple-touch-icon.png` (180x180px)

### 3. Submit to Search Engines
- **Google Search Console**: https://search.google.com/search-console
- **Bing Webmaster Tools**: https://www.bing.com/webmasters
- Submit sitemap: `https://yourdomain.com/sitemap.xml`

### 4. Enable HTTPS
Uncomment HTTPS redirect in `.htaccess`:
```apache
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

---

## 📱 Mobile Optimization

### Testing Tools
- **Google Mobile-Friendly Test**: https://search.google.com/test/mobile-friendly
- **PageSpeed Insights**: https://pagespeed.web.dev/
- **Chrome DevTools**: F12 → Toggle device toolbar (Ctrl+Shift+M)

### Responsive Breakpoints
- **1024px**: Tablets (single column layout)
- **768px**: Mobile landscape (hamburger menu)
- **480px**: Mobile portrait (stacked elements)
- **360px**: Small phones (compact view)

### Mobile Features
- ✅ Hamburger menu with smooth animations
- ✅ Touch-friendly buttons (44x44px minimum)
- ✅ Responsive typography with clamp()
- ✅ Flexible grid layouts
- ✅ Optimized images and assets
- ✅ Fast loading on mobile networks

See `MOBILE_OPTIMIZATION.md` for detailed testing guide.

---

## 🎨 Customization

### Add New QR Data Type
1. Add button in Step 1 HTML (`index.php`)
2. Add input field in Step 2
3. Add JavaScript handler in `script.js`
4. Add case in `generate-qr.php`

### Customize Colors
Edit `styles.css`:
```css
.btn-primary { background-color: #86efac; } /* Primary button */
.text-green { color: #16a34a; }              /* Accent color */
```

### Change Logo
Replace `images/logo.png` with your logo (recommended: 80px height)

---

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| Database connection failed | Check MySQL service + credentials in `config.php` |
| QR generation fails | Verify GD extension enabled + `composer install` |
| File upload issues | Check `php.ini` upload limits + directory permissions |
| Login problems | Verify database initialized + user exists |
| ZIP file error | Enable `zip` extension in `php.ini` |
| Mobile menu not working | Check JavaScript console for errors |
| Cleanup script fails | Verify secret key + file permissions |
| SEO not working | Submit sitemap to Google Search Console |

### Enable PHP Extensions
```ini
; php.ini
extension=gd
extension=zip
extension=pdo_mysql
```

### Check Permissions
```bash
chmod 755 uploads/qr-codes uploads/bar-codes uploads/user-files
chown www-data:www-data uploads/ -R  # Linux
```

---

## 📈 Performance Optimization

- ✅ Gzip compression enabled
- ✅ Browser caching configured
- ✅ Minified CSS/JS (production)
- ✅ Optimized images
- ✅ Lazy loading ready
- ✅ CDN-ready architecture

### Target Metrics
- **PageSpeed Score**: > 90 (mobile & desktop)
- **LCP**: < 2.5s
- **FID**: < 100ms
- **CLS**: < 0.1

---

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

---

## 📄 License

MIT License - see LICENSE file for details

---

## 🙏 Acknowledgments

- [chillerlan/php-qrcode](https://github.com/chillerlan/php-qrcode) - QR code generation
- [JsBarcode](https://github.com/lindell/JsBarcode) - Barcode generation
- Community contributors

---

## 📞 Support

- **Issues**: [GitHub Issues](https://github.com/kingnigma/makecode-qr-generator/issues)
- **Email**: mk@crestdigico.com
- **Twitter**: [@mathewkings9](https://twitter.com/mathewkings9)

---

**Made with ❤️ by Mathew Kings**
