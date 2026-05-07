<?php

namespace App\Http\Controllers;

use App\Models\Controller as RouteController;
use App\Models\DailyRoute;
use App\Models\Team;
use Illuminate\Http\Request;

class DailyRouteController extends Controller
{
    public function index(Request $request)
    {
        $teams = Team::query()->orderBy('name')->get();

        $controllers = RouteController::query()->with('team')->orderBy('name')->get();

        $routes = DailyRoute::query()
            ->with([
                'controller.team',
                'routePoints',
            ])
            ->when($request->filled('team_id'), function ($query) use ($request) {
                $query->whereHas('controller', function ($controllerQuery) use ($request) {
                    $controllerQuery->where('team_id', $request->integer('team_id'));
                });
            })
            ->when($request->filled('controller_id'), function ($query) use ($request) {
                $query->where('controller_id', $request->integer('controller_id'));
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
            'controllers' => $controllers,
        ]);
    }
}
