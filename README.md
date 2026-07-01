# EMKO Gencontrol Indonesia

Aplikasi Laravel 9 untuk B2B Product Catalogue + Request Quotation berdasarkan dokumen arsitektur EMKO / Gencontrol Indonesia.

## Modul MVP

- Homepage B2B industrial
- Katalog produk dengan filter kategori dan pencarian product code
- Detail produk dengan fitur, spesifikasi, harga estimasi Rupiah, disclaimer, CTA RFQ dan WhatsApp
- Pricelist Indonesia 2026 dalam Rupiah setelah diskon 5%
- Download catalogue/datasheet placeholder
- Solusi industri dan artikel SEO awal
- Form Request Quotation dengan validasi, honeypot, dan rate limit
- Admin dashboard CRM untuk produk, harga, RFQ, status follow-up, dan export CSV

## Harga Rupiah

Data harga awal tetap mengikuti pricelist USD dari dokumen, lalu ditampilkan sebagai Rupiah memakai rate di `.env`:

`USD_TO_IDR_RATE=16000`

Ubah nilai tersebut jika ingin memakai kurs internal perusahaan yang berbeda, lalu jalankan `php artisan optimize:clear`.

## Setup Lokal XAMPP

1. Buat database MySQL bernama `emco`.
2. Jalankan `php artisan key:generate`.
3. Jalankan `php artisan migrate --seed`.
4. Buka `http://localhost/emco`.
5. Login admin: `admin@emko.local` / `password`.

Harga ditampilkan sebagai estimasi Rupiah dan belum termasuk pajak, shipping, instalasi, konfigurasi, dan biaya proyek.