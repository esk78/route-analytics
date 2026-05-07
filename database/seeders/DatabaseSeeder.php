<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Checkpoint;
use App\Models\Controller;
use App\Models\DailyRoute;
use App\Models\Team;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $checkpoints = Checkpoint::factory()->count(300)->create();

        Team::factory()->count(5)->create()->each(function (Team $team) use ($checkpoints) {
                $controllers = Controller::factory()->count(4)->create([
                        'team_id' => $team->id,
                    ]);

                foreach ($controllers as $controller) {
                    for ($day = 0; $day < 7; $day++) {
                        $routeDate = now()->subDays($day)->toDateString();

                        $plannedPointsCount = fake()->numberBetween(10, 30);

                        $dailyRoute = DailyRoute::create([
                            'controller_id' => $controller->id,
                            'route_date' => $routeDate,
                            'planned_points_count' => $plannedPointsCount,
                            'completed_points_count' => 0,
                            'completion_percentage' => 0,
                            'average_speed' => null,
                        ]);

                        $plannedCheckpoints = $checkpoints->random($plannedPointsCount);

                        $visitedCount = fake()->numberBetween(5, $plannedPointsCount);

                        $visitedAt = Carbon::parse($routeDate)->setHour(9);

                        foreach ($plannedCheckpoints->take($visitedCount) as $index => $checkpoint) {
                            $visitedAt = $visitedAt->addMinutes(fake()->numberBetween(5, 25));

                            $dailyRoute->routePoints()->create([
                                'checkpoint_id' => $checkpoint->id,
                                'latitude' => $checkpoint->latitude,
                                'longitude' => $checkpoint->longitude,
                                'visited_at' => $visitedAt,
                                'sequence_order' => $index + 1,
                                'is_planned' => true,
                                'is_visited' => true,
                                'speed_from_previous' => $index === 0
                                    ? null
                                    : fake()->randomFloat(2, 20, 80),
                            ]);
                        }

                        $dailyRoute->update([
                            'completed_points_count' => $visitedCount,
                            'completion_percentage' => round(($visitedCount / $plannedPointsCount) * 100, 2),
                            'average_speed' => $dailyRoute->routePoints()->whereNotNull('speed_from_previous')->avg('speed_from_previous'),
                        ]);
                    }
                }
            });
    }
}
