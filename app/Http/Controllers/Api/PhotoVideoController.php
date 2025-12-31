<?php

namespace App\Http\Controllers\Api;

use App\Models\PhotoVideo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoVideoController extends Controller
{
    public function index()
    {
        $videos = PhotoVideo::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(function ($video) {
                return [
                    'id' => $video->id,
                    'src' => $video->video_source,
                    'caption' => $video->caption,
                    'type' => $video->type,
                ];
            });

        return response()->json($videos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'video' => 'required_without:video_url|file|mimes:mp4,avi,mov,wmv,webm|max:20480', // 20MB max
            'video_url' => 'required_without:video|url',
            'caption' => 'required|string|max:255',
        ]);

        $relativePath = null;

        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $filename = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();
            $videoPath = $video->move(public_path('uploads/photo-videos'), $filename);
            $relativePath = 'photo-videos/' . $filename;
            $videoData = ['video_path' => $relativePath];
        } else {
            $videoData = ['video_url' => $request->video_url];
        }

        $video = PhotoVideo::create(array_merge($videoData, [
            'caption' => $request->caption,
            'order' => PhotoVideo::max('order') + 1,
        ]));

        return response()->json([
            'message' => 'Video added successfully',
            'video' => [
                'id' => $video->id,
                'src' => $video->video_source,
                'caption' => $video->caption,
                'type' => $video->type,
            ]
        ], 201);
    }

    public function show(PhotoVideo $photoVideo)
    {
        return response()->json([
            'id' => $photoVideo->id,
            'src' => $photoVideo->video_source,
            'caption' => $photoVideo->caption,
            'type' => $photoVideo->type,
        ]);
    }

    public function update(Request $request, PhotoVideo $photoVideo)
    {
        $request->validate([
            'caption' => 'sometimes|string|max:255',
            'video' => 'sometimes|file|mimes:mp4,avi,mov,wmv,webm|max:20480',
            'video_url' => 'sometimes|url',
            'order' => 'sometimes|integer',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($request->hasFile('video')) {
            // Delete old video file if exists
            if ($photoVideo->video_path && file_exists(public_path('uploads/' . $photoVideo->video_path))) {
                unlink(public_path('uploads/' . $photoVideo->video_path));
            }
            
            // Store new video
            $video = $request->file('video');
            $filename = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();
            $videoPath = $video->move(public_path('uploads/photo-videos'), $filename);
            $photoVideo->video_path = 'photo-videos/' . $filename;
            $photoVideo->video_url = null; // Clear URL if uploading file
        } elseif ($request->has('video_url')) {
            // Delete old video file if exists
            if ($photoVideo->video_path && file_exists(public_path('uploads/' . $photoVideo->video_path))) {
                unlink(public_path('uploads/' . $photoVideo->video_path));
            }
            
            $photoVideo->video_path = null;
            $photoVideo->video_url = $request->video_url;
        }

        $photoVideo->update($request->only(['caption', 'order', 'is_active']));

        return response()->json([
            'message' => 'Video updated successfully',
            'video' => [
                'id' => $photoVideo->id,
                'src' => $photoVideo->video_source,
                'caption' => $photoVideo->caption,
                'type' => $photoVideo->type,
            ]
        ]);
    }

    public function destroy(PhotoVideo $photoVideo)
    {
        // Delete video file if exists
        if ($photoVideo->video_path && file_exists(public_path('uploads/' . $photoVideo->video_path))) {
            unlink(public_path('uploads/' . $photoVideo->video_path));
        }
        
        $photoVideo->delete();

        return response()->json(['message' => 'Video deleted successfully']);
    }
}