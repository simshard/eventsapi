<?php

use App\Models\Event;
use App\Models\User;
use App\Models\Booking;
use App\Models\Attendee;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->eventOwner = User::factory()->create();
    $this->event = Event::factory()->create([
        'user_id' => $this->eventOwner->id,
        'venue_capacity' => 5,
    ]);
});

test('user can book an available event', function () {
    $response = $this->actingAs($this->user)->postJson('/api/bookings', [
        'event_id' => $this->event->id,
        'attendee' => [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '555-0100',
        ],
    ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('bookings', [
        'event_id' => $this->event->id,
        'user_id' => $this->user->id,
        'status' => 'confirmed',
    ]);
});

test('user can view their bookings on dashboard', function () {
    $booking = Booking::factory()->create([
        'user_id' => $this->user->id,
        'event_id' => $this->event->id,
    ]);

    $response = $this->actingAs($this->user)->getJson('/api/bookings');

    $response->assertStatus(200);
    $response->assertJsonCount(1, 'data');
});

test('user cannot book a fully booked event', function () {
    // Fill event to capacity
    for ($i = 0; $i < 5; $i++) {
        Booking::factory()->create([
            'event_id' => $this->event->id,
            'status' => 'confirmed',
        ]);
    }

    $response = $this->actingAs($this->user)->postJson('/api/bookings', [
        'event_id' => $this->event->id,
        'attendee' => [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '555-0101',
        ],
    ]);

    $response->assertStatus(422);
});

test('user cannot book the same event twice', function () {
    Booking::factory()->create([
        'user_id' => $this->user->id,
        'event_id' => $this->event->id,
    ]);

    $response = $this->actingAs($this->user)->postJson('/api/bookings', [
        'event_id' => $this->event->id,
        'attendee' => [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '555-0100',
        ],
    ]);

    $response->assertStatus(422);
});

test('booking is rejected if event capacity is reached', function () {
    $this->event->update(['venue_capacity' => 2]);

    Booking::factory()->count(2)->create([
        'event_id' => $this->event->id,
        'status' => 'confirmed',
    ]);

    $response = $this->actingAs($this->user)->postJson('/api/bookings', [
        'event_id' => $this->event->id,
        'attendee' => [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '555-0101',
        ],
    ]);

    $response->assertStatus(422);
});

test('user can cancel a booking', function () {
    $booking = Booking::factory()->create([
        'user_id' => $this->user->id,
        'event_id' => $this->event->id,
        'status' => 'confirmed',
    ]);

    $response = $this->actingAs($this->user)->deleteJson("/api/bookings/{$booking->id}");

    $response->assertStatus(204);
    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'status' => 'cancelled',
    ]);
});
