<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class FamilyRoomVideo extends Model
{
    use HasFactory;

    protected $table = 'family_room_videos';

    protected $fillable = [
        'name',
        'video_url',
        'video_file',
        'review',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the video source (URL or uploaded file)
     */
    protected function videoSource(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->video_url ?? 
                ($this->video_file ? asset('storage/' . $this->video_file) : null)
        );
    }
}