<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerFeedbackController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'feedback' => 'required|string',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $feedback = CustomerFeedback::create($validator->validated());

        return response()->json([
            'message' => 'Feedback submitted successfully',
            'data' => $feedback
        ], 201);
    }
    
    public function index()
    {
        return response()->json(CustomerFeedback::orderBy('created_at', 'desc')->get());
    }
}