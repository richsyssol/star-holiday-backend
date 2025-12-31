<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FestivalCategory extends Model
{
    protected $table = 'festival_categories';
    
    protected $fillable = [
        'name',
        'is_active',
        'sort_order'
    ];
    
    protected $casts = [
        'is_active' => 'boolean'
    ];
    
    // Relationship with galleries
    public function galleries(): HasMany
    {
        return $this->hasMany(FestivalGallery::class, 'category_id');
    }
    
    // Scope for active categories
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    // Scope ordered
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}