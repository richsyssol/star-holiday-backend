<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class FestivalGallery extends Model
{
    protected $table = 'festival_galleries';
    
    protected $fillable = [
        'category_id',
        'images',
        'is_active',
        'sort_order'
    ];
    
    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean'
    ];
    
    // Accessor for images
    protected function images(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true) ?? [],
            set: fn ($value) => json_encode($value),
        );
    }
    
    // Relationship with category
    public function category(): BelongsTo
    {
        return $this->belongsTo(FestivalCategory::class, 'category_id');
    }
    
    // Scope for active galleries
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    // Scope ordered
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }
}