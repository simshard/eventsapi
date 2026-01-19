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


test('authenticated users can visit the dashboard and see a list of all events ', function () {
    $user = User::factory()->create();
    $user2 = User::factory()->create();

    // Create events owned by the user
     $ownedEvents = \App\Models\Event::factory(3)->create(['owner_id' => $user->id]);

    // Create events owned by other users
    $otherEvents = \App\Models\Event::factory(2)->create(['owner_id' => $user2->id]);

    $this->actingAs($user);

    $response = $this->get('/dashboard');

    $response->assertOk();

    // Assert all events are visible
    foreach ($ownedEvents->merge($otherEvents) as $event) {
        $response->assertSee($event->name);
    }

    // Assert owned events are marked/identified as owned by the user
    // foreach ($ownedEvents as $event) {
    //     $response->assertSeeText($event->name); // You may want a more specific assertion here
    // }
});
