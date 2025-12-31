<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
      
        'type',
        'video_path',
        'youtube_url',
        'thumbnail',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getVideoSourceAttribute()
    {
        if ($this->type === 'youtube') {
            return $this->youtube_url;
        }
        
        return asset('storage/' . $this->video_path);
    }

    public function getEmbedUrlAttribute()
    {
        if ($this->type === 'youtube') {
            // Extract YouTube ID from URL
            preg_match('/^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&]+)/', $this->youtube_url, $matches);
            $videoId = $matches[1] ?? null;
            
            if ($videoId) {
                return "https://www.youtube.com/embed/{$videoId}";
            }
        }
        
        return null;
    }
}