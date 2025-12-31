<?php
// app/Models/SixbedroomAbout.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SixbedroomAbout extends Model
{
    use HasFactory;

    protected $table = 'sixbedroom_abouts';

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
}