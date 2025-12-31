<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Add this if using soft deletes

class Testimonial extends Model
{
    use HasFactory;
    // use SoftDeletes; // Uncomment if using soft deletes

    protected $fillable = [
        'name',
        'project',
        'quote',
        'rating',
        'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'rating' => 'integer'
    ];
}