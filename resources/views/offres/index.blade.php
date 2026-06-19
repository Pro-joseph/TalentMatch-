<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="page-title">Mes offres d'emploi</h1>
                <p class="page-subtitle">{{ $offres->total() }} offre(s) publiée(s)</p>
            </div>
            <a href="{{ route('offres.create') }}" class="btn-primary shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nouvelle offre
            </a>
        </div>
    </x-slot>

    <div class="pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($offres->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg class="w-7 h-7 text-warm-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="empty-state-title">Aucune offre pour le moment</h3>
                    <p class="empty-state-text">Créez votre première offre d'emploi pour commencer.</p>
                    <a href="{{ route('offres.create') }}" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Créer une offre
                    </a>
                </div>
            @else
                <div class="grid gap-3">
                    @foreach ($offres as $i => $offre)
                        <a href="{{ route('offres.show', $offre) }}"
                           class="card-hover flex items-start gap-4 animate-slide-up opacity-0"
                           style="animation-fill-mode: forwards; animation-delay: {{ $i * 0.04 }}s">
                            <div class="hidden sm:flex w-12 h-12 rounded-xl bg-brand-50 text-brand-600 items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-4">
                                    <h3 class="font-display text-lg font-semibold text-warm-900 truncate">{{ $offre->titre }}</h3>
                                    <span class="shrink-0 text-xs text-warm-400 mt-1">{{ $offre->created_at->format('d/m/Y') }}</span>
                                </div>
                                <p class="text-sm text-warm-500 mt-1 line-clamp-2">{{ $offre->description }}</p>
                                <div class="flex items-center gap-3 mt-3">
                                    <span class="badge-warm">{{ $offre->experience_min }} an(s) min.</span>
                                    <span class="badge-warm">{{ count($offre->competences_requises ?? []) }} compétence(s)</span>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-warm-300 shrink-0 mt-1 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $offres->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
