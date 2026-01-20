<?php

use App\Models\User;
use App\Models\Event;
uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('authenticated user can create a new event', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    $eventData = [
        'title' => 'NWS Conference 2026',
        'description' => 'Annual Net World sports conference with workshops and talks',
        'location' => 'Wrexham',
        'venue_name' => 'Convention Center',
        'fee' => 9.99,
        'currency' => 'GBP',
        'venue_capacity' => 100,
        'start_time' => now()->addDays(30)->format('Y-m-d H:i:s'),
        'end_time' => now()->addDays(31)->format('Y-m-d H:i:s'),
    ];

    $response = $this
        ->withHeader('Authorization', "Bearer $token")
        ->postJson('/api/events', $eventData);

    $response->assertStatus(201);
    $response->assertJsonStructure(['data' => ['id', 'title', 'user_id']]);

    if ($response->status() !== 201) {
        dump('Response:', $response->json());
        dump('Status:', $response->status());
    }
    // $this->assertDatabaseHas('events', [
    //     'title' => 'NWS Conference 2026',
    //     'user_id' => $user->id,
    // ]);
       $response->assertStatus(201);
});




// test('unauthenticated user cannot create an event', function () {
//     $eventData = [
//         'title' => 'Test Event',
//         'venue_capacity' => 100,
//         'start_time' => now()->addDays(10)->format('Y-m-d H:i:s'),
//     ];

//     $response = $this->post('/events', $eventData);

//     $response->assertRedirect('/login');
// });







// test('event creation fails with missing required fields', function () {
//     $user = User::factory()->create();

//     $response = $this
//         ->actingAs($user)
//         ->post('/events', [
//             'description' => 'Missing required fields',
//         ]);

//     $response->assertSessionHasErrors(['title', 'venue_capacity', 'start_time']);
// });

// test('event creation fails when start_time is after end_time', function () {
//     $user = User::factory()->create();

//     $eventData = [
//         'title' => 'Invalid Event',
//         'venue_capacity' => 100,
//         'start_time' => now()->addDays(10)->format('Y-m-d H:i:s'),
//         'end_time' => now()->addDays(5)->format('Y-m-d H:i:s'),
//     ];

//     $response = $this
//         ->actingAs($user)
//         ->post('/events', $eventData);

//     $response->assertSessionHasErrors('start_time');
// });

// test('event is created with correct user_id', function () {
//     $user = User::factory()->create();

//     $eventData = [
//         'title' => 'User Event',
//         'venue_capacity' => 50,
//         'start_time' => now()->addDays(15)->format('Y-m-d H:i:s'),
//     ];

//     $this->actingAs($user)->post('/events', $eventData);

//     $event = Event::where('title', 'User Event')->first();

//     expect($event->user_id)->toBe($user->id);
// });
