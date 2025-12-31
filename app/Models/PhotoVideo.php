<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_path',
        'video_url',
        'caption',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getVideoSourceAttribute()
    {
        if ($this->video_path) {
            return asset('uploads/' . $this->video_path);
        }
        return $this->video_url;
    }

    public function getTypeAttribute()
    {
        return $this->video_path ? 'uploaded' : 'external';
    }
}