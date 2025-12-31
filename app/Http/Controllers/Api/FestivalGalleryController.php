<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FestivalCategory;
use App\Models\FestivalGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FestivalGalleryController extends Controller
{
    /**
     * Get all festival categories with their galleries
     */
    public function index()
    {
        try {
            $categories = FestivalCategory::active()
                ->ordered()
                ->with(['galleries' => function($query) {
                    $query->active()->ordered();
                }])
                ->get();

            $formattedData = $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'galleries' => $category->galleries->map(function ($gallery) {
                        return [
                            'id' => $gallery->id,
                            'images' => $this->formatGalleryImages($gallery->images),
                        ];
                    }),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Festival gallery data retrieved successfully',
                'data' => $formattedData
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve festival gallery data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get galleries by category ID
     */
    public function byCategory($categoryId)
    {
        try {
            $category = FestivalCategory::active()
                ->where('id', $categoryId)
                ->with(['galleries' => function($query) {
                    $query->active()->ordered();
                }])
                ->first();

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found',
                    'data' => null
                ], 404);
            }

            $formattedData = [
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                ],
                'galleries' => $category->galleries->map(function ($gallery) {
                    return [
                        'id' => $gallery->id,
                        'images' => $this->formatGalleryImages($gallery->images),
                    ];
                }),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Category gallery data retrieved successfully',
                'data' => $formattedData
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve category gallery data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format gallery images with full URLs
     */
    private function formatGalleryImages($images)
    {
        if (empty($images)) {
            return [];
        }

        return array_map(function ($image) {
            return $this->getFileUrl($image);
        }, $images);
    }

    /**
 * Generate full URL for stored files
 */
private function getFileUrl($filePath)
{
    if (!$filePath) {
        return null;
    }

    // Check if it's already a full URL
    if (filter_var($filePath, FILTER_VALIDATE_URL)) {
        return $filePath;
    }

    // Build full URL with uploads path
    $uploadsPath = 'uploads/' . ltrim($filePath, '/');

    // Check if file exists in public/uploads
    if (Storage::disk('public')->exists($uploadsPath)) {
        return Storage::url($uploadsPath);
    }

    // Return default /uploads path even if not found in storage
    return asset('uploads/' . ltrim($filePath, '/'));
}

}