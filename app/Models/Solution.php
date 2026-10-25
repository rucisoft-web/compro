<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Solution extends Model
{
    /**
     * Used when a product has no pilot scope of its own yet.
     */
    public const DEFAULT_PILOT = [
        ['element' => 'Durasi', 'scope' => 'Usulan awal 30 hari, disesuaikan kebutuhan'],
        ['element' => 'Unit', 'scope' => 'Satu unit kerja, divisi, atau cabang'],
        ['element' => 'Pengguna', 'scope' => 'Sponsor, administrator, dan beberapa pengguna inti'],
        ['element' => 'Data', 'scope' => 'Sejumlah data dan proses yang disepakati bersama'],
        ['element' => 'Pendampingan', 'scope' => 'Kickoff, pemeriksaan kemajuan, dan evaluasi akhir'],
        ['element' => 'Ukuran keberhasilan', 'scope' => 'Disepakati sebelum pilot dimulai'],
        ['element' => 'Keputusan akhir', 'scope' => 'Lanjut, perbaiki lingkup, atau tidak melanjutkan'],
    ];

    /**
     * Cost components every proposal separates, so buyers see what they pay for.
     */
    public const PROPOSAL_COMPONENTS = [
        ['Lisensi atau langganan', 'Hak penggunaan, batas pengguna, entitas, atau modul', 'heroicon-o-key'],
        ['Implementasi', 'Konfigurasi, pengaturan akses, dan penyiapan proses', 'heroicon-o-cog-6-tooth'],
        ['Migrasi data', 'Impor, pemetaan, dan pemeriksaan data', 'heroicon-o-arrow-path'],
        ['Pelatihan', 'Untuk administrator dan pengguna', 'heroicon-o-academic-cap'],
        ['Pendampingan', 'Bantuan penerapan dan review penggunaan', 'heroicon-o-lifebuoy'],
        ['Tambahan (opsional)', 'Integrasi, pengembangan khusus, atau penggunaan AI', 'heroicon-o-puzzle-piece'],
    ];

    protected $fillable = [
        'name', 'slug', 'tagline', 'problem_statement', 'description', 'features', 'pilot_scope', 'icon', 'logo', 'logo_url',
        'image', 'image_url', 'website_url', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'pilot_scope' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function catalogItems(): HasMany
    {
        return $this->hasMany(CatalogItem::class);
    }

    public function pilotScope(): array
    {
        return $this->pilot_scope ?: self::DEFAULT_PILOT;
    }

    public function academyItems(): HasMany
    {
        return $this->catalogItems()->whereIn('type', CatalogItem::ACADEMY_TYPES);
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
