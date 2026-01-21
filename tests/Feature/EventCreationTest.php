<?php

use App\Models\User;
use App\Models\Event;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can update their own event', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    $event = Event::factory()->create([
        'user_id' => $user->id,
        'title' => 'Original Title',
    ]);

 $response = $this
        ->actingAs($user)  
        ->putJson("/api/events/{$event->id}", ['title' => 'Updated Title']);

    $response->assertStatus(200);
    $response->assertJson(['data' => ['title' => 'Updated Title']]);
});


test('user cannot update another user\'s event', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $token = $user2->createToken('api-token')->plainTextToken;

    $event = Event::factory()->create(['user_id' => $user1->id]);

    $response = $this
        ->withHeader('Authorization', "Bearer $token")
        ->putJson("/api/events/{$event->id}", ['title' => 'Hacked']);

    $response->assertStatus(403);
});

test('unauthenticated user cannot create an event', function () {
    $eventData = [
        'title' => 'Test Event',
        'venue_capacity' => 100,
        'start_time' => now()->addDays(10)->format('Y-m-d H:i:s'),
    ];
$response = $this->postJson('/api/events', $eventData);

    $response->assertStatus(401);  // Unauthorized, not redirect
});







test('event creation fails when data is missing required fields', function () {
  $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    $response = $this
        ->withHeader('Authorization', "Bearer $token")
        ->postJson('/api/events', [
            'description' => 'Missing required fields',
        ]);

    $response->assertStatus(422);  // Unprocessable Entity
    $response->assertJsonValidationErrors(['title', 'venue_capacity', 'start_time']);
});

test('event update fails with invalid dates', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create(['user_id' => $user->id]);

    $response = $this
        ->actingAs($user)
        ->putJson("/api/events/{$event->id}", [
            'start_time' => now()->addDays(10)->format('Y-m-d H:i:s'),
            'end_time' => now()->addDays(5)->format('Y-m-d H:i:s'),
        ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('end_time');
});


test('event creation fails when start_time is after end_time', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    $eventData = [
        'title' => 'Invalid Event',
        'venue_capacity' => 100,
        'start_time' => now()->addDays(10)->format('Y-m-d H:i:s'),
        'end_time' => now()->addDays(5)->format('Y-m-d H:i:s'),
    ];

    $response = $this
        ->withHeader('Authorization', "Bearer $token")
        ->postJson('/api/events', $eventData);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('end_time');
});



test('event is created with correct user_id', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    $eventData = [
        'title' => 'User Event',
        'venue_capacity' => 50,
        'start_time' => now()->addDays(15)->format('Y-m-d H:i:s'),
        'end_time' => now()->addDays(16)->format('Y-m-d H:i:s'),
    ];

    $response = $this
        ->withHeader('Authorization', "Bearer $token")
        ->postJson('/api/events', $eventData);

    $response->assertStatus(201);

    $event = Event::where('title', 'User Event')->first();
    expect($event->user_id)->toBe($user->id);
});
