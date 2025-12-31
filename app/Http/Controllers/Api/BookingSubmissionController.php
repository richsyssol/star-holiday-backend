<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingSubmissionController extends Controller
{
    /**
     * Handle booking form submission
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'booking_date' => 'required|date',
            'phone_number' => 'required|string|max:20',
            'room_type' => 'required|in:2_bedded_super_deluxe_ac_couple_rooms,4_bedded_super_deluxe_ac_family_rooms,6_bedded_super_deluxe_ac_family_suite',
            'day' => 'required|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'message' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create booking submission
            $booking = BookingSubmission::create($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Booking submission received successfully',
                'data' => [
                    'id' => $booking->id,
                    'full_name' => $booking->full_name,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}