<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('offres.index') }}" class="btn-ghost p-1.5 -ml-1.5 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <h1 class="page-title">{{ $offre->titre }}</h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('analyses.index', $offre) }}" class="btn-secondary btn-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Analyses
                </a>
                <a href="{{ route('offres.edit', $offre) }}" class="btn-secondary btn-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Modifier
                </a>
                <form method="POST" action="{{ route('offres.destroy', $offre) }}" onsubmit="return confirm('Supprimer cette offre ? Cette action est irréversible.')">
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
            <div class="card p-8 space-y-8">
                <div>
                    <span class="divider-label mb-4">Description</span>
                    <p class="mt-3 text-warm-700 whitespace-pre-wrap leading-relaxed">{{ $offre->description }}</p>
                </div>

                <div class="flex flex-wrap gap-6">
                    <div>
                        <span class="stat-label">Expérience min.</span>
                        <p class="mt-1.5 stat">{{ $offre->experience_min }} <span class="text-base font-normal text-warm-400">ans</span></p>
                    </div>
                </div>

                <div>
                    <span class="divider-label mb-4">Compétences requises</span>
                    <div class="flex flex-wrap gap-2 mt-3">
                        @forelse ($offre->competences_requises ?? [] as $skill)
                            <span class="badge-brand">{{ $skill }}</span>
                        @empty
                            <span class="text-sm text-warm-500">Aucune compétence spécifiée.</span>
                        @endforelse
                    </div>
                </div>

                <div class="divider"></div>

                @php
                    $candidats = auth()->user()->candidats;
                @endphp
                <div>
                    <span class="divider-label mb-4">Lancer une analyse</span>
                    @if ($candidats->isEmpty())
                        <div class="mt-3 p-4 bg-warm-50 rounded-xl text-sm text-warm-600">
                            <p class="mb-3">Ajoutez d'abord des candidats pour pouvoir les analyser.</p>
                            <a href="{{ route('candidats.create') }}" class="text-brand-600 hover:text-brand-700 font-medium inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Ajouter un candidat
                            </a>
                        </div>
                    @else
                        <form method="POST" action="{{ route('analyses.store', $offre) }}" class="mt-3 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                            @csrf
                            <div class="relative flex-1">
                                <select name="candidat_id" class="form-select">
                                    @foreach ($candidats as $candidat)
                                        <option value="{{ $candidat->id }}">{{ $candidat->nom }}</option>
                                    @endforeach
                                </select>
                                <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-warm-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                            <button type="submit" class="btn-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                Analyser
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
