<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AttendeeController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public routes
Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{event}', [EventController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/events', [EventController::class, 'store']);
    Route::put('/events/{event}', [EventController::class, 'update']);
    Route::delete('/events/{event}', [EventController::class, 'destroy']);

    // Booking routes
    Route::apiResource('bookings', BookingController::class)->only(['index', 'store', 'destroy']);

    // Attendee routes
    Route::post('/attendees', [AttendeeController::class, 'store']);
    Route::get('/events/{eventId}/attendees', [AttendeeController::class, 'index']);
    Route::patch('/attendees/{attendee}', [AttendeeController::class, 'update']);
    Route::delete('/attendees/{attendee}', [AttendeeController::class, 'destroy']);
});
