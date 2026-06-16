<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl font-semibold text-warm-900">Analyses &mdash; {{ $offre->titre }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if ($analyses->isEmpty())
                <div class="card-plain text-warm-500">
                    Aucune analyse pour cette offre.
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($analyses as $analyse)
                        <div class="card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-display text-lg font-semibold text-warm-900">
                                        {{ $analyse->candidat->nom }}
                                    </h3>
                                    <div class="flex flex-wrap items-center gap-4 mt-2 text-sm text-warm-500">
                                        <span class="font-medium {{ $analyse->matching_score >= 70 ? 'text-emerald-600' : ($analyse->matching_score >= 40 ? 'text-amber-600' : 'text-red-600') }}">
                                            Score: {{ $analyse->matching_score ?? '—' }}/100
                                        </span>
                                        <span>{{ $analyse->annees_experience }} an(s) exp.</span>
                                        <span>{{ $analyse->niveau_etudes }}</span>
                                        @if ($analyse->recommandation)
                                            <span class="recommendation-badge-{{ $analyse->recommandation->value === 'recommandé' ? 'green' : ($analyse->recommandation->value === 'réservé' ? 'amber' : 'red') }}">
                                                {{ $analyse->recommandation->label() }}
                                            </span>
                                        @endif
                                        <span class="text-xs uppercase {{ $analyse->status === 'done' ? 'text-emerald-600' : ($analyse->status === 'failed' ? 'text-red-600' : 'text-amber-600') }}">
                                            {{ $analyse->status }}
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ route('analyses.show', $analyse) }}" class="text-sm text-brand-600 hover:text-brand-700 underline underline-offset-2">
                                    Détail &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $analyses->links() }}
                </div>
            @endif

            <div>
                <a href="{{ route('offres.show', $offre) }}" class="text-sm text-warm-500 hover:text-brand-600 underline underline-offset-2">&larr; Retour à l'offre</a>
            </div>
        </div>
    </div>
</x-app-layout>
