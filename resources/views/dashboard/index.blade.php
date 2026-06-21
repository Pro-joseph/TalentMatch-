<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="page-title">Tableau de bord</h1>
                <p class="page-subtitle">Vue d'ensemble de vos offres et candidats</p>
            </div>
            <a href="{{ route('offres.create') }}" class="btn-primary shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle offre
            </a>
        </div>
    </x-slot>

    <div class="py-8 md:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($offresCount === 0)

                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="empty-state-title">Bienvenue sur TalentMatch</h3>
                    <p class="empty-state-text">
                        Créez votre première offre d'emploi pour commencer à analyser des CV avec l'intelligence artificielle.
                    </p>
                    <a href="{{ route('offres.create') }}" class="btn-primary">
                        Créer une offre
                    </a>
                </div>

            @else

                <!-- Metrics -->
                <div class="grid grid-cols-4 gap-5 mb-10">
                    <div class="card !p-6">
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1.5">Offres</p>
                        <p class="stat">{{ $offresCount }}</p>
                    </div>
                    <div class="card !p-6">
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1.5">Candidats</p>
                        <p class="stat">{{ $candidatsCount }}</p>
                    </div>
                    <div class="card !p-6">
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1.5">Analyses</p>
                        <p class="stat">{{ $analysesCount }}</p>
                    </div>
                    <div class="card !p-6">
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1.5">Score moyen</p>
                        <p class="stat">{{ $avgScore ? number_format($avgScore, 0) : '—' }}<span class="text-sm text-slate-400 font-normal">%</span></p>
                    </div>
                </div>

                <!-- Recent offers -->
                <div class="card !p-0 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-6 border-b border-slate-100">
                        <h2 class="font-display font-semibold text-slate-950">Offres récentes</h2>
                        <a href="{{ route('offres.index') }}" class="text-xs font-medium text-slate-500 hover:text-slate-900 transition-colors">
                            Voir tout
                        </a>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @foreach ($recentOffers as $offre)
                            <a href="{{ route('offres.show', $offre) }}" class="flex items-center justify-between px-6 py-6 hover:bg-slate-50 transition-colors">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-slate-900 truncate">{{ $offre->titre }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $offre->candidats_count }} candidat(s)</p>
                                </div>
                                <svg class="w-4 h-4 text-slate-300 shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        @endforeach
                    </div>
                </div>

            @endif

        </div>
    </div>
</x-app-layout>
