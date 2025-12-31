<?php

namespace App\Http\Controllers\Api;

use App\Models\CoupleRoomAbout;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CoupleRoomAboutController extends Controller
{
    /**
     * Display the active couple room about data.
     */
    public function index()
    {
        try {
            $roomData = CoupleRoomAbout::where('is_active', true)->first();

            if (!$roomData) {
                return response()->json([
                    'message' => 'No active couple room data found'
                ], 404);
            }

            // Format the response data
            $formattedData = $roomData->toArray();
            
            // Format images with full URLs - UPDATED PATH
            if (!empty($formattedData['images'])) {
                $formattedData['images'] = array_map(function($image) {
                    // Updated path to match your requirement
                    return asset('uploads/couple-room-images/' . basename($image));
                }, $formattedData['images']);
            } else {
                $formattedData['images'] = [];
            }

            return response()->json([
                'data' => $formattedData,
                'message' => 'Couple room data retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('CoupleRoomAboutController index error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to retrieve couple room data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Add error handling for other methods if needed
}