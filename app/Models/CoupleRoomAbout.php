<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class CoupleRoomAbout extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'tagline',
        'descriptions',
        'specs',
        'amenities',
        'images',
        'booking_button',
        'styling',
        'is_active'
    ];

    protected $casts = [
        'descriptions' => 'array',
        'specs' => 'array',
        'amenities' => 'array',
        'images' => 'array',
        'booking_button' => 'array',
        'styling' => 'array',
        'is_active' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Remove the custom accessor and use a method instead
    public function getFormattedImages()
    {
        $images = $this->images ?? [];
        
        return array_map(function ($imagePath) {
            // If it's already a full URL, return as is
            if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
                return $imagePath;
            }
            
            // Check if file exists before generating URL
            $cleanPath = str_replace('uploads/', '', $imagePath);
            
            if (Storage::disk('public')->exists($cleanPath)) {
                return Storage::disk('public')->url($cleanPath);
            }
            
            // Return placeholder if image doesn't exist
            return "https://via.placeholder.com/600x400?text=Image+Not+Found";
        }, $images);
    }
}