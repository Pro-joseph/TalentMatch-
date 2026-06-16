<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-display text-2xl font-semibold text-warm-900">Analyse &mdash; {{ $analyse->candidat->nom }}</h2>
            <span class="text-sm text-warm-500 italic">{{ $analyse->offre->titre }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if ($analyse->status === 'failed')
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4 text-sm">
                    L'analyse a échoué : {{ $analyse->justification }}
                </div>
            @elseif ($analyse->status === 'pending')
                <div class="bg-amber-50 border border-amber-200 text-amber-700 rounded-lg p-4 text-sm">
                    Analyse en cours... Revenez dans quelques instants.
                </div>
            @else
                <div class="card">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <span class="text-4xl font-bold {{ $analyse->matching_score >= 70 ? 'text-emerald-600' : ($analyse->matching_score >= 40 ? 'text-amber-600' : 'text-red-600') }}">
                                {{ $analyse->matching_score }}%
                            </span>
                            <span class="text-sm text-warm-500 ml-2">matching</span>
                        </div>
                        @if ($analyse->recommandation)
                            <span class="recommendation-badge-{{ $analyse->recommandation->value === 'recommandé' ? 'green' : ($analyse->recommandation->value === 'réservé' ? 'amber' : 'red') }}">
                                {{ $analyse->recommandation->label() }}
                            </span>
                        @endif
                    </div>

                    <p class="text-warm-600 text-sm italic">{{ $analyse->justification }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="card">
                        <h3 class="text-xs font-semibold text-warm-400 uppercase tracking-widest mb-2">Compétences extraites</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($analyse->competences_extraites ?? [] as $skill)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-brand-100 text-brand-700">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="card">
                        <h3 class="text-xs font-semibold text-warm-400 uppercase tracking-widest mb-2">Compétences manquantes</h3>
                        @forelse ($analyse->competences_manquantes ?? [] as $skill)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">{{ $skill }}</span>
                        @empty
                            <p class="text-sm text-warm-500">Aucune compétence manquante.</p>
                        @endforelse
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="card">
                        <h3 class="text-xs font-semibold text-warm-400 uppercase tracking-widest mb-2">Points forts</h3>
                        <ul class="list-disc list-inside text-sm text-warm-700 space-y-1">
                            @foreach ($analyse->points_forts ?? [] as $point)
                                <li>{{ $point }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="card">
                        <h3 class="text-xs font-semibold text-warm-400 uppercase tracking-widest mb-2">Lacunes</h3>
                        <ul class="list-disc list-inside text-sm text-warm-700 space-y-1">
                            @foreach ($analyse->lacunes ?? [] as $lacune)
                                <li>{{ $lacune }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="card">
                        <h3 class="text-xs font-semibold text-warm-400 uppercase tracking-widest mb-2">Expérience</h3>
                        <p class="text-2xl font-semibold text-warm-900">{{ $analyse->annees_experience }} an(s)</p>
                    </div>

                    <div class="card">
                        <h3 class="text-xs font-semibold text-warm-400 uppercase tracking-widest mb-2">Niveau d'études</h3>
                        <p class="text-2xl font-semibold text-warm-900">{{ $analyse->niveau_etudes ?? '—' }}</p>
                    </div>
                </div>

                @if ($analyse->langues)
                    <div class="card">
                        <h3 class="text-xs font-semibold text-warm-400 uppercase tracking-widest mb-2">Langues</h3>
                        <div class="flex flex-wrap gap-4">
                            @foreach ($analyse->langues as $langue)
                                <span class="text-sm text-warm-700">
                                    <strong>{{ $langue['langue'] }}</strong> : {{ $langue['niveau'] }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif

            <div class="flex items-center gap-4">
                <a href="{{ route('analyses.index', $analyse->offre) }}" class="text-sm text-warm-500 hover:text-brand-600 underline underline-offset-2">&larr; Toutes les analyses</a>
                <a href="{{ route('offres.show', $analyse->offre) }}" class="text-sm text-warm-500 hover:text-brand-600 underline underline-offset-2">Voir l'offre</a>
            </div>
        </div>
    </div>
</x-app-layout>
