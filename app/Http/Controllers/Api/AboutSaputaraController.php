<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AboutSaputara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutSaputaraController extends Controller
{
    /**
     * Get all About Saputara data
     */
    public function index()
    {
        try {
            $aboutSaputara = AboutSaputara::first();
            
            if (!$aboutSaputara) {
                return response()->json([
                    'success' => false,
                    'message' => 'About Saputara data not found',
                    'data' => null
                ], 404);
            }
            
            // Format the response with full URLs for images and videos
            $formattedData = [
                'about_saputara' => [
                    'content' => $aboutSaputara->about_content,
                    'image' => $this->getFileUrl($aboutSaputara->about_image)
                ],
                'sightseeing' => [
                    'content' => $aboutSaputara->sightseeing_content,
                    'image' => $this->getFileUrl($aboutSaputara->sightseeing_image)
                ],
                'video_testimonials' => $this->formatVideoTestimonials($aboutSaputara->video_testimonials),
                'gallery' => $this->formatGallery($aboutSaputara->gallery)
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'About Saputara data retrieved successfully',
                'data' => $formattedData
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve About Saputara data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get specific section data
     */
    public function show($section)
    {
        try {
            $aboutSaputara = AboutSaputara::first();
            
            if (!$aboutSaputara) {
                return response()->json([
                    'success' => false,
                    'message' => 'About Saputara data not found',
                    'data' => null
                ], 404);
            }
            
            $allowedSections = ['about', 'sightseeing', 'testimonials', 'gallery'];
            
            if (!in_array($section, $allowedSections)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid section requested',
                    'data' => null
                ], 400);
            }
            
            $responseData = [];
            
            switch ($section) {
                case 'about':
                    $responseData = [
                        'content' => $aboutSaputara->about_content,
                        'image' => $this->getFileUrl($aboutSaputara->about_image)
                    ];
                    break;
                    
                case 'sightseeing':
                    $responseData = [
                        'content' => $aboutSaputara->sightseeing_content,
                        'image' => $this->getFileUrl($aboutSaputara->sightseeing_image)
                    ];
                    break;
                    
                case 'testimonials':
                    $responseData = $this->formatVideoTestimonials($aboutSaputara->video_testimonials);
                    break;
                    
                case 'gallery':
                    $responseData = $this->formatGallery($aboutSaputara->gallery);
                    break;
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Section data retrieved successfully',
                'data' => $responseData
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve section data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    // Add this method to your AboutSaputaraController
private function normalizeYouTubeUrl($url)
{
    if (empty($url)) {
        return $url;
    }
    
    // Check if it's a YouTube URL
    if (preg_match('/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$/', $url)) {
        // Extract video ID
        $videoId = '';
        
        // Standard watch URL
        if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $url, $matches)) {
            $videoId = $matches[1];
        } 
        // Short URL
        else if (preg_match('/youtu\.be\/([^?]+)/', $url, $matches)) {
            $videoId = $matches[1];
        }
        // Embedded URL
        else if (preg_match('/youtube\.com\/embed\/([^?]+)/', $url, $matches)) {
            $videoId = $matches[1];
        }
        // Live stream URL
        else if (preg_match('/youtube\.com\/live\/([^?]+)/', $url, $matches)) {
            $videoId = $matches[1];
        }
        
        if ($videoId) {
            // Return the standardized embed URL
            return "https://www.youtube.com/embed/{$videoId}";
        }
    }
    
    // Return the original URL if not YouTube or if we couldn't extract the ID
    return $url;
}

// Then update the formatVideoTestimonials method:
private function formatVideoTestimonials($testimonials)
{
    if (empty($testimonials)) {
        return [];
    }
    
    return array_map(function ($testimonial) {
        $videoUrl = $testimonial['video_url'] ?? null;
        
        // Normalize YouTube URLs to embed format
        if ($videoUrl) {
            $videoUrl = $this->normalizeYouTubeUrl($videoUrl);
        }
        
        return [
            'name' => $testimonial['name'] ?? '',
            'review' => $testimonial['review'] ?? '',
            'video_url' => $videoUrl,
            'video_file' => isset($testimonial['video_file']) ? 
                $this->getFileUrl($testimonial['video_file']) : null
        ];
    }, $testimonials);
}
    
    /**
     * Format gallery with proper URLs
     */
    private function formatGallery($gallery)
    {
        if (empty($gallery)) {
            return [];
        }
        
        return array_map(function ($image) {
            return $this->getFileUrl($image);
        }, $gallery);
    }
    
    /**
     * Generate full URL for stored files
     */
    private function getFileUrl($filePath)
    {
        if (!$filePath) {
            return null;
        }
        
        // Check if it's already a URL
        if (filter_var($filePath, FILTER_VALIDATE_URL)) {
            return $filePath;
        }
        
        // Check if file exists in storage
        if (Storage::disk('public')->exists($filePath)) {
            return asset('uploads/' . $filePath);
        }
        
        // Return the path as is if file doesn't exist in storage
        return $filePath;
    }
}