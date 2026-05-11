<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <a
                    href="{{ route('daily-routes.index') }}"
                    class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition"
                >
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-2">
                            Маршрути
                        </h3>

                        <p class="text-sm text-gray-600">
                            Перегляд маршрутів, фільтрація по контролеру, команді і періоду.
                        </p>
                    </div>
                </a>

                <a
                    href="{{ route('reports.index') }}"
                    class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition"
                >
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-2">
                            Звіти
                        </h3>

                        <p class="text-sm text-gray-600">
                            Порівнянн команд і контролерів за плановими і пройденими точками.
                        </p>
                    </div>
                </a>

                <a
                    href="{{ route('checkpoints.index') }}"
                    class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition"
                >
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-2">
                            Точки
                        </h3>

                        <p class="text-sm text-gray-600">
                            Ручний пошук, створення і редагування точок.
                        </p>
                    </div>
                </a>

            </div>

        </div>
    </div>
</x-app-layout>
