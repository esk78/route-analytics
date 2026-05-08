<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Route details
            </h2>

            <a
                href="{{ route('daily-routes.index') }}"
                class="text-sm text-blue-600 hover:underline"
            >
                Back to routes
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-semibold mb-4">
                        General information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="border rounded-lg p-4">
                            <div class="text-sm text-gray-500">Date</div>
                            <div class="text-xl font-semibold">
                                {{ $route->route_date->format('Y-m-d') }}
                            </div>
                        </div>

                        <div class="border rounded-lg p-4">
                            <div class="text-sm text-gray-500">Team</div>
                            <div class="text-xl font-semibold">
                                {{ $route->inspector->team->name }}
                            </div>
                        </div>

                        <div class="border rounded-lg p-4">
                            <div class="text-sm text-gray-500">Inspector</div>
                            <div class="text-xl font-semibold">
                                {{ $route->inspector->name }}
                            </div>
                        </div>

                        <div class="border rounded-lg p-4">
                            <div class="text-sm text-gray-500">Average speed</div>
                            <div class="text-xl font-semibold">
                                {{ $route->average_speed ? $route->average_speed . ' km/h' : '-' }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-semibold mb-4">
                        Route statistics
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="border rounded-lg p-4">
                            <div class="text-sm text-gray-500">Planned points</div>
                            <div class="text-2xl font-bold">
                                {{ $route->planned_points_count }}
                            </div>
                        </div>

                        <div class="border rounded-lg p-4">
                            <div class="text-sm text-gray-500">Completed points</div>
                            <div class="text-2xl font-bold">
                                {{ $route->completed_points_count }}
                            </div>
                        </div>

                        <div class="border rounded-lg p-4">
                            <div class="text-sm text-gray-500">Completion</div>
                            <div class="text-2xl font-bold">
                                {{ $route->completion_percentage }}%
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div
                                class="bg-blue-600 h-4 rounded-full"
                                style="width: {{ min($route->completion_percentage, 100) }}%"
                            ></div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-semibold mb-4">
                        Route map
                    </h3>

                    <div
                        id="route-map"
                        class="w-full rounded-lg border"
                        style="height: 500px;"
                    ></div>

                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-semibold mb-4">
                        Route points
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-4 py-2 text-left">#</th>
                                    <th class="border px-4 py-2 text-left">Checkpoint</th>
                                    <th class="border px-4 py-2 text-right">Latitude</th>
                                    <th class="border px-4 py-2 text-right">Longitude</th>
                                    <th class="border px-4 py-2 text-left">Visited at</th>
                                    <th class="border px-4 py-2 text-center">Planned</th>
                                    <th class="border px-4 py-2 text-center">Visited</th>
                                    <th class="border px-4 py-2 text-right">Speed from previous</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($routePoints as $point)
                                    <tr>
                                        <td class="border px-4 py-2">
                                            {{ $point->sequence_order ?? $loop->iteration }}
                                        </td>

                                        <td class="border px-4 py-2">
                                            {{ $point->checkpoint?->name ?? 'Custom point' }}
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            {{ $point->latitude }}
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            {{ $point->longitude }}
                                        </td>

                                        <td class="border px-4 py-2">
                                            {{ $point->visited_at->format('Y-m-d H:i:s') }}
                                        </td>

                                        <td class="border px-4 py-2 text-center">
                                            @if ($point->is_planned)
                                                <span class="text-green-600 font-semibold">Yes</span>
                                            @else
                                                <span class="text-red-600 font-semibold">No</span>
                                            @endif
                                        </td>

                                        <td class="border px-4 py-2 text-center">
                                            @if ($point->is_visited)
                                                <span class="text-green-600 font-semibold">Yes</span>
                                            @else
                                                <span class="text-red-600 font-semibold">No</span>
                                            @endif
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            {{ $point->speed_from_previous ? $point->speed_from_previous . ' km/h' : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="border px-4 py-4 text-center text-gray-500">
                                            No points found for this route.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
    @push('scripts')
        <script>
            window.routePoints = @json($mapPoints);
        </script>

        @vite('resources/js/route-map.js')
    @endpush
</x-app-layout>
