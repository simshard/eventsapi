<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

 Route::get('/demo', function () {
    return response()->json(['API Demonstration Success'], 200);
});





// Route::get('/events/{id}',function($id){
//     $event = \App\Models\Event::find($id);
//     if(!$event){
//         return response()->json(['message' => 'Event not found'], 404);
//     }
//     return response()->json($event);
//  });


Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{id}', [EventController::class, 'show']);
Route::post('/events', [EventController::class, 'store']);
Route::put('/events/{id}', [EventController::class, 'update']);
Route::delete('/events/{id}', [EventController::class, 'destroy']);



