<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StayReason;
use Illuminate\Http\Request;

class StayReasonController extends Controller
{
    public function index()
    {
        $reasons = StayReason::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get()
            ->map(function ($reason) {
                // Add full URL to image - using uploads directory instead of storage
                $reason->image_url = asset('uploads/' . $reason->image);
                return $reason;
            });
            
        return response()->json($reasons);
    }
}