<?php

namespace Database\Seeders;

use App\Models\SocialPost;
use App\Models\Solution;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * One promotional article per product, structured masalah → penyebab → solusi → bantuan aplikasi,
 * stored as drafts in the social media content bank. Images (infographic, app screenshots, charts)
 * live in database/seeders/sosmed/{slug} and are copied to the public disk. Matched by title, so it
 * is safe to rerun and never overwrites a post that has already been edited away from draft.
 */
class SocialPostSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->articles() as $slug => [$title, $hashtags, $body]) {
            $solution = Solution::where('slug', $slug)->first();
            if (! $solution) {
                continue;
            }

            $post = SocialPost::firstOrNew(['title' => $title]);
            if ($post->exists && $post->status !== 'draf') {
                continue;
            }

            $images = $this->copyImages($slug);

            $post->fill([
                'solution_id' => $solution->id,
                'image' => $images[0] ?? null,
                'gallery' => array_slice($images, 1),
                'notes' => 'Gambar 1 infografis (sampul), berikutnya screenshot aplikasi dengan data demo'
                    .($slug === 'ruci-bsc' ? ' dan grafik empat hambatan strategi' : '')
                    .'. Untuk LinkedIn/Facebook unggah sebagai beberapa foto atau dokumen carousel.',
                'funnel_stage' => 'edukasi',
                'format' => 'artikel',
                'platforms' => ['linkedin', 'facebook'],
                'body' => trim($body),
                'hashtags' => $hashtags,
                'status' => 'draf',
            ])->save();
        }
    }

    /**
     * @return list<string> public-disk paths, in posting order
     */
    private function copyImages(string $slug): array
    {
        $dir = database_path("seeders/sosmed/{$slug}");
        if (! File::isDirectory($dir)) {
            return [];
        }

        return collect(File::files($dir))
            ->sortBy(fn ($file) => $file->getFilename())
            ->map(function ($file) use ($slug) {
                $path = "sosmed/{$slug}/".$file->getFilename();
                Storage::disk('public')->put($path, File::get($file->getPathname()));

                return $path;
            })
            ->values()->all();
    }

    /**
     * @return array<string, array{string, string, string}>
     */
    private function articles(): array
    {
        return [
            'ruci-bsc' => [
                'Artikel: Mengapa strategi berhenti di dokumen rencana',
                '#Rucisoft #RuciBSC #BalancedScorecard #ManajemenKinerja #KPI #StrategiPerusahaan',
                <<<'TXT'
Mengapa strategi yang bagus sering berhenti di dokumen rencana?

Hampir setiap organisasi punya rencana strategis. Disusun berbulan-bulan, dipresentasikan di depan direksi, lalu disahkan. Tetapi tiga bulan kemudian, banyak yang sudah tidak membacanya lagi.

MASALAH YANG SERING TERJADI
• Karyawan tidak tahu sasaran perusahaan dan bagaimana pekerjaannya ikut menentukan hasil.
• KPI tertulis di dokumen, tetapi tidak jelas siapa pemiliknya dan berapa target bulanannya.
• Anggaran disusun terpisah dari inisiatif strategis.
• Rapat pimpinan membahas angka dari rekap Excel yang berbeda-beda versi.

MENGAPA INI TERJADI
Kaplan dan Norton, perancang Balanced Scorecard, menyebut empat hambatan klasik pelaksanaan strategi:
1. Hambatan visi: strategi ditulis panjang dan abstrak, tanpa peta sebab-akibat yang mudah dipahami.
2. Hambatan manusia: target dan insentif manajer tidak terkait dengan sasaran strategis.
3. Hambatan sumber daya: anggaran tidak terhubung ke inisiatif.
4. Hambatan manajemen: tidak ada ritme review bulanan dan satu sumber data yang dipercaya.

SOLUSI YANG PERLU DILAKUKAN
• Ringkas strategi menjadi peta strategi satu halaman per perspektif.
• Turunkan setiap sasaran menjadi KPI dengan pemilik, target, dan cara ukur yang jelas.
• Kaitkan setiap inisiatif dan anggarannya ke sasaran yang didukung.
• Bangun ritme review bulanan dengan data yang diverifikasi, bukan rekap manual.

BAGAIMANA RUCI BSC MEMBANTU
Ruci BSC dari Rucisoft menerjemahkan strategi menjadi pekerjaan yang dipantau setiap bulan:
• Peta Strategi satu halaman yang bisa dibaca dari Komisaris sampai kepala unit.
• KPI dengan PIC, target bulanan, dan skor yang terlihat jelas.
• Inisiatif terhubung ke sasaran, lengkap dengan anggaran dan serapannya.
• Pusat Kendali dan alur input, verifikasi, dan persetujuan realisasi, sehingga rapat review memakai satu sumber data.

Ingin mencobanya tanpa komitmen? Mulailah dari webinar gratis Rucisoft Academy, atau ikuti workshop praktik untuk menyusun strategy map dan KPI organisasi Anda langsung di aplikasi.
TXT,
            ],
            'akredia' => [
                'Artikel: Mengapa persiapan akreditasi selalu mepet tenggat',
                '#Rucisoft #Akredia #AkreditasiInstitusi #IAPT #BANPT #PenjaminanMutu #PerguruanTinggi',
                <<<'TXT'
Mengapa persiapan akreditasi institusi selalu mepet tenggat?

Banyak perguruan tinggi mengalami pola yang sama: beberapa bulan menjelang batas waktu, tim LPM bekerja lembur mengumpulkan bukti, menyusun LED, dan mencocokkan angka dari berbagai unit.

MASALAH YANG SERING TERJADI
• Pimpinan tidak tahu seberapa siap institusinya, sehingga masalah baru terlihat ketika tenggat sudah dekat.
• Bukti tersebar di folder bersama dan grup percakapan, nama berkas tidak seragam, dan SK yang kedaluwarsa tidak ada yang mengingatkan.
• Satu syarat perlu yang gagal bisa menutup peringkat yang dituju, dan sering baru ketahuan saat penilaian.
• Jumlah mahasiswa, dosen, dan lulusan di laporan internal berbeda dengan PDDikti.

MENGAPA INI TERJADI
Akreditasi masih diperlakukan sebagai proyek musiman, bukan pekerjaan rutin:
1. Indikator IAPT 4.1 belum dipetakan ke unit, PIC, dan bukti yang dibutuhkan.
2. Dokumen tidak punya penanggung jawab, status validasi, dan masa berlaku.
3. Data internal dan PDDikti dikelola terpisah dan tidak pernah direkonsiliasi.
4. Tidak ada ukuran kesiapan yang bisa dipantau pimpinan setiap bulan.

SOLUSI YANG PERLU DILAKUKAN
• Petakan seluruh indikator ke unit dan PIC sejak awal siklus.
• Kelola bukti di satu pustaka dengan pemilik, versi, dan masa berlaku.
• Pantau syarat perlu secara khusus, jangan menunggu simulasi akhir.
• Rekonsiliasi data dengan PDDikti secara berkala.
• Gunakan satu indikator kesiapan yang ditinjau pimpinan secara rutin.

BAGAIMANA AKREDIA MEMBANTU
Akredia dari Rucisoft menjadikan persiapan akreditasi terukur sepanjang tahun:
• Dasbor pimpinan dengan Indeks Kesiapan Akreditasi dan proyeksi skor.
• Peta instrumen IAPT 4.1 (39 indikator) beserta pemantauan syarat perlu.
• Pustaka bukti berversi dan tervalidasi, dengan pengingat masa berlaku.
• Penyusunan LED, tabel LKPT, dan rekonsiliasi PDDikti dalam satu tempat.

Mulailah dari webinar gratis Rucisoft Academy, atau ikuti workshop praktik menyusun peta bukti, LED, dan rencana tindak lanjut akreditasi bersama tim Anda.
TXT,
            ],
            'ruci-notaria' => [
                'Artikel: Risiko kepatuhan yang tersembunyi di administrasi kantor notaris',
                '#Rucisoft #RuciNotaria #Notaris #PPAT #UUJN #MPD #KantorNotaris',
                <<<'TXT'
Risiko kepatuhan yang sering tersembunyi di administrasi kantor notaris

Notaris bertanggung jawab atas setiap akta yang ditandatangani. Namun waktu yang seharusnya untuk melayani klien sering habis untuk pekerjaan administrasi, dan kesalahan kecil di sana bisa menjadi temuan saat pemeriksaan MPD.

MASALAH YANG SERING TERJADI
• Nomor akta ganda, celah nomor, atau tanggal yang mundur baru ketahuan saat repertorium disusun.
• Repertorium, klapper, legalisasi, waarmerking, wasiat, dan surat pengantar MPD diketik ulang setiap bulan.
• Tenggat mudah terlewat: pengesahan PT 60 hari, fidusia 30 hari, daftar wasiat tanggal 5, laporan MPD tanggal 15.
• Honorarium serta titipan pajak dan PNBP bercampur, sehingga sulit dipertanggungjawabkan.

MENGAPA INI TERJADI
Data kantor dicatat berulang di tempat yang berbeda:
1. Nomor akta diberikan tanpa pemeriksaan otomatis terhadap urutan dan tanggal.
2. Buku daftar dan laporan MPD disusun ulang, bukan diambil dari akta yang sudah tercatat.
3. Batas waktu AHU dan PPAT dihitung manual dengan hari kalender.
4. Honorarium dan titipan dicatat di buku yang sama tanpa saldo per klien.

SOLUSI YANG PERLU DILAKUKAN
• Catat setiap berkas sekali, sejak klien datang sampai protokol tersimpan.
• Periksa kesiapan akta (pihak, identitas, saksi, wilayah jabatan) sebelum nomor diberikan.
• Susun register dan laporan bulanan langsung dari akta yang sudah bernomor.
• Hitung tenggat dengan hari kerja dan pasang pengingat.
• Pisahkan honorarium dan titipan per klien.

BAGAIMANA RUCI NOTARIA MEMBANTU
Ruci Notaria dari Rucisoft membuat kantor rapi, patuh UUJN, dan siap diperiksa MPD:
• Cek kesiapan akta berbasis aturan UUJN, dan nomor repertorium yang terkunci.
• Repertorium, klapper, legalisasi, waarmerking, dan laporan MPD siap cetak.
• Pengingat tenggat AHU dan PPAT yang dihitung dengan hari kerja.
• Tagihan honorarium, titipan, dan protokol yang tercatat rapi, dengan hak akses per peran dan jejak audit.

Ingin melihat alurnya? Ikuti webinar gratis Rucisoft Academy, atau jadwalkan demo untuk kantor Anda.
TXT,
            ],
            'ruci-medika' => [
                'Artikel: Mengapa petugas klinik sibuk tetapi pasien tetap menunggu',
                '#Rucisoft #RuciMedika #Klinik #RekamMedisElektronik #SATUSEHAT #BPJS #PCare',
                <<<'TXT'
Mengapa petugas klinik sibuk, tetapi pasien tetap lama menunggu?

Di banyak klinik, petugas sudah bekerja keras sejak pagi. Namun antrean tetap panjang, dan setiap akhir bulan tim harus lembur untuk menyusun laporan.

MASALAH YANG SERING TERJADI
• Pasien lama menunggu map rekam medisnya ditemukan, atau dibuatkan nomor RM baru karena mapnya hilang.
• Data kunjungan ditulis di buku klinik, lalu diketik ulang ke P-Care, dan kini juga harus dikirim ke SATUSEHAT.
• Stok obat dicatat di kartu manual. Stok kosong baru ketahuan saat resep datang, dan obat kedaluwarsa tertinggal di rak.
• LB1, sepuluh besar penyakit, SIPNAP, dan KBK dihitung dari buku register.

MENGAPA INI TERJADI
Setiap bagian klinik bekerja dengan catatannya sendiri:
1. Riwayat pasien tersimpan di map fisik yang harus dicari.
2. Sistem klinik tidak terhubung ke BPJS dan SATUSEHAT, sehingga data dipindahkan manual.
3. Kartu stok tidak mencatat batch dan tanggal kedaluwarsa.
4. Laporan dihitung ulang dari buku, bukan dari data layanan.

SOLUSI YANG PERLU DILAKUKAN
• Satukan pendaftaran, pemeriksaan, farmasi, laboratorium, dan kasir dalam satu alur data.
• Beralih ke rekam medis elektronik dengan kode diagnosis yang baku.
• Kirim data kunjungan ke sistem pemerintah tanpa input ulang.
• Kelola stok per batch dengan prinsip FEFO (yang lebih dulu kedaluwarsa, keluar lebih dulu).
• Buat laporan langsung dari data layanan.

BAGAIMANA RUCI MEDIKA MEMBANTU
Ruci Medika dari Rucisoft menyatukan alur klinik dari pasien datang sampai pulang:
• Pendaftaran dan antrean per poli, dengan layar TV dan panggilan suara.
• Rekam medis elektronik: asesmen awal, SOAP, ICD-10, dan ICD-9-CM.
• E-resep dan stok farmasi per batch dengan kedaluwarsa, serta hasil laboratorium.
• Integrasi BPJS P-Care dan SATUSEHAT, plus laporan LB1, KBK, SIPNAP, dan indikator mutu yang tinggal dicetak.

Mulailah dari webinar gratis Rucisoft Academy, atau ajukan pelatihan dan pilot 30 hari untuk klinik Anda.
TXT,
            ],
            'ruci-prima' => [
                'Artikel: Biaya tersembunyi dari aset dan fasilitas yang tidak terkelola',
                '#Rucisoft #RuciPRIMA #ManajemenAset #GeneralAffairs #FacilityManagement #BUMN',
                <<<'TXT'
Biaya tersembunyi dari aset dan fasilitas yang tidak terkelola

Berapa aset perusahaan yang kondisinya kritis? Izin mana yang akan habis bulan depan? Ruang rapat dan kendaraan mana yang paling sering terpakai? Pertanyaan sederhana seperti ini sering sulit dijawab dengan cepat.

MASALAH YANG SERING TERJADI
• Data aset ada di Excel, izin di lemari arsip, dan pemesanan ruang di grup percakapan.
• Perbaikan baru dilakukan setelah rusak. Biaya darurat, lembur, dan gangguan layanan lebih besar daripada pemeliharaan terjadwal.
• Usulan investasi (CAPEX) dibahas tanpa ukuran yang sama, sehingga anggaran tidak selalu jatuh ke kebutuhan paling penting.
• Izin dan sertifikat baru diketahui habis saat inspeksi, audit, atau klaim asuransi.

MENGAPA INI TERJADI
Layanan umum dikelola per kegiatan, bukan sebagai satu sistem:
1. Tidak ada register tunggal untuk aset, ruang, kendaraan, dan izin.
2. Pemeliharaan tidak direncanakan, dan riwayat perbaikan per aset tidak tercatat.
3. Tidak ada skor prioritas yang seragam untuk menilai usulan investasi.
4. Masa berlaku izin, pajak, dan sertifikat tidak dipantau secara otomatis.

SOLUSI YANG PERLU DILAKUKAN
• Bangun satu register aset dan fasilitas dengan penanggung jawab yang jelas.
• Jadwalkan pemeliharaan preventif dan catat setiap work order beserta biayanya.
• Nilai usulan investasi dengan kriteria yang sama: keselamatan, pelanggan, pendapatan, dan urgensi.
• Pasang pengingat sebelum izin dan dokumen penting habis masa berlakunya.

BAGAIMANA RUCI PRIMA MEMBANTU
Ruci PRIMA (Pengelolaan Ruang, Inventaris, Mobilitas & Administrasi) dari Rucisoft menjadi satu kendali untuk layanan umum perusahaan:
• Dasbor Pimpinan dengan Indeks Kesiapan Operasional, serta register aset dengan skor kesehatan dan risiko.
• Pemeliharaan, work order, label QR, dan sensus aset.
• Peta risiko dan skor prioritas CAPEX yang seragam.
• Ruang rapat, kendaraan dinas, ATK, serta dokumen dan perizinan dengan pengingat otomatis.

Ingin melihat dengan data contoh? Ikuti webinar gratis Rucisoft Academy, atau ajukan workshop in-house dan pilot untuk perusahaan Anda.
TXT,
            ],
            'ruci-mufakat' => [
                'Artikel: Keputusan rapat dewan yang tidak bisa dibuktikan',
                '#Rucisoft #RuciMUFAKAT #GoodCorporateGovernance #Direksi #DewanKomisaris #BoardManagement',
                <<<'TXT'
Ketika keputusan rapat Direksi dan Dewan Komisaris sulit dibuktikan

Rapat Direksi dan Dewan Komisaris menghasilkan keputusan yang menentukan arah perusahaan. Tetapi ketika auditor, pemegang saham, atau regulator bertanya "apa dasar keputusan ini, dan apakah sudah ditindaklanjuti?", jawabannya sering tersebar di surel, pesan singkat, dan berkas PDF.

MASALAH YANG SERING TERJADI
• Bahan rapat dikirim lewat surel atau pesan singkat; versinya bercampur dan perubahannya tidak terlihat.
• Undangan yang dijawab hadir dianggap kehadiran, dan keputusan rapat gabungan tercampur antarorgan.
• Tugas dinyatakan 100% selesai tanpa bukti yang diperiksa.
• Pertanyaan Komisaris kepada manajemen tenggelam di percakapan, tanpa PIC dan batas waktu.

MENGAPA INI TERJADI
Tata kelola rapat masih bergantung pada dokumen lepas dan ingatan:
1. Bahan rapat tidak memiliki riwayat versi dan daftar perubahan.
2. Fungsi pengurusan (Direksi) dan pengawasan (Dewan Komisaris) tidak dicatat terpisah.
3. Penyelesaian tindak lanjut tidak melalui verifikasi bukti.
4. Risalah bisa diubah tanpa jejak.

SOLUSI YANG PERLU DILAKUKAN
• Kelola bahan rapat sebagai board book berversi.
• Hitung kuorum dari kehadiran aktual, per organ dan per agenda.
• Catat keputusan beserta sumbernya, terpisah untuk setiap organ.
• Tetapkan tindak lanjut dengan PIC, tenggat, dan bukti yang diverifikasi.
• Kunci risalah final; koreksi dilakukan lewat addendum.

BAGAIMANA RUCI MUFAKAT MEMBANTU
Ruci MUFAKAT dari Rucisoft adalah ruang kerja Direksi dan Dewan Komisaris, dari bahan rapat sampai bukti tindak lanjut:
• Board book berversi dan ruang baca dengan anotasi.
• Kuorum dari kehadiran aktual, serta keputusan Direksi dan persetujuan Dewan Komisaris yang dicatat terpisah.
• Tindak lanjut yang selesai hanya setelah buktinya diterima verifikator.
• Permintaan informasi ber-SLA, risalah terkunci dengan addendum, jejak audit, dan asisten AI yang selalu menunjuk dokumen sumber.

Mulailah dari webinar gratis Rucisoft Academy, atau jadwalkan sesi demo bersama sekretariat perusahaan Anda.
TXT,
            ],
        ];
    }
}
