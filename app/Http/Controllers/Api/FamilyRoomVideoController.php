<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FamilyRoomVideo; 
use Illuminate\Http\Request;

class FamilyRoomVideoController extends Controller
{
    public function index()
    {
        $videos = FamilyRoomVideo::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get(['name', 'video_url', 'video_file', 'review']);
            
        return response()->json([
            'success' => true,
            'data' => $videos->map(function ($video) {
                return [
                    'name' => $video->name,
                    'video' => $video->video_url ?? asset('storage/' . $video->video_file),
                    'review' => $video->review
                ];
            })
        ]);
    }
    
    public function show($id)
    {
        $video = FamilyRoomVideo::where('id', $id)
            ->where('is_active', true)
            ->first(['name', 'video_url', 'video_file', 'review']);
            
        if (!$video) {
            return response()->json([
                'success' => false,
                'message' => 'Video not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'name' => $video->name,
                'video' => $video->video_url ?? asset('storage/' . $video->video_file),
                'review' => $video->review
            ]
        ]);
    }
}