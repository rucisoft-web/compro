<?php

namespace Database\Seeders;

use App\Models\CatalogItem;
use App\Models\SoftwareCategory;
use App\Models\Solution;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Software categories plus the products that live on *.rucisoft.id subdomains.
 *
 * Content comes from each app's landing page (or its README when the page was
 * not reachable). Safe to run repeatedly: categories are refreshed by slug,
 * new products are created once, and products already in the database keep
 * every edit made in the admin; they only get a category when they have none.
 */
class SoftwareCatalogSeeder extends Seeder
{
    private const RENAMED = [
        'gpch-bsc' => 'bsc-hotel-konvensi',
        'gpch-crm' => 'crm-hotel-konvensi',
        'gpch-ifm' => 'ifm-hotel-konvensi',
    ];

    public function run(): void
    {
        DB::transaction(function () {
            $categories = [];
            foreach ($this->categories() as $i => [$slug, $name, $icon, $description, $members]) {
                $categories[$slug] = SoftwareCategory::updateOrCreate(['slug' => $slug], [
                    'name' => $name,
                    'icon' => $icon,
                    'description' => $description,
                    'sort_order' => $i + 1,
                ]);

                foreach ($members as $productSlug) {
                    Solution::where('slug', $productSlug)->whereNull('software_category_id')
                        ->update(['software_category_id' => $categories[$slug]->id]);
                }
            }

            // Early runs used the client's name; the presentation is still in progress.
            foreach (self::RENAMED as $old => $new) {
                if (! Solution::where('slug', $new)->exists()) {
                    Solution::where('slug', $old)->delete();
                    CatalogItem::where('slug', 'software-'.$old)->delete();
                }
            }

            $sort = (int) Solution::max('sort_order');

            foreach ($this->products() as $product) {
                $solution = Solution::firstWhere('slug', $product['slug']);

                if (! $solution) {
                    $solution = Solution::create([
                        'software_category_id' => $categories[$product['category']]->id,
                        'slug' => $product['slug'],
                        'name' => $product['name'],
                        'icon' => $product['icon'],
                        'tagline' => $product['tagline'],
                        'problem_statement' => $product['problem'],
                        'features' => $product['features'],
                        'description' => $product['description'],
                        'logo_url' => $product['logo_url'] ?? null,
                        'image_url' => $product['image_url'] ?? null,
                        'website_url' => $product['website_url'],
                        'sort_order' => ++$sort,
                        'is_active' => true,
                    ]);
                } elseif (! $solution->software_category_id) {
                    $solution->update(['software_category_id' => $categories[$product['category']]->id]);
                }

                CatalogItem::firstOrCreate(['slug' => 'software-'.$solution->slug], [
                    'type' => 'software',
                    'solution_id' => $solution->id,
                    'title' => $solution->name,
                    'summary' => $product['summary'],
                    'body' => $product['description'],
                    'sort_order' => $solution->sort_order,
                    'is_active' => true,
                ]);
            }
        });
    }

