<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class SoftwareCategory extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'icon', 'sort_order'];

    public function solutions(): HasMany
    {
        return $this->hasMany(Solution::class);
    }

    public function activeSolutions(): HasMany
    {
        return $this->solutions()->where('is_active', true)->orderBy('sort_order');
    }

    /**
     * Categories in display order, each carrying its products in `items`.
     * Products without a category land in a trailing "Lainnya" group;
     * empty categories are left out.
     *
     * @param  Collection<int, Solution>  $solutions
     * @return Collection<int, SoftwareCategory>
     */
    public static function withProducts(Collection $solutions): Collection
    {
        // Keeps every page up when the code is deployed before `php artisan migrate`.
        if (! Schema::hasTable('software_categories')) {
            return $solutions->isEmpty() ? new Collection : new Collection([
                (new self(['name' => 'Semua produk', 'slug' => 'semua-produk']))->setAttribute('items', $solutions->values()),
            ]);
        }

        $byCategory = $solutions->groupBy(fn (Solution $s) => (int) $s->software_category_id);

        $groups = static::orderBy('sort_order')->orderBy('name')->get()
            ->each(fn (self $c) => $c->setAttribute('items', $byCategory->get($c->id, collect())->values()));

        $known = $groups->pluck('id')->all();
        $orphans = $solutions->reject(fn (Solution $s) => in_array($s->software_category_id, $known, true))->values();

        if ($orphans->isNotEmpty()) {
            $groups->push((new self(['name' => 'Lainnya', 'slug' => 'lainnya']))->setAttribute('items', $orphans));
        }

        return $groups->filter(fn (self $c) => $c->items->isNotEmpty())->values();
    }
}
