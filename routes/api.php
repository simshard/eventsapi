<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
GET    /api/events           - List all events + user's events
POST   /api/events           - Create event
GET    /api/events/{id}      - Show event
PUT    /api/events/{id}      - Update event
DELETE /api/events/{id}      - Delete event
*/


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('events', EventController::class);
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/{id}', [EventController::class, 'show']);
    Route::post('/events', [EventController::class, 'store']);
    Route::put('/events/{id}', [EventController::class, 'update']);
    Route::delete('/events/{id}', [EventController::class, 'destroy']);
    });

// Additional routes for bookings and attendees
