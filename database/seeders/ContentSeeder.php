<?php

namespace Database\Seeders;

use App\Models\ClientSegment;
use App\Models\DashboardMetric;
use App\Models\Principle;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Setting::DEFAULTS as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        $this->call(ProductSeeder::class);

        $this->call(FunnelSeeder::class);

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
