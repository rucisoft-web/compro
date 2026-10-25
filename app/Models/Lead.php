<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    /**
     * What the visitor asked for, the funnel stage it implies, and the follow-up it triggers.
     */
    public const KINDS = [
        'asesmen' => ['label' => 'Asesmen kebutuhan', 'stage' => 'asesmen', 'action' => 'Hubungi untuk membahas hasil asesmen dan undang ke sesi pengenalan', 'days' => 3],
        'minat' => ['label' => 'Daftar minat Academy', 'stage' => 'edukasi', 'action' => 'Kabari jadwal webinar/workshop berikutnya', 'days' => 7],
        'webinar' => ['label' => 'Pendaftaran webinar', 'stage' => 'edukasi', 'action' => 'Kirim materi webinar dan ajak ke workshop praktik', 'days' => 1],
        'workshop' => ['label' => 'Pendaftaran workshop', 'stage' => 'workshop', 'action' => 'Konfirmasi peserta; setelah workshop kirim hasil praktik dan materi', 'days' => 1],
        'inhouse' => ['label' => 'Permintaan workshop in-house', 'stage' => 'workshop', 'action' => 'Jadwalkan pembahasan lingkup workshop in-house', 'days' => 2],
        'demo' => ['label' => 'Klinik kebutuhan & demo', 'stage' => 'klinik', 'action' => 'Jadwalkan klinik kebutuhan 30-45 menit bersama sponsor/PIC', 'days' => 1],
        'pilot' => ['label' => 'Permintaan pilot', 'stage' => 'pilot', 'action' => 'Sepakati lingkup, ukuran keberhasilan, dan jadwal evaluasi pilot', 'days' => 3],
        'proposal' => ['label' => 'Permintaan proposal', 'stage' => 'proposal', 'action' => 'Kirim proposal: lisensi, implementasi, migrasi, pelatihan, pendampingan', 'days' => 3],
    ];

    /**
     * Short needs-assessment questions, mirroring the post-workshop clinic.
     */
    public const ASSESSMENT_QUESTIONS = [
        'masalah' => 'Proses mana yang paling membutuhkan perbaikan?',
        'dampak' => 'Apa akibatnya jika masalah ini dibiarkan?',
        'pengguna' => 'Siapa yang akan mengoperasikan aplikasi dan menerima laporan?',
        'sponsor' => 'Siapa pimpinan yang mendukung penerapan?',
        'kesiapan' => 'Apakah data, PIC, dan waktu penerapan sudah tersedia?',
        'pengadaan' => 'Bagaimana proses anggaran dan pengadaannya?',
    ];

    protected $fillable = [
        'kind', 'name', 'position', 'email', 'phone', 'organization_name', 'organization_id',
        'opportunity_id', 'solution_id', 'catalog_item_id', 'answers', 'message', 'consent',
        'source_url', 'handled_at',
    ];

    protected function casts(): array
    {
        return [
            'answers' => 'array',
            'consent' => 'boolean',
            'handled_at' => 'datetime',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function solution(): BelongsTo
    {
        return $this->belongsTo(Solution::class);
    }

    public function catalogItem(): BelongsTo
    {
        return $this->belongsTo(CatalogItem::class);
    }

    public function kindLabel(): string
    {
        return self::KINDS[$this->kind]['label'] ?? $this->kind;
    }
}
