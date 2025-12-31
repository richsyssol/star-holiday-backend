<?php
// app/Models/VideoSection.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'video_type',
        'youtube_url',
        'uploaded_video_path',
        'autoplay',
        'muted',
        'loop',
        'show_controls',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'autoplay' => 'boolean',
        'muted' => 'boolean',
        'loop' => 'boolean',
        'show_controls' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['youtube_id'];

    /**
     * Extract YouTube ID from URL
     */
    public function getYoutubeIdAttribute()
    {
        if ($this->video_type !== 'youtube' || empty($this->youtube_url)) {
            return null;
        }

        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $this->youtube_url, $matches);
        
        return $matches[1] ?? null;
    }
}