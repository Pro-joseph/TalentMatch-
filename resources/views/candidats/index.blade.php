<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-display text-2xl font-semibold text-warm-900">Candidats</h2>
            <a href="{{ route('candidats.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition ease-in-out duration-150">
                + Nouveau candidat
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if ($candidats->isEmpty())
                <div class="card-plain text-warm-500">
                    Aucun candidat pour le moment.
                </div>
            @else
                <div class="grid gap-4">
                    @foreach ($candidats as $candidat)
                        <a href="{{ route('candidats.show', $candidat) }}" class="card hover:border-brand-300 transition-all">
                            <h3 class="font-display text-lg font-semibold text-warm-900">{{ $candidat->nom }}</h3>
                            <p class="text-sm text-warm-500 mt-1 line-clamp-2">{{ Str::limit($candidat->cv_texte, 200) }}</p>
                            <p class="text-xs text-warm-400 mt-2">{{ $candidat->created_at->format('d/m/Y') }}</p>
                        </a>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $candidats->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
