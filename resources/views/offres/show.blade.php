<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-display text-2xl font-semibold text-warm-900">{{ $offre->titre }}</h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('analyses.index', $offre) }}" class="inline-flex items-center px-4 py-2 bg-brand-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Analyses
                </a>
                <a href="{{ route('offres.edit', $offre) }}" class="inline-flex items-center px-4 py-2 bg-white border border-warm-300 rounded-md font-semibold text-xs text-warm-700 uppercase tracking-widest shadow-sm hover:bg-warm-50 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Modifier
                </a>
                <form method="POST" action="{{ route('offres.destroy', $offre) }}" onsubmit="return confirm('Supprimer cette offre ?')">
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
            <div class="card space-y-6">
                <div>
                    <h3 class="text-xs font-semibold text-warm-400 uppercase tracking-widest">Description</h3>
                    <p class="mt-2 text-warm-800 whitespace-pre-wrap">{{ $offre->description }}</p>
                </div>

                <div>
                    <h3 class="text-xs font-semibold text-warm-400 uppercase tracking-widest">Expérience minimale</h3>
                    <p class="mt-2 text-warm-800">{{ $offre->experience_min }} an(s)</p>
                </div>

                <div>
                    <h3 class="text-xs font-semibold text-warm-400 uppercase tracking-widest mb-2">Compétences requises</h3>
                    <div class="flex flex-wrap gap-2">
                        @forelse ($offre->competences_requises ?? [] as $skill)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-brand-100 text-brand-700">
                                {{ $skill }}
                            </span>
                        @empty
                            <span class="text-sm text-warm-500">Aucune compétence spécifiée.</span>
                        @endforelse
                    </div>
                </div>

                <div class="pt-4 border-t border-warm-200">
                    <h3 class="text-xs font-semibold text-warm-400 uppercase tracking-widest mb-4">Lancer une analyse</h3>
                    @php
                        $candidats = auth()->user()->candidats;
                    @endphp
                    @if ($candidats->isEmpty())
                        <p class="text-sm text-warm-500 mb-3">Ajoutez d'abord des candidats.</p>
                        <a href="{{ route('candidats.create') }}" class="text-sm text-brand-600 hover:text-brand-700 underline underline-offset-2">+ Ajouter un candidat</a>
                    @else
                        <form method="POST" action="{{ route('analyses.store', $offre) }}" class="flex items-center gap-3">
                            @csrf
                            <select name="candidat_id" class="rounded-md border-warm-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm">
                                @foreach ($candidats as $candidat)
                                    <option value="{{ $candidat->id }}">{{ $candidat->nom }}</option>
                                @endforeach
                            </select>
                            <x-primary-button>Analyser</x-primary-button>
                        </form>
                    @endif
                </div>

                <div class="pt-2">
                    <a href="{{ route('offres.index') }}" class="text-sm text-warm-500 hover:text-brand-600 underline underline-offset-2">&larr; Retour à mes offres</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
