<?php

use App\Models\User;
use App\Models\Event;
use App\Models\Attendee;
use App\Models\Booking;

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->event = Event::factory()->create(['user_id' => $this->user->id]);
});

test('attendee name is required', function () {
    $response = $this->actingAs($this->user)->postJson('/api/attendees', [
        'event_id' => $this->event->id,
        'email' => 'test@example.com',
        'phone' => '555-0100',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('name');
});

test('user can view all attendees for their event', function () {
    Attendee::factory()->count(3)->create(['event_id' => $this->event->id]);

    $response = $this->actingAs($this->user)->getJson("/api/events/{$this->event->id}/attendees");

    $response->assertStatus(200);
    $response->assertJsonCount(3, 'attendees');
});

test('event organizer can see booking details who booked when', function () {
    $attendee = Attendee::factory()->create(['event_id' => $this->event->id]);
    $booking = Booking::factory()->create([
        'event_id' => $this->event->id,
        'attendee_id' => $attendee->id,
        'status' => 'confirmed',
    ]);

    $response = $this->actingAs($this->user)->getJson("/api/events/{$this->event->id}/attendees");

    $response->assertStatus(200);
    $response->assertJsonFragment([
        'id' => $attendee->id,
        'name' => $attendee->name,
        'booking_status' => 'confirmed',
        'booked_at' => $booking->created_at->toIso8601String(),
    ]);
});

test('attendee list shows booking status confirmed or cancelled', function () {
    $confirmedAttendee = Attendee::factory()->create(['event_id' => $this->event->id]);
    $cancelledAttendee = Attendee::factory()->create(['event_id' => $this->event->id]);

    Booking::factory()->create([
        'event_id' => $this->event->id,
        'attendee_id' => $confirmedAttendee->id,
        'status' => 'confirmed',
    ]);

    Booking::factory()->create([
        'event_id' => $this->event->id,
        'attendee_id' => $cancelledAttendee->id,
        'status' => 'cancelled',
    ]);

    $response = $this->actingAs($this->user)->getJson("/api/events/{$this->event->id}/attendees");

    $response->assertStatus(200);
    $attendees = $response->json('attendees');

    $confirmed = collect($attendees)->firstWhere('id', $confirmedAttendee->id);
    $cancelled = collect($attendees)->firstWhere('id', $cancelledAttendee->id);

    expect($confirmed['booking_status'])->toBe('confirmed');
    expect($cancelled['booking_status'])->toBe('cancelled');
});

test('attendee can be marked as cancelled', function () {
    $attendee = Attendee::factory()->create(['event_id' => $this->event->id]);
    $booking = Booking::factory()->create([
        'event_id' => $this->event->id,
        'attendee_id' => $attendee->id,
        'status' => 'confirmed',
    ]);

    $response = $this->actingAs($this->user)->patchJson("/api/attendees/{$attendee->id}", [
        'status' => 'cancelled',
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'status' => 'cancelled',
    ]);
});

test('event with capacity of 5 accepts only 5 bookings and rejects when capacity reached', function () {
    $this->event->update(['venue_capacity' => 5]);

    for ($i = 0; $i < 5; $i++) {
        $attendee = Attendee::factory()->create(['event_id' => $this->event->id]);
        Booking::factory()->create([
            'event_id' => $this->event->id,
            'attendee_id' => $attendee->id,
            'status' => 'confirmed',
        ]);
    }

    $response = $this->actingAs($this->user)->postJson('/api/attendees', [
        'event_id' => $this->event->id,
        'name' => 'Sixth Attendee',
        'email' => 'sixth@example.com',
        'phone' => '555-0106',
    ]);

    $response->assertStatus(422);
    $response->assertJsonFragment(['error' => 'Event is at full capacity']);
});

test('cancelling a booking reduces attendee count', function () {
    $attendee = Attendee::factory()->create(['event_id' => $this->event->id]);
    $booking = Booking::factory()->create([
        'event_id' => $this->event->id,
        'attendee_id' => $attendee->id,
        'status' => 'confirmed',
    ]);

    $countBefore = Booking::where('event_id', $this->event->id)
        ->where('status', 'confirmed')
        ->count();

    // Cancel the booking
    $this->actingAs($this->user)->patchJson("/api/attendees/{$attendee->id}", [
        'status' => 'cancelled',
    ]);

    $countAfter = Booking::where('event_id', $this->event->id)
        ->where('status', 'confirmed')
        ->count();

    expect($countBefore)->toBe(1);
    expect($countAfter)->toBe(0);
});
