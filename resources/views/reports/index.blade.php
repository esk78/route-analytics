<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reports
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-semibold mb-4">
                        Filters
                    </h3>

                    <form method="GET" action="{{ route('reports.index') }}">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">
                                    Date from
                                </label>

                                <input id="date_from" type="date" name="date_from" value="{{ request('date_from') }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm">
                            </div>

                            <div>
                                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">
                                    Date to
                                </label>

                                <input id="date_to" type="date" name="date_to" value="{{ request('date_to') }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm">
                            </div>

                            <div class="flex items-end gap-2">
                                <button type="submit"
                                    class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">
                                    Filter
                                </button>

                                <a href="{{ route('reports.index') }}"
                                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500">Routes</div>
                    <div class="text-3xl font-bold">{{ $summary['routes_count'] }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500">Planned points</div>
                    <div class="text-3xl font-bold">{{ $summary['planned_points'] }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500">Completed planned</div>
                    <div class="text-3xl font-bold">{{ $summary['completed_points'] }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500">Extra points</div>
                    <div class="text-3xl font-bold">{{ $summary['extra_points'] }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500">Completion</div>
                    <div class="text-3xl font-bold">{{ $summary['completion_percentage'] }}%</div>
                    <div class="text-sm text-gray-500 mt-1">
                        Avg speed: {{ $summary['average_speed'] }} km/h
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-semibold mb-4">
                        Team comparison
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-4 py-2 text-left">Team</th>
                                    <th class="border px-4 py-2 text-right">Routes</th>
                                    <th class="border px-4 py-2 text-right">Planned</th>
                                    <th class="border px-4 py-2 text-right">Completed planned</th>
                                    <th class="border px-4 py-2 text-right">Extra</th>
                                    <th class="border px-4 py-2 text-right">Total visited</th>
                                    <th class="border px-4 py-2 text-right">Completion</th>
                                    <th class="border px-4 py-2 text-right">Avg speed</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($teamReports as $report)
                                    <tr>
                                        <td class="border px-4 py-2">
                                            {{ $report['team']->name }}
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            {{ $report['routes_count'] }}
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            {{ $report['planned_points'] }}
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            {{ $report['completed_points'] }}
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            {{ $report['extra_points'] }}
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            {{ $report['total_visited_points'] }}
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            {{ $report['completion_percentage'] }}%
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            {{ $report['average_speed'] }} km/h
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-semibold mb-4">
                        Inspector comparison
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-4 py-2 text-left">Inspector</th>
                                    <th class="border px-4 py-2 text-left">Team</th>
                                    <th class="border px-4 py-2 text-right">Routes</th>
                                    <th class="border px-4 py-2 text-right">Planned</th>
                                    <th class="border px-4 py-2 text-right">Completed planned</th>
                                    <th class="border px-4 py-2 text-right">Extra</th>
                                    <th class="border px-4 py-2 text-right">Total visited</th>
                                    <th class="border px-4 py-2 text-right">Completion</th>
                                    <th class="border px-4 py-2 text-right">Avg speed</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($inspectorReports as $report)
                                    <tr>
                                        <td class="border px-4 py-2">
                                            {{ $report['inspector']->name }}
                                        </td>

                                        <td class="border px-4 py-2">
                                            {{ $report['inspector']->team->name }}
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            {{ $report['routes_count'] }}
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            {{ $report['planned_points'] }}
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            {{ $report['completed_points'] }}
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            {{ $report['extra_points'] }}
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            {{ $report['total_visited_points'] }}
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            {{ $report['completion_percentage'] }}%
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            {{ $report['average_speed'] }} km/h
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
