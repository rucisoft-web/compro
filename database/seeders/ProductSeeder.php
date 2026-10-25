<?php

namespace Database\Seeders;

use App\Models\CatalogItem;
use App\Models\Solution;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Replaces the original six solutions with the current Rucisoft product line.
 *
 * Safe to run repeatedly: old seeded products are removed, new ones are created
 * or refreshed by slug. Catalog items added by hand in the admin are kept.
 */
class ProductSeeder extends Seeder
{
    private const OLD_SOLUTIONS = [
        'ruci-bsc-professional', 'rucilegal-professional', 'ruciesg',
        'rucinexa-professional', 'rucitop', 'ruciai-workspace',
    ];

    private const OLD_CATALOG_ITEMS = [
        'panduan-balanced-scorecard-untuk-organisasi-contoh',
        'workshop-penyusunan-strategy-map-kpi-contoh',
    ];

    public function run(): void
    {
        DB::transaction(function () {
            CatalogItem::whereIn('slug', array_merge(
                self::OLD_CATALOG_ITEMS,
                array_map(fn ($slug) => 'software-'.$slug, self::OLD_SOLUTIONS),
            ))->delete();
            Solution::whereIn('slug', self::OLD_SOLUTIONS)->delete();

            foreach ($this->products() as $i => $product) {
                $solution = Solution::updateOrCreate(['slug' => $product['slug']], [
                    'name' => $product['name'],
                    'tagline' => $product['tagline'],
                    'description' => $product['description'],
                    'features' => $product['features'],
                    'icon' => $product['icon'],
                    'logo_url' => $product['logo_url'],
                    'image_url' => $product['image_url'],
                    'website_url' => $product['website_url'],
                    'sort_order' => $i + 1,
                    'is_active' => true,
                ]);

                CatalogItem::updateOrCreate(['slug' => 'software-'.$solution->slug], [
                    'type' => 'software',
                    'solution_id' => $solution->id,
                    'title' => $solution->name,
                    'summary' => $product['summary'],
                    'body' => $product['description'],
                    'sort_order' => $i + 1,
                    'is_active' => true,
                ]);
            }
        });
    }

