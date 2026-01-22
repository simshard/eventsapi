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
    $ownedEvents = Event::factory(3)->create(['user_id' => $user->id]);

    // Create events owned by other users
    $otherEvents = Event::factory(2)->create(['user_id' => $user2->id]);

    $this->actingAs($user);

    $response = $this->get('/dashboard');

   // $response->assertOk();

    // Assert all events are visible by title
    foreach ($ownedEvents->merge($otherEvents) as $event) {
        $response->assertSee($event->title);
    }

    // Assert owned events show the owner's name
    foreach ($ownedEvents as $event) {
        $response->assertSeeText($user->name);
    }

    // Assert other events show the other user's name
    foreach ($otherEvents as $event) {
        $response->assertSeeText($user2->name);
    }
});
