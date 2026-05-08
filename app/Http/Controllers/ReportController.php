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

        $teamReports = Team::query()
            ->with(['inspectors.dailyRoutes' => function ($query) use ($dateFrom, $dateTo) {
                $query
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

                $planned = $routes->sum('planned_points_count');
                $completed = $routes->sum('completed_points_count');

                return [
                    'team' => $team,
                    'routes_count' => $routes->count(),
                    'planned_points' => $planned,
                    'completed_points' => $completed,
                    'completion_percentage' => $planned > 0
                        ? round(($completed / $planned) * 100, 2)
                        : 0,
                    'average_speed' => round((float) $routes->avg('average_speed'), 2),
                ];
            });

        $inspectorReports = Inspector::query()
            ->with(['team', 'dailyRoutes' => function ($query) use ($dateFrom, $dateTo) {
                $query
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
                $routes = $inspector->dailyRoutes;

                $planned = $routes->sum('planned_points_count');
                $completed = $routes->sum('completed_points_count');

                return [
                    'inspector' => $inspector,
                    'routes_count' => $routes->count(),
                    'planned_points' => $planned,
                    'completed_points' => $completed,
                    'completion_percentage' => $planned > 0
                        ? round(($completed / $planned) * 100, 2)
                        : 0,
                    'average_speed' => round((float) $routes->avg('average_speed'), 2),
                ];
            });

        $summaryRoutes = DailyRoute::query()
            ->when($dateFrom, function ($query) use ($dateFrom) {
                $query->whereDate('route_date', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                $query->whereDate('route_date', '<=', $dateTo);
            })
            ->get();

        $summaryPlanned = $summaryRoutes->sum('planned_points_count');
        $summaryCompleted = $summaryRoutes->sum('completed_points_count');

        $summary = [
            'routes_count' => $summaryRoutes->count(),
            'planned_points' => $summaryPlanned,
            'completed_points' => $summaryCompleted,
            'completion_percentage' => $summaryPlanned > 0
                ? round(($summaryCompleted / $summaryPlanned) * 100, 2)
                : 0,
            'average_speed' => round((float) $summaryRoutes->avg('average_speed'), 2),
        ];

        return view('reports.index', [
            'teamReports' => $teamReports,
            'inspectorReports' => $inspectorReports,
            'summary' => $summary,
        ]);
    }
}
