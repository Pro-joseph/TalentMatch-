<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('analyses.index', $analyse->offre) }}" class="btn-ghost p-1.5 -ml-1.5 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h1 class="page-title">{{ $analyse->candidat->nom }}</h1>
                    <p class="page-subtitle">{{ $analyse->offre->titre }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="pb-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6" x-data="{ tab: 'resume' }">
            @if ($analyse->status === 'failed')
                <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-200/60 rounded-xl text-red-700 text-sm">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    L'analyse a échoué : {{ $analyse->justification }}
                </div>
            @elseif ($analyse->status === 'pending')
                <div class="flex items-center gap-3 p-4 bg-amber-50 border border-amber-200/60 rounded-xl text-amber-700 text-sm">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse-soft"></span>
                    Analyse en cours... Revenez dans quelques instants.
                </div>
            @else
                <div class="card p-6 flex items-center gap-6">
                    <div class="shrink-0">
                        <div class="w-20 h-20 rounded-xl flex items-center justify-center text-2xl font-bold
                            {{ $analyse->matching_score >= 70 ? 'bg-emerald-50 text-emerald-700' : ($analyse->matching_score >= 40 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700') }}">
                            {{ $analyse->matching_score }}%
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1">
                            <h3 class="font-display text-lg font-semibold text-warm-900">Score de matching</h3>
                            @if ($analyse->recommandation)
                                <span class="badge-{{ $analyse->recommandation->value === 'recommandé' ? 'emerald' : ($analyse->recommandation->value === 'réservé' ? 'amber' : 'red') }}">
                                    {{ $analyse->recommandation->label() }}
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-warm-600 italic">{{ $analyse->justification }}</p>
                    </div>
                </div>

                <div class="tab-bar" role="tablist">
                    <button @click="tab = 'resume'" :class="tab === 'resume' ? 'tab tab-active' : 'tab'">
                        Résumé
                    </button>
                    <button @click="tab = 'profil'" :class="tab === 'profil' ? 'tab tab-active' : 'tab'">
                        Profil candidat
                    </button>
                </div>

                <div x-show="tab === 'resume'" x-transition:enter="transition ease-golden duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="card p-6">
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="divider-label">Points forts</span>
                            </div>
                            <ul class="space-y-2">
                                @forelse ($analyse->points_forts ?? [] as $point)
                                    <li class="flex items-start gap-2 text-sm text-warm-700">
                                        <span class="mt-0.5 w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span>
                                        {{ $point }}
                                    </li>
                                @empty
                                    <li class="text-sm text-warm-500">Aucun point fort identifié.</li>
                                @endforelse
                            </ul>
                        </div>

                        <div class="card p-6">
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="divider-label">Lacunes</span>
                            </div>
                            <ul class="space-y-2">
                                @forelse ($analyse->lacunes ?? [] as $lacune)
                                    <li class="flex items-start gap-2 text-sm text-warm-700">
                                        <span class="mt-0.5 w-1.5 h-1.5 rounded-full bg-red-300 shrink-0"></span>
                                        {{ $lacune }}
                                    </li>
                                @empty
                                    <li class="text-sm text-warm-500">Aucune lacune identifiée.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <div class="card p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            <span class="divider-label">Compétences manquantes</span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @forelse ($analyse->competences_manquantes ?? [] as $skill)
                                <span class="badge-red">{{ $skill }}</span>
                            @empty
                                <p class="text-sm text-warm-500">Le candidat possède toutes les compétences demandées.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'profil'" x-transition:enter="transition ease-golden duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="card p-6">
                            <span class="divider-label mb-4">Compétences</span>
                            <div class="flex flex-wrap gap-2">
                                @forelse ($analyse->competences_extraites ?? [] as $skill)
                                    <span class="badge-brand">{{ $skill }}</span>
                                @empty
                                    <p class="text-sm text-warm-500">Aucune compétence listée.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="card p-6">
                            <span class="divider-label mb-4">Expérience</span>
                            <p class="stat mt-3">{{ $analyse->annees_experience }} <span class="text-base font-normal text-warm-400">ans</span></p>
                        </div>

                        <div class="card p-6">
                            <span class="divider-label mb-4">Niveau d'études</span>
                            <p class="stat mt-3">{{ $analyse->niveau_etudes ?? '—' }}</p>
                        </div>

                        @if ($analyse->langues)
                            <div class="card p-6">
                                <span class="divider-label mb-4">Langues</span>
                                <div class="flex flex-wrap gap-x-6 gap-y-2 mt-3">
                                    @foreach ($analyse->langues as $langue)
                                        <span class="text-sm text-warm-700">
                                            @if (is_string($langue))
                                                {{ $langue }}
                                            @else
                                                <strong>{{ $langue['langue'] }}</strong> : {{ $langue['niveau'] }}
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <div class="flex items-center gap-4 pt-2">
                <a href="{{ route('analyses.index', $analyse->offre) }}" class="btn-ghost">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Toutes les analyses
                </a>
                <span class="text-warm-200">·</span>
                <a href="{{ route('offres.show', $analyse->offre) }}" class="btn-ghost">Voir l'offre</a>
            </div>
        </div>
    </div>
</x-app-layout>
