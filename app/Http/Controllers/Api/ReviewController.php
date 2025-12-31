<?php

namespace App\Http\Controllers\Api;

use App\Models\Review;
use App\Mail\ReviewSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Review submission attempt', $request->all());

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $review = Review::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'review' => $request->review,
                'rating' => $request->rating,
                'type' => 'text'
            ]);

            Log::info('Review created successfully', ['review_id' => $review->id]);

            // Send email notification
            try {
                Mail::to('kalyanideore235@gmail.com')
                    ->send(new ReviewSubmitted($review));
                Log::info('Email sent successfully');
            } catch (\Exception $emailError) {
                Log::error('Email sending failed: ' . $emailError->getMessage());
                // Continue even if email fails
            }

            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully!',
                'data' => $review
            ], 201);

        } catch (\Exception $e) {
            Log::error('Review submission error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit review. Please try again.',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function storeVideoFeedback(Request $request)
    {
        Log::info('Video feedback submission attempt');

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'video' => 'required|file|mimes:mp4,mov,avi,webm|max:102400',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            if ($request->hasFile('video')) {
                $videoPath = $request->file('video')->store('video-feedback', 'public');
                
                $review = Review::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'feedback' => $request->feedback ?? '',
                    'rating' => 5,
                    'type' => 'video',
                    'video_path' => $videoPath,
                    'review' => $request->feedback ?? 'Video feedback submitted',
                    'phone' => $request->phone ?? ''
                ]);

                try {
                    Mail::to('kalyanideore235@gmail.com')
                        ->send(new ReviewSubmitted($review, true));
                    Log::info('Video review email sent successfully');
                } catch (\Exception $emailError) {
                    Log::error('Video review email failed: ' . $emailError->getMessage());
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Video feedback submitted successfully!',
                    'data' => $review
                ], 201);
            }

            return response()->json([
                'success' => false,
                'message' => 'No video file uploaded.'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Video feedback error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit video feedback. Please try again.',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    // Remove CSRF token related methods as they're no longer needed
    public function testConnection()
    {
        return response()->json([
            'success' => true,
            'message' => 'API is working!',
            'timestamp' => now()
        ]);
    }
}   