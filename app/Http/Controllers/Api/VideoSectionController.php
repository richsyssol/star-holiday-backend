<?php
// app/Http/Controllers/VideoSectionController.php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\VideoSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VideoSectionController extends Controller
{
    /**
     * Display the active video section
     */
    public function showActive()
    {
        try {
            \Log::info('Fetching active video section');
            
            $videoSection = VideoSection::where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->first();
            
            if (!$videoSection) {
                \Log::info('No active video section found');
                return response()->json([
                    'is_active' => false,
                    'message' => 'No active video section found'
                ], 200);
            }
            
            \Log::info('Found active video section: ' . $videoSection->id);
            
            // Get full URL for uploaded video
            $uploadedVideoUrl = null;
            if ($videoSection->video_type === 'upload' && $videoSection->uploaded_video_path) {
                try {
                    // Use the public disk URL generation
                    $uploadedVideoUrl = Storage::disk('public')->url($videoSection->uploaded_video_path);
                    
                    \Log::info('Generated video URL: ' . $uploadedVideoUrl);
                } catch (\Exception $e) {
                    \Log::error('Error generating video URL: ' . $e->getMessage());
                    $uploadedVideoUrl = null;
                }
            }
            
            // Return the model with appended youtube_id attribute
            $responseData = [
                'id' => $videoSection->id,
                'title' => $videoSection->title,
                'video_type' => $videoSection->video_type,
                'youtube_url' => $videoSection->youtube_url,
                'youtube_id' => $videoSection->youtube_id, // This is now computed, not from DB
                'uploaded_video_path' => $videoSection->uploaded_video_path,
                'uploaded_video_url' => $uploadedVideoUrl,
                'autoplay' => (bool) $videoSection->autoplay,
                'muted' => (bool) $videoSection->muted,
                'loop' => (bool) $videoSection->loop,
                'show_controls' => (bool) $videoSection->show_controls,
                'is_active' => (bool) $videoSection->is_active,
            ];
            
            \Log::info('Sending video section data', $responseData);
            
            return response()->json($responseData);
            
        } catch (\Exception $e) {
            \Log::error('Error in VideoSectionController: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'error' => 'Internal server error',
                'message' => 'Unable to load video section data',
                'details' => env('APP_DEBUG') ? $e->getMessage() : 'Please check server logs'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage (for admin)
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'video_type' => 'required|in:youtube,upload',
                'youtube_url' => 'required_if:video_type,youtube|url|nullable',
                'uploaded_video' => 'required_if:video_type,upload|file|mimes:mp4,webm,ogg|max:102400', // 100MB max
                'autoplay' => 'boolean',
                'muted' => 'boolean',
                'loop' => 'boolean',
                'show_controls' => 'boolean',
                'sort_order' => 'integer',
                'is_active' => 'boolean',
            ]);

            // Handle file upload
            if ($request->hasFile('uploaded_video') && $request->file('uploaded_video')->isValid()) {
                $path = $request->file('uploaded_video')->store('video-sections', 'public');
                $validated['uploaded_video_path'] = $path;
            }

            // Remove youtube_id from validated data if it exists (it's computed, not stored)
            if (isset($validated['youtube_id'])) {
                unset($validated['youtube_id']);
            }

            $videoSection = VideoSection::create($validated);

            return response()->json($videoSection, 201);
            
        } catch (\Exception $e) {
            Log::error('Error storing video section: ' . $e->getMessage());
            return response()->json([
                'error' => 'Internal server error',
                'message' => 'Failed to create video section'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage (for admin)
     */
    public function update(Request $request, $id)
    {
        try {
            $videoSection = VideoSection::findOrFail($id);
            
            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'video_type' => 'sometimes|required|in:youtube,upload',
                'youtube_url' => 'required_if:video_type,youtube|url|nullable',
                'uploaded_video' => 'sometimes|file|mimes:mp4,webm,ogg|max:102400',
                'autoplay' => 'boolean',
                'muted' => 'boolean',
                'loop' => 'boolean',
                'show_controls' => 'boolean',
                'sort_order' => 'integer',
                'is_active' => 'boolean',
            ]);

            // Remove youtube_id from validated data if it exists (it's computed, not stored)
            if (isset($validated['youtube_id'])) {
                unset($validated['youtube_id']);
            }

            // Handle file upload if a new file is provided
            if ($request->hasFile('uploaded_video') && $request->file('uploaded_video')->isValid()) {
                // Delete old video file if it exists
                if ($videoSection->uploaded_video_path) {
                    Storage::disk('public')->delete($videoSection->uploaded_video_path);
                }
                
                $path = $request->file('uploaded_video')->store('video-sections', 'public');
                $validated['uploaded_video_path'] = $path;
            }

            $videoSection->update($validated);

            return response()->json($videoSection);
            
        } catch (\Exception $e) {
            Log::error('Error updating video section: ' . $e->getMessage());
            return response()->json([
                'error' => 'Internal server error',
                'message' => 'Failed to update video section'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage (for admin)
     */
    public function destroy($id)
    {
        try {
            $videoSection = VideoSection::findOrFail($id);
            
            // Delete associated video file
            if ($videoSection->uploaded_video_path) {
                Storage::disk('public')->delete($videoSection->uploaded_video_path);
            }
            
            $videoSection->delete();

            return response()->json([
                'message' => 'Video section deleted successfully'
            ], 200);
            
        } catch (\Exception $e) {
            Log::error('Error deleting video section: ' . $e->getMessage());
            return response()->json([
                'error' => 'Internal server error',
                'message' => 'Failed to delete video section'
            ], 500);
        }
    }
}