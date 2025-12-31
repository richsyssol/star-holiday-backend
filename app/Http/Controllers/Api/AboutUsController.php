<?php
// app/Http/Controllers/Api/AboutUsController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    /**
     * Display the about us data.
     */
    public function index()
    {
        $about = AboutUs::first();
        
        if (!$about) {
            // Return default data if no record exists
            return response()->json([
                'welcome_title' => 'Welcome to',
                'main_title' => 'Star Holiday Home Hill Resort',
                'description_1' => 'At Star Holiday Home Resort, we believe in creating unforgettable memories through luxury, comfort, and exceptional service. Located in the heart of Saputara, our resort offers the perfect getaway with a blend of traditional hospitality and modern amenities.',
                'description_2' => 'With years of experience in hosting families, solo travelers, and corporate guests, we take pride in our secure and peaceful environment. Our team is committed to ensuring your stay is not only comfortable but truly memorable.',
                'button_text' => 'LEARN MORE',
                'image_1' => null,
                'image_2' => null,
                'image_3' => null,
            ]);
        }
        
        return response()->json($about);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // This method can be used if you want to allow API creation
        // of about us content, though typically this would be done
        // through the Filament admin panel
    }

    /**
     * Display the specified resource.
     */
    public function show(AboutUs $aboutUs)
    {
        return response()->json($aboutUs);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AboutUs $aboutUs)
    {
        // API update endpoint if needed
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AboutUs $aboutUs)
    {
        // API delete endpoint if needed
    }
}