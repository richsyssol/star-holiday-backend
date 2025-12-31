<?php

namespace App\Http\Controllers;

use App\Models\VideoFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoFeedbackController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'feedback' => 'nullable|string',
            'video' => 'required|file|mimetypes:video/mp4,video/webm,video/quicktime|max:102400' // Max 100MB
        ]);

        try {
            // Store the video file
            $videoPath = $request->file('video')->store('video-feedback', 'public');
            
            // Create record in database
            $feedback = VideoFeedback::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'feedback' => $validated['feedback'],
                'video_path' => $videoPath
            ]);

            return response()->json([
                'message' => 'Video feedback submitted successfully',
                'data' => $feedback
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error submitting video feedback',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}