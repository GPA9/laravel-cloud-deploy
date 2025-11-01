<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Personajes de Los Simpsons</h1>

        <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            @foreach ($characters as $character)
                <div
                    class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 shadow hover:shadow-lg transition">
                    
                    @php
                        // Detecta si la imagen es local o remota
                        $imagePath = $character->portrait_path;
                        if (Str::startsWith($imagePath, 'http')) {
                            // Si aún no se descargó, usa la URL completa
                            $imageUrl = $imagePath;
                        } else {
                            // Si ya está descargada, la busca en storage
                            $imageUrl = asset('storage/characters/' . basename($imagePath));
                        }
                    @endphp

                    <img src="{{ $imageUrl }}" 
                         alt="{{ $character->name }}"
                         class="rounded-t-xl w-full h-56 object-cover bg-gray-100 dark:bg-neutral-700">

                    <div class="p-4 bg-white dark:bg-neutral-800">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $character->name }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $character->status }}</p>
                        @php
                            $phrases = json_decode($character->phrases, true);
                        @endphp
                        @if (!empty($phrases) && is_array($phrases))
                            <p class="mt-2 text-sm text-gray-700 dark:text-gray-200 line-clamp-3">
                                “{{ $phrases[0] }}”
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if ($characters->isEmpty())
            <p class="text-center text-gray-500 dark:text-gray-400 mt-8">
                No hay personajes importados.
                <a href="{{ url('api/characters/import') }}" class="text-blue-500 hover:underline">
                    Importa personajes
                </a>
            </p>
        @endif
    </div>
</x-layouts.app>
