<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-display text-2xl font-semibold text-warm-900">{{ $candidat->nom }}</h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('candidats.edit', $candidat) }}" class="inline-flex items-center px-4 py-2 bg-white border border-warm-300 rounded-md font-semibold text-xs text-warm-700 uppercase tracking-widest shadow-sm hover:bg-warm-50 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Modifier
                </a>
                <form method="POST" action="{{ route('candidats.destroy', $candidat) }}" onsubmit="return confirm('Supprimer ce candidat ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="card">
                <h3 class="text-xs font-semibold text-warm-400 uppercase tracking-widest mb-2">CV</h3>
                <pre class="text-sm text-warm-800 whitespace-pre-wrap font-sans">{{ $candidat->cv_texte }}</pre>
            </div>

            <div class="card">
                <h3 class="text-xs font-semibold text-warm-400 uppercase tracking-widest mb-4">Analyses</h3>

                @if ($candidat->analyses->isEmpty())
                    <p class="text-warm-500 text-sm">Aucune analyse pour ce candidat.</p>
                @else
                    <div class="space-y-3">
                        @foreach ($candidat->analyses as $analyse)
                            <div class="flex items-center justify-between p-4 border border-warm-200 rounded-lg hover:bg-warm-50 transition-colors">
                                <div>
                                    <p class="font-medium text-warm-900">{{ $analyse->offre->titre }}</p>
                                    <div class="flex items-center gap-3 mt-1 text-xs text-warm-500">
                                        <span>Score: <strong>{{ $analyse->matching_score ?? '—' }}/100</strong></span>
                                        @if ($analyse->recommandation)
                                            <span class="recommendation-badge-{{ $analyse->recommandation->value === 'recommandé' ? 'green' : ($analyse->recommandation->value === 'réservé' ? 'amber' : 'red') }}">
                                                {{ $analyse->recommandation->label() }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('analyses.show', $analyse) }}" class="text-sm text-brand-600 hover:text-brand-700 underline underline-offset-2">
                                    Voir &rarr;
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <a href="{{ route('candidats.index') }}" class="text-sm text-warm-500 hover:text-brand-600 underline underline-offset-2">&larr; Retour à la liste</a>
            </div>
        </div>
    </div>
</x-app-layout>
