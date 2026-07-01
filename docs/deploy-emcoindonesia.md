# Deploy EMCO Indonesia ke Hosting

Panduan ini untuk deploy aplikasi Laravel EMCO ke domain:

```text
https://emcoindonesia.com
```

## 1. Kebutuhan Hosting

Pastikan hosting mendukung:

- PHP 8.0 atau lebih baru.
- MySQL/MariaDB.
- Composer.
- Extension PHP umum Laravel: `openssl`, `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`.
- Akses File Manager atau FTP/SFTP.
- Akses Terminal/SSH lebih baik, tapi masih bisa deploy manual lewat cPanel.

## 2. Struktur Folder Hosting Yang Disarankan

Jangan taruh semua file Laravel langsung terbuka di `public_html`.

Struktur aman:

```text
/home/username/
├── emco-app/              <- semua source Laravel
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── .env
│   └── artisan
└── public_html/           <- isi dari folder public Laravel
    ├── index.php
    ├── .htaccess
    ├── css/
    ├── images/
    ├── uploads/
    └── storage/
```

Jika hosting hanya mengizinkan project di `public_html`, gunakan subfolder:

```text
/home/username/public_html/emco-app/
/home/username/public_html/index.php
```

Namun opsi paling aman tetap `emco-app` di luar `public_html`.

## 3. Persiapan File Lokal

Dari komputer lokal:

```bash
cd C:\xampp\htdocs\emco
composer install --no-dev --optimize-autoloader
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

Lalu upload folder project ke hosting sebagai `emco-app`.

Folder yang tidak perlu diupload:

```text
.git
.agents
.codex
node_modules
tests
```

## 4. Setup Database Hosting

Di cPanel:

1. Buka **MySQL Databases**.
2. Buat database, contoh: `emcoindonesia_db`.
3. Buat user database, contoh: `emcoindonesia_user`.
4. Assign user ke database dengan permission **All Privileges**.
5. Catat nama database, user, dan password.

Jika data lokal sudah ada, export dari phpMyAdmin lokal lalu import ke phpMyAdmin hosting.

Jika ingin install dari awal, jalankan:

```bash
php artisan migrate --seed --force
```

## 5. Setup File `.env` Production

Copy file contoh:

```bash
cp .env.production.example .env
```

Isi minimal:

```env
APP_NAME="EMCO Indonesia"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://emcoindonesia.com

DB_DATABASE=nama_database_hosting
DB_USERNAME=user_database_hosting
DB_PASSWORD=password_database_hosting

MAIL_HOST=mail.emcoindonesia.com
MAIL_USERNAME=admin@emcoindonesia.com
MAIL_PASSWORD=password_email
MAIL_ENCRYPTION=ssl
MAIL_PORT=465

USD_TO_IDR_RATE=16000
SALES_EMAIL=aldiansyah@tramatek.id
SALES_WHATSAPP=6281292718681
```

Jika `APP_KEY` masih kosong:

```bash
php artisan key:generate --force
```

## 6. Arahkan `public_html/index.php`

File `public_html/index.php` harus mengarah ke folder `emco-app`.

Contoh jika struktur hosting:

```text
/home/username/emco-app
/home/username/public_html
```

Edit `public_html/index.php`:

```php
require __DIR__.'/../emco-app/vendor/autoload.php';
$app = require_once __DIR__.'/../emco-app/bootstrap/app.php';
```

Pastikan file `.htaccess` dari folder `public` Laravel ada di `public_html`.

## 7. Folder Upload Dan Storage

Beri permission tulis untuk:

```text
storage/
bootstrap/cache/
public_html/uploads/
public_html/storage/
```

Permission umum di shared hosting:

```bash
chmod -R 775 storage bootstrap/cache
```

Buat storage link:

```bash
php artisan storage:link
```

Jika hosting tidak mengizinkan symlink, copy folder:

```text
emco-app/storage/app/public
```

ke:

```text
public_html/storage
```

## 8. Optimasi Production

Jalankan setelah `.env` benar:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## 9. Checklist Testing Setelah Deploy

Buka:

```text
https://emcoindonesia.com
https://emcoindonesia.com/login
https://emcoindonesia.com/products
https://emcoindonesia.com/pricelist
https://emcoindonesia.com/admin
```

Cek fitur:

- Login admin.
- Upload gambar produk.
- Produk tampil di katalog.
- Checkout dan invoice.
- Konfirmasi pembayaran.
- Admin melihat konfirmasi pembayaran di `Admin > Pembelian & Pembayaran`.
- Email invoice atau notifikasi terkirim.
- Tombol WhatsApp mengarah ke nomor `081292718681`.

## 10. Update Deploy Berikutnya

Untuk update setelah ada perubahan:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Upload file yang berubah saja jika memakai FTP/File Manager.

Jika memakai Git di hosting:

```bash
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize
```

## 11. Troubleshooting Cepat

Jika muncul error 500:

```bash
php artisan optimize:clear
```

Cek:

```text
storage/logs/laravel.log
```

Jika CSS/gambar tidak tampil:

- Pastikan `APP_URL=https://emcoindonesia.com`.
- Pastikan folder `css`, `images`, `uploads`, dan `storage` ada di `public_html`.
- Clear cache browser.

Jika upload gambar gagal:

- Pastikan folder upload writable.
- Pastikan PHP extension `fileinfo` aktif.
- Pastikan limit upload hosting cukup.

Jika login kembali ke halaman login:

- Pastikan `SESSION_DRIVER=file`.
- Pastikan folder `storage/framework/sessions` writable.
- Pastikan domain menggunakan HTTPS yang sama dengan `APP_URL`.
