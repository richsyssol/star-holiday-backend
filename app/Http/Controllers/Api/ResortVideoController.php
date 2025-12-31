<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ResortVideo;

class ResortVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $resortVideos = ResortVideo::all();
            
            return response()->json([
                'success' => true,
                'data' => $resortVideos->map(function ($video) {
                    return [
                        'id' => $video->id,
                        'title' => $video->title,
                        'welcome_text' => $video->welcome_text,
                        'description' => $video->description,
                        'youtube_url' => $video->youtube_url,
                        'video_url' => $video->video_url,
                        'use_uploaded_video' => (bool) $video->use_uploaded_video,
                        'autoplay' => (bool) $video->autoplay,
                        'mute' => (bool) $video->mute,
                        'loop' => (bool) $video->loop,
                    ];
                })
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching resort videos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the resort video data
     */
    public function show()
    {
        try {
            $resortVideo = ResortVideo::first();
            
            if (!$resortVideo) {
                return response()->json([
                    'success' => false,
                    'message' => 'No resort video data found',
                    'data' => null
                ], 200);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $resortVideo->id,
                    'title' => $resortVideo->title,
                    'welcome_text' => $resortVideo->welcome_text,
                    'description' => $resortVideo->description,
                    'youtube_url' => $resortVideo->youtube_url,
                    'video_url' => $resortVideo->video_url,
                    'use_uploaded_video' => (bool) $resortVideo->use_uploaded_video,
                    'autoplay' => (bool) $resortVideo->autoplay,
                    'mute' => (bool) $resortVideo->mute,
                    'loop' => (bool) $resortVideo->loop,
                    'created_at' => $resortVideo->created_at,
                    'updated_at' => $resortVideo->updated_at
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching resort video data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}