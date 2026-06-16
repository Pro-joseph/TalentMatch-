<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $offre->titre }}
            </h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('analyses.index', $offre) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                    Analyses
                </a>
                <a href="{{ route('offres.edit', $offre) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                    Modifier
                </a>
                <form method="POST" action="{{ route('offres.destroy', $offre) }}" onsubmit="return confirm('Supprimer cette offre ?')">
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
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</h3>
                    <p class="mt-2 text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $offre->description }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Expérience minimale</h3>
                    <p class="mt-2 text-gray-900 dark:text-gray-100">{{ $offre->experience_min }} an(s)</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Compétences requises</h3>
                    <div class="flex flex-wrap gap-2">
                        @forelse ($offre->competences_requises ?? [] as $skill)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300">
                                {{ $skill }}
                            </span>
                        @empty
                            <span class="text-sm text-gray-500 dark:text-gray-400">Aucune compétence spécifiée.</span>
                        @endforelse
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Lancer une analyse</h3>
                    @php
                        $candidats = auth()->user()->candidats;
                    @endphp
                    @if ($candidats->isEmpty())
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Ajoutez d'abord des candidats.</p>
                        <a href="{{ route('candidats.create') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800">+ Ajouter un candidat</a>
                    @else
                        <form method="POST" action="{{ route('analyses.store', $offre) }}" class="flex items-center gap-3">
                            @csrf
                            <select name="candidat_id" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                @foreach ($candidats as $candidat)
                                    <option value="{{ $candidat->id }}">{{ $candidat->nom }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                                Analyser
                            </button>
                        </form>
                    @endif
                </div>

                <div class="pt-4">
                    <a href="{{ route('offres.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">&larr; Retour à mes offres</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
