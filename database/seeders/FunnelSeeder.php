<?php

namespace Database\Seeders;

use App\Models\CatalogItem;
use App\Models\Solution;
use App\Models\Stage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Sales-funnel content: the "cara bekerja sama" steps, the Ruci BSC pilot scope,
 * and the Rucisoft Academy programs per product (Ruci BSC, Akredia, Ruci Notaria).
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
