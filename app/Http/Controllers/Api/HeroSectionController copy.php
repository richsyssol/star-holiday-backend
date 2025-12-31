<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\HeroSection;
use Illuminate\Http\Request;

class HeroSectionController extends Controller
{
    public function index()
    {
        $heroSections = HeroSection::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get()
            ->map(function ($item) {
                // Ensure proper URL format for the image
                $item->image_url = $item->image_url ? asset('storage/' . $item->image_url) : null;
                return $item;
            });
            
        return response()->json($heroSections);
    }
}