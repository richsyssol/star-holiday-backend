<?php

namespace App\Http\Controllers\Api;

use App\Models\SixbedroomAbout;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SixbedroomAboutController extends Controller
{
    public function index()
    {
        try {
            $roomData = SixbedroomAbout::where('is_active', true)->first();

            if (!$roomData) {
                return response()->json([
                    'message' => 'No active six bedroom data found'
                ], 404);
            }

            // Format the response data
            $formattedData = $roomData->toArray();
            
            // Format images with full URLs - Using the correct path
            if (!empty($formattedData['images'])) {
                $formattedData['images'] = array_map(function($image) {
                    // Use the correct URL path based on your filesystem config
                    return url('uploads/sixbed-room-img/' . basename($image));
                }, $formattedData['images']);
            } else {
                $formattedData['images'] = [];
            }

            return response()->json([
                'data' => $formattedData,
                'message' => 'Six bedroom data retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('SixbedroomAboutController index error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to retrieve six bedroom data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'tagline' => 'required|string',
            'descriptions' => 'required|array',
            'descriptions.*' => 'string',
            'specs' => 'required|array',
            'amenities' => 'required|array',
            'amenities.*' => 'string',
            'images' => 'nullable|array',
            'booking_button' => 'required|array',
            'styling' => 'required|array',
            'is_active' => 'boolean'
        ]);

        $room = SixbedroomAbout::create($validated);

        return response()->json([
            'data' => $room,
            'message' => 'Six bedroom data created successfully'
        ]);
    }

    public function show(SixbedroomAbout $sixbedroomAbout)
    {
        // Format the response data
        $formattedData = $sixbedroomAbout->toArray();
        
        // Format images with full URLs - Using the correct path
        if (!empty($formattedData['images'])) {
            $formattedData['images'] = array_map(function($image) {
                // Use the correct URL path based on your filesystem config
                return url('uploads/sixbed-room-img/' . basename($image));
            }, $formattedData['images']);
        } else {
            $formattedData['images'] = [];
        }

        return response()->json([
            'data' => $formattedData,
            'message' => 'Six bedroom data retrieved successfully'
        ]);
    }

    public function update(Request $request, SixbedroomAbout $sixbedroomAbout)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'tagline' => 'sometimes|required|string',
            'descriptions' => 'sometimes|required|array',
            'descriptions.*' => 'string',
            'specs' => 'sometimes|required|array',
            'amenities' => 'sometimes|required|array',
            'amenities.*' => 'string',
            'images' => 'nullable|array',
            'booking_button' => 'sometimes|required|array',
            'styling' => 'sometimes|required|array',
            'is_active' => 'boolean'
        ]);

        $sixbedroomAbout->update($validated);

        return response()->json([
            'data' => $sixbedroomAbout,
            'message' => 'Six bedroom data updated successfully'
        ]);
    }

    public function destroy(SixbedroomAbout $sixbedroomAbout)
    {
        $sixbedroomAbout->delete();

        return response()->json([
            'message' => 'Six bedroom data deleted successfully'
        ], 204);
    }
}