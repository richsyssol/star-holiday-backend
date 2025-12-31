<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'review',
        'rating',
        'type',
        'video_path',
        'feedback'
    ];

    protected $casts = [
        'rating' => 'integer'
    ];

    // Remove the booted method to avoid duplicate emails
}