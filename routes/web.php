<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

require __DIR__.'/settings.php';

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('auth')->get('/dashboard', [EventController::class, 'index'])->name('dashboard');

// Livewire CRUD routes
Route::middleware('auth')->group(function () {
    Route::livewire('/events', \App\Livewire\Events\EventsList::class)->name('events.index');
    Route::livewire('/bookings', \App\Livewire\Bookings\MyBookings::class)->name('bookings.index');
    Route::livewire('/events/{eventId}/attendees', \App\Livewire\Events\EventAttendees::class)->name('events.attendees');
});
