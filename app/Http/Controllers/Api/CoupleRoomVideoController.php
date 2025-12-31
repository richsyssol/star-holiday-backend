<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CoupleRoomVideo;
use Illuminate\Http\Request;

class CoupleRoomVideoController extends Controller
{
    public function index()
    {
        $videos = CoupleRoomVideo::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return response()->json($videos);
    }
}