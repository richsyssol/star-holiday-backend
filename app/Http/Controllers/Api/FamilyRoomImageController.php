<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FamilyRoomImage;
use Illuminate\Http\Request;

class FamilyRoomImageController extends Controller
{
    public function index()
    {
        $images = FamilyRoomImage::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $images
        ]);
    }
}