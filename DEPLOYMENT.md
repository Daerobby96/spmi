# Panduan Deployment Laravel ke Hosting

Panduan lengkap untuk deploy aplikasi SPMI Laravel ke hosting.

## 📋 Prasyarat Hosting

Pastikan hosting Anda mendukung:
- **PHP**: versi 8.2 atau lebih tinggi
- **Database**: MySQL/MariaDB (disarankan) atau SQLite
- **Composer**: untuk instalasi dependencies
- **Node.js & NPM**: untuk build assets (opsional, bisa di lokal)
- **SSL Certificate**: untuk keamanan (disarankan)

---

## 🚀 Metode 1: Deploy via FTP/File Manager (Shared Hosting)

### Langkah 1: Persiapan File di Lokal

```bash
# 1. Build assets untuk production
npm run build

# 2. Optimasi autoloader composer
composer install --optimize-autoloader --no-dev

# 3. Clear cache konfigurasi
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Langkah 2: Siapkan File untuk Upload

File dan folder yang **HARUS** di-upload:
```
app/
bootstrap/
config/
database/
lang/
public/
resources/
routes/
storage/
vendor/
.env
artisan
composer.json
composer.lock
package.json
```

File yang **TIDAK PERLU** di-upload:
```
node_modules/          # Tidak diperlukan di production
tests/                 # Hanya untuk development
.git/                  # Repository git
.editorconfig
.gitattributes
.gitignore
phpunit.xml
vite.config.js         # Sudah di-build
```

### Langkah 3: Konfigurasi .env untuk Production

Buat file `.env` di hosting dengan konfigurasi berikut:

```env
APP_NAME=SPMI
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://domain-anda.com

APP_LOCALE=id
APP_FALLBACK_LOCALE=id

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username_db
DB_PASSWORD=password_db

# Session & Cache
SESSION_DRIVER=file
SESSION_LIFETIME=120
CACHE_STORE=file

# Queue (gunakan database atau sync untuk shared hosting)
QUEUE_CONNECTION=database

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@domain-anda.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Langkah 4: Struktur Folder di Hosting

**Opsi A: Document Root ke folder `public/`**
```
public_html/
├── app/
├── bootstrap/
├── config/
├── ... (semua file laravel)
└── public/
    └── index.php
```
Set document root hosting ke: `public_html/public`

**Opsi B: Semua file di dalam public_html (jika tidak bisa ubah document root)**
```
public_html/
├── app/
├── bootstrap/
├── config/
├── ... (semua file laravel)
└── public/
    └── index.php
```
Pindahkan isi folder `public/` ke `public_html/` dan sesuaikan path di `index.php`:
```php
// Ubah dari:
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

// Menjadi:
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
```

### Langkah 5: Set Permissions

Via SSH atau File Manager:
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 755 public/
```

### Langkah 6: Generate APP_KEY dan Migrate Database

Via SSH:
```bash
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force  # Jika ada seeder
```

Atau manual tanpa SSH:
1. Generate key di lokal: `php artisan key:generate --show`
2. Copy key tersebut ke `.env` di hosting
3. Import file SQL dari lokal ke database hosting

---

## 🚀 Metode 2: Deploy via SSH (VPS/Dedicated Server)

### Langkah 1: Upload atau Clone Project

```bash
# Via Git
cd /var/www/html
git clone https://github.com/username/spmi-app.git
cd spmi-app

# Atau upload via SCP/SFTP
scp -r ./spmi-app user@server:/var/www/html/
```

### Langkah 2: Install Dependencies

```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

### Langkah 3: Konfigurasi Environment

```bash
cp .env.example .env
nano .env  # Edit konfigurasi
php artisan key:generate
```

### Langkah 4: Setup Database

```bash
php artisan migrate --force
php artisan db:seed --force  # Jika diperlukan
```

### Langkah 5: Optimasi Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### Langkah 6: Set Permissions

```bash
chown -R www-data:www-data /var/www/html/spmi-app
chmod -R 755 /var/www/html/spmi-app
chmod -R 775 /var/www/html/spmi-app/storage
chmod -R 775 /var/www/html/spmi-app/bootstrap/cache
```

---

## 🌐 Konfigurasi Web Server

### Apache (.htaccess)

File `.htaccess` di folder `public/`:
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### Nginx Configuration

