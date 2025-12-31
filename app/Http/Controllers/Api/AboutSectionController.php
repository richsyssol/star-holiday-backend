<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AboutSection;
use Illuminate\Http\Request;

class AboutSectionController extends Controller
{
    public function index()
    {
        try {
            $sections = AboutSection::where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->get()
                ->map(function ($section) {
                    // Update to use uploads directory instead of storage
                    $section->image_url = $section->image_path 
                        ? asset('uploads/' . $section->image_path) 
                        : null;
                    return $section;
                });
            
            return response()->json([
                'success' => true,
                'data' => $sections
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch about sections',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Add other resource methods for completeness
    public function show($id)
    {
        // Implementation for showing a single section
    }

    public function store(Request $request)
    {
        // Implementation for storing a new section
    }

    public function update(Request $request, $id)
    {
        // Implementation for updating a section
    }

    public function destroy($id)
    {
        // Implementation for deleting a section
    }
}