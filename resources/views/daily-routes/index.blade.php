<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Щоденні маршрути
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-semibold mb-4">
                        Огляд маршрутів
                    </h3>

                    <form method="GET" action="{{ route('daily-routes.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div>
                                <label for="team_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Команда
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
                                    Контролер
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
                                    Дата з
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
                                    Дата по
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
                                    Фільтр
                                </button>

                                <a
                                    href="{{ route('daily-routes.index') }}"
                                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300"
                                >
                                    Скинути
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-4 py-2 text-left">Дата</th>
                                    <th class="border px-4 py-2 text-left">Команда</th>
                                    <th class="border px-4 py-2 text-left">Контролер</th>
                                    <th class="border px-4 py-2 text-right">Заплановані</th>
                                    <th class="border px-4 py-2 text-right">Пройдені</th>
                                    <th class="border px-4 py-2 text-right">Виконання</th>
                                    <th class="border px-4 py-2 text-right">Сер. швидкість</th>
                                    <th class="border px-4 py-2 text-right">Точки</th>
                                    <th class="border px-4 py-2 text-right">Дії</th>
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
                                            {{ $route->average_speed ? $route->average_speed . ' км/год' : '-' }}
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            {{ $route->routePoints->count() }}
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            <a
                                                href="{{ route('daily-routes.show', $route) }}"
                                                class="text-blue-600 hover:underline"
                                            >
                                                Перегляд
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="border px-4 py-4 text-center text-gray-500">
                                            Маршрутів не знайдено.
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
