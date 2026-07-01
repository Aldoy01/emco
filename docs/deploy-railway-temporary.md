# Deploy Sementara EMCO di Railway

Panduan ini untuk menjalankan EMCO dengan URL sementara dari Railway, misalnya:

```text
https://nama-project.up.railway.app
```

Railway cocok untuk preview, demo ke klien, atau staging sebelum domain utama `https://emcoindonesia.com` dipasang.

Referensi resmi:

- Railway Laravel: https://docs.railway.com/guides/laravel
- Railway Public Networking: https://docs.railway.com/networking/public-networking

## 1. Alur Paling Mudah

Gunakan GitHub:

1. Push project `emco` ke repository GitHub.
2. Buka Railway.
3. Pilih **New Project**.
4. Pilih **Deploy from GitHub repo**.
5. Pilih repo EMCO.
6. Tambahkan service database, disarankan **MySQL** agar sama dengan development lokal.
7. Isi Variables.
8. Deploy.
9. Buka service app > **Settings** > **Networking** > **Generate Domain**.

Setelah itu Railway memberi URL sementara `.railway.app`.

## 2. Variables Railway

Di service Laravel EMCO, buka tab **Variables** lalu isi:

```env
APP_NAME="EMCO Indonesia"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://url-sementara-railway.up.railway.app

LOG_CHANNEL=stderr
LOG_LEVEL=error

CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

USD_TO_IDR_RATE=16000
SALES_EMAIL=aldiansyah@tramatek.id
SALES_WHATSAPP=6281292718681
```

Railway/Railpack saat ini bisa gagal jika `composer.json` meminta PHP `8.0.2`.
Untuk EMCO, requirement PHP sudah diset ke:

```json
"php": "^8.2"
```

Jika masih muncul error `No version available for php 8.0.2`, pastikan perubahan `composer.json` sudah di-commit dan di-push ke GitHub, lalu redeploy Railway.

Catatan lokal: XAMPP lama dengan PHP 8.0 tidak bisa menjalankan `composer install` normal setelah perubahan ini. Untuk development lokal, upgrade XAMPP/PHP ke 8.2 atau gunakan `composer install --ignore-platform-req=php` hanya jika benar-benar perlu.

EMCO juga membutuhkan PDO MySQL untuk koneksi database:

```json
"ext-pdo": "*",
"ext-pdo_mysql": "*"
```

Jika log Railway menampilkan `could not find driver`, pastikan perubahan ini sudah ter-push ke GitHub dan deploy ulang dari commit terbaru.

Untuk `APP_KEY`, generate dari lokal:

```bash
php artisan key:generate --show
```

Copy hasilnya ke Railway:

```env
APP_KEY=base64:hasil_generate_key
```

## 3. Database Railway

Jika memakai MySQL Railway, tambahkan variable database di app service.

Biasanya Railway menyediakan variable dari service MySQL seperti:

```env
MYSQLHOST
MYSQLPORT
MYSQLDATABASE
MYSQLUSER
MYSQLPASSWORD
```

Isi variable Laravel:

```env
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
```

Nama `MySQL` menyesuaikan nama database service di Railway. Jika service database diberi nama lain, sesuaikan prefix-nya.

Jika log menampilkan query ke database `forge`, berarti variable DB belum masuk ke service Laravel. Pastikan `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `DB_HOST`, dan `DB_PORT` sudah ada di **Variables** service aplikasi, bukan hanya di service database.

EMCO juga sudah dibuat kompatibel dengan variable bawaan Railway MySQL:

```env
MYSQL_URL
MYSQLHOST
MYSQLPORT
MYSQLDATABASE
MYSQLUSER
MYSQLPASSWORD
```

Jadi jika variable `MYSQL*` dari service database sudah tersedia di service Laravel, aplikasi akan membacanya otomatis. Namun cara paling jelas tetap menambahkan `DB_*` seperti contoh di atas.

## 4. Build Dan Start

Railway dapat mendeteksi Laravel otomatis. Untuk EMCO, biasanya cukup deploy dari GitHub.

Jika perlu custom command:

Build command:

```bash
composer install --no-dev --optimize-autoloader
```

Pre-deploy command:

```bash
php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

Jika Railway meminta start command manual:

```bash
php artisan serve --host=0.0.0.0 --port=$PORT
```

Namun jika Railway auto-detect Laravel dengan PHP-FPM/Caddy, start command manual tidak perlu diisi.

## 5. Generate URL Sementara

Setelah deploy sukses:

1. Masuk service Laravel EMCO.
2. Buka **Settings**.
3. Cari **Networking**.
4. Klik **Generate Domain**.
5. Railway akan memberi URL public, biasanya domain `.railway.app`.
6. Copy URL itu ke variable `APP_URL`.
7. Redeploy.

Contoh:

```env
APP_URL=https://emco-production.up.railway.app
```

## 6. Login Admin Dan Testing

Buka:

```text
https://url-sementara-railway.up.railway.app
https://url-sementara-railway.up.railway.app/login
https://url-sementara-railway.up.railway.app/admin
```

Cek:

- Home tampil normal.
- Katalog produk tampil.
- Gambar default produk tampil.
- Login admin berhasil.
- Admin bisa tambah produk.
- Checkout bisa membuat invoice.
- Konfirmasi pembayaran bisa dicek admin.

## 7. Catatan Penting Upload Gambar

Railway bukan file hosting permanen secara default. File yang diupload ke filesystem app bisa hilang saat redeploy.

Untuk demo sementara, upload gambar masih bisa dipakai selama container belum redeploy/reset.

Untuk lebih aman:

- Gunakan Railway Volume untuk folder upload/storage, atau
- Pakai storage external seperti S3/R2, atau
- Untuk production final, gunakan hosting/cPanel dengan storage permanen.

Jika tetap ingin pakai Railway untuk staging dengan upload:

1. Tambahkan Railway Volume.
2. Mount ke path yang dipakai upload, misalnya:

```text
/app/public/uploads
```

3. Pastikan folder upload dipakai oleh aplikasi untuk gambar produk.

## 8. Update Deploy Setelah Ada Perubahan

Jika deploy dari GitHub:

1. Commit perubahan.
2. Push ke GitHub.
3. Railway auto-deploy.

Jika memakai Railway CLI:

```bash
railway login
railway link
railway up
```

## 9. Jika Nanti Ingin Pakai Domain emcoindonesia.com

Di Railway:

1. Buka service Laravel EMCO.
2. Settings > Networking.
3. Add Custom Domain.
4. Masukkan:

```text
emcoindonesia.com
www.emcoindonesia.com
```

5. Railway akan memberi DNS record.
6. Masukkan DNS record itu di panel domain.
7. Setelah aktif, ubah:

```env
APP_URL=https://emcoindonesia.com
```

8. Redeploy.

## 10. Troubleshooting

Jika error 500:

- Cek logs di Railway.
- Pastikan `APP_KEY` sudah ada.
- Pastikan variable DB benar.
- Jalankan migration.

Jika style tidak berubah:

- Pastikan file `public/css/emko.css` ikut ter-push.
- Clear browser cache.
- Redeploy service.

Jika login tidak bertahan:

- Pastikan `APP_URL` sesuai URL Railway.
- Pastikan `SESSION_DRIVER=file`.

Jika gambar upload hilang:

- Itu normal jika belum pakai Volume atau storage external.
