<?php
// app/Http/Controllers/API/CoupleRoomImageController.php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CoupleRoomImage;
use Illuminate\Http\Request;

class CoupleRoomImageController extends Controller
{
    public function index()
    {
        $images = CoupleRoomImage::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get(['id', 'image_path']);

        return response()->json([
            'success' => true,
            'data' => $images
        ]);
    }

    public function show($id)
    {
        $image = CoupleRoomImage::where('id', $id)
            ->where('is_active', true)
            ->first(['id', 'image_path']);

        if (!$image) {
            return response()->json([
                'success' => false,
                'message' => 'Image not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $image
        ]);
    }
}