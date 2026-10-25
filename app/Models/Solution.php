<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Solution extends Model
{
    protected $fillable = [
        'name', 'slug', 'tagline', 'description', 'features', 'icon', 'logo', 'logo_url',
        'image', 'image_url', 'website_url', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function catalogItems(): HasMany
    {
        return $this->hasMany(CatalogItem::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Uploaded logo first, then an external logo URL, then the bundled mark.
     */
    public function logoUrl(): ?string
    {
        if ($this->logo) {
            return Storage::disk('public')->url($this->logo);
        }

        return $this->logo_url ?: $this->fallbackLogoUrl();
    }

    public function fallbackLogoUrl(): ?string
    {
        return self::bundledAsset($this->slug.'-mark.svg');
    }

    /**
     * Uploaded image first, then an external image URL, then the bundled cover.
     */
    public function imageUrl(): ?string
    {
        if ($this->image) {
            return Storage::disk('public')->url($this->image);
        }

        return $this->image_url ?: $this->fallbackImageUrl();
    }

    public function fallbackImageUrl(): ?string
    {
        return self::bundledAsset($this->slug.'-cover.svg');
    }

    private static function bundledAsset(string $file): ?string
    {
        return is_file(public_path('images/products/'.$file)) ? asset('images/products/'.$file) : null;
    }
}
