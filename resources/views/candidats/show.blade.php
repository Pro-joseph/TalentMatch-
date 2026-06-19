<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('candidats.index') }}" class="btn-ghost p-1.5 -ml-1.5 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <h1 class="page-title">{{ $candidat->nom }}</h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('candidats.edit', $candidat) }}" class="btn-secondary btn-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Modifier
                </a>
                <form method="POST" action="{{ route('candidats.destroy', $candidat) }}" onsubmit="return confirm('Supprimer ce candidat ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger btn-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="pb-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="card p-8">
                <span class="divider-label mb-4">CV</span>
                <pre class="text-sm text-warm-800 whitespace-pre-wrap font-sans leading-relaxed mt-3">{{ $candidat->cv_texte }}</pre>
            </div>

            <div class="card p-8">
                <span class="divider-label mb-4">Analyses</span>

                @if ($candidat->analyses->isEmpty())
                    <p class="text-warm-500 text-sm mt-3">Aucune analyse pour ce candidat.</p>
                @else
                    <div class="space-y-3 mt-3">
                        @foreach ($candidat->analyses as $analyse)
                            <div class="flex items-center justify-between p-4 bg-warm-50 rounded-xl border border-warm-200/60 hover:bg-warm-100/50 transition-colors">
                                <div>
                                    <p class="font-medium text-warm-900">{{ $analyse->offre->titre }}</p>
                                    <div class="flex items-center gap-3 mt-1 text-xs text-warm-500">
                                        <span>Score: <strong class="text-warm-900">{{ $analyse->matching_score ?? '—' }}/100</strong></span>
                                        @if ($analyse->recommandation)
                                            <span class="badge-{{ $analyse->recommandation->value === 'recommandé' ? 'emerald' : ($analyse->recommandation->value === 'réservé' ? 'amber' : 'red') }}">
                                                {{ $analyse->recommandation->label() }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('analyses.show', $analyse) }}" class="btn-ghost btn-sm">
                                    Voir
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <a href="{{ route('candidats.index') }}" class="btn-ghost">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Retour à la liste
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
