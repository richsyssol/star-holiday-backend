<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Display the gallery data including all sections
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $gallery = Gallery::latest()->first();

        if (!$gallery) {
            return response()->json([
                'data' => null,
                'message' => 'No gallery content found'
            ], 404);
        }

        return response()->json([
            'data' => [
                'gallery_images' => $this->formatGalleryImages($gallery->gallery_images),
                'amenities_highlights' => $this->formatAmenities($gallery->amenities_highlights),
            ],
            'message' => 'Gallery content retrieved successfully'
        ]);
    }

    /**
     * Format gallery images data
     *
     * @param  array|null  $images
     * @return array
     */
    protected function formatGalleryImages($images)
    {
        if (empty($images)) {
            return [];
        }

        return array_map(function ($image) {
            return [
                'url' => asset('uploads/' . $image['url']),
                'category' => $image['category'] ?? 'all',
            ];
        }, $images);
    }

    /**
     * Format amenities highlights data
     *
     * @param  array|null  $amenities
     * @return array
     */
    protected function formatAmenities($amenities)
    {
        if (empty($amenities)) {
            return [];
        }

        return array_map(function ($amenity) {
            return [
                'title' => $amenity['title'] ?? '',
                'icon' => $amenity['icon'] ?? 'tree',
                'description' => $amenity['description'] ?? '',
                'images' => array_map(function ($image) {
                    return asset('uploads/' . $image);
                }, $amenity['images'] ?? []),
            ];
        }, $amenities);
    }
}