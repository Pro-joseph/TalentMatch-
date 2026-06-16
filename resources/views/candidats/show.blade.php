<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $candidat->nom }}
            </h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('candidats.edit', $candidat) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                    Modifier
                </a>
                <form method="POST" action="{{ route('candidats.destroy', $candidat) }}" onsubmit="return confirm('Supprimer ce candidat ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">CV</h3>
                <pre class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap font-sans">{{ $candidat->cv_texte }}</pre>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Analyses</h3>

                @if ($candidat->analyses->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Aucune analyse pour ce candidat.</p>
                @else
                    <div class="space-y-3">
                        @foreach ($candidat->analyses as $analyse)
                            <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $analyse->offre->titre }}</p>
                                    <div class="flex items-center gap-3 mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        <span>Score: {{ $analyse->matching_score ?? '—' }}/100</span>
                                        <span>Status: {{ $analyse->status }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('analyses.show', $analyse) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                    Voir →
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <a href="{{ route('candidats.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">&larr; Retour à la liste</a>
            </div>
        </div>
    </div>
</x-app-layout>
