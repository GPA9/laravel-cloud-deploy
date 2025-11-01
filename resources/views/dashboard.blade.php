<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Personajes de Los Simpsons</h1>

        <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            @foreach ($characters as $character)
                <div
                    class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 shadow hover:shadow-lg transition">
                    <img src="{{ $character->portrait_path }}" alt="{{ $character->name }}" class="rounded-xl w-32 h-32 object-cover">
                    <div class="p-4 bg-white dark:bg-neutral-800">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $character->name }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $character->status }}</p>
                        @if (!empty($character->phrases))
                            <p class="mt-2 text-sm text-gray-700 dark:text-gray-200 line-clamp-3">
                                "{{ $character->phrases[0] }}"
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if ($characters->isEmpty())
            <p class="text-center text-gray-500 dark:text-gray-400 mt-8">
                No hay personajes importados.
                <a href="{{ url('api/characters/import') }}" class="text-blue-500 hover:underline">Importa
                    personajes</a>
            </p>
        @endif
    </div>
</x-layouts.app>
