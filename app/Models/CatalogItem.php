<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatalogItem extends Model
{
    public const TYPES = [
        'software' => 'Software',
        'buku' => 'Buku',
        'workshop' => 'Workshop',
    ];

    protected $fillable = [
        'type', 'solution_id', 'title', 'slug', 'summary', 'body', 'cover_image',
        'event_date', 'location', 'whatsapp_message', 'is_featured', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
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

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }

    public function whatsappUrl(): string
    {
        return Setting::whatsappUrl($this->whatsapp_message ?: "Halo Rucisoft, saya tertarik dengan {$this->typeLabel()} \"{$this->title}\".");
    }
}
