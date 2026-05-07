<?php

namespace Database\Factories;

use App\Models\Controller;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DailyRoute>
 */
class DailyRouteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $planned = fake()->numberBetween(10, 100);
        $completed = fake()->numberBetween(0, $planned);

        return [
            'controller_id' => Controller::factory(),
            'route_date' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'planned_points_count' => $planned,
            'completed_points_count' => $completed,
            'completion_percentage' => round(($completed / $planned) * 100, 2),
            'average_speed' => fake()->randomFloat(2, 20, 90),
        ];
    }
}
