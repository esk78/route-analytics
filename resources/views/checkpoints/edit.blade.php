<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Редагувати точку
            </h2>

            <a
                href="{{ route('checkpoints.index') }}"
                class="text-sm text-blue-600 hover:underline"
            >
                Назад до точок
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('checkpoints.update', $checkpoint) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        @include('checkpoints.partials.form', ['checkpoint' => $checkpoint])

                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700"
                            >
                                Зберегти
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
