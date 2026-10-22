<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $fillable = ['number', 'label', 'title', 'description', 'sort_order'];
}
