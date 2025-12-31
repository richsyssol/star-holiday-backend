<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class AboutSaputara extends Model
{
    protected $table = 'about_saputara';
    
    protected $fillable = [
        'about_content',
        'about_image',
        'sightseeing_content',
        'sightseeing_image',
        'video_testimonials',
        'gallery'
    ];
    
    protected $casts = [
        'video_testimonials' => 'array',
        'gallery' => 'array'
    ];
    
    // Accessor for video testimonials
    protected function videoTestimonials(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true) ?? [],
            set: fn ($value) => json_encode($value),
        );
    }
    
    // Accessor for gallery
    protected function gallery(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true) ?? [],
            set: fn ($value) => json_encode($value),
        );
    }
}