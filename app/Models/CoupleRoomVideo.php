<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoupleRoomVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'video_url',
        'video_file',
        'review',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
}