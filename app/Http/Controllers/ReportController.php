<?php

namespace App\Http\Controllers;

use App\Models\DailyRoute;
use App\Models\Inspector;
use App\Models\Team;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $routesQuery = DailyRoute::query()
            ->with([
                'inspector.team',
                'plannedRoutePoints',
                'routePoints',
            ])
            ->when($dateFrom, function ($query) use ($dateFrom) {
                $query->whereDate('route_date', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                $query->whereDate('route_date', '<=', $dateTo);
            });

        $routes = $routesQuery->get();

        $summary = $this->buildReportData($routes);

        $teamReports = Team::query()
            ->with(['inspectors.dailyRoutes' => function ($query) use ($dateFrom, $dateTo) {
                $query
                    ->with(['plannedRoutePoints', 'routePoints'])
                    ->when($dateFrom, function ($query) use ($dateFrom) {
                        $query->whereDate('route_date', '>=', $dateFrom);
                    })
                    ->when($dateTo, function ($query) use ($dateTo) {
                        $query->whereDate('route_date', '<=', $dateTo);
                    });
            }])
            ->orderBy('name')
            ->get()
            ->map(function (Team $team) {
                $routes = $team->inspectors
                    ->flatMap(fn (Inspector $inspector) => $inspector->dailyRoutes);

                return array_merge(
                    ['team' => $team],
                    $this->buildReportData($routes)
                );
            });

        $inspectorReports = Inspector::query()
            ->with(['team', 'dailyRoutes' => function ($query) use ($dateFrom, $dateTo) {
                $query
                    ->with(['plannedRoutePoints', 'routePoints'])
                    ->when($dateFrom, function ($query) use ($dateFrom) {
                        $query->whereDate('route_date', '>=', $dateFrom);
                    })
                    ->when($dateTo, function ($query) use ($dateTo) {
                        $query->whereDate('route_date', '<=', $dateTo);
                    });
            }])
            ->orderBy('name')
            ->get()
            ->map(function (Inspector $inspector) {
                return array_merge(
                    ['inspector' => $inspector],
                    $this->buildReportData($inspector->dailyRoutes)
                );
            });

        return view('reports.index', [
            'teamReports' => $teamReports,
            'inspectorReports' => $inspectorReports,
            'summary' => $summary,
        ]);
    }

    private function buildReportData($routes): array
    {
        $plannedPoints = $routes->sum(function (DailyRoute $route) {
            $plannedRoutePointsCount = $route->plannedRoutePoints->count();

            return $plannedRoutePointsCount > 0
                ? $plannedRoutePointsCount
                : $route->planned_points_count;
        });

        $completedPlannedPoints = $routes->sum(function (DailyRoute $route) {
            return $route->routePoints
                ->where('is_planned', true)
                ->where('is_visited', true)
                ->count();
        });

        $extraPoints = $routes->sum(function (DailyRoute $route) {
            return $route->routePoints
                ->where('is_planned', false)
                ->where('is_visited', true)
                ->count();
        });

        return [
            'routes_count' => $routes->count(),
            'planned_points' => $plannedPoints,
            'completed_points' => $completedPlannedPoints,
            'extra_points' => $extraPoints,
            'total_visited_points' => $completedPlannedPoints + $extraPoints,
            'completion_percentage' => $plannedPoints > 0
                ? round(($completedPlannedPoints / $plannedPoints) * 100, 2)
                : 0,
            'average_speed' => round((float) $routes->avg('average_speed'), 2),
        ];
    }
}
