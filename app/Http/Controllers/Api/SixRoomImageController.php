<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SixRoomImage;
use Illuminate\Http\Request;

class SixRoomImageController extends Controller
{
    public function index()
    {
        $images = SixRoomImage::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get(['id', 'title', 'description', 'image_path', 'sort_order']);

        return response()->json([
            'success' => true,
            'data' => $images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'title' => $image->title,
                    'description' => $image->description,
                    'src' => asset('uploads/' . $image->image_path),
                    'alt' => $image->title,
                    'sort_order' => $image->sort_order
                ];
            })
        ]);
    }

    public function show($id)
    {
        $image = SixRoomImage::where('is_active', true)->find($id);

        if (!$image) {
            return response()->json([
                'success' => false,
                'message' => 'Image not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $image->id,
                'title' => $image->title,
                'description' => $image->description,
                'src' => asset('uploads/' . $image->image_path),
                'alt' => $image->title
            ]
        ]);
    }
}