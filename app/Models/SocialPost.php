<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * A ready-to-post social media text explaining a product, tied to the funnel stage it moves people to.
 */
class SocialPost extends Model
{
    public const PLATFORMS = [
        'instagram' => 'Instagram',
        'linkedin' => 'LinkedIn',
        'facebook' => 'Facebook',
        'x' => 'X (Twitter)',
        'tiktok' => 'TikTok',
        'whatsapp' => 'WhatsApp (status/grup)',
    ];

    /** Character limits shown as a guide while writing. */
    public const LIMITS = ['instagram' => 2200, 'linkedin' => 3000, 'facebook' => 5000, 'x' => 280, 'tiktok' => 2200, 'whatsapp' => 700];

    public const FORMATS = [
        'post' => 'Post gambar tunggal',
        'carousel' => 'Carousel',
        'video' => 'Video pendek / reel',
        'artikel' => 'Artikel panjang',
        'story' => 'Story',
    ];

    public const STATUSES = [
        'draf' => 'Draf',
        'siap' => 'Siap diposting',
        'terjadwal' => 'Terjadwal',
        'terposting' => 'Sudah diposting',
    ];

    public const STATUS_COLORS = ['draf' => 'gray', 'siap' => 'info', 'terjadwal' => 'warning', 'terposting' => 'success'];

    /** Funnel stages a post can push readers towards (see Opportunity::STAGES). */
    public const STAGES = [
        'edukasi' => 'Edukasi masalah',
        'asesmen' => 'Asesmen kebutuhan',
        'workshop' => 'Workshop / webinar',
        'klinik' => 'Klinik & demo',
        'pilot' => 'Pilot',
        'proposal' => 'Proposal & pembelian',
        'pendampingan' => 'Kisah pelanggan',
    ];

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'platforms' => 'array',
            'gallery' => 'array',
            'scheduled_at' => 'datetime',
            'posted_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (SocialPost $post) {
            if ($post->status === 'terposting' && ! $post->posted_at) {
                $post->posted_at = now();
            }
        });
    }

    public function solution(): BelongsTo
    {
        return $this->belongsTo(Solution::class);
    }

    public function catalogItem(): BelongsTo
    {
        return $this->belongsTo(CatalogItem::class);
    }

    /**
     * The post's link with UTM tags, so leads from social media can be traced back to the post.
     */
    public function trackedLink(?string $platform = null): ?string
    {
        $url = $this->link_url ?: $this->defaultLink();
        if (! $url || ! str_starts_with($url, url('/'))) {
            return $url;
        }

        [$base, $fragment] = array_pad(explode('#', $url, 2), 2, null);
        $query = http_build_query([
            'utm_source' => $platform ?: ($this->platforms[0] ?? 'sosmed'),
            'utm_medium' => 'social',
            'utm_campaign' => trim(($this->solution?->slug ?? 'rucisoft').'-'.$this->funnel_stage, '-'),
        ]);

        return $base.(str_contains($base, '?') ? '&' : '?').$query.($fragment ? '#'.$fragment : '');
    }

    public function defaultLink(): ?string
    {
        return match (true) {
            (bool) $this->catalogItem => route('catalog.show', $this->catalogItem),
            (bool) $this->solution => route('solutions.show', $this->solution).(in_array($this->funnel_stage, ['asesmen', 'klinik', 'pilot', 'proposal']) ? '?langkah='.($this->funnel_stage === 'klinik' ? 'demo' : $this->funnel_stage).'#mulai' : ''),
            default => url('/'),
        };
    }

    /** Text to paste into the platform: body, tracked link, and hashtags. */
    public function fullText(?string $platform = null): string
    {
        return collect([trim($this->body), $this->trackedLink($platform), trim((string) $this->hashtags)])
            ->filter()->implode("\n\n");
    }

    public function imageUrl(): ?string
    {
        return $this->image ? Storage::disk('public')->url($this->image) : null;
    }

    /** @return list<string> URLs of the attached screenshots and charts, in posting order. */
    public function galleryUrls(): array
    {
        return collect($this->gallery)->map(fn (string $path) => Storage::disk('public')->url($path))->values()->all();
    }
}
