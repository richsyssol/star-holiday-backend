<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SixBedroomVideo;
use Illuminate\Http\Request;

class SixBedroomVideoController extends Controller
{
    public function index()
    {
        $videos = SixBedroomVideo::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return response()->json($videos);
    }
}