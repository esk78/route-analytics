<?php

namespace Tests\Feature;

use App\Models\Checkpoint;
use App\Models\DailyRoute;
use App\Models\PlannedRoutePoint;
use App\Models\RoutePoint;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckpointTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkpoints_index_is_available(): void
    {
        $user = User::factory()->create();

        Checkpoint::factory()->create([
            'name' => 'Manual Checkpoint',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('checkpoints.index'));

        $response
            ->assertOk()
            ->assertSee('Точки')
            ->assertSee('Manual Checkpoint');
    }

    public function test_checkpoint_can_be_created(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('checkpoints.store'), [
                'name' => 'New Checkpoint',
                'latitude' => 49.2321000,
                'longitude' => 28.4682000,
            ]);

        $response->assertRedirect(route('checkpoints.index'));

        $this->assertDatabaseHas('checkpoints', [
            'name' => 'New Checkpoint',
            'latitude' => 49.2321000,
            'longitude' => 28.4682000,
        ]);
    }

    public function test_checkpoint_can_be_updated(): void
    {
        $user = User::factory()->create();

        $checkpoint = Checkpoint::factory()->create([
            'name' => 'Old Name',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('checkpoints.update', $checkpoint), [
                'name' => 'Updated Checkpoint',
                'latitude' => 50.4501000,
                'longitude' => 30.5234000,
            ]);

        $response->assertRedirect(route('checkpoints.index'));

        $this->assertDatabaseHas('checkpoints', [
            'id' => $checkpoint->id,
            'name' => 'Updated Checkpoint',
            'latitude' => 50.4501000,
            'longitude' => 30.5234000,
        ]);
    }

    public function test_checkpoint_can_be_deleted_if_not_used(): void
    {
        $user = User::factory()->create();

        $checkpoint = Checkpoint::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete(route('checkpoints.destroy', $checkpoint));

        $response->assertRedirect(route('checkpoints.index'));

        $this->assertDatabaseMissing('checkpoints', [
            'id' => $checkpoint->id,
        ]);
    }

    public function test_checkpoint_cannot_be_deleted_if_used_in_route_points(): void
    {
        $user = User::factory()->create();

        $checkpoint = Checkpoint::factory()->create();
        $route = DailyRoute::factory()->create();

        RoutePoint::factory()->create([
            'daily_route_id' => $route->id,
            'checkpoint_id' => $checkpoint->id,
            'latitude' => $checkpoint->latitude,
            'longitude' => $checkpoint->longitude,
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('checkpoints.destroy', $checkpoint));

        $response->assertRedirect(route('checkpoints.index'));

        $this->assertDatabaseHas('checkpoints', [
            'id' => $checkpoint->id,
        ]);
    }

    public function test_checkpoint_cannot_be_deleted_if_used_in_planned_route_points(): void
    {
        $user = User::factory()->create();

        $checkpoint = Checkpoint::factory()->create();
        $route = DailyRoute::factory()->create();

        PlannedRoutePoint::factory()->create([
            'daily_route_id' => $route->id,
            'checkpoint_id' => $checkpoint->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('checkpoints.destroy', $checkpoint));

        $response->assertRedirect(route('checkpoints.index'));

        $this->assertDatabaseHas('checkpoints', [
            'id' => $checkpoint->id,
        ]);
    }

    public function test_checkpoint_validation_requires_valid_coordinates(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('checkpoints.store'), [
                'name' => '',
                'latitude' => 120,
                'longitude' => 200,
            ]);

        $response
            ->assertSessionHasErrors([
                'name',
                'latitude',
                'longitude',
            ]);
    }
}
