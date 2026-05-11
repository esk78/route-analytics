<?php

namespace App\Http\Controllers;

use App\Models\Checkpoint;
use Illuminate\Http\Request;

class CheckpointController extends Controller
{
    public function index(Request $request)
    {
        $checkpoints = Checkpoint::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($query) use ($search) {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('id', $search);
                });
            })
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('checkpoints.index', [
            'checkpoints' => $checkpoints,
        ]);
    }

    public function create()
    {
        return view('checkpoints.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        Checkpoint::create($validated);

        return redirect()
            ->route('checkpoints.index')
            ->with('success', 'Checkpoint created successfully.');
    }

    public function edit(Checkpoint $checkpoint)
    {
        return view('checkpoints.edit', [
            'checkpoint' => $checkpoint,
        ]);
    }

    public function update(Request $request, Checkpoint $checkpoint)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $checkpoint->update($validated);

        return redirect()
            ->route('checkpoints.index')
            ->with('success', 'Checkpoint updated successfully.');
    }

        public function destroy(Checkpoint $checkpoint)
        {
            $isUsedInRoutes = $checkpoint->routePoints()->exists();

            $isUsedInPlannedRoutes = $checkpoint->plannedRoutePoints()->exists();

            if ($isUsedInRoutes || $isUsedInPlannedRoutes) {
                return redirect()
                    ->route('checkpoints.index')
                    ->with('error', 'Точку неможливо видалити, тому що використана в маршрутах.');
            }

            $checkpoint->delete();

            return redirect()
                ->route('checkpoints.index')
                ->with('success', 'Точка видалена.');
        }
}
