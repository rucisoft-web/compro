<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * The funnel counts organizations, not people: several leads from one
 * organization are still one buying opportunity per product.
 */
class Organization extends Model
{
    protected $fillable = ['name', 'sector', 'notes'];

    public function opportunities(): HasMany
    {
        return $this->hasMany(Opportunity::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }
}
