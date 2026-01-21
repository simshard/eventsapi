<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'location' => $this->faker->city(),
            'venue_name' => $this->faker->word(),
            'fee' => $this->faker->randomFloat(2, 0, 100),
            'currency' => 'GBP',
            'venue_capacity' => $this->faker->numberBetween(1, 100),
            'start_time' => $this->faker->dateTimeBetween('+1 day', '+30 days'),
            'end_time' => $this->faker->dateTimeBetween('+31 days', '+60 days'),
        ];
    }
}
