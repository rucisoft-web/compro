<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            'published_on_site' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (SocialPost $post) {
            if ($post->status === 'terposting' && ! $post->posted_at) {
                $post->posted_at = now();
            }
            if (blank($post->slug)) {
                $post->slug = static::uniqueSlug($post->headline());
            }
            if ($post->published_on_site && ! $post->published_at) {
                $post->published_at = now();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published_on_site', true)->where('published_at', '<=', now())->latest('published_at');
    }

    private static function uniqueSlug(string $title): string
    {
        $base = Str::slug(Str::limit($title, 80, '')) ?: 'artikel';
        $slug = $base;
        for ($i = 2; static::where('slug', $slug)->exists(); $i++) {
            $slug = "{$base}-{$i}";
        }

        return $slug;
    }

    /** The article headline: the first line of the text, or the internal title without its prefix. */
    public function headline(): string
    {
        $first = trim(strtok((string) $this->body, "\n") ?: '');

        return $first !== '' && mb_strlen($first) <= 140 ? $first : Str::after($this->title, 'Artikel: ');
    }

    /** First paragraph after the headline, for cards and meta descriptions. */
    public function excerpt(int $limit = 200): string
    {
        $paragraph = collect(preg_split('/\n\s*\n/', trim((string) $this->body)))->skip(1)->first() ?? $this->body;

        return Str::limit(Str::squish($paragraph), $limit);
    }

    /**
     * The text after the headline split into blocks for the website: a line in capitals is a heading,
     * lines starting with "•" or "1." form lists, anything else is a paragraph.
     *
     * @return list<array{type: string, text?: string, items?: list<string>}>
     */
    public function blocks(): array
    {
        $blocks = [];
        $lines = array_slice(preg_split('/\r?\n/', trim((string) $this->body)), 1);

        foreach ($lines as $line) {
            $line = trim($line);
            $last = array_key_last($blocks);

            if ($line === '') {
                $blocks[] = ['type' => 'break'];
            } elseif (preg_match('/^[•\-]\s+(.+)/u', $line, $m) || preg_match('/^\d+[.)]\s+(.+)/u', $line, $n)) {
                $type = isset($m[1]) ? 'ul' : 'ol';
                if ($last !== null && $blocks[$last]['type'] === $type) {
                    $blocks[$last]['items'][] = $m[1] ?? $n[1];
                } else {
                    $blocks[] = ['type' => $type, 'items' => [$m[1] ?? $n[1]]];
                }
                unset($m, $n);
            } elseif (mb_strlen($line) <= 80 && $line === mb_strtoupper($line) && preg_match('/\p{Lu}/u', $line)) {
                $heading = Str::ucfirst(mb_strtolower($line));
                $blocks[] = ['type' => 'h2', 'text' => $this->solution ? str_ireplace($this->solution->name, $this->solution->name, $heading) : $heading];
            } elseif ($last !== null && $blocks[$last]['type'] === 'p') {
                $blocks[$last]['text'] .= ' '.$line;
            } else {
                $blocks[] = ['type' => 'p', 'text' => $line];
            }
        }

        return array_values(array_filter($blocks, fn ($b) => $b['type'] !== 'break'));
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
            $this->published_on_site && filled($this->slug) => route('articles.show', $this),
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
