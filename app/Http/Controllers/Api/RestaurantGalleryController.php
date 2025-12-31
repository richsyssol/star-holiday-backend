<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RestaurantGallery;
use Illuminate\Http\Request;

class RestaurantGalleryController extends Controller
{
    public function index()
    {
        $gallery = RestaurantGallery::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get(['id', 'description', 'image', 'sort_order']);
            
        return response()->json($gallery);
    }
}