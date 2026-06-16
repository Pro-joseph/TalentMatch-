<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Analyses &mdash; {{ $offre->titre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($analyses->isEmpty())
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-500 dark:text-gray-400">
                    Aucune analyse pour cette offre.
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($analyses as $analyse)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $analyse->candidat->nom }}
                                    </h3>
                                    <div class="flex flex-wrap items-center gap-4 mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-medium {{ $analyse->matching_score >= 70 ? 'text-green-600' : ($analyse->matching_score >= 40 ? 'text-yellow-600' : 'text-red-600') }}">
                                            Score: {{ $analyse->matching_score ?? '—' }}/100
                                        </span>
                                        <span>{{ $analyse->annees_experience }} an(s) exp.</span>
                                        <span>{{ $analyse->niveau_etudes }}</span>
                                        @if ($analyse->recommandation)
                                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                                {{ $analyse->recommandation->value === 'recommandé' ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' : '' }}
                                                {{ $analyse->recommandation->value === 'réservé' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300' : '' }}
                                                {{ $analyse->recommandation->value === 'non_retenu' ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' : '' }}">
                                                {{ $analyse->recommandation->value }}
                                            </span>
                                        @endif
                                        <span class="text-xs uppercase {{ $analyse->status === 'done' ? 'text-green-600' : ($analyse->status === 'failed' ? 'text-red-600' : 'text-yellow-600') }}">
                                            {{ $analyse->status }}
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ route('analyses.show', $analyse) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                    Détail →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $analyses->links() }}
                </div>
            @endif

            <div class="mt-6">
                <a href="{{ route('offres.show', $offre) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">&larr; Retour à l'offre</a>
            </div>
        </div>
    </div>
</x-app-layout>
