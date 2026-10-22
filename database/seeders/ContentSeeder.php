<?php

namespace Database\Seeders;

use App\Models\CatalogItem;
use App\Models\ClientSegment;
use App\Models\DashboardMetric;
use App\Models\Principle;
use App\Models\Setting;
use App\Models\Solution;
use App\Models\Stage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Setting::DEFAULTS as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        $solutions = [
            ['Ruci BSC Professional', 'heroicon-o-presentation-chart-line', 'Memastikan strategi diterjemahkan menjadi hasil yang dapat dipantau.', ['Strategy map & sasaran strategis', 'KPI, target & realisasi', 'Program kerja & anggaran', 'Dashboard pimpinan & evaluasi']],
            ['RuciLegal Professional', 'heroicon-o-scale', 'Menertibkan seluruh pekerjaan dan dokumen hukum perusahaan.', ['Legal request & SLA', 'Contract lifecycle', 'Perizinan & kewajiban', 'Perkara, counsel & biaya legal']],
            ['RuciESG', 'heroicon-o-globe-asia-australia', 'Mengelola keberlanjutan, emisi karbon, bukti, dan disclosure.', ['Inventaris emisi & sumber data', 'Indikator, target & risiko ESG', 'Program keberlanjutan', 'Disclosure PSPK 1 & PSPK 2']],
            ['RuciNexa Professional', 'heroicon-o-building-office-2', 'Mengintegrasikan layanan Bagian Umum dan corporate services.', ['Service desk korporat', 'Fasilitas, aset & workplace', 'Vendor, kontrak & utilitas', 'Perjalanan dinas & keamanan']],
            ['RuciTOP', 'heroicon-o-server-stack', 'Mengendalikan layanan dan operasi TI lebih dari service desk.', ['Incident, request & SLA', 'Aset TI & CMDB', 'Problem, change & release', 'Vendor, knowledge & continuity']],
            ['RuciAI Workspace', 'heroicon-o-sparkles', 'Ruang kerja AI internal berbasis pengetahuan perusahaan.', ['Knowledge base tepercaya', 'Workspace per divisi', 'Prompt template terkelola', 'Hak akses & audit penggunaan']],
        ];

        foreach ($solutions as $i => [$name, $icon, $tagline, $features]) {
            $solution = Solution::updateOrCreate(['slug' => Str::slug($name)], [
                'name' => $name,
                'icon' => $icon,
                'tagline' => $tagline,
                'features' => $features,
                'sort_order' => $i + 1,
                'is_active' => true,
            ]);

            CatalogItem::updateOrCreate(['slug' => 'software-'.$solution->slug], [
                'type' => 'software',
                'solution_id' => $solution->id,
                'title' => $name,
                'summary' => $tagline,
                'body' => '<p>'.$tagline.'</p><ul>'.collect($features)->map(fn ($f) => "<li>{$f}</li>")->implode('').'</ul>',
                'sort_order' => $i + 1,
                'is_active' => true,
            ]);
        }

        // Contoh buku & workshop: silakan ganti lewat panel admin.
        $bsc = Solution::where('slug', 'ruci-bsc-professional')->first();
        $samples = [
            ['buku', 'Panduan Balanced Scorecard untuk Organisasi (contoh)', 'Konsep, praktik terbaik, template, dan checklist penerapan BSC.'],
            ['workshop', 'Workshop Penyusunan Strategy Map & KPI (contoh)', 'Asesmen, latihan, studi kasus, dan rancangan awal KPI organisasi.'],
        ];
        foreach ($samples as $i => [$type, $title, $summary]) {
            CatalogItem::updateOrCreate(['slug' => Str::slug($title)], [
                'type' => $type,
                'solution_id' => $bsc?->id,
                'title' => $title,
                'summary' => $summary,
                'body' => "<p>{$summary}</p>",
                'sort_order' => 10 + $i,
                'is_active' => true,
            ]);
        }

        $stages = [
            ['01', 'Memahami', 'Buku & Panduan', 'Konsep, praktik terbaik, template, checklist, dan pengenalan software.'],
            ['02', 'Mempersiapkan', 'Workshop Praktis', 'Asesmen, latihan, studi kasus, penyamaan persepsi, dan rancangan awal.'],
            ['03', 'Menerapkan', 'Implementasi Software', 'Konfigurasi, migrasi, integrasi, UAT, pelatihan, go-live, dan dukungan.'],
        ];
        foreach ($stages as $i => [$number, $label, $title, $description]) {
            Stage::updateOrCreate(['number' => $number], compact('label', 'title', 'description') + ['sort_order' => $i + 1]);
        }

        foreach (['Perusahaan Terbuka', 'BUMN & BUMD', 'Pemerintah & BL', 'Rumah Sakit', 'Universitas'] as $i => $name) {
            ClientSegment::updateOrCreate(['name' => $name], ['sort_order' => $i + 1]);
        }

        $principles = [
            ['Kebenaran Data', 'Satu sumber informasi yang konsisten.'],
            ['Kepatuhan', 'Proses, bukti, dan audit trail terjaga.'],
            ['Keterukuran', 'Sasaran dan hasil dipantau dengan jelas.'],
            ['Kesiapan Pelaporan', 'Informasi siap ketika diperlukan.'],
        ];
        foreach ($principles as $i => [$title, $description]) {
            Principle::updateOrCreate(['title' => $title], ['description' => $description, 'sort_order' => $i + 1]);
        }

        foreach ([['Pencapaian Strategi', 86], ['Kepatuhan Legal', 94], ['Kesiapan ESG', 78]] as $i => [$label, $value]) {
            DashboardMetric::updateOrCreate(['label' => $label], ['value' => $value, 'sort_order' => $i + 1]);
        }
    }
}
