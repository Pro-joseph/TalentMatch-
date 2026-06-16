<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Mes offres d'emploi
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 rounded-lg border border-green-200 dark:border-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex items-center justify-end mb-6">
                <a href="{{ route('offres.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    + Nouvelle offre
                </a>
            </div>

            @if ($offres->isEmpty())
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-500 dark:text-gray-400">
                    Aucune offre pour le moment.
                </div>
            @else
                <div class="grid gap-4">
                    @foreach ($offres as $offre)
                        <a href="{{ route('offres.show', $offre) }}" class="block bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors border border-transparent hover:border-indigo-300 dark:hover:border-indigo-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $offre->titre }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">{{ $offre->description }}</p>
                            <div class="flex items-center gap-4 mt-3 text-xs text-gray-500 dark:text-gray-400">
                                <span>{{ $offre->experience_min }} an(s) min.</span>
                                <span>{{ count($offre->competences_requises ?? []) }} compétence(s)</span>
                                <span>{{ $offre->created_at->format('d/m/Y') }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $offres->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
