<?php

namespace App\Http\Controllers\Api;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestimonialController extends Controller
{
    /**
     * Display a listing of active testimonials.
     */
    public function index()
    {
        $testimonials = Testimonial::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($testimonials);
    }

    /**
     * Display the specified testimonial.
     */
    public function show($id)
    {
        $testimonial = Testimonial::where('is_active', true)
            ->findOrFail($id);

        return response()->json($testimonial);
    }
}