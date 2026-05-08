<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daily Routes
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-semibold mb-4">
                        Routes overview
                    </h3>

                    <form method="GET" action="{{ route('daily-routes.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div>
                                <label for="team_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Team
                                </label>

                                <select
                                    id="team_id"
                                    name="team_id"
                                    class="w-full border-gray-300 rounded-md shadow-sm"
                                >
                                    <option value="">All teams</option>

                                    @foreach ($teams as $team)
                                        <option
                                            value="{{ $team->id }}"
                                            @selected((string) request('team_id') === (string) $team->id)
                                        >
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="inspector_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Inspector
                                </label>

                                <select
                                    id="inspector_id"
                                    name="inspector_id"
                                    class="w-full border-gray-300 rounded-md shadow-sm"
                                >
                                    <option value="">All inspectors</option>

                                    @foreach ($inspectors as $inspector)
                                        <option
                                            value="{{ $inspector->id }}"
                                            @selected((string) request('inspector_id') === (string) $inspector->id)
                                        >
                                            {{ $inspector->name }} — {{ $inspector->team->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">
                                    Date from
                                </label>

                                <input
                                    id="date_from"
                                    type="date"
                                    name="date_from"
                                    value="{{ request('date_from') }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm"
                                >
                            </div>

                            <div>
                                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">
                                    Date to
                                </label>

                                <input
                                    id="date_to"
                                    type="date"
                                    name="date_to"
                                    value="{{ request('date_to') }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm"
                                >
                            </div>

                            <div class="flex items-end gap-2">
                                <button
                                    type="submit"
                                    class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700"
                                >
                                    Filter
                                </button>

                                <a
                                    href="{{ route('daily-routes.index') }}"
                                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300"
                                >
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-4 py-2 text-left">Date</th>
                                    <th class="border px-4 py-2 text-left">Team</th>
                                    <th class="border px-4 py-2 text-left">Inspector</th>
                                    <th class="border px-4 py-2 text-right">Planned</th>
                                    <th class="border px-4 py-2 text-right">Completed</th>
                                    <th class="border px-4 py-2 text-right">Completion</th>
                                    <th class="border px-4 py-2 text-right">Avg speed</th>
                                    <th class="border px-4 py-2 text-right">Points</th>
                                    <th class="border px-4 py-2 text-right">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($routes as $route)
                                    <tr>
                                        <td class="border px-4 py-2">
                                            {{ $route->route_date->format('Y-m-d') }}
                                        </td>

                                        <td class="border px-4 py-2">
                                            {{ $route->inspector->team->name }}
                                        </td>

                                        <td class="border px-4 py-2">
                                            {{ $route->inspector->name }}
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            {{ $route->planned_points_count }}
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            {{ $route->completed_points_count }}
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            {{ $route->completion_percentage }}%
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            {{ $route->average_speed ? $route->average_speed . ' km/h' : '-' }}
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            {{ $route->routePoints->count() }}
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            <a
                                                href="{{ route('daily-routes.show', $route) }}"
                                                class="text-blue-600 hover:underline"
                                            >
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="border px-4 py-4 text-center text-gray-500">
                                            No routes found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $routes->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
