<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResortVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'welcome_text',
        'description',
        'youtube_url',
        'uploaded_video_path',
        'use_uploaded_video',
        'autoplay',
        'mute',
        'loop',
    ];

    protected $casts = [
        'use_uploaded_video' => 'boolean',
        'autoplay' => 'boolean',
        'mute' => 'boolean',
        'loop' => 'boolean',
    ];

    /**
     * Get the full URL for the uploaded video
     */
    public function getVideoUrlAttribute()
    {
        if ($this->uploaded_video_path) {
            // Check if it's already a full URL (for stored files)
            if (filter_var($this->uploaded_video_path, FILTER_VALIDATE_URL)) {
                return $this->uploaded_video_path;
            }
            return asset('storage/' . $this->uploaded_video_path);
        }
        
        return null;
    }
}   