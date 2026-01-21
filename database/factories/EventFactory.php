<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $startTime = Carbon::now()->addDays(rand(1, 30));
        $endTime = $startTime->copy()->addHours(8);
        return [
            'user_id' => User::factory(),
            'title' => 'Test Event ' . rand(1, 1000),
            'description' => 'Event description',
            'location' => 'Test Location',
            'venue_name' => 'Test Venue',
            'fee' => 50.00,
            'currency' => 'GBP',
            'venue_capacity' => rand(1, 100),
            'start_time' => $startTime,
            'end_time' => $endTime,
        ];
    }
}
