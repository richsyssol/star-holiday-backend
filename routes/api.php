<?php

use App\Http\Controllers\Api\BookingSubmissionController;
use Illuminate\Support\Facades\Route;

// Booking submission API route
Route::post('/booking-submission', [BookingSubmissionController::class, 'store']);