<?php

namespace App\Http\Controllers\Api;

use App\Models\PhotoImage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoImageController extends Controller
{
    public function index()
    {
        $images = PhotoImage::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(function ($image) {
                return [
                    'id' => $image->id,
                    'src' => $image->image_url,
                    'caption' => $image->caption,
                ];
            });

        return response()->json($images);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'caption' => 'required|string|max:255',
        ]);

        // Store image in public/uploads directory
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->move(public_path('uploads/photo-images'), $filename);
            $relativePath = 'photo-images/' . $filename;
        }

        $image = PhotoImage::create([
            'image_path' => $relativePath,
            'caption' => $request->caption,
            'order' => PhotoImage::max('order') + 1,
        ]);

        return response()->json([
            'message' => 'Image uploaded successfully',
            'image' => [
                'id' => $image->id,
                'src' => $image->image_url,
                'caption' => $image->caption,
            ]
        ], 201);
    }

    public function show(PhotoImage $photoImage)
    {
        return response()->json([
            'id' => $photoImage->id,
            'src' => $photoImage->image_url,
            'caption' => $photoImage->caption,
        ]);
    }

    public function update(Request $request, PhotoImage $photoImage)
    {
        $request->validate([
            'caption' => 'sometimes|string|max:255',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'order' => 'sometimes|integer',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($photoImage->image_path && file_exists(public_path('uploads/' . $photoImage->image_path))) {
                unlink(public_path('uploads/' . $photoImage->image_path));
            }
            
            // Store new image
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->move(public_path('uploads/photo-images'), $filename);
            $photoImage->image_path = 'photo-images/' . $filename;
        }

        $photoImage->update($request->only(['caption', 'order', 'is_active']));

        return response()->json([
            'message' => 'Image updated successfully',
            'image' => [
                'id' => $photoImage->id,
                'src' => $photoImage->image_url,
                'caption' => $photoImage->caption,
            ]
        ]);
    }

    public function destroy(PhotoImage $photoImage)
    {
        // Delete image file
        if ($photoImage->image_path && file_exists(public_path('uploads/' . $photoImage->image_path))) {
            unlink(public_path('uploads/' . $photoImage->image_path));
        }
        
        $photoImage->delete();

        return response()->json(['message' => 'Image deleted successfully']);
    }
}