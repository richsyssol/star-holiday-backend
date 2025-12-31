<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RestaurantSection;
use Illuminate\Http\Request;

class RestaurantSectionController extends Controller
{
    public function index()
    {
        $section = RestaurantSection::with(['images' => function($query) {
            $query->orderBy('order', 'asc');
        }])->first();
        
        if (!$section) {
            return response()->json([
                'message' => 'No restaurant section found',
                'data' => null
            ], 404);
        }
        
        // Transform the data to include full image URLs - changed from storage to uploads
        $transformedImages = $section->images->map(function($image) {
            return [
                'id' => $image->id,
                'image_path' => asset('uploads/' . $image->image_path),
                'order' => $image->order,
                'created_at' => $image->created_at,
                'updated_at' => $image->updated_at,
            ];
        });
        
        return response()->json([
            'id' => $section->id,
            'heading' => $section->heading,
            'description' => $section->description,
            'images' => $transformedImages,
            'created_at' => $section->created_at,
            'updated_at' => $section->updated_at,
        ]);
    }
}