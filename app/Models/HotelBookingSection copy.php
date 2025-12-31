<?php

// app/Models/HotelBookingSection.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelBookingSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'button_text',
        'button_link',
        'video_type',
        'video_url',
        'uploaded_video',
        'is_active'
    ];
}