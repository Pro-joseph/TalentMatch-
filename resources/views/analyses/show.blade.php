<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('analyses.index', $analyse->offre) }}" class="btn-ghost p-1.5 -ml-1.5 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="page-title">{{ $analyse->candidat?->nom ?? 'Candidat' }}</h1>
                    <p class="page-subtitle">{{ $analyse->offre->titre }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @include('analyses.partials.chat-modal', ['candidatNom' => $analyse->candidat?->nom ?? 'ce candidat'])
            </div>
        </div>
    </x-slot>

    <div class="py-8 md:py-10"
         x-data="{ status: '{{ $analyse->status }}', statusUrl: '{{ route('analyses.status', $analyse) }}' }"
         x-init="if (status === 'pending') {
             let poll = setInterval(async () => {
                 let res = await fetch(statusUrl);
                 let data = await res.json();
                 if (data.status !== 'pending') window.location.reload();
             }, 3000);
             try {
                 Echo.private('analyses.{{ $analyse->id }}')
                     .listen('.AnalysisCompleted', () => window.location.reload());
             } catch (e) {
                 console.warn('Echo non disponible, polling uniquement', e);
             }
         }">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if ($analyse->status === 'failed')

                <div class="card !p-0 overflow-hidden">
                    <div class="flex items-center gap-3 p-5 bg-red-50 border-b border-red-100">
                        <svg class="w-5 h-5 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm text-red-700">{{ $analyse->justification }}</p>
                    </div>
                </div>

            @elseif ($analyse->status === 'pending')

                <!-- Skeleton: Score + Recommendation -->
                <div class="card !p-0 overflow-hidden animate-pulse">
                    <div class="flex items-center gap-6 p-5 sm:p-6">
                        <div class="w-[72px] h-[72px] rounded-full bg-slate-200 shrink-0"></div>
                        <div class="flex-1 min-w-0 space-y-3">
                            <div class="h-5 bg-slate-200 rounded w-48"></div>
                            <div class="h-4 bg-slate-200 rounded w-full max-w-md"></div>
                        </div>
                    </div>
                </div>

                <!-- Skeleton: Extracted info -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 animate-pulse">
                    <div class="card !p-5 space-y-2">
                        <div class="h-3 bg-slate-200 rounded w-20"></div>
                        <div class="h-5 bg-slate-200 rounded w-16"></div>
                    </div>
                    <div class="card !p-5 space-y-2">
                        <div class="h-3 bg-slate-200 rounded w-20"></div>
                        <div class="h-5 bg-slate-200 rounded w-24"></div>
                    </div>
                    <div class="card !p-5 col-span-2 space-y-2">
                        <div class="h-3 bg-slate-200 rounded w-14"></div>
                        <div class="flex gap-3">
                            <div class="h-4 bg-slate-200 rounded w-16"></div>
                            <div class="h-4 bg-slate-200 rounded w-20"></div>
                            <div class="h-4 bg-slate-200 rounded w-14"></div>
                        </div>
                    </div>
                </div>

                <!-- Skeleton: Skills -->
                <div class="card !p-5 space-y-3 animate-pulse">
                    <div class="h-3 bg-slate-200 rounded w-36"></div>
                    <div class="flex gap-2">
                        <div class="h-7 bg-slate-200 rounded-full w-20"></div>
                        <div class="h-7 bg-slate-200 rounded-full w-28"></div>
                        <div class="h-7 bg-slate-200 rounded-full w-24"></div>
                        <div class="h-7 bg-slate-200 rounded-full w-16"></div>
                    </div>
                </div>

                <!-- Skeleton: Strengths / Lacunes / Missing -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 animate-pulse">
                    <div class="card !p-5 border-t-2 border-t-slate-200 space-y-3">
                        <div class="h-3 bg-slate-200 rounded w-24"></div>
                        <div class="space-y-2">
                            <div class="h-4 bg-slate-200 rounded w-full"></div>
                            <div class="h-4 bg-slate-200 rounded w-3/4"></div>
                            <div class="h-4 bg-slate-200 rounded w-5/6"></div>
                        </div>
                    </div>
                    <div class="card !p-5 border-t-2 border-t-slate-200 space-y-3">
                        <div class="h-3 bg-slate-200 rounded w-16"></div>
                        <div class="space-y-2">
                            <div class="h-4 bg-slate-200 rounded w-full"></div>
                            <div class="h-4 bg-slate-200 rounded w-2/3"></div>
                        </div>
                    </div>
                    <div class="card !p-5 border-t-2 border-t-slate-200 space-y-3">
                        <div class="h-3 bg-slate-200 rounded w-36"></div>
                        <div class="flex gap-2 flex-wrap">
                            <div class="h-6 bg-slate-200 rounded-full w-20"></div>
                            <div class="h-6 bg-slate-200 rounded-full w-28"></div>
                            <div class="h-6 bg-slate-200 rounded-full w-16"></div>
                        </div>
                    </div>
                </div>

                <!-- Skeleton: Justification -->
                <div class="card !p-5 space-y-2 animate-pulse">
                    <div class="h-3 bg-slate-200 rounded w-28 mb-3"></div>
                    <div class="space-y-2">
                        <div class="h-4 bg-slate-200 rounded w-full"></div>
                        <div class="h-4 bg-slate-200 rounded w-5/6"></div>
                        <div class="h-4 bg-slate-200 rounded w-4/6"></div>
                        <div class="h-4 bg-slate-200 rounded w-full"></div>
                        <div class="h-4 bg-slate-200 rounded w-3/4"></div>
                    </div>
                </div>

            @else

                <!-- Score + Recommendation -->
                <div class="card !p-0 overflow-hidden">
                    <div class="flex items-center gap-6 p-5 sm:p-6">
                        <x-score-ring :score="$analyse->matching_score" :size="72" />
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 flex-wrap">
                                <h2 class="font-display text-lg font-semibold text-slate-950">Score de matching</h2>
                                @if ($analyse->recommandation)
                                    <x-recommendation-badge :recommandation="$analyse->recommandation" />
                                @endif
                            </div>
                            <p class="text-sm text-slate-500 mt-1.5 italic leading-relaxed">{{ $analyse->justification }}</p>
                        </div>
                    </div>
                </div>

                <!-- Extracted info -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="card !p-5">
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Expérience</p>
                        <p class="stat mt-1.5">{{ $analyse->annees_experience ?? '—' }} <span class="text-sm text-slate-400 font-normal">ans</span></p>
                    </div>
                    <div class="card !p-5">
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Niveau d'études</p>
                        <p class="stat mt-1.5 text-base">{{ $analyse->niveau_etudes ?? '—' }}</p>
                    </div>
                    <div class="card !p-5 col-span-2">
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-2">Langues</p>
                        @if ($analyse->langues)
                            <div class="flex flex-wrap gap-x-4 gap-y-1">
                                @foreach ($analyse->langues as $langue)
                                    <span class="text-sm text-slate-700">
                                        @if (is_string($langue))
                                            {{ $langue }}
                                        @else
                                            <strong>{{ $langue['langue'] ?? '' }}</strong> {{ $langue['niveau'] ?? '' }}
                                        @endif
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-slate-400">—</p>
                        @endif
                    </div>
                </div>

                <!-- Skills extracted -->
                <div class="card !p-5">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-3">Compétences extraites</p>
                    <div class="flex flex-wrap gap-2">
                        @forelse ($analyse->competences_extraites ?? [] as $skill)
                            <span class="badge bg-slate-100 text-slate-700 border border-slate-200/60">{{ $skill }}</span>
                        @empty
                            <p class="text-sm text-slate-400">Aucune compétence listée.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Strengths / Weaknesses / Missing skills -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="card !p-5 border-t-2 border-t-emerald-400">
                        <p class="text-xs font-medium text-emerald-600 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Points forts
                        </p>
                        <ul class="space-y-2">
                            @forelse ($analyse->points_forts ?? [] as $point)
                                <li class="flex items-start gap-2 text-sm text-slate-700">
                                    <span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span>
                                    {{ $point }}
                                </li>
                            @empty
                                <li class="text-sm text-slate-400">Aucun point fort identifié.</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="card !p-5 border-t-2 border-t-amber-400">
                        <p class="text-xs font-medium text-amber-600 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Lacunes
                        </p>
                        <ul class="space-y-2">
                            @forelse ($analyse->lacunes ?? [] as $lacune)
                                <li class="flex items-start gap-2 text-sm text-slate-700">
                                    <span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-amber-300 shrink-0"></span>
                                    {{ $lacune }}
                                </li>
                            @empty
                                <li class="text-sm text-slate-400">Aucune lacune identifiée.</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="card !p-5 border-t-2 border-t-red-400">
                        <p class="text-xs font-medium text-red-600 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                            Compétences manquantes
                        </p>
                        <div class="flex flex-wrap gap-2">
                            @forelse ($analyse->competences_manquantes ?? [] as $skill)
                                <span class="badge bg-red-50 text-red-700 border border-red-200/60">{{ $skill }}</span>
                            @empty
                                <p class="text-sm text-slate-400">Toutes les compétences sont couvertes.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Justification -->
                <div class="card !p-5">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-3">Justification IA</p>
                    <div class="prose-custom">
                        {!! str($analyse->justification)->markdown() !!}
                    </div>
                </div>

            @endif

            <!-- Back links -->
            <div class="flex items-center gap-4 pt-2">
                <a href="{{ route('analyses.index', $analyse->offre) }}" class="btn-ghost btn-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Toutes les analyses
                </a>
                <span class="text-slate-200">·</span>
                <a href="{{ route('offres.show', $analyse->offre) }}" class="btn-ghost btn-sm">Voir l'offre</a>
            </div>

        </div>
    </div>
</x-app-layout>
