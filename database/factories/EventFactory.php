<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Event;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'event_type' => $this->faker->word(),
            'location' => $this->faker->city(),
            'venue_name' => $this->faker->word(),
            'fee' => $this->faker->randomFloat(2, 0, 1000),
            'currency' => 'GBP',
            'venue_capacity' => $this->faker->numberBetween(1, 1000),
            'start_time' => $this->faker->dateTimeBetween(now(), now()->addMonth()),
            'end_time' => $this->faker->dateTimeBetween(now(), now()->addMonth()),
        ];
    }
}
