<?php

use App\Models\Event;
use App\Models\User;
use App\Models\Booking;
use App\Models\Attendee;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class)->in('Unit');

test('event belongs to user', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create(['user_id' => $user->id]);

    expect($event->user)->toBeInstanceOf(User::class);
    expect($event->user->id)->toBe($user->id);
});

test('event has many bookings', function () {
    $event = Event::factory()->create();
    $bookings = Booking::factory()->count(3)->create(['event_id' => $event->id]);

    expect($event->bookings)->toHaveCount(3);
    expect($event->bookings->contains($bookings->first()))->toBeTrue();
});

test('event has many attendees', function () {
    $event = Event::factory()->create();
    $attendees = User::factory()->count(3)->create();
    $event->attendees()->attach($attendees);

    expect($event->attendees)->toHaveCount(3);
    expect($event->attendees->contains($attendees->first()))->toBeTrue();
});

test('event title is required', function () {
    Event::create([
        'start_time' => now()->addDay(),
        'end_time' => now()->addDay(2),
        'venue_capacity' => 100,
        'user_id' => User::factory()->create()->id,
    ]);
})->throws(\Illuminate\Database\QueryException::class);

test('event start_time is required', function () {
    Event::create([
        'title' => 'Test Event',
        'end_time' => now()->addDay(2),
        'venue_capacity' => 100,
        'user_id' => User::factory()->create()->id,
    ]);
})->throws(\Illuminate\Database\QueryException::class);

test('event end_time is required', function () {
    Event::create([
        'title' => 'Test Event',
        'start_time' => now()->addDay(),
        'venue_capacity' => 100,
        'user_id' => User::factory()->create()->id,
    ]);
})->throws(\Illuminate\Database\QueryException::class);

test('event venue_capacity is required', function () {
    Event::create([
        'title' => 'Test Event',
        'start_time' => now()->addDay(),
        'end_time' => now()->addDay(2),
        'user_id' => User::factory()->create()->id,
    ]);
})->throws(\Illuminate\Database\QueryException::class);

test('event venue_capacity must be positive', function () {
    $event = Event::factory()->make(['venue_capacity' => -5]);

    expect($event->venue_capacity)->toBeLessThan(0);
});

test('event start_time must be before end_time', function () {
    $startTime = now()->addDay(2);
    $endTime = now()->addDay(1);

    $event = Event::factory()->make([
        'start_time' => $startTime,
        'end_time' => $endTime,
    ]);

    expect($event->start_time)->toBeGreaterThan($event->end_time);
});

test('event can calculate available capacity', function () {
    $event = Event::factory()->create(['venue_capacity' => 100]);
    Booking::factory()->count(30)->create([
        'event_id' => $event->id,
        'status' => 'confirmed',
    ]);

    $availableCapacity = $event->available_capacity;

    expect($availableCapacity)->toBe(70);
});

test('event scope upcoming events', function () {
    Event::factory()->create(['start_time' => now()->subDay()]);
    Event::factory()->create(['start_time' => now()->addDay()]);
    Event::factory()->create(['start_time' => now()->addDays(7)]);

    $upcomingEvents = Event::upcoming()->get();

    expect($upcomingEvents)->toHaveCount(2);
    expect($upcomingEvents->every(fn($event) => $event->start_time > now()))->toBeTrue();
});

test('event scope past events', function () {
    Event::factory()->create(['start_time' => now()->subDay()]);
    Event::factory()->create(['start_time' => now()->subDays(7)]);
    Event::factory()->create(['start_time' => now()->addDay()]);

    $pastEvents = Event::past()->get();

    expect($pastEvents)->toHaveCount(2);
    expect($pastEvents->every(fn($event) => $event->start_time <= now()))->toBeTrue();
});

test('event scope by user', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    Event::factory()->count(3)->create(['user_id' => $user1->id]);
    Event::factory()->count(2)->create(['user_id' => $user2->id]);

    $user1Events = Event::byUser($user1->id)->get();

    expect($user1Events)->toHaveCount(3);
    expect($user1Events->every(fn($event) => $event->user_id === $user1->id))->toBeTrue();
});

test('event scope available events', function () {
    $fullEvent = Event::factory()->create(['venue_capacity' => 2]);
    Booking::factory()->count(2)->create([
        'event_id' => $fullEvent->id,
        'status' => 'confirmed',
    ]);

    $availableEvent = Event::factory()->create(['venue_capacity' => 100]);
    Booking::factory()->count(50)->create([
        'event_id' => $availableEvent->id,
        'status' => 'confirmed',
    ]);

    $availableEvents = Event::available()->get();

    expect($availableEvents)->toHaveCount(1);
    expect($availableEvents->first()->id)->toBe($availableEvent->id);
});

test('event is fully booked when confirmed bookings equal venue capacity', function () {
    $event = Event::factory()->create(['venue_capacity' => 5]);
    Booking::factory()->count(5)->create([
        'event_id' => $event->id,
        'status' => 'confirmed',
    ]);

    expect($event->is_fully_booked)->toBeTrue();
});
