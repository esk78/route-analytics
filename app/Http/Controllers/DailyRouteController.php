<?php

namespace App\Http\Controllers;

use App\Models\Inspector;
use App\Models\DailyRoute;
use App\Models\Team;
use Illuminate\Http\Request;

class DailyRouteController extends Controller
{
    public function index(Request $request)
    {
        $teams = Team::query()->orderBy('name')->get();

        $inspectors = Inspector::query()->with('team')->orderBy('name')->get();

        $routes = DailyRoute::query()
            ->with([
                'inspector.team',
                'routePoints',
            ])
            ->when($request->filled('team_id'), function ($query) use ($request) {
                $query->whereHas('inspector', function ($inspectorQuery) use ($request) {
                    $inspectorQuery->where('team_id', $request->integer('team_id'));
                });
            })
            ->when($request->filled('inspector_id'), function ($query) use ($request) {
                $query->where('inspector_id', $request->integer('inspector_id'));
            })
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereDate('route_date', '>=', $request->date('date_from'));
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate('route_date', '<=', $request->date('date_to'));
            })
            ->latest('route_date')->paginate(20)->withQueryString();

        return view('daily-routes.index', [
            'routes' => $routes,
            'teams' => $teams,
            'inspectors' => $inspectors,
        ]);
    }

    public function show(DailyRoute $dailyRoute)
    {
        $dailyRoute->load([
            'inspector.team',
            'routePoints.checkpoint',
        ]);

        $routePoints = $dailyRoute->routePoints()
            ->with('checkpoint')
            ->orderBy('visited_at')
            ->get();

        $mapPoints = $routePoints->map(function ($point) {
            return [
                'name' => $point->checkpoint?->name ?? 'Custom point',
                'latitude' => (float) $point->latitude,
                'longitude' => (float) $point->longitude,
                'visited_at' => $point->visited_at->format('Y-m-d H:i:s'),
                'is_planned' => (bool) $point->is_planned,
                'is_visited' => (bool) $point->is_visited,
                'speed_from_previous' => $point->speed_from_previous,
            ];
        })->values();

        return view('daily-routes.show', [
            'route' => $dailyRoute,
            'routePoints' => $routePoints,
            'mapPoints' => $mapPoints,
        ]);
    }
}
