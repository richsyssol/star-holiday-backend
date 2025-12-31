<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * Display a listing of active videos.
     */ 
    public function index()
    {
        $videos = Video::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get()
            ->map(function ($video) {
                return [
                    'id' => $video->id,
                    'type' => $video->type,
                    'video_path' => $video->video_path,
                    'youtube_url' => $video->youtube_url,
                    'youtube_id' => $video->youtube_id,
                    'embed_url' => $video->embed_url,
                    'thumbnail' => $video->thumbnail,
                    'order' => $video->order,
                    'created_at' => $video->created_at,
                    'updated_at' => $video->updated_at,
                ];
            });

        return response()->json($videos);
    }

    /**
     * Display the specified active video.
     */
    public function show($id)
    {
        $video = Video::where('id', $id)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json([
            'id' => $video->id,
            'type' => $video->type,
            'video_path' => $video->video_path,
            'youtube_url' => $video->youtube_url,
            'youtube_id' => $video->youtube_id,
            'embed_url' => $video->embed_url,
            'thumbnail' => $video->thumbnail,
            'order' => $video->order,
            'created_at' => $video->created_at,
            'updated_at' => $video->updated_at,
        ]);
    }

    /**
     * Get the first active video.
     */
    public function active()
    {
        $video = Video::where('is_active', true)
            ->orderBy('order', 'asc')
            ->first();

        if (!$video) {
            return response()->json(['message' => 'No active video found'], 404);
        }

        return response()->json([
            'id' => $video->id,
            'type' => $video->type,
            'video_path' => $video->video_path,
            'youtube_url' => $video->youtube_url,
            'youtube_id' => $video->youtube_id,
            'embed_url' => $video->embed_url,
            'thumbnail' => $video->thumbnail,
            'order' => $video->order,
            'created_at' => $video->created_at,
            'updated_at' => $video->updated_at,
        ]);
    }
}