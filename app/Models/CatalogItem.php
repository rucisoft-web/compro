<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CatalogItem extends Model
{
    public const TYPES = [
        'software' => 'Software',
        'buku' => 'Buku',
        'webinar' => 'Webinar',
        'workshop' => 'Workshop',
        'inhouse' => 'In-house & Pilot',
    ];

    /**
     * Rucisoft Academy offer tiers, from free introduction to paid in-house work.
     */
    public const ACADEMY_TYPES = ['webinar', 'workshop', 'inhouse'];

    protected $fillable = [
        'type', 'solution_id', 'title', 'subtitle', 'slug', 'summary', 'body', 'outcomes', 'agenda',
        'registration_open', 'cover_image', 'event_date', 'location', 'audience', 'duration',
        'price_label', 'whatsapp_message', 'is_featured', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'outcomes' => 'array',
            'agenda' => 'array',
            'registration_open' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function solution(): BelongsTo
    {
        return $this->belongsTo(Solution::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isAcademy(): bool
    {
        return in_array($this->type, self::ACADEMY_TYPES, true);
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }

    public function coverUrl(): ?string
    {
        if ($this->cover_image) {
            return Storage::disk('public')->url($this->cover_image);
        }

        return $this->type === 'software' ? $this->solution?->imageUrl() : null;
    }

    public function coverFallbackUrl(): ?string
    {
        return $this->cover_image ? null : $this->solution?->fallbackImageUrl();
    }

    public function whatsappUrl(): string
    {
        return Setting::whatsappUrl($this->whatsapp_message ?: "Halo Rucisoft, saya tertarik dengan {$this->typeLabel()} \"{$this->title}\".");
    }
}