    /**
     * [slug, name, icon, description, product slugs].
     */
    private function categories(): array
    {
        return [
            ['strategi-kinerja', 'Strategi & Kinerja', 'heroicon-o-presentation-chart-line',
                'Balanced Scorecard, KPI, Perjanjian Kinerja, dan Kontrak Kinerja untuk perusahaan, instansi, dan BLU.',
                ['ruci-bsc', 'ruci-bsc-blu', 'kinera']],
            ['tata-kelola-kepatuhan', 'Tata Kelola, Risiko & Kepatuhan', 'heroicon-o-shield-check',
                'Rapat organ perusahaan, GRC, akreditasi, keselamatan kontraktor, dan perizinan yang terdokumentasi dan siap diperiksa.',
                ['ruci-mufakat', 'pranata', 'akredia', 'rekana', 'izinara']],
            ['aset-fasilitas', 'Aset & Fasilitas', 'heroicon-o-wrench-screwdriver',
                'Register aset, label QR, pemeliharaan, gangguan, ruang, dan kendaraan untuk kantor, hotel, kampus, dan rumah sakit.',
                ['ruci-prima', 'ruci-asset-care', 'siaga']],
            ['proyek-layanan-ti', 'Proyek & Layanan TI', 'heroicon-o-server-stack',
                'Manajemen proyek berbasis PMBOK dan layanan TI berbasis ITIL untuk PMO dan tim TI grup usaha.',
                ['promise', 'simpul']],
            ['pelanggan-hotel-ritel', 'Pelanggan, Hotel & Ritel', 'heroicon-o-users',
                'CRM tamu dan loyalitas untuk hotel, serta POS dan operasional outlet untuk ritel dan restoran.',
                ['ruci-setia', 'rasentra']],
            ['klinik-kantor-profesi', 'Klinik & Kantor Profesi', 'heroicon-o-heart',
                'Aplikasi siap pakai untuk klinik pratama dan utama serta kantor notaris dan PPAT.',
                ['ruci-medika', 'ruci-notaria']],
            ['edisi-klien', 'Edisi Klien', 'heroicon-o-building-office-2',
                'Aplikasi yang disesuaikan dan dijalankan untuk satu klien, sebagai contoh implementasi Rucisoft di lapangan.',
                ['bsc-hotel-konvensi', 'crm-hotel-konvensi', 'ifm-hotel-konvensi']],
        ];
    }

