<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Analyse &mdash; {{ $analyse->candidat->nom }}
            </h2>
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $analyse->offre->titre }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if ($analyse->status === 'failed')
                <div class="bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 rounded-lg p-4 border border-red-200 dark:border-red-800">
                    L'analyse a échoué : {{ $analyse->justification }}
                </div>
            @elseif ($analyse->status === 'pending')
                <div class="bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200 rounded-lg p-4 border border-yellow-200 dark:border-yellow-800">
                    Analyse en cours... Revenez dans quelques instants.
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <span class="text-4xl font-bold {{ $analyse->matching_score >= 70 ? 'text-green-600' : ($analyse->matching_score >= 40 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $analyse->matching_score }}%
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">matching</span>
                        </div>
                        @if ($analyse->recommandation)
                            <span class="px-4 py-1.5 rounded-full text-sm font-medium
                                {{ $analyse->recommandation->value === 'recommandé' ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' : '' }}
                                {{ $analyse->recommandation->value === 'réservé' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300' : '' }}
                                {{ $analyse->recommandation->value === 'non_retenu' ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' : '' }}">
                                {{ $analyse->recommandation->value }}
                            </span>
                        @endif
                    </div>

                    <p class="text-gray-700 dark:text-gray-300 text-sm italic">{{ $analyse->justification }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Compétences extraites</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($analyse->competences_extraites ?? [] as $skill)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Compétences manquantes</h3>
                        @forelse ($analyse->competences_manquantes ?? [] as $skill)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">{{ $skill }}</span>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">Aucune compétence manquante.</p>
                        @endforelse
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Points forts</h3>
                        <ul class="list-disc list-inside text-sm text-gray-700 dark:text-gray-300 space-y-1">
                            @foreach ($analyse->points_forts ?? [] as $point)
                                <li>{{ $point }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Lacunes</h3>
                        <ul class="list-disc list-inside text-sm text-gray-700 dark:text-gray-300 space-y-1">
                            @foreach ($analyse->lacunes ?? [] as $lacune)
                                <li>{{ $lacune }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Expérience</h3>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $analyse->annees_experience }} an(s)</p>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Niveau d'études</h3>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $analyse->niveau_etudes ?? '—' }}</p>
                    </div>
                </div>

                @if ($analyse->langues)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Langues</h3>
                        <div class="flex flex-wrap gap-4">
                            @foreach ($analyse->langues as $langue)
                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                    <strong>{{ $langue['langue'] }}</strong> : {{ $langue['niveau'] }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif

            <div class="flex items-center gap-4">
                <a href="{{ route('analyses.index', $analyse->offre) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">&larr; Toutes les analyses</a>
                <a href="{{ route('offres.show', $analyse->offre) }}" class="text-sm text-gray-600 dark:text-gray-400 underline underline-offset-2 hover:text-gray-900 dark:hover:text-gray-100">Voir l'offre</a>
            </div>
        </div>
    </div>
</x-app-layout>
