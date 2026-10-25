# Instalasi di cPanel lewat Terminal

## 0. Persiapan di cPanel

1. **Cari Terminal:** login cPanel → bagian **Advanced** → **Terminal** (bisa juga ketik "Terminal" di kotak pencarian cPanel). Kalau menu ini tidak ada, akses SSH belum diaktifkan; minta ke penyedia hosting.
2. **Versi PHP:** cPanel → **MultiPHP Manager** (atau **Select PHP Version**) → pilih **PHP 8.3** atau lebih baru untuk domain Anda. Pastikan ekstensi `intl`, `zip`, `pdo_mysql`, `mbstring`, `fileinfo`, `gd` aktif.
3. **Database:** cPanel → **MySQL® Database Wizard** → buat database (misal `namauser_rucisoft`), buat user beserta password, beri **ALL PRIVILEGES**. Catat nama database, user, dan password.
4. **Token GitHub** (hanya bila repo private): GitHub → Settings → Developer settings → Personal access tokens → buat token dengan akses baca ke repo `rucisoft-web/compro`.

## 1. Ambil kode

```bash
cd ~
git clone https://github.com/rucisoft-web/compro.git compro
# repo private: git clone https://TOKEN@github.com/rucisoft-web/compro.git compro
cd compro
```

## 2. Pasang dependensi PHP

```bash
composer install --no-dev --optimize-autoloader
```

Kalau muncul `composer: command not found`:

```bash
curl -sS https://getcomposer.org/installer | php
php composer.phar install --no-dev --optimize-autoloader
```

Aset tampilan (`public/build`) sudah ikut di repo, jadi **tidak perlu Node.js/npm** di hosting.

## 3. Konfigurasi `.env`

```bash
cp .env.example .env
nano .env
```

Ubah baris berikut (simpan dengan Ctrl+O, Enter, lalu keluar dengan Ctrl+X):

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://rucisoft.id

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=namauser_rucisoft
DB_USERNAME=namauser_dbuser
DB_PASSWORD=passworddatabase

ADMIN_EMAIL=rucisoft@gmail.com
ADMIN_PASSWORD=password-admin-yang-kuat
```

## 4. Inisialisasi aplikasi

```bash
php artisan key:generate
php artisan migrate --seed --force
php artisan storage:link
php artisan filament:optimize
php artisan optimize
```

## 5. Arahkan domain ke folder `public`

**Cara A (disarankan):** cPanel → **Domains** → klik **Manage** pada domain → ubah **Document Root** menjadi `compro/public` → Simpan.

**Cara B (domain utama yang terkunci di `public_html`):**

```bash
cd ~
mv public_html public_html_lama
ln -s ~/compro/public ~/public_html
```

Lalu buka https://rucisoft.id dan https://rucisoft.id/admin.

## Update setelah ada perubahan di GitHub

```bash
cd ~/compro
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize
```

## Mengganti produk ke 6 aplikasi terbaru

Perintah ini menghapus 6 produk lama (BSC Professional, RuciLegal, RuciESG, RuciNexa, RuciTOP, RuciAI) beserta item katalognya dan 2 item "(contoh)", lalu membuat Ruci BSC, Akredia, Ruci PRIMA, Ruci Notaria, Ruci Medika, dan Ruci MUFAKAT. Item katalog lain yang Anda buat sendiri lewat admin tidak dihapus.

```bash
cd ~/compro
git pull
php artisan migrate --force
php artisan db:seed --class=ProductSeeder --force
php artisan optimize:clear && php artisan optimize
```

Logo dan screenshot diambil langsung dari situs masing-masing aplikasi (misalnya `https://medika.rucisoft.id/img/logo/ruci-medika.svg`). Jika salah satunya gagal dimuat, website otomatis memakai gambar bawaan. Semua logo, gambar, dan teks bisa diubah di admin melalui menu **Solusi**.

## Jika ada masalah

- **Error 500:** lihat `storage/logs/laravel.log`, dan pastikan izin folder: `chmod -R 775 storage bootstrap/cache`.
- **Gambar upload tidak tampil:** jalankan ulang `php artisan storage:link`. Bila hosting melarang symlink, minta bantuan penyedia hosting.
- **Versi PHP di terminal berbeda:** cek dengan `php -v`. Bila masih lama, pakai path PHP 8.3 yang disediakan hosting, misalnya `/opt/cpanel/ea-php83/root/usr/bin/php artisan ...`.

## Update: Sales funnel & Rucisoft Academy

Pembaruan ini menambah halaman `/academy`, formulir prospek (demo, pilot, proposal, asesmen, pendaftaran workshop) dan menu CRM di admin (Prospek Masuk, Peluang Penjualan, Organisasi). Jalankan di Terminal cPanel:

```bash
cd ~/compro
git pull
php artisan migrate --force
php artisan db:seed --class=FunnelSeeder --force
php artisan optimize:clear && php artisan optimize
```

`FunnelSeeder` mengganti 6 tahapan "Cara Kerja Sama", serta membuat 3 acara Academy untuk Ruci BSC, Akredia, dan Ruci Notaria (webinar, workshop, in-house/pilot); ketiganya juga mendapat masalah & lingkup pilotnya sendiri. Aman dijalankan ulang.

Agar setiap prospek baru juga dikirim ke email, isi SMTP di `.env` (`MAIL_MAILER=smtp`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`). Tanpa SMTP, prospek tetap tersimpan dan bisa dilihat di admin → CRM → Prospek Masuk.
