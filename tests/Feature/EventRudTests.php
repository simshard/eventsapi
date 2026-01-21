<?php

use App\Models\User;
use App\Models\Event;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can update their own event', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $event = Event::factory()->create([
        'user_id' => $user->id,
        'title' => 'Original Title',
    ]);

    $response = $this
        ->withHeader('Authorization', "Bearer $token")
        ->putJson("/api/events/{$event->id}", ['title' => 'Updated Title']);

    $response->assertStatus(200);
    $response->assertJson(['data' => ['title' => 'Updated Title']]);
});

test('user cannot update another user\'s event', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $token = $user2->createToken('test')->plainTextToken;
    $event = Event::factory()->create(['user_id' => $user1->id]);

    $response = $this
        ->withHeader('Authorization', "Bearer $token")
        ->putJson("/api/events/{$event->id}", ['title' => 'Updated Title']);

    $response->assertStatus(403);
});

test('event update fails with invalid dates', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $event = Event::factory()->create(['user_id' => $user->id]);

    $response = $this
        ->withHeader('Authorization', "Bearer $token")
        ->putJson("/api/events/{$event->id}", [
            'start_time' => now()->addDays(10)->format('Y-m-d H:i:s'),
            'end_time' => now()->addDays(5)->format('Y-m-d H:i:s'),
        ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('end_time');
});

test('unauthenticated user cannot update event', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create(['user_id' => $user->id]);

    $response = $this->putJson("/api/events/{$event->id}", ['title' => 'Updated Title']);

    $response->assertStatus(401);
});

test('authenticated user can delete their own event', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $event = Event::factory()->create(['user_id' => $user->id]);

    $response = $this
        ->withHeader('Authorization', "Bearer $token")
        ->deleteJson("/api/events/{$event->id}");

    $response->assertStatus(204);
    expect(Event::find($event->id))->toBeNull();
});

test('user cannot delete another user\'s event', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $token = $user2->createToken('test')->plainTextToken;
    $event = Event::factory()->create(['user_id' => $user1->id]);

    $response = $this
        ->withHeader('Authorization', "Bearer $token")
        ->deleteJson("/api/events/{$event->id}");

    $response->assertStatus(403);
});

test('cannot delete event with existing bookings', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $event = Event::factory()->create(['user_id' => $user->id]);
    Booking::factory()->create(['event_id' => $event->id]);

    $response = $this
        ->withHeader('Authorization', "Bearer $token")
        ->deleteJson("/api/events/{$event->id}");

    $response->assertStatus(422);
});

test('unauthenticated user cannot delete event', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create(['user_id' => $user->id]);

    $response = $this->deleteJson("/api/events/{$event->id}");

    $response->assertStatus(401);
});

test('can get all events', function () {
    Event::factory(3)->create();
    $response = $this->getJson('/api/events');
    $response->assertStatus(200);
    expect($response->json('data'))->toHaveCount(3);
});

test('can get user\'s own events', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    Event::factory(3)->create(['user_id' => $user->id]);
    Event::factory(2)->create();

    $response = $this
        ->withHeader('Authorization', "Bearer $token")
        ->getJson('/api/events?filter=my-events');

    $response->assertStatus(200);
    expect($response->json('data'))->toHaveCount(3);
});

test('can search events by title', function () {
    Event::factory()->create(['title' => 'PHP Workshop']);
    Event::factory()->create(['title' => 'Laravel Meetup']);

    $response = $this->getJson('/api/events?search=Laravel');
    $response->assertStatus(200);
    expect($response->json('data'))->toHaveCount(1);
});

test('can get single event', function () {
    $event = Event::factory()->create();
    $response = $this->getJson("/api/events/{$event->id}");
    $response->assertStatus(200);
    $response->assertJson(['data' => ['id' => $event->id]]);
});

test('getting non-existent event returns 404', function () {
    $response = $this->getJson('/api/events/999');
    $response->assertStatus(404);
});

test('events list is paginated', function () {
    Event::factory(20)->create();
    $response = $this->getJson('/api/events?per_page=10');
    $response->assertStatus(200);
    expect($response->json('data'))->toHaveCount(10);
    expect($response->json('meta.total'))->toBe(20);
});
