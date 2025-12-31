<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FourBedRoomAbout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FourBedRoomAboutController extends Controller
{
    public function index()
    {
        $roomData = FourBedRoomAbout::first();
        
        if (!$roomData) {
            return response()->json([
                'message' => 'Room data not found'
            ], 404);
        }
        
        // Convert image paths to full URLs
        if ($roomData->images && is_array($roomData->images)) {
            $roomData->images = array_map(function($image) {
                if (filter_var($image, FILTER_VALIDATE_URL)) {
                    return $image; // Already a full URL
                }
                
                // Convert relative path to full URL
                if (strpos($image, '/uploads/') === 0) {
                    return url($image);
                }
                
                // Handle other storage paths
                if (strpos($image, 'storage/') === 0) {
                    return asset($image);
                }
                
                // Default: assume it's in the uploads directory
                return asset('uploads/' . ltrim($image, '/'));
            }, $roomData->images);
        }
        
        return response()->json([
            'data' => $roomData
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'tagline' => 'sometimes|string',
            'descriptions' => 'sometimes|array',
            'descriptions.*' => 'string',
            'specs' => 'sometimes|array',
            'amenities' => 'sometimes|array',
            'amenities.*' => 'string',
            'images' => 'sometimes|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'styling' => 'sometimes|array',
            'booking_button' => 'sometimes|array',
        ]);

        $roomData = FourBedRoomAbout::firstOrNew();

        // Handle file uploads
        if ($request->hasFile('images')) {
            $imagePaths = [];
            
            // Delete old images if needed
            if ($roomData->images) {
                foreach ($roomData->images as $oldImage) {
                    // Extract just the filename from the full URL/path
                    $filename = basename($oldImage);
                    if (Storage::disk('public')->exists('room-images/' . $filename)) {
                        Storage::disk('public')->delete('room-images/' . $filename);
                    }
                }
            }
            
            foreach ($request->file('images') as $image) {
                $path = $image->store('room-images', 'public');
                $imagePaths[] = 'storage/' . $path; // This will create paths like "storage/room-images/filename.jpg"
            }
            
            $validated['images'] = $imagePaths;
        } elseif (isset($validated['images']) && is_array($validated['images'])) {
            // If images are passed as URLs (from frontend), keep them as is
            $validated['images'] = $validated['images'];
        }

        $roomData->fill($validated);
        $roomData->save();

        return response()->json([
            'message' => 'Room data updated successfully',
            'data' => $roomData
        ]);
    }
}