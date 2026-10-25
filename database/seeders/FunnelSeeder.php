<?php

namespace Database\Seeders;

use App\Models\CatalogItem;
use App\Models\Solution;
use App\Models\Stage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Sales-funnel content: the "cara bekerja sama" steps, the Ruci BSC pilot scope,
 * and the first Rucisoft Academy programs (BSC as the pilot product line).
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
        $bsc = Solution::where('slug', 'ruci-bsc')->first();
        if (! $bsc) {
            return;
        }

        $bsc->update([
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
        ]);

        $items = [
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
        ];

        foreach ($items as $i => $item) {
            CatalogItem::updateOrCreate(['slug' => $item['slug']], $item + [
                'solution_id' => $bsc->id,
                'registration_open' => true,
                'is_active' => true,
                'sort_order' => 20 + $i,
            ]);
        }
    }
}
