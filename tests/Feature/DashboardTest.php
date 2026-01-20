<?php

use App\Models\User;

use App\Models\Event;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests are redirected to the login page', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get('/dashboard')->assertOk();
});


test('auth users can see a list of their owned events and other events', function () {
    $user = User::factory()->create();
    $user2 = User::factory()->create();

    // Create events owned by the user
     $ownedEvents = \App\Models\Event::factory(3)->create(['user_id' => $user->id]);

    // Create events owned by other users
    $otherEvents = \App\Models\Event::factory(2)->create(['user_id' => $user2->id]);

    $this->actingAs($user);

    $response = $this->get('/dashboard');

    $response->assertOk();
    // Assert all events are visible
    foreach ($ownedEvents->merge($otherEvents) as $event) {
        $response->assertSee($event->name);
    }

    foreach ($ownedEvents as $event) {
        $response->assertSeeText($user->name);
    }

   foreach ($otherEvents as $event) {
     expect($event->owner->name)->toBe($user2->name);
        $this->assertTrue($event->owner->name === $user2->name);
    }
});
