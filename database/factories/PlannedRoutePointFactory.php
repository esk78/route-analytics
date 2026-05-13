<?php

namespace Database\Factories;

use App\Models\Checkpoint;
use App\Models\DailyRoute;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlannedRoutePointFactory extends Factory
{
    public function definition(): array
    {
        return [
            'daily_route_id' => DailyRoute::factory(),
            'checkpoint_id' => Checkpoint::factory(),
            'sequence_order' => fake()->numberBetween(1, 100),
        ];
    }
}
