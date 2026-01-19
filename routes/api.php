<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//  Route::get('/demo', function () {
//     return response()->json(['API Demonstration Success'], 200);
// });


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('events', EventController::class);
});

// Route::get('/events', [EventController::class, 'index']);
// Route::get('/events/{id}', [EventController::class, 'show']);
// Route::post('/events', [EventController::class, 'store']);
// Route::put('/events/{id}', [EventController::class, 'update']);
// Route::delete('/events/{id}', [EventController::class, 'destroy']);

/*
GET    /api/events           - List all events + user's events
POST   /api/events           - Create event
GET    /api/events/{id}      - Show event
PUT    /api/events/{id}      - Update event
DELETE /api/events/{id}      - Delete event
*/


// Public routes (no auth required)
Route::post('/events/{eventId}/attendees', [AttendeeController::class, 'store']);
Route::get('/events/{eventId}/attendees', [AttendeeController::class, 'index']);

// Protected routes (auth required)
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/attendees/{attendee}', [AttendeeController::class, 'update']);
    Route::delete('/attendees/{attendee}', [AttendeeController::class, 'destroy']);
});