```nginx
server {
    listen 80;
    server_name domain-anda.com;
    root /var/www/html/spmi-app/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 🌐 Deployment di Subdomain

### Struktur Folder Subdomain di Hosting

#### **cPanel - Subdomain sebagai Folder Terpisah**
Jika subdomain `spmi.polka.ac.id` dibuat, cPanel biasanya membuat folder tersendiri:
```
/home/username/                    # Home directory
├── public_html/                   # Domain utama (polka.ac.id)
│   └── ...
├── spmi.polka.ac.id/              # Folder subdomain (auto-create)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── ...
│   └── public/                    # Document root subdomain
│       └── index.php
└── ...
```

Atau kadang diletakkan di dalam public_html:
```
/home/username/public_html/
├── ...                            # File domain utama
└── spmi/                          # Folder subdomain
    ├── app/
    ├── bootstrap/
    ├── config/
    ├── ...
    └── public/                    # Document root subdomain
        └── index.php
```

---

### 0. Mengisi Folder Subdomain yang Kosong

Jika folder subdomain Anda masih kosong (baru dibuat di cPanel), berikut cara mengisinya:

#### **Opsi A: Upload via File Manager cPanel (Cepat)**

1. **Kompres project di lokal**:
   ```bash
   # Di komputer lokal, buat ZIP file
   zip -r spmi-deploy.zip app bootstrap config database lang public resources routes storage vendor .env
   
   # Atau gunakan 7-Zip/WinRAR di Windows
   # Pilih folder: app, bootstrap, config, database, lang, public, resources, routes, storage, vendor
   # Pilih file: .env, artisan, composer.json, composer.lock
   ```

2. **Upload ke hosting**:
   - Login cPanel → File Manager
   - Masuk ke folder `spmi.polka.ac.id/` (atau `public_html/spmi/`)
   - Klik **Upload** → Pilih file `spmi-deploy.zip`
   - Tunggu upload selesai

3. **Extract file**:
   - Klik kanan file ZIP → **Extract**
   - Extract ke folder subdomain (bukan ke public/)

4. **Hasil struktur**:
   ```
   spmi.polka.ac.id/              # Folder subdomain
   ├── app/
   ├── bootstrap/
   ├── config/
   ├── database/
   ├── lang/
   ├── public/                    # Document root otomatis diarahkan ke sini
   │   ├── index.php
   │   ├── .htaccess
   │   └── build/
   ├── resources/
   ├── routes/
   ├── storage/
   ├── vendor/
   ├── .env
   └── ...
   ```

#### **Opsi B: Upload via FTP (FileZilla)**

1. **Build assets dulu di lokal**:
   ```bash
   npm run build
   composer install --optimize-autoloader --no-dev
   ```

2. **Connect FTP ke hosting**:
   - Host: `ftp.polka.ac.id` atau IP server
   - Username: username cPanel
   - Password: password cPanel
   - Port: 21

3. **Upload folder dan file**:
   - Di **Remote Site** (kanan): navigasi ke `spmi.polka.ac.id/`
   - Di **Local Site** (kiri): pilih semua folder dan file project
   - Drag & drop ke folder subdomain

4. **Folder yang WAJIB di-upload**:
   ```
   📁 app/
   📁 bootstrap/
   📁 config/
   📁 database/
   📁 lang/
   📁 public/
   📁 resources/
   📁 routes/
   📁 storage/
   📁 vendor/
   📄 .env
   📄 artisan
   📄 composer.json
   📄 composer.lock
   ```

#### **Opsi C: Upload via SSH (Jika ada akses SSH)**

```bash
# 1. SSH ke server
ssh username@polka.ac.id

# 2. Masuk ke folder subdomain
cd ~/spmi.polka.ac.id

