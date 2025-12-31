<?php

namespace App\Http\Controllers\Api;

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
            ->map(function ($heroSection) {
                // Convert storage path to URL
                if ($heroSection->image_url) {
                    $heroSection->image_url = asset('uploads/' . $heroSection->image_url);
                }
                return $heroSection;
            });
            
        return response()->json($heroSections);
    }
}