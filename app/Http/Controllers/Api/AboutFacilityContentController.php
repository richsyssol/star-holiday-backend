<?php

namespace App\Http\Controllers\Api;

use App\Models\AboutFacilityContent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AboutFacilityContentController extends Controller
{
public function index()
    {
        try {
            // Assuming only one row stores about_facility_content
            $facilityContent = AboutFacilityContent::select('about_facility_content')->first();

            if (!$facilityContent) {
                return response()->json([
                    'success' => false,
                    'message' => 'No facility content found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $facilityContent
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching content',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

