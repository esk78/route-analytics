<?php

namespace Tests\Feature;

use App\Models\Checkpoint;
use App\Models\DailyRoute;
use App\Models\Inspector;
use App\Models\PlannedRoutePoint;
use App\Models\RoutePoint;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimulateInspectorRoutesCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_routes_simulate_command_creates_daily_routes(): void
    {
        $team = Team::factory()->create();

        Inspector::factory()
            ->count(2)
            ->create([
                'team_id' => $team->id,
            ]);

        Checkpoint::factory()
            ->count(150)
            ->create();

        $this->artisan('routes:simulate', [
            '--date' => '2026-05-13',
            '--inspectors' => 2,
        ])
            ->assertSuccessful();

        $this->assertDatabaseCount('daily_routes', 2);

        $this->assertDatabaseHas('daily_routes', [
            'route_date' => '2026-05-13',
        ]);

        $this->assertGreaterThan(0, PlannedRoutePoint::count());
        $this->assertGreaterThan(0, RoutePoint::count());

        DailyRoute::query()->each(function (DailyRoute $route) {
            $this->assertLessThanOrEqual(
                $route->planned_points_count,
                $route->completed_points_count
            );
        });
    }
}
