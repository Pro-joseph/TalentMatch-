<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="page-title">Candidats</h1>
                <p class="page-subtitle">{{ $candidats->count() }} candidat(s)</p>
            </div>
            <a href="{{ route('candidats.create') }}" class="btn-primary shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nouveau candidat
            </a>
        </div>
    </x-slot>

    <div class="pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if ($candidats->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg class="w-7 h-7 text-warm-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <h3 class="empty-state-title">Aucun candidat pour le moment</h3>
                    <p class="empty-state-text">Ajoutez votre premier candidat pour commencer les analyses.</p>
                    <a href="{{ route('candidats.create') }}" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Ajouter un candidat
                    </a>
                </div>
            @else
                <div class="grid gap-3">
                    @foreach ($candidats as $i => $candidat)
                        <a href="{{ route('candidats.show', $candidat) }}"
                           class="card-hover flex items-center gap-4 animate-slide-up opacity-0"
                           style="animation-fill-mode: forwards; animation-delay: {{ $i * 0.04 }}s">
                            <div class="w-11 h-11 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center shrink-0 font-display font-semibold text-lg">
                                {{ substr($candidat->nom, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-display font-semibold text-warm-900">{{ $candidat->nom }}</h3>
                                <p class="text-sm text-warm-500 mt-0.5 line-clamp-1">{{ Str::limit($candidat->cv_texte, 120) }}</p>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                <span class="text-xs text-warm-400">{{ $candidat->created_at->format('d/m/Y') }}</span>
                            </div>
                            <svg class="w-5 h-5 text-warm-300 shrink-0 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
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
