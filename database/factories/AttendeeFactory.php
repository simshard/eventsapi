<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Attendee;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendeeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
        ];
    }
}
