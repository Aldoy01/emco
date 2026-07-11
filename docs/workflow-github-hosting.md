# Workflow Update EMCO: Lokal, GitHub, dan Hosting

Panduan ini dipakai setiap kali aplikasi EMCO diubah dari komputer lokal lalu dikirim ke GitHub dan hosting production.

## Ringkasan Alur

```text
Local XAMPP
  -> test perubahan
  -> git add + commit
  -> git push GitHub
  -> update hosting
  -> migrate/cache
  -> test website
```

Repository GitHub:

```text
https://github.com/Aldoy01/emco
```

Domain production:

```text
https://emkoindonesia.com
```

## 1. Update Kode Di Lokal

Masuk ke folder project:

```bash
cd C:\xampp\htdocs\emco
```

Cek file yang berubah:

```bash
git status
```

Test cepat:

```bash
php artisan optimize:clear
```

Jika ada perubahan PHP, cek syntax file yang diubah:

```bash
php -l path\file.php
```

Catatan: lokal saat ini memakai PHP 8.0.30, sedangkan project production/Railway memakai PHP 8.2. Jangan jalankan `composer install` normal di lokal PHP 8.0 kecuali memakai:

```bash
composer install --no-dev --optimize-autoloader --ignore-platform-req=php
```

## 2. Commit Ke GitHub

Lihat perubahan:

```bash
git status
git diff
```

Stage dan commit:

```bash
git add .
git commit -m "Tuliskan ringkasan perubahan"
```

Push ke GitHub:

```bash
git push origin main
```

Pastikan push sukses. Jika GitHub meminta login, gunakan GitHub Personal Access Token.

## 3. Update Hosting Dengan Git Pull

Ini opsi terbaik jika hosting punya SSH/Terminal dan Git.

Masuk ke terminal hosting:

```bash
cd /home/username/emco-app
git pull origin main
```

Install dependency production:

```bash
composer install --no-dev --optimize-autoloader
```

Jalankan migration:

```bash
php artisan migrate --force
```

Bersihkan dan buat cache production:

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Jika ada perubahan file public seperti CSS, image, atau upload default, pastikan folder `public_html` ikut tersinkron:

```bash
cp -R public/css public_html/
cp -R public/images public_html/
cp -R public/uploads public_html/
```

Sesuaikan path `public_html` dengan struktur hosting Anda.

## 4. Update Hosting Manual Via File Manager/FTP

Jika hosting tidak punya Git/SSH:

1. Di lokal, pastikan perubahan sudah di-push ke GitHub.
2. Download ZIP repository dari GitHub atau upload file yang berubah via FTP.
3. Upload source Laravel ke:

```text
/home/username/emco-app
```

4. Upload isi folder `public` ke:

```text
/home/username/public_html
```

Jangan upload file berikut:

```text
.env
vendor/
node_modules/
.git/
storage/logs/
storage/framework/cache/
storage/framework/sessions/
```

Jika tidak ada SSH, migration perlu dijalankan lewat fitur Terminal hosting. Jika tidak tersedia, gunakan phpMyAdmin untuk import SQL perubahan database.

## 5. File `.env` Hosting

Pastikan `.env` production di hosting berisi:

```env
APP_NAME="EMCO Indonesia"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://emkoindonesia.com

SESSION_DRIVER=file
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_hosting
DB_USERNAME=user_database_hosting
DB_PASSWORD=password_database_hosting

MAIL_MAILER=smtp
MAIL_HOST=mail.emkoindonesia.com
MAIL_PORT=465
MAIL_USERNAME=admin@emkoindonesia.com
MAIL_PASSWORD=password_email
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=admin@emkoindonesia.com
MAIL_FROM_NAME="EMCO Indonesia"

USD_TO_IDR_RATE=16000
EMKO_HIDE_COMMERCIAL_VALUES=true
SALES_EMAIL=aldiansyah@tramatek.id
SALES_WHATSAPP=6285188337500
```

Untuk menampilkan kembali harga dan qty:

```env
EMKO_HIDE_COMMERCIAL_VALUES=false
```

Lalu jalankan:

```bash
php artisan config:cache
```

## 6. Checklist Setelah Update

Buka dan cek:

```text
https://emkoindonesia.com
https://emkoindonesia.com/produk
https://emkoindonesia.com/pricelist
https://emkoindonesia.com/login
https://emkoindonesia.com/admin
```

Cek admin:

- Login admin.
- Kode 2FA admin terkirim email.
- Produk bisa ditambah/edit.
- Upload gambar produk tampil.
- Security Logs terisi.
- Pembelian & Pembayaran bisa dibuka.

Cek publik:

- Katalog tampil.
- Harga/qty kosong jika `EMKO_HIDE_COMMERCIAL_VALUES=true`.
- Tombol Beli/Sales berjalan.
- Form Hubungi Sales terkirim.

## 7. Rollback Cepat

Jika update bermasalah dan hosting memakai Git:

Lihat commit:

```bash
git log --oneline -5
```

Kembali ke commit sebelumnya:

```bash
git reset --hard COMMIT_ID
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Gunakan rollback hanya jika benar-benar perlu. Backup database sebelum rollback besar.

## 8. Backup Sebelum Update Besar

Sebelum update besar:

- Export database dari phpMyAdmin.
- Backup folder `public_html/uploads`.
- Backup file `.env`.
- Backup folder `storage/app/public` jika dipakai.

## 9. Command Cepat Harian

Di lokal:

```bash
cd C:\xampp\htdocs\emco
git status
git add .
git commit -m "Update EMCO"
git push origin main
```

Di hosting:

```bash
cd /home/username/emco-app
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
