<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/facilities",
     *     summary="Get all active facilities",
     *     tags={"Facilities"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Facility")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $facilities = Facility::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get()
            ->map(function ($facility) {
                return $this->formatFacilityData($facility);
            });

        return response()->json([
            'success' => true,
            'data' => $facilities,
            'message' => 'Facilities retrieved successfully'
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/facilities/{id}",
     *     summary="Get specific facility",
     *     tags={"Facilities"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Facility")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Facility not found"
     *     )
     * )
     */
    public function show($id)
    {
        $facility = Facility::where('is_active', true)->find($id);

        if (!$facility) {
            return response()->json([
                'success' => false,
                'message' => 'Facility not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatFacilityData($facility),
            'message' => 'Facility retrieved successfully'
        ]);
    }

    /**
     * Format facility data with proper image URLs
     */
    private function formatFacilityData($facility)
    {
        $imageUrl = null;
        
        if ($facility->image) {
            // Check if the image is a URL (from external source) or a local file
            if (filter_var($facility->image, FILTER_VALIDATE_URL)) {
                $imageUrl = $facility->image;
            } else {
                // Generate full URL for local storage files
                $imageUrl = asset('uploads/' . $facility->image);
                
                // Alternatively, if you want to use the Storage facade:
                // $imageUrl = Storage::disk('public')->exists('uploads/facilities/' . $facility->image) 
                //     ? Storage::disk('public')->url('uploads/facilities/' . $facility->image)
                //     : null;
            }
        }

        return [
            'id' => $facility->id,
            'name' => $facility->name,
            'subtitle' => $facility->subtitle,
            'description' => $facility->description,
            'image' => $imageUrl,
            'image_filename' => $facility->image, // Keep original filename if needed
            'order' => $facility->order,
            'is_active' => $facility->is_active,
            'created_at' => $facility->created_at,
            'updated_at' => $facility->updated_at
        ];
    }
}