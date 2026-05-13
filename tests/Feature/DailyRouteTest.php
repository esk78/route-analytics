<?php

namespace Tests\Feature;

use App\Models\Checkpoint;
use App\Models\DailyRoute;
use App\Models\PlannedRoutePoint;
use App\Models\RoutePoint;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DailyRouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_daily_routes_index_is_available_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        DailyRoute::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('daily-routes.index'));

        $response
            ->assertOk()
            ->assertSee('Огляд маршрутів');
    }

    public function test_daily_route_details_page_is_available(): void
    {
        $user = User::factory()->create();

        $route = DailyRoute::factory()->create();

        $checkpoint = Checkpoint::factory()->create();

        PlannedRoutePoint::factory()->create([
            'daily_route_id' => $route->id,
            'checkpoint_id' => $checkpoint->id,
            'sequence_order' => 1,
        ]);

        RoutePoint::factory()->create([
            'daily_route_id' => $route->id,
            'checkpoint_id' => $checkpoint->id,
            'latitude' => $checkpoint->latitude,
            'longitude' => $checkpoint->longitude,
            'sequence_order' => 1,
            'is_planned' => true,
            'is_visited' => true,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('daily-routes.show', $route));

        $response
            ->assertOk()
            ->assertSee('Деталі маршруту')
            ->assertSee('Заплановані точки')
            ->assertSee('Точки маршруту');
    }

    public function test_daily_routes_can_be_filtered_by_date(): void
    {
        $user = User::factory()->create();

        DailyRoute::factory()->create([
            'route_date' => '2026-05-10',
        ]);

        DailyRoute::factory()->create([
            'route_date' => '2026-05-01',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('daily-routes.index', [
                'date_from' => '2026-05-10',
                'date_to' => '2026-05-10',
            ]));

        $response
            ->assertOk()
            ->assertSee('2026-05-10')
            ->assertDontSee('2026-05-01');
    }
}
