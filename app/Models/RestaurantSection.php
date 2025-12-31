<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RestaurantSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'heading',
        'description',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(RestaurantImage::class)->orderBy('order', 'asc');
    }
}