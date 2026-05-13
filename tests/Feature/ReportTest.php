<?php

namespace Tests\Feature;

use App\Models\Checkpoint;
use App\Models\DailyRoute;
use App\Models\PlannedRoutePoint;
use App\Models\RoutePoint;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_reports_page_is_available(): void
    {
        $user = User::factory()->create();

        $route = DailyRoute::factory()->create();

        $checkpointOne = Checkpoint::factory()->create();
        $checkpointTwo = Checkpoint::factory()->create();

        PlannedRoutePoint::factory()->create([
            'daily_route_id' => $route->id,
            'checkpoint_id' => $checkpointOne->id,
        ]);

        PlannedRoutePoint::factory()->create([
            'daily_route_id' => $route->id,
            'checkpoint_id' => $checkpointTwo->id,
        ]);

        RoutePoint::factory()->create([
            'daily_route_id' => $route->id,
            'checkpoint_id' => $checkpointOne->id,
            'latitude' => $checkpointOne->latitude,
            'longitude' => $checkpointOne->longitude,
            'is_planned' => true,
            'is_visited' => true,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('reports.index'));

        $response
            ->assertOk()
            ->assertSee('Звіти')
            ->assertSee('Порівняння команд')
            ->assertSee('Порівняння контролерів')
            ->assertSee('Заплановані точки')
            ->assertSee('Пройдено запланованих');
    }
}