    private function products(): array
    {
        return [
            [
                'slug' => 'ruci-bsc',
                'name' => 'Ruci BSC',
                'icon' => 'heroicon-o-presentation-chart-line',
                'tagline' => 'Strategi yang dipahami semua orang, dijalankan setiap bulan.',
                'summary' => 'Manajemen strategi berbasis Balanced Scorecard, dari analisis lingkungan hingga rapat tinjauan dan tindak lanjut.',
                'features' => [
                    'Analisis PESTEL, Porter & SWOT-TOWS',
                    'Peta strategi, KPI & target bulanan',
                    'Inisiatif, milestone & anggaran',
                    'Rapat tinjauan & tindakan perbaikan',
                    'Pusat Kendali dengan peringatan dini',
                    'Audit trail & multi-perusahaan',
                ],
                'description' => '<p>Ruci BSC mengelola strategi berbasis Balanced Scorecard secara utuh: analisis lingkungan dan SWOT-TOWS, peta strategi empat perspektif, KPI dan target bulanan, inisiatif dan anggaran, hingga rapat tinjauan dan tindak lanjut.</p>'
                    .'<h3>Lima fase pengelolaan strategi</h3><ol>'
                    .'<li><strong>Analisis:</strong> PESTEL, lima kekuatan Porter, pasar, pesaing, regulasi, dan pemangku kepentingan.</li>'
                    .'<li><strong>Formulasi:</strong> SWOT-TOWS, isu strategis, visi-misi-nilai, dan pilihan strategi.</li>'
                    .'<li><strong>Peta Strategi:</strong> sasaran empat perspektif dengan hubungan sebab-akibat, kamus KPI, dan target bulanan.</li>'
                    .'<li><strong>Eksekusi:</strong> inisiatif, milestone, aktivitas, anggaran dan realisasi, serta input capaian bulanan.</li>'
                    .'<li><strong>Tinjauan:</strong> rapat tinjauan, temuan, tindakan perbaikan, dan penilaian kematangan.</li></ol>'
                    .'<p>Data unit diverifikasi Keuangan dan disetujui Direksi sebagai satu sumber kebenaran, dengan audit trail, akses berbasis peran, dan laporan siap untuk Dewan Komisaris.</p>',
                'logo_url' => 'https://bsc.rucisoft.id/images/rucisoft-logo.svg',
                'image_url' => 'https://bsc.rucisoft.id/images/kenali/pusat-kendali.jpg?v=1',
                'website_url' => 'https://bsc.rucisoft.id/',
            ],
            [
                'slug' => 'akredia',
                'name' => 'Akredia',
                'icon' => 'heroicon-o-academic-cap',
                'tagline' => 'Akreditasi institusi yang terukur, terbukti, dan siap diasesmen.',
                'summary' => 'Persiapan akreditasi institusi IAPT 4.1 dalam satu tempat: evaluasi diri, bukti, LED, LKPT, dan tindak lanjut.',
                'features' => [
                    'Dasbor pimpinan & Indeks Kesiapan Akreditasi',
                    'Peta instrumen IAPT 4.1 (39 indikator)',
                    'Pustaka bukti berversi & tervalidasi',
                    'Penyusunan LED & simulasi skor',
                    'Tabel LKPT & rekonsiliasi PDDikti',
                    'SPMI & persiapan asesmen lapangan',
                ],
                'description' => '<p>Akredia menyatukan persiapan akreditasi institusi IAPT 4.1 perguruan tinggi: evaluasi diri per subindikator, bukti berversi, narasi LED, tabel LKPT, dan tindak lanjut. Pimpinan melihat kesiapan sebagai satu skor dan tahu siapa yang perlu bertindak minggu ini.</p>'
                    .'<ul><li>Indeks Kesiapan Akreditasi dan proyeksi skor jalur Terakreditasi maupun Unggul.</li>'
                    .'<li>4 kriteria, 39 indikator, dan 64 subindikator untuk delapan varian Buku 3 (PTN-BH, PTN, PTS, vokasi, PJJ).</li>'
                    .'<li>Impor data dosen, mahasiswa, publikasi, dan penelitian; 18 metrik IAPT dan 12 tabel LKPT.</li>'
                    .'<li>Rekonsiliasi dengan PDDikti, SPMI dan CAPA, papan Kanban, register risiko, serta portal bukti untuk asesor.</li></ul>',
                'logo_url' => 'https://akredia.rucisoft.id/favicon.svg',
                'image_url' => 'https://akredia.rucisoft.id/img/pimpinan-dasbor.jpg',
                'website_url' => 'https://akredia.rucisoft.id/',
            ],
            [
                'slug' => 'ruci-prima',
                'name' => 'Ruci PRIMA',
                'icon' => 'heroicon-o-building-office-2',
                'tagline' => 'Fasilitas tertata, keputusan lebih jernih.',
                'summary' => 'Pengelolaan Ruang, Inventaris, Mobilitas & Administrasi perusahaan dalam satu sistem dengan dasbor pimpinan.',
                'features' => [
                    'Ruang rapat & penjadwalan',
                    'Inventaris & aset perusahaan',
                    'Mobilitas & armada kendaraan',
                    'Administrasi & perizinan',
                    'Dasbor untuk pimpinan',
                    'Akses berbasis peran & jejak audit',
                ],
                'description' => '<p>Ruci PRIMA (Pengelolaan Ruang, Inventaris, Mobilitas &amp; Administrasi) adalah satu sistem untuk mengelola aset perusahaan, ruang rapat, kendaraan, persediaan, dan perizinan, lengkap dengan dasbor yang siap ditinjau pimpinan.</p>'
                    .'<p>Data terenkripsi, akses berbasis peran, dan setiap perubahan tercatat dalam jejak audit. Cocok untuk perhotelan dan MICE, transportasi, hingga industri makanan dan rantai dingin.</p>',
                'logo_url' => 'https://prima.rucisoft.id/brand/ruci-prima-logo-simple.svg',
                'image_url' => null,
                'website_url' => 'https://prima.rucisoft.id/',
            ],
            [
                'slug' => 'ruci-notaria',
                'name' => 'Ruci Notaria',
                'icon' => 'heroicon-o-document-text',
                'tagline' => 'Kantor notaris yang rapi, patuh UUJN, dan siap diperiksa MPD.',
                'summary' => 'Aplikasi kantor Notaris & PPAT: dari penerimaan klien, draf akta, penomoran repertorium, hingga laporan bulanan MPD.',
                'features' => [
                    'Cek kesiapan akta berbasis aturan UUJN',
                    'Klien, berkas & draf akta (.docx)',
                    'Penomoran repertorium terkunci',
                    'Legalisasi, waarmerking & laporan MPD',
                    'Pengingat tenggat AHU & PPAT',
                    'Tagihan honorarium, titipan & protokol',
                ],
                'description' => '<p>Ruci Notaria adalah aplikasi web untuk kantor Notaris dan PPAT di Indonesia. Setiap berkas dilacak dari penerimaan klien sampai penjilidan protokol, nomor akta dikunci, register dan laporan bulanan MPD dibuat otomatis, dan tenggat hukum diingatkan.</p>'
                    .'<ul><li>Cek kesiapan akta sebelum tanda tangan: usia dan status pihak, masa berlaku identitas, saksi, hubungan keluarga, dan wilayah jabatan.</li>'
                    .'<li>Repertorium, klapper, legalisasi, waarmerking, wasiat, dan protes siap cetak, termasuk surat pengantar ke MPD.</li>'
                    .'<li>Tenggat AHU (pendirian PT, fidusia) dan PPAT dihitung dengan hari kerja.</li>'
                    .'<li>Peran Notaris, Staf, Keuangan, Arsiparis, dan Admin TI, MFA, NIK/NPWP terenkripsi, serta audit trail berantai hash.</li></ul>'
                    .'<p>Berjalan di hosting cPanel biasa (PHP 8.3, MySQL 8).</p>',
                'logo_url' => 'https://notaria.rucisoft.id/img/logo/ruci-notaria.svg',
                'image_url' => 'https://notaria.rucisoft.id/img/landing/dasbor.png',
                'website_url' => 'https://notaria.rucisoft.id/',
            ],
            [
                'slug' => 'ruci-medika',
                'name' => 'Ruci Medika',
                'icon' => 'heroicon-o-heart',
                'tagline' => 'Pasien terlayani lebih cepat, data klinik tertib dan siap dilaporkan.',
                'summary' => 'Aplikasi klinik yang menyatukan pendaftaran, rekam medis elektronik, farmasi, laboratorium, dan kasir, terhubung BPJS P-Care & SATUSEHAT.',
                'features' => [
                    'Pendaftaran & antrean per poli',
                    'Rekam medis elektronik (SOAP, ICD-10)',
                    'Farmasi & laboratorium',
                    'Kasir BPJS, asuransi & perusahaan',
                    'Integrasi BPJS P-Care & SATUSEHAT',
                    'Laporan LB1, mutu & akreditasi klinik',
                ],
                'description' => '<p>Ruci Medika menyatukan pendaftaran, rekam medis elektronik, farmasi, laboratorium, dan kasir dalam satu alur kerja klinik. Data kunjungan dikirim ke BPJS P-Care dan SATUSEHAT tanpa input ulang, dan laporan bulanan dibuat otomatis.</p>'
                    .'<ul><li>Antrean per poli dengan layar TV dan panggilan suara.</li>'
                    .'<li>Asesmen awal, SOAP, diagnosis ICD-10, tindakan ICD-9-CM, rujukan, dan surat keterangan.</li>'
                    .'<li>E-resep, stok per batch dengan kedaluwarsa (FEFO), serta hasil laboratorium dengan nilai rujukan.</li>'
                    .'<li>Laporan kunjungan, 10 besar penyakit, LB1, pendapatan, SIPNAP, dan KBK BPJS.</li>'
                    .'<li>Indikator mutu, pelaporan insiden keselamatan pasien, dan pengingat STR/SIP tenaga kesehatan.</li></ul>'
                    .'<p>Cocok untuk klinik pratama dan klinik utama, dan berjalan di hosting cPanel biasa.</p>',
                'logo_url' => 'https://medika.rucisoft.id/img/logo/ruci-medika.svg',
                'image_url' => 'https://medika.rucisoft.id/img/landing/dasbor.png',
                'website_url' => 'https://medika.rucisoft.id/',
            ],
            [
                'slug' => 'ruci-mufakat',
                'name' => 'Ruci MUFAKAT',
                'icon' => 'heroicon-o-user-group',
                'tagline' => 'Dari bahan rapat sampai bukti tindak lanjut.',
                'summary' => 'Board management & AI untuk Direksi dan Dewan Komisaris: rapat, keputusan, dan tindak lanjut yang terbukti.',
                'features' => [
                    'Musyawarah & forum rapat',
                    'Usulan & agenda rapat',
                    'Keputusan tercatat beserta sumbernya',
                    'Akuntabilitas Direksi & Dewan Komisaris',
                    'Tindak lanjut selesai setelah bukti terverifikasi',
                ],
                'description' => '<p>Ruci MUFAKAT adalah ruang kerja Direksi dan Dewan Komisaris yang memisahkan fungsi pengurusan dan pengawasan. Setiap keputusan dicatat bersama sumbernya, dan tugas baru dinyatakan selesai setelah bukti tindak lanjutnya diverifikasi.</p>'
                    .'<p>Tujuh modulnya membentuk nama MUFAKAT: <strong>M</strong>usyawarah, <strong>U</strong>sulan, <strong>F</strong>orum, <strong>A</strong>genda, <strong>K</strong>eputusan, <strong>A</strong>kuntabilitas, dan <strong>T</strong>indak lanjut.</p>',
                'logo_url' => 'https://mufakat.rucisoft.id/favicon.svg',
                'image_url' => null,
                'website_url' => 'https://mufakat.rucisoft.id/',
            ],
        ];
    }
}
