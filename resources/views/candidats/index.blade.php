<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Candidats
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-end mb-6">
                <a href="{{ route('candidats.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                    + Nouveau candidat
                </a>
            </div>

            @if ($candidats->isEmpty())
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-500 dark:text-gray-400">
                    Aucun candidat pour le moment.
                </div>
            @else
                <div class="grid gap-4">
                    @foreach ($candidats as $candidat)
                        <a href="{{ route('candidats.show', $candidat) }}" class="block bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $candidat->nom }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ Str::limit($candidat->cv_texte, 200) }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">{{ $candidat->created_at->format('d/m/Y') }}</p>
                        </a>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $candidats->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
