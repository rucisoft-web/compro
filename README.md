# Rucisoft Indonesia: Website Company Profile

Website company profile Rucisoft Indonesia, dibangun dengan **Laravel 13**, **MySQL**, **Tailwind CSS 4**, **Alpine.js**, dan panel admin **Filament 5**.

## Fitur

**Website publik**
- Landing page satu halaman: Hero, Executive Control Center, 6 Solusi, Knowledge-to-Implementation, Katalog (filter Software/Buku/Workshop), Tentang, CTA, Footer.
- Halaman detail solusi (`/solusi/{slug}`) dan katalog (`/katalog`, `/katalog/{slug}`).
- Semua CTA (Jadwalkan Demo, Konsultasi, Tanya via WA) membuka WhatsApp dengan pesan otomatis.
- Tombol WhatsApp mengambang, SEO dasar (title, meta description, Open Graph, canonical), dan `sitemap.xml`.
- Responsif untuk ponsel, tablet, dan desktop.

**Panel admin** (`/admin`)
- **Konten:** Solusi (nama, fitur, ikon, gambar, deskripsi) dan Katalog (software, buku, workshop dengan jadwal & lokasi).
- **Beranda:** Tahapan, Segmen Klien, Nilai Perusahaan, Metrik Dasbor.
- **Pengaturan Situs:** nomor WhatsApp, email, teks hero, tentang, CTA, dan meta SEO.
- Urutan item bisa diatur dengan drag-and-drop, item bisa disembunyikan tanpa dihapus.

## Instalasi lokal

Kebutuhan: PHP 8.3+, Composer, Node.js 20+, MySQL 8.

```bash
git clone https://github.com/rucisoft-web/compro.git
cd compro
composer install
cp .env.example .env
php artisan key:generate
# buat database `rucisoft` di MySQL, lalu sesuaikan DB_* dan ADMIN_* di .env
php artisan migrate --seed
php artisan storage:link
npm install
npm run build
php artisan serve
```

Buka http://localhost:8000 untuk website dan http://localhost:8000/admin untuk panel admin. Login memakai `ADMIN_EMAIL` dan `ADMIN_PASSWORD` dari `.env` (bawaan `rucisoft@gmail.com` / `password` bila tidak diisi). **Segera ganti password setelah login pertama.**

Seeder berisi konten dari rucisoft.id. Dua item katalog bertanda "(contoh)" hanyalah contoh buku dan workshop; silakan ubah atau hapus lewat admin.

## Deploy ke server (VPS / shared hosting)

1. Upload kode, arahkan document root ke folder `public/`.
2. Jalankan `composer install --no-dev --optimize-autoloader`.
3. Isi `.env` (`APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://rucisoft.id`, kredensial MySQL).
4. Jalankan `php artisan migrate --seed --force`, `php artisan storage:link`, `php artisan filament:optimize`, dan `php artisan optimize`.
5. Jalankan `npm ci && npm run build` (atau build di lokal lalu upload folder `public/build`).

Panduan khusus cPanel ada di [DEPLOY-CPANEL.md](DEPLOY-CPANEL.md).

## Testing

```bash
php artisan test
```

## Struktur penting

| Lokasi | Isi |
|---|---|
| `app/Models` | Setting, Solution, CatalogItem, Stage, ClientSegment, Principle, DashboardMetric |
| `app/Http/Controllers/PageController.php` | Halaman publik |
| `app/Filament` | Resource, halaman Pengaturan Situs, dan widget dasbor admin |
| `resources/views/pages` | Template halaman publik |
| `database/seeders/ContentSeeder.php` | Konten awal website |
| `public/images/logo.svg` | Logo baru Rucisoft |
