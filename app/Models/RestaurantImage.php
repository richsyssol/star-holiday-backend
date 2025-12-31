<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_section_id',
        'image_path',
        'order',
    ];

    public function restaurantSection(): BelongsTo
    {
        return $this->belongsTo(RestaurantSection::class);
    }
}