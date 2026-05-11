<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Точки
            </h2>

            <a
                href="{{ route('checkpoints.create') }}"
                class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700"
            >
                Додати точку
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded-md">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="GET" action="{{ route('checkpoints.index') }}" class="mb-6">
                        <div class="flex gap-2">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search by ID or name"
                                class="w-full border-gray-300 rounded-md shadow-sm"
                            >

                            <button
                                type="submit"
                                class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700"
                            >
                                Пошук
                            </button>

                            <a
                                href="{{ route('checkpoints.index') }}"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300"
                            >
                                Скинути
                            </a>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-4 py-2 text-right">ID</th>
                                    <th class="border px-4 py-2 text-left">Назва</th>
                                    <th class="border px-4 py-2 text-right">Широта</th>
                                    <th class="border px-4 py-2 text-right">Довгота</th>
                                    <th class="border px-4 py-2 text-right">Дії</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($checkpoints as $checkpoint)
                                    <tr>
                                        <td class="border px-4 py-2 text-right">
                                            {{ $checkpoint->id }}
                                        </td>

                                        <td class="border px-4 py-2">
                                            {{ $checkpoint->name }}
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            {{ $checkpoint->latitude }}
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            {{ $checkpoint->longitude }}
                                        </td>

                                        <td class="border px-4 py-2 text-right">
                                            <div class="flex justify-end gap-3">
                                                <a
                                                    href="{{ route('checkpoints.edit', $checkpoint) }}"
                                                    class="text-blue-600 hover:underline"
                                                >
                                                    Редагувати
                                                </a>

                                                <form
                                                    method="POST"
                                                    action="{{ route('checkpoints.destroy', $checkpoint) }}"
                                                    onsubmit="return confirm('Delete this checkpoint?')"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="text-red-600 hover:underline"
                                                    >
                                                        Видалити
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="border px-4 py-4 text-center text-gray-500">
                                            Немає точок.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $checkpoints->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
