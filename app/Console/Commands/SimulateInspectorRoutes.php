<?php

namespace App\Console\Commands;

use App\Models\Checkpoint;
use App\Models\DailyRoute;
use App\Models\Inspector;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Services\GeoService;

#[Signature('routes:simulate
            {--date= : Route date in Y-m-d format}
            {--inspectors= : Number of inspectors to simulate}')]
#[Description('Simulate daily routes for inspectors and store route points')]
class SimulateInspectorRoutes extends Command
{
    public function handle(GeoService $geoService): int
    {
        $routeDate = $this->option('date')
            ? Carbon::parse($this->option('date'))->toDateString()
            : now()->toDateString();

        $inspectorsLimit = $this->option('inspectors')
            ? (int) $this->option('inspectors')
            : null;

        $inspectors = Inspector::query()
            ->when($inspectorsLimit, function ($query) use ($inspectorsLimit) {
                $query->limit($inspectorsLimit);
            })
            ->get();

        if ($inspectors->isEmpty()) {
            $this->error('No inspectors found. Please run seeders first.');

            return self::FAILURE;
        }

        $checkpointsCount = Checkpoint::query()->count();

        if ($checkpointsCount < 100) {
            $this->error('Not enough checkpoints found. Please run seeders first.');

            return self::FAILURE;
        }

        $this->info("Simulating routes for date: {$routeDate}");
        $this->info("Inspectors: {$inspectors->count()}");

        foreach ($inspectors as $inspector) {
            $plannedPointsCount = fake()->numberBetween(10, 100);

            $dailyRoute = DailyRoute::query()->updateOrCreate(
                [
                    'inspector_id' => $inspector->id,
                    'route_date' => $routeDate,
                ],
                [
                    'planned_points_count' => $plannedPointsCount,
                    'completed_points_count' => 0,
                    'completion_percentage' => 0,
                    'average_speed' => null,
                ]
            );

            $dailyRoute->routePoints()->delete();
            $dailyRoute->plannedRoutePoints()->delete();

            $baseCheckpoint = Checkpoint::query()
                ->inRandomOrder()
                ->first();

            $plannedCheckpoints = Checkpoint::query()
                ->near(
                    (float) $baseCheckpoint->latitude,
                    (float) $baseCheckpoint->longitude,
                    0.3
                )
                ->inRandomOrder()
                ->limit($plannedPointsCount)
                ->get();

            if ($plannedCheckpoints->count() < $plannedPointsCount) {
                $plannedCheckpoints = Checkpoint::query()
                    ->inRandomOrder()
                    ->limit($plannedPointsCount)
                    ->get();
            }

            foreach ($plannedCheckpoints as $index => $checkpoint) {
                $dailyRoute->plannedRoutePoints()->create([
                    'checkpoint_id' => $checkpoint->id,
                    'sequence_order' => $index + 1,
                ]);
            }

            $actualPlannedPointsCount = $plannedCheckpoints->count();

            $dailyRoute->update([
                'planned_points_count' => $actualPlannedPointsCount,
            ]);

            $visitedCount = fake()->numberBetween(
                min(5, $actualPlannedPointsCount),
                $actualPlannedPointsCount
            );

            $visitedAt = Carbon::parse($routeDate)->setHour(9)->setMinute(0);

            $previousPoint = null;
            $previousVisitedAt = null;

            foreach ($plannedCheckpoints->take($visitedCount) as $index => $checkpoint) {
                $travelMinutes = fake()->numberBetween(20, 60);

                $visitedAt = $visitedAt->copy()->addMinutes($travelMinutes);

                $speed = null;

                if ($previousPoint !== null && $previousVisitedAt !== null) {
                    $distance = $geoService->distanceInKilometers(
                        (float) $previousPoint->latitude,
                        (float) $previousPoint->longitude,
                        (float) $checkpoint->latitude,
                        (float) $checkpoint->longitude
                    );

                    $minutesDiff = $previousVisitedAt->diffInMinutes($visitedAt);

                    $speed = $geoService->speedInKilometersPerHour(
                        $distance,
                        $minutesDiff
                    );
                }

                $dailyRoute->routePoints()->create([
                    'checkpoint_id' => $checkpoint->id,
                    'latitude' => $checkpoint->latitude,
                    'longitude' => $checkpoint->longitude,
                    'visited_at' => $visitedAt,
                    'sequence_order' => $index + 1,
                    'is_planned' => true,
                    'is_visited' => true,
                    'speed_from_previous' => $speed,
                ]);

                $previousPoint = $checkpoint;
                $previousVisitedAt = $visitedAt->copy();
            }

            $extraPointsCount = fake()->numberBetween(0, 3);

            if ($extraPointsCount > 0) {
                $extraCheckpoints = Checkpoint::query()
                    ->whereNotIn('id', $plannedCheckpoints->pluck('id'))
                    ->near(
                        (float) $baseCheckpoint->latitude,
                        (float) $baseCheckpoint->longitude,
                        0.9
                    )
                    ->inRandomOrder()
                    ->limit($extraPointsCount)
                    ->get();

                foreach ($extraCheckpoints as $extraCheckpoint) {
                    $travelMinutes = fake()->numberBetween(5, 20);

                    $visitedAt = $visitedAt->copy()->addMinutes($travelMinutes);

                    $speed = null;

                    if ($previousPoint !== null && $previousVisitedAt !== null) {
                        $distance = $geoService->distanceInKilometers(
                            (float) $previousPoint->latitude,
                            (float) $previousPoint->longitude,
                            (float) $extraCheckpoint->latitude,
                            (float) $extraCheckpoint->longitude
                        );

                        $minutesDiff = $previousVisitedAt->diffInMinutes($visitedAt);

                        $speed = $geoService->speedInKilometersPerHour(
                            $distance,
                            $minutesDiff
                        );
                    }

                    $dailyRoute->routePoints()->create([
                        'checkpoint_id' => $extraCheckpoint->id,
                        'latitude' => $extraCheckpoint->latitude,
                        'longitude' => $extraCheckpoint->longitude,
                        'visited_at' => $visitedAt,
                        'sequence_order' => $dailyRoute->routePoints()->count() + 1,
                        'is_planned' => false,
                        'is_visited' => true,
                        'speed_from_previous' => $speed,
                    ]);

                    $previousPoint = $extraCheckpoint;
                    $previousVisitedAt = $visitedAt->copy();
                }
            }

            $completedPointsCount = $dailyRoute->routePoints()
                ->where('is_planned', true)
                ->where('is_visited', true)
                ->count();

            $averageSpeed = $dailyRoute->routePoints()
                ->whereNotNull('speed_from_previous')
                ->avg('speed_from_previous');

            $dailyRoute->update([
                'completed_points_count' => $completedPointsCount,
                'completion_percentage' => $actualPlannedPointsCount > 0
                    ? round(($completedPointsCount / $actualPlannedPointsCount) * 100, 2)
                    : 0,
                'average_speed' => $averageSpeed,
            ]);

            $this->line(
                "Inspector #{$inspector->id} {$inspector->name}: {$completedPointsCount}/{$plannedPointsCount} points"
            );
        }

        $this->info('Simulation completed successfully.');

        return self::SUCCESS;
    }
}
