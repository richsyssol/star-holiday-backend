<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_path',
        'caption',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('uploads/' . $this->image_path);
        }
        return null;
    }
}