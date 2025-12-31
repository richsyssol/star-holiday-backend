<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImpactStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'number',
        'suffix',
        'decimals',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'number' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}