<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AboutResort;
use Illuminate\Http\Request;

class AboutResortController extends Controller
{
    public function index()
    {
        $aboutResort = AboutResort::where('is_active', true)->first();
        
        if (!$aboutResort) {
            return response()->json(['message' => 'No active about section found'], 404);
        }
        
        // Update the image path to use the correct URL
        if ($aboutResort->image_path && !str_starts_with($aboutResort->image_path, 'http')) {
            $aboutResort->image_path = url('uploads/' . $aboutResort->image_path);
        }
        
        return response()->json($aboutResort);
    }
}