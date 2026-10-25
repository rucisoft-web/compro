<?php

namespace Database\Seeders;

use App\Models\CatalogItem;
use App\Models\Solution;
use App\Models\Stage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Sales-funnel content: the "cara bekerja sama" steps, the Ruci BSC pilot scope,
 * and the Rucisoft Academy programs per product (all six products).
 *
 * Safe to run repeatedly; records are matched by number or slug.
 */
class FunnelSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $this->seedStages();
            $this->seedBsc();
            $this->seedAkredia();
            $this->seedNotaria();
            $this->seedMedika();
            $this->seedPrima();
            $this->seedMufakat();
        });
    }

    private function seedStages(): void
    {
        $stages = [
            ['01', 'Memahami', 'Edukasi masalah', 'Artikel, video, dan webinar 60-90 menit dengan studi kasus nyata.'],
            ['02', 'Memetakan', 'Asesmen kebutuhan', 'Checklist dan asesmen singkat: organisasi, peran, masalah, dan kebutuhan.'],
            ['03', 'Mempraktikkan', 'Workshop praktik', 'Peserta menghasilkan pekerjaan nyata langsung di aplikasi dan membawanya pulang.'],
            ['04', 'Memastikan', 'Klinik & demo', 'Klinik kebutuhan 30-45 menit dan demo khusus bersama sponsor dan PIC.'],
            ['05', 'Membuktikan', 'Pilot terukur', 'Lingkup terbatas, pendampingan, dan ukuran keberhasilan yang disepakati di awal.'],
            ['06', 'Menerapkan', 'Pembelian & pendampingan', 'Proposal transparan, implementasi, pelatihan, review penggunaan, dan perpanjangan.'],
        ];

        Stage::whereNotIn('number', array_column($stages, 0))->delete();

        foreach ($stages as $i => [$number, $label, $title, $description]) {
            Stage::updateOrCreate(['number' => $number], compact('label', 'title', 'description') + ['sort_order' => $i + 1]);
        }
    }

    private function seedBsc(): void
    {
        $this->seedProduct('ruci-bsc', 20, [
            'problem_statement' => 'Strategi sering berhenti di dokumen: sulit diterjemahkan menjadi pekerjaan, target, penanggung jawab, dan ukuran keberhasilan yang dipantau manajemen.',
            'pilot_scope' => [
                ['element' => 'Durasi', 'scope' => 'Usulan awal 30 hari, disesuaikan kebutuhan'],
                ['element' => 'Unit', 'scope' => 'Satu direktorat atau departemen'],
                ['element' => 'Pengguna', 'scope' => 'Sponsor, administrator, dan beberapa pemilik KPI'],
                ['element' => 'Data', 'scope' => 'Sejumlah KPI dan inisiatif yang disepakati'],
                ['element' => 'Pendampingan', 'scope' => 'Kickoff, pemeriksaan kemajuan, dan evaluasi akhir'],
                ['element' => 'Keberhasilan', 'scope' => 'Data berhasil dimasukkan, dashboard digunakan, dan satu review kinerja terlaksana'],
                ['element' => 'Keputusan akhir', 'scope' => 'Lanjut, perbaiki lingkup, atau tidak melanjutkan'],
            ],
        ], [
            [
                'type' => 'webinar',
                'slug' => 'webinar-strategi-yang-dapat-dipantau',
                'title' => 'Webinar: Mengapa Strategi Sulit Dijalankan, dan Cara Membuatnya Terukur',
                'subtitle' => 'Pengenalan pendekatan Balanced Scorecard bersama Ruci BSC',
                'summary' => 'Sesi 60-90 menit dengan studi kasus: mengapa strategi sulit diterjemahkan menjadi pekerjaan dan ukuran keberhasilan, serta bagaimana organisasi memantaunya setiap bulan.',
                'audience' => 'Direksi, tim strategi, perencanaan, dan pimpinan unit yang baru mengenal BSC',
                'duration' => '60-90 menit',
                'location' => 'Online',
                'price_label' => 'Gratis, dengan pendaftaran',
                'outcomes' => ['Pemahaman masalah eksekusi strategi', 'Contoh strategy map dan KPI yang baik', 'Checklist kesiapan penerapan BSC'],
                'agenda' => [
                    ['part' => 'Masalah eksekusi strategi', 'portion' => '25%', 'content' => 'Mengapa strategi berhenti di dokumen'],
                    ['part' => 'Studi kasus', 'portion' => '45%', 'content' => 'Dari strategy map sampai review kinerja bulanan'],
                    ['part' => 'Demo singkat & tanya jawab', 'portion' => '30%', 'content' => 'Melihat alur kerja di Ruci BSC'],
                ],
            ],
            [
                'type' => 'workshop',
                'slug' => 'workshop-strategy-map-kpi-rencana-kerja',
                'title' => 'Workshop Menyusun Strategy Map, KPI, dan Rencana Kerja yang Dapat Dipantau Manajemen',
                'subtitle' => 'Praktik menggunakan perangkat lunak Ruci BSC',
                'summary' => 'Workshop praktik setengah hari atau satu hari. Peserta menyusun sasaran, KPI, target, PIC, dan inisiatif langsung di aplikasi, lalu membawa pulang hasilnya.',
                'audience' => 'Profesional atau tim strategi, perencanaan, dan manajemen kinerja',
                'duration' => 'Setengah hari / 1 hari',
                'location' => 'Online atau tatap muka',
                'price_label' => 'Berbayar per peserta atau per tim',
                'outcomes' => [
                    'Satu rancangan strategy map',
                    'Lima sampai sepuluh KPI prioritas',
                    'Target dan penanggung jawab',
                    'Dua atau tiga inisiatif',
                    'Contoh dashboard dan agenda review kinerja',
                    'Hasil praktik yang bisa diekspor',
                ],
                'agenda' => [
                    ['part' => 'Masalah dan pendekatan', 'portion' => '20%', 'content' => 'Mengapa strategi sulit diterjemahkan menjadi pekerjaan dan ukuran keberhasilan'],
                    ['part' => 'Praktik terpandu', 'portion' => '50%', 'content' => 'Menyusun sasaran, KPI, target, PIC, dan inisiatif dalam aplikasi'],
                    ['part' => 'Pembahasan hasil', 'portion' => '20%', 'content' => 'Menguji kualitas pekerjaan peserta dan kesiapan penerapan'],
                    ['part' => 'Rencana implementasi', 'portion' => '10%', 'content' => 'Tahapan penerapan, dukungan, dan pilihan paket'],
                ],
            ],
            [
                'type' => 'inhouse',
                'slug' => 'workshop-in-house-dan-pilot-ruci-bsc',
                'title' => 'Workshop In-House & Pilot Ruci BSC',
                'subtitle' => 'Untuk organisasi yang sedang mempertimbangkan penerapan',
                'summary' => 'Workshop khusus tim Anda dengan data organisasi sendiri, dilanjutkan pilot terbatas yang didampingi, dengan ukuran keberhasilan dan jadwal keputusan yang disepakati di awal.',
                'audience' => 'Organisasi, BUMN, dan perusahaan yang menyiapkan penerapan BSC',
                'duration' => 'Workshop 1-2 hari, pilot usulan 30 hari',
                'location' => 'Di lokasi Anda atau online',
                'price_label' => 'Sesuai lingkup',
                'outcomes' => [
                    'Strategy map dan KPI organisasi Anda',
                    'Lingkup dan ukuran keberhasilan pilot',
                    'Satu review kinerja dengan data nyata',
                    'Rekomendasi lanjut, perbaiki lingkup, atau berhenti',
                ],
                'agenda' => null,
            ],
        ]);
    }

    private function seedAkredia(): void
    {
        $this->seedProduct('akredia', 30, [
            'problem_statement' => 'Persiapan akreditasi sering menumpuk menjelang tenggat: bukti tersebar di banyak unit, LED dan LKPT disusun terburu-buru, dan pimpinan sulit melihat indikator mana yang belum siap.',
            'pilot_scope' => [
                ['element' => 'Durasi', 'scope' => 'Usulan awal 30 hari, disesuaikan jadwal akreditasi'],
                ['element' => 'Unit', 'scope' => 'LPM atau tim akreditasi institusi, bersama beberapa unit pemilik data'],
                ['element' => 'Pengguna', 'scope' => 'Pimpinan, ketua LPM, administrator, dan PIC kriteria'],
                ['element' => 'Data', 'scope' => 'Sejumlah indikator IAPT 4.1 dan bukti pendukung yang disepakati'],
                ['element' => 'Pendampingan', 'scope' => 'Kickoff, pemeriksaan kemajuan, dan evaluasi akhir'],
                ['element' => 'Keberhasilan', 'scope' => 'Bukti terunggah dan tervalidasi, Indeks Kesiapan terbaca, dan satu rapat tinjauan pimpinan terlaksana'],
                ['element' => 'Keputusan akhir', 'scope' => 'Lanjut, perbaiki lingkup, atau tidak melanjutkan'],
            ],
        ], [
            [
                'type' => 'webinar',
                'slug' => 'webinar-akreditasi-tanpa-kejar-tenggat',
                'title' => 'Webinar: Akreditasi Institusi Tanpa Kejar Tenggat',
                'subtitle' => 'Memahami IAPT 4.1 dan cara menyiapkan bukti sejak awal bersama Akredia',
                'summary' => 'Sesi 60-90 menit dengan studi kasus: mengapa persiapan akreditasi selalu mepet, apa yang dinilai asesor, dan bagaimana institusi memantau kesiapan setiap indikator.',
                'audience' => 'Pimpinan perguruan tinggi, LPM, tim akreditasi, dan kepala unit',
                'duration' => '60-90 menit',
                'location' => 'Online',
                'price_label' => 'Gratis, dengan pendaftaran',
                'outcomes' => ['Pemahaman struktur dan indikator IAPT 4.1', 'Contoh peta bukti dan LED yang baik', 'Checklist kesiapan akreditasi institusi'],
                'agenda' => [
                    ['part' => 'Masalah persiapan akreditasi', 'portion' => '25%', 'content' => 'Mengapa bukti tersebar dan penyusunan selalu mepet'],
                    ['part' => 'Studi kasus', 'portion' => '45%', 'content' => 'Dari peta instrumen sampai simulasi skor dan asesmen lapangan'],
                    ['part' => 'Demo singkat & tanya jawab', 'portion' => '30%', 'content' => 'Melihat alur kerja di Akredia'],
                ],
            ],
            [
                'type' => 'workshop',
                'slug' => 'workshop-peta-bukti-led-tindak-lanjut-akreditasi',
                'title' => 'Workshop Menyusun Peta Bukti, LED, dan Rencana Tindak Lanjut Akreditasi Institusi',
                'subtitle' => 'Praktik menggunakan perangkat lunak Akredia',
                'summary' => 'Workshop praktik setengah hari atau satu hari. Peserta memetakan indikator IAPT 4.1, menautkan bukti, menyusun draf narasi LED, dan menjalankan simulasi skor langsung di aplikasi, lalu membawa pulang hasilnya.',
                'audience' => 'LPM, tim akreditasi, dan PIC kriteria di perguruan tinggi',
                'duration' => 'Setengah hari / 1 hari',
                'location' => 'Online atau tatap muka',
                'price_label' => 'Berbayar per peserta atau per tim',
                'outcomes' => [
                    'Peta indikator IAPT 4.1 dan PIC per kriteria',
                    'Daftar bukti prioritas dan status validasinya',
                    'Draf narasi LED untuk satu kriteria',
                    'Hasil simulasi skor dan Indeks Kesiapan',
                    'Daftar celah dan rencana tindak lanjut',
                    'Hasil praktik yang bisa diekspor',
                ],
                'agenda' => [
                    ['part' => 'Masalah dan pendekatan', 'portion' => '20%', 'content' => 'Apa yang dinilai asesor dan mengapa bukti harus disiapkan sejak awal'],
                    ['part' => 'Praktik terpandu', 'portion' => '50%', 'content' => 'Memetakan indikator, menautkan bukti, menyusun LED, dan simulasi skor dalam aplikasi'],
                    ['part' => 'Pembahasan hasil', 'portion' => '20%', 'content' => 'Menguji kualitas bukti dan narasi peserta serta kesiapan asesmen'],
                    ['part' => 'Rencana implementasi', 'portion' => '10%', 'content' => 'Tahapan penerapan, dukungan, dan pilihan paket'],
                ],
            ],
            [
                'type' => 'inhouse',
                'slug' => 'workshop-in-house-dan-pilot-akredia',
                'title' => 'Workshop In-House & Pilot Akredia',
                'subtitle' => 'Untuk institusi yang sedang menyiapkan akreditasi',
                'summary' => 'Workshop khusus tim akreditasi Anda dengan data institusi sendiri, dilanjutkan pilot terbatas yang didampingi, dengan ukuran keberhasilan dan jadwal keputusan yang disepakati di awal.',
                'audience' => 'Perguruan tinggi negeri dan swasta yang menyiapkan akreditasi institusi',
                'duration' => 'Workshop 1-2 hari, pilot usulan 30 hari',
                'location' => 'Di kampus Anda atau online',
                'price_label' => 'Sesuai lingkup',
                'outcomes' => [
                    'Peta indikator dan bukti institusi Anda',
                    'Lingkup dan ukuran keberhasilan pilot',
                    'Satu tinjauan kesiapan bersama pimpinan',
                    'Rekomendasi lanjut, perbaiki lingkup, atau berhenti',
                ],
                'agenda' => null,
            ],
        ]);
    }

    private function seedNotaria(): void
    {
        $this->seedProduct('ruci-notaria', 40, [
            'problem_statement' => 'Kantor notaris bekerja dengan tenggat dan aturan yang ketat: berkas dan draf akta tersebar, nomor repertorium rawan salah, laporan bulanan MPD disusun manual, dan tenggat AHU atau PPAT mudah terlewat.',
            'pilot_scope' => [
                ['element' => 'Durasi', 'scope' => 'Usulan awal 30 hari, mencakup satu siklus laporan bulanan MPD'],
                ['element' => 'Unit', 'scope' => 'Satu kantor Notaris & PPAT'],
                ['element' => 'Pengguna', 'scope' => 'Notaris, staf akta, bagian keuangan, dan arsiparis'],
                ['element' => 'Data', 'scope' => 'Berkas klien dan akta yang berjalan selama masa pilot'],
                ['element' => 'Pendampingan', 'scope' => 'Kickoff, pemeriksaan kemajuan, dan evaluasi akhir'],
                ['element' => 'Keberhasilan', 'scope' => 'Akta tercatat dan bernomor di aplikasi, tenggat AHU/PPAT terpantau, dan satu laporan bulanan MPD dibuat dari sistem'],
                ['element' => 'Keputusan akhir', 'scope' => 'Lanjut, perbaiki lingkup, atau tidak melanjutkan'],
            ],
        ], [
            [
                'type' => 'webinar',
                'slug' => 'webinar-kantor-notaris-rapi-siap-diperiksa-mpd',
                'title' => 'Webinar: Kantor Notaris yang Rapi, Patuh UUJN, dan Siap Diperiksa MPD',
                'subtitle' => 'Mengenal alur kerja kantor Notaris & PPAT bersama Ruci Notaria',
                'summary' => 'Sesi 60-90 menit dengan studi kasus: di mana risiko kepatuhan biasanya muncul di kantor notaris, apa yang diperiksa MPD, dan bagaimana register, laporan, dan tenggat dikelola tanpa catatan manual.',
                'audience' => 'Notaris, PPAT, calon notaris, dan staf kantor notaris',
                'duration' => '60-90 menit',
                'location' => 'Online',
                'price_label' => 'Gratis, dengan pendaftaran',
                'outcomes' => ['Peta risiko kepatuhan UUJN di kantor notaris', 'Contoh register dan laporan bulanan MPD yang rapi', 'Checklist kesiapan digitalisasi kantor'],
                'agenda' => [
                    ['part' => 'Masalah kepatuhan kantor notaris', 'portion' => '25%', 'content' => 'Di mana kesalahan nomor akta, register, dan tenggat biasanya terjadi'],
                    ['part' => 'Studi kasus', 'portion' => '45%', 'content' => 'Dari penerimaan klien sampai laporan bulanan MPD dan penjilidan protokol'],
                    ['part' => 'Demo singkat & tanya jawab', 'portion' => '30%', 'content' => 'Melihat alur kerja di Ruci Notaria'],
                ],
            ],
            [
                'type' => 'workshop',
                'slug' => 'workshop-alur-akta-repertorium-laporan-mpd',
                'title' => 'Workshop Mengelola Alur Akta, Repertorium, dan Laporan Bulanan MPD yang Patuh UUJN',
                'subtitle' => 'Praktik menggunakan perangkat lunak Ruci Notaria',
                'summary' => 'Workshop praktik setengah hari atau satu hari. Peserta mencatat klien dan berkas, menyiapkan draf akta, menjalankan cek kesiapan akta, memberi nomor repertorium, dan membuat laporan bulanan MPD langsung di aplikasi.',
                'audience' => 'Notaris, PPAT, dan staf akta atau administrasi kantor notaris',
                'duration' => 'Setengah hari / 1 hari',
                'location' => 'Online atau tatap muka',
                'price_label' => 'Berbayar per peserta atau per kantor',
                'outcomes' => [
                    'Alur kerja berkas dari penerimaan klien sampai protokol',
                    'Contoh cek kesiapan akta berbasis aturan UUJN',
                    'Repertorium, klapper, dan register legalisasi/waarmerking',
                    'Draf laporan bulanan MPD beserta surat pengantar',
                    'Daftar tenggat AHU dan PPAT kantor Anda',
                    'Hasil praktik yang bisa diekspor',
                ],
                'agenda' => [
                    ['part' => 'Masalah dan pendekatan', 'portion' => '20%', 'content' => 'Risiko kepatuhan yang umum ditemui dan apa yang diperiksa MPD'],
                    ['part' => 'Praktik terpandu', 'portion' => '50%', 'content' => 'Mencatat berkas, cek kesiapan akta, penomoran repertorium, dan laporan MPD dalam aplikasi'],
                    ['part' => 'Pembahasan hasil', 'portion' => '20%', 'content' => 'Meninjau hasil praktik peserta dan kesiapan kantor'],
                    ['part' => 'Rencana implementasi', 'portion' => '10%', 'content' => 'Tahapan penerapan, migrasi data, dukungan, dan pilihan paket'],
                ],
            ],
            [
                'type' => 'inhouse',
                'slug' => 'pelatihan-kantor-dan-pilot-ruci-notaria',
                'title' => 'Pelatihan Kantor & Pilot Ruci Notaria',
                'subtitle' => 'Untuk kantor Notaris & PPAT yang siap beralih dari catatan manual',
                'summary' => 'Pelatihan khusus untuk notaris dan seluruh staf di kantor Anda, dilanjutkan pilot terbatas yang didampingi selama satu siklus laporan MPD, dengan ukuran keberhasilan dan jadwal keputusan yang disepakati di awal.',
                'audience' => 'Kantor Notaris & PPAT',
                'duration' => 'Pelatihan 1 hari, pilot usulan 30 hari',
                'location' => 'Di kantor Anda atau online',
                'price_label' => 'Sesuai lingkup',
                'outcomes' => [
                    'Peran dan hak akses staf kantor Anda',
                    'Lingkup dan ukuran keberhasilan pilot',
                    'Satu laporan bulanan MPD dari data nyata',
                    'Rekomendasi lanjut, perbaiki lingkup, atau berhenti',
                ],
                'agenda' => null,
            ],
        ]);
    }

    private function seedMedika(): void
    {
        $this->seedProduct('ruci-medika', 50, [
            'problem_statement' => 'Klinik sering menginput data yang sama berkali-kali: pendaftaran, rekam medis, farmasi, dan kasir berjalan terpisah, laporan BPJS dan SATUSEHAT disusun manual, dan antrean pasien sulit dikendalikan.',
            'pilot_scope' => [
                ['element' => 'Durasi', 'scope' => 'Usulan awal 30 hari, mencakup satu siklus laporan bulanan'],
                ['element' => 'Unit', 'scope' => 'Satu klinik, dimulai dari satu atau dua poli'],
                ['element' => 'Pengguna', 'scope' => 'Pimpinan klinik, pendaftaran, dokter, perawat, farmasi, dan kasir'],
                ['element' => 'Data', 'scope' => 'Kunjungan pasien, resep, dan transaksi selama masa pilot'],
                ['element' => 'Pendampingan', 'scope' => 'Kickoff, pemeriksaan kemajuan, dan evaluasi akhir'],
                ['element' => 'Keberhasilan', 'scope' => 'Kunjungan tercatat di rekam medis elektronik, data terkirim ke P-Care/SATUSEHAT, dan satu laporan bulanan dibuat dari sistem'],
                ['element' => 'Keputusan akhir', 'scope' => 'Lanjut, perbaiki lingkup, atau tidak melanjutkan'],
            ],
        ], [
            [
                'type' => 'webinar',
                'slug' => 'webinar-klinik-tanpa-input-ulang',
                'title' => 'Webinar: Klinik Tanpa Input Ulang, dari Pendaftaran sampai Laporan BPJS',
                'subtitle' => 'Mengenal alur kerja klinik terpadu bersama Ruci Medika',
                'summary' => 'Sesi 60-90 menit dengan studi kasus: di mana waktu dan data klinik hilang, apa yang dibutuhkan untuk BPJS P-Care dan SATUSEHAT, dan bagaimana laporan bulanan dibuat tanpa rekap manual.',
                'audience' => 'Pemilik dan pimpinan klinik, dokter penanggung jawab, serta staf administrasi klinik',
                'duration' => '60-90 menit',
                'location' => 'Online',
                'price_label' => 'Gratis, dengan pendaftaran',
                'outcomes' => [
                    'Peta alur pasien dan titik input ganda di klinik',
                    'Gambaran kewajiban integrasi P-Care dan SATUSEHAT',
                    'Checklist kesiapan rekam medis elektronik',
                ],
                'agenda' => [
                    ['part' => 'Masalah operasional klinik', 'portion' => '25%', 'content' => 'Mengapa data diinput berulang dan laporan selalu terlambat'],
                    ['part' => 'Studi kasus', 'portion' => '45%', 'content' => 'Dari pendaftaran dan antrean sampai laporan LB1 dan KBK BPJS'],
                    ['part' => 'Demo singkat & tanya jawab', 'portion' => '30%', 'content' => 'Melihat alur kerja di Ruci Medika'],
                ],
            ],
            [
                'type' => 'workshop',
                'slug' => 'workshop-alur-pasien-rekam-medis-laporan-klinik',
                'title' => 'Workshop Menata Alur Pasien, Rekam Medis Elektronik, dan Laporan Klinik yang Siap Diperiksa',
                'subtitle' => 'Praktik menggunakan perangkat lunak Ruci Medika',
                'summary' => 'Workshop praktik setengah hari atau satu hari. Peserta menjalankan alur pasien dari pendaftaran, pemeriksaan SOAP, e-resep, sampai kasir, lalu menyusun laporan bulanan dan indikator mutu langsung di aplikasi.',
                'audience' => 'Pimpinan klinik, dokter, perawat, petugas farmasi, dan administrasi klinik',
                'duration' => 'Setengah hari / 1 hari',
                'location' => 'Online atau tatap muka',
                'price_label' => 'Berbayar per peserta atau per klinik',
                'outcomes' => [
                    'Alur pasien klinik Anda dari pendaftaran sampai kasir',
                    'Contoh rekam medis SOAP dengan ICD-10',
                    'Pengaturan stok farmasi per batch dan kedaluwarsa',
                    'Contoh laporan LB1, kunjungan, dan pendapatan',
                    'Daftar indikator mutu dan kebutuhan akreditasi klinik',
                    'Hasil praktik yang bisa diekspor',
                ],
                'agenda' => [
                    ['part' => 'Masalah dan pendekatan', 'portion' => '20%', 'content' => 'Titik rawan alur pasien dan kewajiban pelaporan klinik'],
                    ['part' => 'Praktik terpandu', 'portion' => '50%', 'content' => 'Pendaftaran, SOAP, e-resep, kasir, dan laporan dalam aplikasi'],
                    ['part' => 'Pembahasan hasil', 'portion' => '20%', 'content' => 'Meninjau hasil praktik peserta dan kesiapan klinik'],
                    ['part' => 'Rencana implementasi', 'portion' => '10%', 'content' => 'Tahapan penerapan, migrasi data, integrasi, dukungan, dan pilihan paket'],
                ],
            ],
            [
                'type' => 'inhouse',
                'slug' => 'pelatihan-klinik-dan-pilot-ruci-medika',
                'title' => 'Pelatihan Klinik & Pilot Ruci Medika',
                'subtitle' => 'Untuk klinik yang siap beralih ke rekam medis elektronik',
                'summary' => 'Pelatihan khusus untuk seluruh tim klinik Anda, dilanjutkan pilot terbatas yang didampingi selama satu siklus laporan bulanan, dengan ukuran keberhasilan dan jadwal keputusan yang disepakati di awal.',
                'audience' => 'Klinik pratama dan klinik utama',
                'duration' => 'Pelatihan 1 hari, pilot usulan 30 hari',
                'location' => 'Di klinik Anda atau online',
                'price_label' => 'Sesuai lingkup',
                'outcomes' => [
                    'Peran dan hak akses tim klinik Anda',
                    'Lingkup dan ukuran keberhasilan pilot',
                    'Satu laporan bulanan dari data nyata',
                    'Rekomendasi lanjut, perbaiki lingkup, atau berhenti',
                ],
                'agenda' => null,
            ],
        ]);
    }

    private function seedPrima(): void
    {
        $this->seedProduct('ruci-prima', 60, [
            'problem_statement' => 'Aset, ruang rapat, kendaraan, dan perizinan perusahaan sering dicatat di spreadsheet yang berbeda: jadwal bentrok, aset sulit dilacak, izin kedaluwarsa tanpa peringatan, dan pimpinan tidak punya gambaran utuh.',
            'pilot_scope' => [
                ['element' => 'Durasi', 'scope' => 'Usulan awal 30 hari, disesuaikan kebutuhan'],
                ['element' => 'Unit', 'scope' => 'Bagian umum atau general affairs, dengan satu lokasi kantor'],
                ['element' => 'Pengguna', 'scope' => 'Pimpinan, staf general affairs, administrator, dan beberapa pemohon dari unit'],
                ['element' => 'Data', 'scope' => 'Sejumlah aset, ruang rapat, kendaraan, dan izin yang disepakati'],
                ['element' => 'Pendampingan', 'scope' => 'Kickoff, pemeriksaan kemajuan, dan evaluasi akhir'],
                ['element' => 'Keberhasilan', 'scope' => 'Aset dan izin tercatat, pemesanan ruang dan kendaraan berjalan di aplikasi, dan dasbor dipakai dalam satu rapat pimpinan'],
                ['element' => 'Keputusan akhir', 'scope' => 'Lanjut, perbaiki lingkup, atau tidak melanjutkan'],
            ],
        ], [
            [
                'type' => 'webinar',
                'slug' => 'webinar-fasilitas-tertata-keputusan-lebih-jernih',
                'title' => 'Webinar: Fasilitas Tertata, Keputusan Lebih Jernih',
                'subtitle' => 'Mengelola ruang, inventaris, kendaraan, dan perizinan bersama Ruci PRIMA',
                'summary' => 'Sesi 60-90 menit dengan studi kasus: biaya tersembunyi dari aset yang tidak terlacak, jadwal ruang dan kendaraan yang bentrok, serta izin yang kedaluwarsa, dan cara pimpinan memantau semuanya dari satu dasbor.',
                'audience' => 'Pimpinan perusahaan, kepala general affairs, dan pengelola aset atau fasilitas',
                'duration' => '60-90 menit',
                'location' => 'Online',
                'price_label' => 'Gratis, dengan pendaftaran',
                'outcomes' => [
                    'Peta masalah pengelolaan fasilitas dan aset',
                    'Contoh register aset dan izin yang rapi',
                    'Checklist kesiapan digitalisasi general affairs',
                ],
                'agenda' => [
                    ['part' => 'Masalah pengelolaan fasilitas', 'portion' => '25%', 'content' => 'Mengapa aset, ruang, dan izin sulit dikendalikan dengan spreadsheet'],
                    ['part' => 'Studi kasus', 'portion' => '45%', 'content' => 'Dari pemesanan ruang dan kendaraan sampai dasbor pimpinan'],
                    ['part' => 'Demo singkat & tanya jawab', 'portion' => '30%', 'content' => 'Melihat alur kerja di Ruci PRIMA'],
                ],
            ],
            [
                'type' => 'workshop',
                'slug' => 'workshop-register-aset-ruang-kendaraan-perizinan',
                'title' => 'Workshop Menyusun Register Aset, Jadwal Ruang & Kendaraan, dan Pemantauan Perizinan Perusahaan',
                'subtitle' => 'Praktik menggunakan perangkat lunak Ruci PRIMA',
                'summary' => 'Workshop praktik setengah hari atau satu hari. Peserta mencatat aset dan inventaris, mengatur pemesanan ruang dan kendaraan, memasang pengingat perizinan, dan menyiapkan dasbor pimpinan langsung di aplikasi.',
                'audience' => 'Tim general affairs, pengelola aset, fasilitas, dan administrasi perusahaan',
                'duration' => 'Setengah hari / 1 hari',
                'location' => 'Online atau tatap muka',
                'price_label' => 'Berbayar per peserta atau per tim',
                'outcomes' => [
                    'Register aset dan inventaris dengan penanggung jawab',
                    'Aturan pemesanan ruang rapat dan kendaraan',
                    'Daftar izin perusahaan beserta pengingat masa berlaku',
                    'Contoh dasbor fasilitas untuk pimpinan',
                    'Pembagian peran dan hak akses tim',
                    'Hasil praktik yang bisa diekspor',
                ],
                'agenda' => [
                    ['part' => 'Masalah dan pendekatan', 'portion' => '20%', 'content' => 'Biaya dan risiko dari fasilitas yang tidak terkelola'],
                    ['part' => 'Praktik terpandu', 'portion' => '50%', 'content' => 'Mencatat aset, mengatur ruang dan kendaraan, serta perizinan dalam aplikasi'],
                    ['part' => 'Pembahasan hasil', 'portion' => '20%', 'content' => 'Meninjau hasil praktik peserta dan kesiapan penerapan'],
                    ['part' => 'Rencana implementasi', 'portion' => '10%', 'content' => 'Tahapan penerapan, migrasi data, dukungan, dan pilihan paket'],
                ],
            ],
            [
                'type' => 'inhouse',
                'slug' => 'workshop-in-house-dan-pilot-ruci-prima',
                'title' => 'Workshop In-House & Pilot Ruci PRIMA',
                'subtitle' => 'Untuk perusahaan yang ingin menertibkan aset dan fasilitas',
                'summary' => 'Workshop khusus tim Anda dengan data aset dan fasilitas perusahaan sendiri, dilanjutkan pilot terbatas yang didampingi, dengan ukuran keberhasilan dan jadwal keputusan yang disepakati di awal.',
                'audience' => 'Perusahaan, perhotelan dan MICE, transportasi, serta industri makanan dan rantai dingin',
                'duration' => 'Workshop 1 hari, pilot usulan 30 hari',
                'location' => 'Di lokasi Anda atau online',
                'price_label' => 'Sesuai lingkup',
                'outcomes' => [
                    'Register aset dan fasilitas perusahaan Anda',
                    'Lingkup dan ukuran keberhasilan pilot',
                    'Satu rapat pimpinan dengan dasbor nyata',
                    'Rekomendasi lanjut, perbaiki lingkup, atau berhenti',
                ],
                'agenda' => null,
            ],
        ]);
    }

    private function seedMufakat(): void
    {
        $this->seedProduct('ruci-mufakat', 70, [
            'problem_statement' => 'Bahan rapat Direksi dan Dewan Komisaris sering tersebar di email dan grup pesan, keputusan sulit ditelusuri sumbernya, dan tindak lanjut dinyatakan selesai tanpa bukti yang bisa diverifikasi.',
            'pilot_scope' => [
                ['element' => 'Durasi', 'scope' => 'Usulan awal 30-60 hari, mencakup satu siklus rapat'],
                ['element' => 'Unit', 'scope' => 'Sekretariat perusahaan bersama Direksi atau Dewan Komisaris'],
                ['element' => 'Pengguna', 'scope' => 'Sekretaris perusahaan, anggota Direksi atau Dewan Komisaris, dan beberapa PIC tindak lanjut'],
                ['element' => 'Data', 'scope' => 'Agenda, bahan, dan keputusan dari rapat yang berjalan selama pilot'],
                ['element' => 'Pendampingan', 'scope' => 'Kickoff, pemeriksaan kemajuan, dan evaluasi akhir'],
                ['element' => 'Keberhasilan', 'scope' => 'Satu rapat berjalan penuh di aplikasi, keputusan tercatat beserta sumbernya, dan tindak lanjut ditutup dengan bukti terverifikasi'],
                ['element' => 'Keputusan akhir', 'scope' => 'Lanjut, perbaiki lingkup, atau tidak melanjutkan'],
            ],
        ], [
            [
                'type' => 'webinar',
                'slug' => 'webinar-rapat-direksi-keputusan-tindak-lanjut-terbukti',
                'title' => 'Webinar: Rapat Direksi dan Komisaris yang Keputusannya Terlacak dan Tindak Lanjutnya Terbukti',
                'subtitle' => 'Praktik tata kelola rapat bersama Ruci MUFAKAT',
                'summary' => 'Sesi 60-90 menit dengan studi kasus: mengapa keputusan rapat sering berhenti di notulen, bagaimana memisahkan fungsi pengurusan dan pengawasan, dan cara membuktikan tindak lanjut sudah benar-benar selesai.',
                'audience' => 'Direksi, Dewan Komisaris, sekretaris perusahaan, dan komite',
                'duration' => '60-90 menit',
                'location' => 'Online',
                'price_label' => 'Gratis, dengan pendaftaran',
                'outcomes' => [
                    'Peta risiko tata kelola rapat dan keputusan',
                    'Contoh risalah, keputusan, dan bukti tindak lanjut yang baik',
                    'Checklist kesiapan board management digital',
                ],
                'agenda' => [
                    ['part' => 'Masalah tata kelola rapat', 'portion' => '25%', 'content' => 'Mengapa keputusan sulit ditelusuri dan tindak lanjut tidak terbukti'],
                    ['part' => 'Studi kasus', 'portion' => '45%', 'content' => 'Dari usulan dan agenda sampai keputusan dan verifikasi tindak lanjut'],
                    ['part' => 'Demo singkat & tanya jawab', 'portion' => '30%', 'content' => 'Melihat alur kerja di Ruci MUFAKAT'],
                ],
            ],
            [
                'type' => 'workshop',
                'slug' => 'workshop-agenda-keputusan-tindak-lanjut-rapat-direksi',
                'title' => 'Workshop Menyusun Agenda, Keputusan, dan Tindak Lanjut Rapat Direksi & Dewan Komisaris yang Dapat Diverifikasi',
                'subtitle' => 'Praktik menggunakan perangkat lunak Ruci MUFAKAT',
                'summary' => 'Workshop praktik setengah hari. Peserta menyiapkan usulan dan agenda, mengelola bahan rapat, mencatat keputusan beserta sumbernya, dan menutup tindak lanjut dengan bukti langsung di aplikasi.',
                'audience' => 'Sekretaris perusahaan, staf sekretariat Direksi dan Dewan Komisaris, serta komite',
                'duration' => 'Setengah hari',
                'location' => 'Online atau tatap muka',
                'price_label' => 'Berbayar per peserta atau per tim',
                'outcomes' => [
                    'Alur usulan, agenda, dan bahan rapat organisasi Anda',
                    'Contoh keputusan yang tercatat beserta sumbernya',
                    'Daftar tindak lanjut dengan PIC dan bukti penyelesaian',
                    'Pemisahan akses pengurusan dan pengawasan',
                    'Contoh laporan akuntabilitas Direksi dan Dewan Komisaris',
                    'Hasil praktik yang bisa diekspor',
                ],
                'agenda' => [
                    ['part' => 'Masalah dan pendekatan', 'portion' => '20%', 'content' => 'Risiko tata kelola dari rapat yang tidak terdokumentasi'],
                    ['part' => 'Praktik terpandu', 'portion' => '50%', 'content' => 'Menyusun agenda, mencatat keputusan, dan memverifikasi tindak lanjut dalam aplikasi'],
                    ['part' => 'Pembahasan hasil', 'portion' => '20%', 'content' => 'Meninjau hasil praktik peserta dan kesiapan penerapan'],
                    ['part' => 'Rencana implementasi', 'portion' => '10%', 'content' => 'Tahapan penerapan, keamanan data, dukungan, dan pilihan paket'],
                ],
            ],
            [
                'type' => 'inhouse',
                'slug' => 'sesi-in-house-dan-pilot-ruci-mufakat',
                'title' => 'Sesi In-House & Pilot Ruci MUFAKAT',
                'subtitle' => 'Untuk Direksi dan Dewan Komisaris yang ingin rapat dan keputusannya terdokumentasi',
                'summary' => 'Sesi khusus bersama sekretariat, Direksi, atau Dewan Komisaris Anda, dilanjutkan pilot terbatas yang didampingi selama satu siklus rapat, dengan ukuran keberhasilan dan jadwal keputusan yang disepakati di awal.',
                'audience' => 'BUMN, BUMD, perusahaan terbuka, dan perusahaan keluarga',
                'duration' => 'Sesi 1 hari, pilot usulan 30-60 hari',
                'location' => 'Di lokasi Anda atau online',
                'price_label' => 'Sesuai lingkup',
                'outcomes' => [
                    'Alur rapat dan hak akses organisasi Anda',
                    'Lingkup dan ukuran keberhasilan pilot',
                    'Satu rapat penuh dengan keputusan dan tindak lanjut terverifikasi',
                    'Rekomendasi lanjut, perbaiki lingkup, atau berhenti',
                ],
                'agenda' => null,
            ],
        ]);
    }

    /**
     * Sets a product's funnel content and upserts its Academy programs.
     */
    private function seedProduct(string $slug, int $sortBase, array $attributes, array $items): void
    {
        $solution = Solution::where('slug', $slug)->first();
        if (! $solution) {
            return;
        }

        $solution->update($attributes);

        foreach ($items as $i => $item) {
            CatalogItem::updateOrCreate(['slug' => $item['slug']], $item + [
                'solution_id' => $solution->id,
                'registration_open' => true,
                'is_active' => true,
                'sort_order' => $sortBase + $i,
            ]);
        }
    }
}
