<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HotelBookingSection;
use Illuminate\Http\Request;

class HotelBookingSectionController extends Controller
{
    public function index()
    {
        $section = HotelBookingSection::where('is_active', true)->first();
        
        // Return an empty object instead of null if no record found
        return response()->json($section ?? (object)[]);
    }
}