    private function products(): array
    {
        return [
            [
                'slug' => 'ruci-bsc-blu',
                'category' => 'strategi-kinerja',
                'name' => 'Ruci BSC BLU',
                'icon' => 'heroicon-o-chart-bar',
                'tagline' => 'Kontrak Kinerja BLU yang terpantau, terbukti, dan tepat waktu.',
                'problem' => 'Kontrak Kinerja Pemimpin BLU dinilai setiap triwulan, tetapi datanya tersebar dan laporannya disusun manual menjelang tenggat.',
                'summary' => 'Balanced Scorecard dan Kontrak Kinerja Pemimpin BLU sesuai PER-6/PB/2025, dari Indikator Kinerja sampai lampiran untuk DJPb.',
                'features' => [
                    'Peta strategi BLU',
                    'Indikator Kinerja dengan Definisi Operasional',
                    'Target trajektori triwulanan',
                    'Peringatan capaian di bawah 65%',
                    'Uji syarat insentif Pasal 14',
                    'Cetak lampiran Kontrak Kinerja',
                ],
                'description' => '<p>Ruci BSC BLU membantu Badan Layanan Umum mengelola Balanced Scorecard dan Kontrak Kinerja Pemimpin BLU sesuai PER-6/PB/2025: Indikator Kinerja dengan Definisi Operasional, target trajektori triwulanan, laporan capaian, uji syarat insentif, dan cetak lampiran untuk DJPb.</p>'
                    .'<p>Cocok untuk BLU rumah sakit dan perguruan tinggi, Pemimpin BLU, Dewan Pengawas, serta PIC perencanaan dan keuangan. Tersedia mode tamu dengan data demo.</p>',
                'image_url' => 'https://blu.rucisoft.id/images/kenali/pusat-kendali.jpg?v=2',
                'website_url' => 'https://blu.rucisoft.id/',
            ],
            [
                'slug' => 'kinera',
                'category' => 'strategi-kinerja',
                'name' => 'Kinera',
                'icon' => 'heroicon-o-presentation-chart-line',
                'tagline' => 'Kinerja instansi yang terukur, terbukti, dan tepat waktu.',
                'problem' => 'Renstra tidak terhubung ke kegiatan unit, capaian Perjanjian Kinerja baru terlihat menjelang LKjIP, dan datanya tersebar dalam banyak versi.',
                'summary' => 'Pengukuran kinerja berbasis Balanced Scorecard untuk kementerian, lembaga, pemda, dan PTN: Renstra, Perjanjian Kinerja, IKU, sampai SAKIP/LKjIP.',
                'features' => [
                    'Peta strategi dari Renstra',
                    'IKU dengan Definisi Operasional baku',
                    'Status hijau, kuning, dan merah',
                    'Satu alur realisasi, verifikasi, dan persetujuan',
                    'Laporan capaian triwulanan dan LKjIP',
                    'Modul Kontrak Kinerja BLU',
                ],
                'description' => '<p>Kinera mengukur kinerja instansi pemerintah berbasis Balanced Scorecard, dari Renstra, Perjanjian Kinerja, dan IKU sampai pelaporan SAKIP/LKjIP. Instansi berpola BLU dapat memakai modul Kontrak Kinerja BLU.</p>'
                    .'<p>Untuk kementerian, lembaga, pemda dan OPD, PTN, serta rumah sakit pemerintah. Tersedia mode tamu dengan data demo beberapa kampus.</p>',
                'logo_url' => 'https://kinera.rucisoft.id/images/kinera-logo.svg',
                'image_url' => 'https://kinera.rucisoft.id/images/kenali/pusat-kendali.jpg?v=3',
                'website_url' => 'https://kinera.rucisoft.id/',
            ],
            [
                'slug' => 'pranata',
                'category' => 'tata-kelola-kepatuhan',
                'name' => 'PRANATA',
                'icon' => 'heroicon-o-shield-check',
                'tagline' => 'Tata kelola terarah. Risiko terkendali.',
                'problem' => 'Pengawasan korporasi tersebar, kewajiban tidak ditindaklanjuti, dan temuan audit tidak terlacak sampai selesai.',
                'summary' => 'Ruang kerja GRC untuk BUMN dan perusahaan terbuka: manajemen risiko, kepatuhan, dan audit internal sampai tindak lanjut.',
                'features' => [
                    'Register dan matriks risiko',
                    'Register kewajiban dan kalender kepatuhan',
                    'Penugasan audit, kertas kerja, dan temuan',
                    'Verifikasi tindak lanjut independen',
                    'Autentikasi dua faktor',
                    'Jejak perubahan per perusahaan',
                ],
                'description' => '<p>PRANATA adalah ruang kerja Governance, Risk &amp; Compliance (GRC) untuk BUMN dan perusahaan terbuka. Risiko, kewajiban kepatuhan, dan audit internal dikelola dalam satu alur sampai tindak lanjut diverifikasi.</p>'
                    .'<p>Dipakai Direksi, Dewan Komisaris, Komite Audit, dan unit manajemen risiko, dengan MFA dan pemisahan akses per perusahaan. Tersedia demo tanpa akun.</p>',
                'image_url' => 'https://pranata.rucisoft.id/images/screenshots/dashboard.png',
                'website_url' => 'https://pranata.rucisoft.id/',
            ],
            [
                'slug' => 'rekana',
                'category' => 'tata-kelola-kepatuhan',
                'name' => 'Rekana',
                'icon' => 'heroicon-o-identification',
                'tagline' => 'Setiap kontraktor yang masuk lokasi Anda harus layak, kompeten, dan terkendali.',
                'problem' => 'Keselamatan pekerja kontraktor sulit dikendalikan: data rekanan tersebar, standar penilaian tidak seragam, dan izin kerja kertas sulit diawasi.',
                'summary' => 'Contractor Safety Management System (CSMS) berbasis IOGP 423, dari penilaian risiko dan prakualifikasi sampai izin kerja digital dan evaluasi.',
                'features' => [
                    'Penilaian risiko pekerjaan 5x5',
                    'Prakualifikasi berbobot dan sertifikat CSMS',
                    'Seleksi dengan HSE Plan',
                    'Kartu akses QR terikat induksi',
                    'Izin kerja digital (panas, ketinggian, LOTO)',
                    'Rapor rekanan dan portal kontraktor',
                ],
                'description' => '<p>Rekana mengelola keselamatan kontraktor sesuai IOGP Report 423: penilaian risiko pekerjaan, prakualifikasi, seleksi, mobilisasi, izin kerja digital, sampai evaluasi kinerja rekanan.</p>'
                    .'<p>Untuk pemberi kerja berisiko tinggi seperti manufaktur pangan, hulu migas, dan konstruksi gedung, beserta kontraktornya. Portal kontraktor gratis dan tersedia mode tamu dengan data simulasi.</p>',
                'image_url' => 'https://rekana.rucisoft.id/img/layar/dasbor-owner.png',
                'website_url' => 'https://rekana.rucisoft.id/',
            ],
            [
                'slug' => 'izinara',
                'category' => 'tata-kelola-kepatuhan',
                'name' => 'Izinara',
                'icon' => 'heroicon-o-document-check',
                'tagline' => 'Pengurusan izin impor dan izin edar pangan, terjadwal sebagai proyek.',
                'problem' => 'Izin impor dan izin edar pangan berlapis di banyak instansi dan bagian, sehingga jadwal dan dasar hukumnya sulit dilacak.',
                'summary' => 'Mengurus izin impor, izin edar pangan, dan sertifikasi halal sebagai proyek, lengkap dengan rujukan peraturan dan jalur kritis.',
                'features' => [
                    'Gantt dan jalur kritis (CPM)',
                    'Template WBS per jenis izin',
                    'Rujukan peraturan per kegiatan',
                    'Lampiran dokumen wajib',
                    'Diskusi antarbagian dengan @mention',
                    'Modul sertifikasi halal',
                ],
                'description' => '<p>Izinara memperlakukan setiap pengurusan izin impor dan izin edar pangan sebagai proyek. Setiap kegiatan memuat rujukan peraturan beserta status keberlakuannya, dokumen wajib, lampiran, dan diskusi antarbagian.</p>'
                    .'<p>Jadwal disusun dengan CPM dan jalur kritis, beban kerja PIC terlihat, dan proyeksi selesai tersedia dalam P50/P80. Cocok untuk importir pangan dengan bagian Perizinan, Impor, Purchasing, QA, dan Halal.</p>',
                'logo_url' => 'https://izinara.rucisoft.id/img/izinara.png',
                'image_url' => 'https://izinara.rucisoft.id/img/bantuan/dashboard.png',
                'website_url' => 'https://izinara.rucisoft.id/',
            ],
            [
                'slug' => 'ruci-asset-care',
                'category' => 'aset-fasilitas',
                'name' => 'Ruci Asset Care',
                'icon' => 'heroicon-o-wrench-screwdriver',
                'tagline' => 'Kondisi setiap aset terlihat, pemeliharaan tepat waktu, keputusan CAPEX berdasar data.',
                'problem' => 'Kondisi aset tidak terpantau, pemeliharaan reaktif, dan usulan penggantian aset tidak didukung data kesehatan dan risiko.',
                'summary' => 'Manajemen aset dan intelijen aset eksekutif untuk hotel, rumah sakit, kampus, gedung kantor, dan pabrik.',
                'features' => [
                    'Dasbor eksekutif dan Asset Care Index',
                    'Skor kesehatan dan risiko aset',
                    'QR Asset ID dan lapor kerusakan dari HP',
                    'Inspeksi dan work order',
                    'Pemeliharaan preventif',
                    'Register risiko dan usulan CAPEX',
                ],
                'description' => '<p>Ruci Asset Care menggabungkan register aset, label QR, inspeksi, permintaan pemeliharaan, work order, dan pemeliharaan preventif dengan dasbor eksekutif. Setiap aset punya skor kesehatan, kekritisan, dan prioritas yang dihitung otomatis.</p>'
                    .'<p>Satu instalasi dapat melayani banyak perusahaan atau properti dengan data terpisah. Cocok untuk hotel, rumah sakit, kampus, gedung kantor, dan pabrik.</p>',
                'website_url' => 'https://aset.rucisoft.id/',
            ],
            [
                'slug' => 'siaga',
                'category' => 'aset-fasilitas',
                'name' => 'SIAGA',
                'icon' => 'heroicon-o-wrench-screwdriver',
                'tagline' => 'Sistem Informasi Aset & Gangguan untuk hotel.',
                'problem' => 'Aset kamar dan area hotel belum terdata rapi, gangguan dilaporkan lisan, dan SLA perbaikan tidak terukur.',
                'summary' => 'Pengelolaan aset dan pemeliharaan untuk hotel: pendataan per kamar dengan label QR, register aset, gangguan ber-SLA, dan panduan implementasi.',
                'features' => [
                    'Register aset dengan kode permanen',
                    'Pendataan aset per kamar lewat HP',
                    'Label QR dan printer Bluetooth',
                    'Gangguan dengan SLA P1 sampai P4',
                    'Impor master aset dari Excel',
                    'Panduan implementasi 8 minggu',
                ],
                'description' => '<p>SIAGA (Sistem Informasi Aset &amp; Gangguan) mengelola aset dan pemeliharaan hotel: gedung, lantai, kamar, dan area; register aset dengan kode permanen; pendataan awal per kamar dengan label QR; serta gangguan dengan SLA P1 sampai P4.</p>'
                    .'<p>Nama hotel, kode aset, logo, dan warna diatur dari aplikasi tanpa mengubah kode, dengan 17 peran bawaan dari GM sampai teknisi.</p>',
                'website_url' => 'https://siaga.rucisoft.id/',
            ],
            [
                'slug' => 'promise',
                'category' => 'proyek-layanan-ti',
                'name' => 'Promise',
                'icon' => 'heroicon-o-calendar-days',
                'tagline' => 'Tahu proyek mana yang terlambat dan boros, sebelum terlambat.',
                'problem' => 'Data proyek tersebar, masalah biaya dan jadwal baru ketahuan belakangan, dan pengetahuan proyek hilang setelah proyek selesai.',
                'summary' => 'Sistem informasi manajemen proyek (PMIS) mengacu pada PMBOK, dalam Bahasa Indonesia dan Rupiah, dengan Earned Value dan risiko.',
                'features' => [
                    'Dasbor portofolio proyek',
                    'Gantt dengan baseline dan keterkaitan',
                    'Earned Value (SPI, CPI, EAC)',
                    'Matriks risiko 5x5',
                    'Dokumen proyek berversi',
                    'Lembar waktu dan sumber daya',
                ],
                'description' => '<p>Promise adalah PMIS yang mengacu pada PMBOK: portofolio, WBS, Gantt dengan baseline, Earned Value, risiko, dan dokumen proyek dalam Bahasa Indonesia dan Rupiah.</p>'
                    .'<p>Untuk Direksi, PMO, manajer proyek, dan tim lapangan. Dapat dipasang di server sendiri, dan tersedia demo tanpa akun dengan 41 proyek contoh.</p>',
                'logo_url' => 'https://promise.rucisoft.id/images/promise-logo.svg?v=2',
                'image_url' => 'https://promise.rucisoft.id/images/kenali/dasbor.jpg?v=3',
                'website_url' => 'https://promise.rucisoft.id/',
            ],
            [
                'slug' => 'simpul',
                'category' => 'proyek-layanan-ti',
                'name' => 'Simpul Enterprise',
                'icon' => 'heroicon-o-server-stack',
                'tagline' => 'Satu layar untuk seluruh layanan TI grup usaha Anda.',
                'problem' => 'Tim TI bekerja tanpa data dampak, SLA dilanggar tanpa diketahui, dan perangkat multi-vendor tersebar di banyak lokasi.',
                'summary' => 'ITSM dan CMDB berbasis ITIL 4: service desk, topologi bertingkat, analisis dampak, SLA, perubahan, dan basis pengetahuan.',
                'features' => [
                    'Service desk ITIL 4',
                    'Topologi jaringan bertingkat',
                    'CMDB dengan analisis dampak',
                    'Katalog layanan, SLA, dan OLA',
                    'Event monitoring menjadi insiden',
                    'Manajemen perubahan dan basis pengetahuan',
                ],
                'description' => '<p>Simpul Enterprise menyatukan service desk, CMDB dengan 18 kelas CI, topologi jaringan bertingkat, katalog layanan dan SLA, manajemen perubahan, serta basis pengetahuan berbasis data model iTop dan ITIL 4.</p>'
                    .'<p>Cocok untuk tim TI grup usaha, BUMN, dan perguruan tinggi yang mengelola banyak organisasi dan perangkat multi-vendor. Tersedia mode tamu dengan data demo tiga organisasi.</p>',
                'image_url' => 'https://simpul.rucisoft.id/img/panduan/dashboard.png',
                'website_url' => 'https://simpul.rucisoft.id/',
            ],
            [
                'slug' => 'ruci-setia',
                'category' => 'pelanggan-hotel-ritel',
                'name' => 'Ruci SETIA',
                'icon' => 'heroicon-o-users',
                'tagline' => 'Tamu yang dikenal dan dihargai akan kembali lagi.',
                'problem' => 'Data tamu tersebar di PMS, buku tamu, dan ponsel staf, sehingga loyalitas, keluhan, dan penjualan korporat tidak terkelola.',
                'summary' => 'CRM hotel dan resor: profil tamu 360°, loyalitas, keluhan ber-SLA, NPS dan ulasan daring, akun korporat, pipeline MICE, dan kampanye.',
                'features' => [
                    'Profil tamu 360° dan segmen RFM',
                    'Program loyalitas bertingkat',
                    'Keluhan dengan SLA',
                    'Survei NPS dan ulasan daring',
                    'Akun korporat dan kontrak tarif',
                    'Pipeline MICE dan kalender venue',
                ],
                'description' => '<p>Ruci SETIA adalah CRM untuk hotel dan resor di Indonesia: profil tamu 360°, program loyalitas, keluhan dengan SLA, survei NPS dan ulasan daring, akun korporat dan kontrak tarif, pipeline MICE dengan kalender venue, segmen dan kampanye, serta analitik untuk pimpinan.</p>'
                    .'<p>Satu instalasi dapat melayani beberapa hotel dalam satu grup, dengan persetujuan pemasaran sesuai UU PDP.</p>',
                'website_url' => 'https://setia.rucisoft.id/',
            ],
            [
                'slug' => 'rasentra',
                'category' => 'pelanggan-hotel-ritel',
                'name' => 'Rasentra',
                'icon' => 'heroicon-o-building-storefront',
                'tagline' => 'POS, dapur, dan stok outlet food specialist dalam satu sistem grup.',
                'problem' => 'Penjualan, stok, dan kas di banyak outlet tidak tersinkron ke kantor pusat, sehingga selisih dan pemborosan baru terlihat di akhir bulan.',
                'summary' => 'Sistem outlet untuk grup ritel daging dan restoran: POS, dapur (KDS), stok per Pack ID, promo, member, delivery, dan sinkron ke pusat dan SAP.',
                'features' => [
                    'POS retail dan restoran dengan QRIS',
                    'Pack ID untuk berat dan kedaluwarsa',
                    'Dapur (KDS), resep, dan jasa masak',
                    'Stok opname, waste, dan karantina',
                    'Promo, member, dan delivery grup',
                    'Sinkron pusat dan integrasi SAP B1',
                ],
                'description' => '<p>Rasentra mengelola operasional outlet food specialist dari kasir sampai kantor pusat: POS dengan QRIS, Pack ID untuk produk timbang, dapur dan resep, stok opname, promo, member lintas outlet, dan delivery.</p>'
                    .'<p>Data setiap outlet tersinkron ke pusat, direkonsiliasi saat tutup hari, dan dapat diposting ke SAP Business One.</p>',
                'website_url' => 'https://rasentra.rucisoft.id/',
            ],
            [
                'slug' => 'bsc-hotel-konvensi',
                'category' => 'edisi-klien',
                'name' => 'BSC Hotel Konvensi',
                'icon' => 'heroicon-o-presentation-chart-line',
                'tagline' => 'Edisi Ruci BSC Professional untuk sebuah hotel konvensi.',
                'problem' => 'Strategi dan KPI hotel perlu dipantau per perusahaan dan per perioda, dengan data yang bisa diunggah dari Excel.',
                'summary' => 'Balanced Scorecard multi-perusahaan untuk sebuah hotel konvensi, dengan impor Excel dan alur input, verifikasi, dan persetujuan.',
                'features' => [
                    'Balanced Scorecard multi-perusahaan',
                    'Impor dan ekspor data dari Excel',
                    'Pusat Kendali Eksekutif',
                    'Alur input, verifikasi, dan persetujuan',
                    'Pengingat siklus otomatis',
                    'Jejak audit',
                ],
                'description' => '<p>BSC Hotel Konvensi adalah edisi Ruci BSC Professional yang dijalankan untuk sebuah hotel konvensi. Setiap perusahaan punya data, perioda, admin, dan pengguna sendiri, dan datanya dapat diunggah dari Excel dengan pratinjau sebelum diproses.</p>'
                    .'<p>Aplikasi ini hanya untuk pengguna klien; organisasi lain bisa memakai Ruci BSC dengan pengaturan serupa.</p>',
                'website_url' => null,
            ],
            [
                'slug' => 'crm-hotel-konvensi',
                'category' => 'edisi-klien',
                'name' => 'CRM Hotel Konvensi',
                'icon' => 'heroicon-o-user-group',
                'tagline' => 'Customer Relationship Management untuk sebuah hotel konvensi.',
                'problem' => 'Data tamu dan acara di sistem hotel belum diolah menjadi segmen, loyalitas, dan tindak lanjut penjualan.',
                'summary' => 'CRM untuk sebuah hotel konvensi: KPI okupansi, tamu 360, segmentasi, loyalitas, kampanye WhatsApp, dan pipeline acara.',
                'features' => [
                    'KPI okupansi, ADR, dan RevPAR',
                    'Tamu 360 dan skor RFM',
                    'Segmentasi dan loyalitas',
                    'Kampanye dan WA blast berizin',
                    'Pipeline acara dan deteksi ruang bentrok',
                    'Keluhan dan NPS',
                ],
                'description' => '<p>CRM Hotel Konvensi mengolah data tamu dan banquet dari sistem hotel menjadi ringkasan eksekutif, profil tamu 360, segmentasi, program loyalitas, kampanye WhatsApp yang hanya menjangkau tamu yang setuju, dan pipeline acara.</p>'
                    .'<p>Aplikasi ini hanya untuk pengguna klien; hotel lain bisa memakai Ruci SETIA dengan fitur serupa.</p>',
                'website_url' => null,
            ],
            [
                'slug' => 'ifm-hotel-konvensi',
                'category' => 'edisi-klien',
                'name' => 'IFM Hotel Konvensi',
                'icon' => 'heroicon-o-building-office-2',
                'tagline' => 'Integrated Facility Management untuk sebuah hotel konvensi.',
                'problem' => 'Aset, pemeliharaan, dan gangguan fasilitas hotel perlu dipantau dalam satu sistem.',
                'summary' => 'Pengelolaan fasilitas terpadu untuk sebuah hotel konvensi: aset, pemeliharaan, dan gangguan.',
                'features' => [
                    'Register aset hotel',
                    'Pemeliharaan preventif',
                    'Laporan dan penanganan gangguan',
                    'Dasbor untuk pimpinan',
                ],
                'description' => '<p>IFM Hotel Konvensi adalah sistem Integrated Facility Management yang dijalankan untuk sebuah hotel konvensi, untuk mengelola aset, pemeliharaan, dan gangguan fasilitas.</p>'
                    .'<p>Aplikasi ini hanya untuk pengguna klien; organisasi lain bisa memakai Ruci Asset Care atau SIAGA.</p>',
                'website_url' => null,
            ],
        ];
    }
}
