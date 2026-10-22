<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DashboardMetric extends Model
{
    protected $fillable = ['label', 'value', 'sort_order'];
}
