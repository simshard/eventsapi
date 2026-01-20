<?php

use App\Models\User;
use App\Models\Event;
use App\Services\BookingService;
use App\Repositories\BookingRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
 

uses(RefreshDatabase::class);

test('user cannot book event twice', function () {
    // Create test data
    $user = User::factory()->create();
    $event = Event::factory()->create([
        'user_id' => $user->id,
        'venue_capacity' => 10,
    ]);

    // Create first booking
    $repository = new BookingRepository();
    $service = new BookingService($repository);
    $service->bookEvent($user->id, $event->id);

    // Try to book again - should throw exception
    $service->bookEvent($user->id, $event->id);
})->throws(Exception::class, 'User already booked this event');