# 3. Upload via SCP dari lokal (buka terminal lain di lokal)
scp -r ./spmi-app/* username@polka.ac.id:~/spmi.polka.ac.id/

# 4. Set permissions
chmod -R 755 storage bootstrap/cache public
chmod -R 775 storage bootstrap/cache
```

---

### ⚠️ Penting: Document Root Subdomain

Setelah upload, **pastikan Document Root sudah benar**:

| Benar ✓ | Salah ✗ |
|---------|---------|
| `spmi.polka.ac.id/public/` | `spmi.polka.ac.id/` |

**Cek di cPanel**:
1. Buka menu **Subdomains**
2. Lihat kolom **Document Root** untuk `spmi.polka.ac.id`
3. Harus mengarah ke: `/home/username/spmi.polka.ac.id/public`
4. Jika belum benar, klik **Manage** → Ubah Document Root

---

### 1. Konfigurasi .env untuk Subdomain

```env
# .env untuk subdomain spmi.domain-utama.com
APP_NAME=SPMI
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://spmi.domain-utama.com  # Sesuaikan dengan subdomain Anda

APP_LOCALE=id
APP_FALLBACK_LOCALE=id

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username_db
DB_PASSWORD=password_db

# Session & Cache
SESSION_DRIVER=file
SESSION_LIFETIME=120
CACHE_STORE=file

# Queue
QUEUE_CONNECTION=database

# Asset URL (PENTING untuk subdomain/subfolder)
# Jika assets tidak muncul, uncomment dan sesuaikan:
# ASSET_URL=https://spmi.domain-utama.com
```

---

### 2. Setup di cPanel

#### Langkah 1: Buat Subdomain
1. Login ke cPanel
2. Cari menu **"Subdomains"**
3. Isi:
   - **Subdomain**: `spmi`
   - **Domain**: `domain-utama.com`
   - **Document Root**: `public_html/spmi/public` (PENTING!)
4. Klik **Create**

#### Langkah 2: Upload File
Upload semua file Laravel ke folder `public_html/spmi/` (bukan ke `public_html/spmi/public/`)

Struktur seharusnya:
```
public_html/
└── spmi/                    # Root aplikasi Laravel
    ├── app/
    ├── bootstrap/
    ├── config/
    ├── resources/
    ├── routes/
    ├── storage/
    ├── vendor/
    ├── .env
    └── public/              # Document root subdomain
        ├── index.php
        ├── .htaccess
        ├── favicon.ico
        ├── robots.txt
        └── build/         # Hasil npm run build
```

---

### 3. Konfigurasi .htaccess untuk Subdomain

File `.htaccess` di `public/` (sudah ada di project):
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# PHP Configuration (sesuaikan versi)
<IfModule php8_module>
    php_flag display_errors off
    php_value memory_limit 256M
    php_value max_execution_time 300
    php_value upload_max_filesize 64M
    php_value post_max_size 64M
</IfModule>
```

---

### 4. Penyesuaian index.php (Jika Diperlukan)

Jika subdomain tidak menggunakan `public/` sebagai document root, ubah `public/index.php`:

```php
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Debug\ExceptionHandler;

// Jika document root di folder spmi/ (bukan spmi/public/)
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

// Jika document root sudah benar di spmi/public/ (STANDAR)
// Tidak perlu diubah - gunakan default:
// require __DIR__.'/../vendor/autoload.php';
// $app = require_once __DIR__.'/../bootstrap/app.php';
```

---

### 5. Konfigurasi Nginx untuk Subdomain (VPS)

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name spmi.domain-utama.com;
    root /var/www/html/spmi/public;

    # Redirect HTTP ke HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name spmi.domain-utama.com;
    root /var/www/html/spmi/public;

    # SSL Configuration
    ssl_certificate /path/to/ssl/certificate.crt;
    ssl_certificate_key /path/to/ssl/private.key;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

---

### 6. Build Assets dengan URL Subdomain

Jika Anda build assets di lokal sebelum upload, pastikan Vite config mengetahui base URL:

**vite.config.js** (tambahkan jika belum ada):
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    base: '/', // Untuk subdomain dengan document root ke public/
});
```

Build assets:
```bash
npm run build
```

---

### 7. Checklist Subdomain

| No | Item | Status |
|----|------|--------|
| 1 | Subdomain dibuat di panel hosting | ☐ |
| 2 | Document root diarahkan ke `public/` | ☐ |
| 3 | `APP_URL` di .env sesuai subdomain | ☐ |
| 4 | Semua file di-upload ke folder subdomain | ☐ |
| 5 | `storage/` dan `bootstrap/cache/` permissions 775 | ☐ |
| 6 | Database dibuat dan dikonfigurasi | ☐ |
| 7 | `APP_KEY` di-generate | ☐ |
| 8 | `npm run build` sudah dijalankan | ☐ |
| 9 | SSL/HTTPS aktif untuk subdomain | ☐ |
| 10 | Testing login dan fitur utama | ☐ |

---

### 8. Troubleshooting Subdomain

#### **Assets (CSS/JS) Tidak Muncul**
```bash
# 1. Pastikan build sudah ada
ls -la public/build/

# 2. Tambahkan di .env jika perlu:
ASSET_URL=https://spmi.domain-utama.com

# 3. Clear cache
php artisan config:clear
php artisan view:clear
```

#### **Route Not Found / 404 Error**
```bash
# Clear route cache
php artisan route:clear

# Jika menggunakan route cache di production:
php artisan route:cache
```

#### **Subdomain Redirect ke Domain Utama**
- Cek file `.htaccess` di domain utama
- Pastikan tidak ada redirect rule yang mengganggu subdomain
- Cek konfigurasi WordPress (jika ada) di domain utama

#### **Session/Cookie Bermasalah**
Tambahkan di `.env`:
```env
SESSION_DOMAIN=.domain-utama.com
SESSION_SECURE_COOKIE=true  # Jika menggunakan HTTPS
```

---

### 9. Perbedaan Utama: Domain vs Subdomain

| Aspek | Domain Utama | Subdomain |
|-------|--------------|-----------|
| **Document Root** | `public_html/public/` | `public_html/subdomain/public/` |
| **APP_URL** | `https://domain.com` | `https://subdomain.domain.com` |
| **Struktur Folder** | Langsung di `public_html/` | Di subfolder `public_html/subdomain/` |
| **SSL** | Satu sertifikat untuk domain | Bisa wildcard atau terpisah |
| **.htaccess** | Sama | Sama, perhatikan path |

---

## 📧 Konfigurasi Email

Untuk mengirim email dari aplikasi:

### Gmail SMTP
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password  # Gunakan App Password, bukan password Gmail
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@domain-anda.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Mailtrap (untuk testing)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=mailtrap-username
MAIL_PASSWORD=mailtrap-password
MAIL_ENCRYPTION=tls
```

---

## ✅ Checklist Sebelum Go Live

- [ ] `APP_ENV=production` di `.env`
- [ ] `APP_DEBUG=false` di `.env`
- [ ] `APP_KEY` sudah di-generate
- [ ] `APP_URL` sesuai dengan domain
- [ ] Database sudah di-migrate
- [ ] Permissions folder `storage/` dan `bootstrap/cache/` benar
- [ ] SSL/HTTPS sudah terpasang
- [ ] Assets sudah di-build (`npm run build`)
- [ ] Cache sudah di-optimize
- [ ] Error logging berfungsi
- [ ] Email sudah dikonfigurasi dan berfungsi

---

## 🔧 Troubleshooting

### Error 500 - Internal Server Error
1. Cek `storage/logs/laravel.log`
2. Pastikan permissions sudah benar
3. Pastikan `.env` sudah dikonfigurasi
4. Cek versi PHP (minimal 8.2)

### Blank Page / White Screen
1. Set `APP_DEBUG=true` sementara untuk melihat error
2. Cek error log hosting
3. Pastikan semua file ter-upload lengkap

### Database Connection Error
1. Cek kredensial database di `.env`
2. Pastikan database sudah dibuat
3. Cek apakah user database punya akses penuh

### Assets Tidak Muncul
1. Jalankan `npm run build`
2. Pastikan folder `public/build/` ada
3. Cek `VITE_ASSET_URL` di `.env` jika menggunakan subfolder

### Permission Denied
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 🔄 Update Aplikasi

```bash
# Pull perubahan terbaru
git pull origin main

# Update dependencies
composer install --no-dev --optimize-autoloader

# Build assets baru
npm run build

# Run migrations
php artisan migrate --force

# Clear dan cache ulang
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📞 Bantuan

Jika mengalami kendala, cek:
1. [Dokumentasi Laravel](https://laravel.com/docs/deployment)
2. Log file: `storage/logs/laravel.log`
3. Error log hosting (biasanya di `error_log` atau `logs/`)

---

## 🏆 Rekomendasi Hosting Laravel Indonesia

1. **IDCloudHost** - Support PHP 8.2, Composer, SSH
2. **Niagahoster** - Shared hosting dengan support Laravel
3. **Dewaweb** - VPS dan managed Laravel hosting
4. **Qwords** - Support PHP 8+ dan Composer

Untuk production yang serius, disarankan menggunakan VPS untuk kontrol penuh.
