<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CtaSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CtaSectionController extends Controller
{
    /**
     * Display the active CTA section.
     */
    public function showActive()
    {
        $ctaSection = CtaSection::where('is_active', true)->first();
        
        if (!$ctaSection) {
            return response()->json([
                'message' => 'No active CTA section found'
            ], 404);
        }
        
        // Add full URL for image
        $ctaSection->image_url = $ctaSection->image_url 
            ? Storage::url($ctaSection->image_url)
            : null;
        
        return response()->json($ctaSection);
    }

    /**
     * Display a listing of the resource (optional, for admin purposes).
     */
    public function index()
    {
        $ctaSections = CtaSection::all();
        
        // Add full URLs for images
        $ctaSections->transform(function ($item) {
            $item->image_url = $item->image_url 
                ? Storage::url($item->image_url)
                : null;
            return $item;
        });
        
        return response()->json($ctaSections);
    }

    /**
     * Store a newly created resource in storage (optional).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone_number' => 'required|string|max:20',
            'is_active' => 'boolean'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/cta', 'public');
            $validated['image_url'] = $imagePath;
        }

        $ctaSection = CtaSection::create($validated);

        // Add full URL for response
        $ctaSection->image_url = Storage::url($ctaSection->image_url);

        return response()->json($ctaSection, 201);
    }

    /**
     * Display the specified resource (optional).
     */
    public function show($id)
    {
        $ctaSection = CtaSection::find($id);
        
        if (!$ctaSection) {
            return response()->json([
                'message' => 'CTA section not found'
            ], 404);
        }
        
        // Add full URL for image
        $ctaSection->image_url = $ctaSection->image_url 
            ? Storage::url($ctaSection->image_url)
            : null;
        
        return response()->json($ctaSection);
    }

    /**
     * Update the specified resource in storage (optional).
     */
    public function update(Request $request, $id)
    {
        $ctaSection = CtaSection::find($id);
        
        if (!$ctaSection) {
            return response()->json([
                'message' => 'CTA section not found'
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone_number' => 'sometimes|required|string|max:20',
            'is_active' => 'sometimes|boolean'
        ]);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($ctaSection->image_url) {
                Storage::disk('public')->delete($ctaSection->image_url);
            }
            
            $imagePath = $request->file('image')->store('uploads/cta', 'public');
            $validated['image_url'] = $imagePath;
        }

        $ctaSection->update($validated);

        // Add full URL for response
        $ctaSection->image_url = Storage::url($ctaSection->image_url);

        return response()->json($ctaSection);
    }

    /**
     * Remove the specified resource from storage (optional).
     */
    public function destroy($id)
    {
        $ctaSection = CtaSection::find($id);
        
        if (!$ctaSection) {
            return response()->json([
                'message' => 'CTA section not found'
            ], 404);
        }

        // Delete associated image
        if ($ctaSection->image_url) {
            Storage::disk('public')->delete($ctaSection->image_url);
        }

        $ctaSection->delete();

        return response()->json([
            'message' => 'CTA section deleted successfully'
        ]);
    }
}