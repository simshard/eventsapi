<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

require __DIR__.'/settings.php';

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('auth')->get('/dashboard', [EventController::class, 'index'])->name('dashboard');
Route::get('/events/{event}', [EventController::class, 'show']);
Route::post('/events', [EventController::class, 'store']);
Route::put('/events/{event}', [EventController::class, 'update']);
Route::delete('/events/{event}', [EventController::class, 'destroy']);
