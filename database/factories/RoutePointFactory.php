<?php

namespace Database\Factories;

use App\Models\Checkpoint;
use App\Models\DailyRoute;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RoutePoint>
 */
class RoutePointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $checkpoint = Checkpoint::factory()->create();

        return [
            'daily_route_id' => DailyRoute::factory(),
            'checkpoint_id' => $checkpoint->id,
            'latitude' => $checkpoint->latitude,
            'longitude' => $checkpoint->longitude,
            'visited_at' => fake()->dateTimeBetween('-30 days', 'now'),
            'sequence_order' => fake()->numberBetween(1, 100),
            'is_planned' => fake()->boolean(85),
            'is_visited' => fake()->boolean(90),
            'speed_from_previous' => fake()->randomFloat(2, 10, 100),
        ];
    }
}
