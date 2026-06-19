<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('offres.show', $offre) }}" class="btn-ghost p-1.5 -ml-1.5 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="page-title">Comparaison — {{ $offre->titre }}</h1>
                <p class="page-subtitle">{{ $analyses->count() }} candidat(s) analysé(s) &middot; Triés par score</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 md:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if ($analyses->isEmpty())

                <div class="card !p-0 overflow-hidden">
                    <div class="text-center py-16 px-6">
                        <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-sm text-slate-500">Aucune analyse disponible pour la comparaison.</p>
                        <p class="text-xs text-slate-400 mt-1">Soumettez des CV pour lancer les analyses.</p>
                    </div>
                </div>

            @else

                <!-- Desktop table -->
                <div class="card !p-0 overflow-hidden hidden md:block">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-100">
                                    <th class="text-left text-xs font-medium text-slate-400 uppercase tracking-wider px-6 py-6 w-12">#</th>
                                    <th class="text-left text-xs font-medium text-slate-400 uppercase tracking-wider px-6 py-6">Candidat</th>
                                    <th class="text-left text-xs font-medium text-slate-400 uppercase tracking-wider px-6 py-6 min-w-[120px]">Score</th>
                                    <th class="text-left text-xs font-medium text-slate-400 uppercase tracking-wider px-6 py-6">Exp.</th>
                                    <th class="text-left text-xs font-medium text-slate-400 uppercase tracking-wider px-6 py-6">Études</th>
                                    <th class="text-left text-xs font-medium text-slate-400 uppercase tracking-wider px-6 py-6">Compétences</th>
                                    <th class="text-left text-xs font-medium text-slate-400 uppercase tracking-wider px-6 py-6">Forces</th>
                                    <th class="text-left text-xs font-medium text-slate-400 uppercase tracking-wider px-6 py-6">Recommandation</th>
                                    <th class="text-right px-6 py-6"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($analyses as $i => $analyse)
                                    @php $isTop = $i < $topCount; @endphp
                                    <tr class="hover:bg-slate-50/50 transition-colors {{ $isTop ? 'bg-emerald-50/40' : '' }}">
                                        <td class="px-6 py-6">
                                            @if ($isTop)
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">{{ $i + 1 }}</span>
                                            @else
                                                <span class="text-slate-400 text-xs font-medium">{{ $i + 1 }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-6">
                                            <span class="font-medium text-slate-900">{{ $analyse->candidat?->nom ?? 'Candidat' }}</span>
                                        </td>
                                        <td class="px-6 py-6">
                                            <x-score-bar :score="$analyse->matching_score" />
                                        </td>
                                        <td class="px-6 py-6 text-slate-700">
                                            {{ $analyse->annees_experience ?? '—' }} <span class="text-xs text-slate-400">ans</span>
                                        </td>
                                        <td class="px-6 py-6 text-slate-700 max-w-[140px] truncate">
                                            {{ $analyse->niveau_etudes ?? '—' }}
                                        </td>
                                        <td class="px-6 py-6">
                                            <div class="flex flex-wrap gap-1 max-w-[200px]">
                                                @forelse (array_slice($analyse->competences_extraites ?? [], 0, 3) as $skill)
                                                    <span class="badge bg-slate-100 text-slate-600 border border-slate-200/60 text-xs">{{ $skill }}</span>
                                                @empty
                                                    <span class="text-slate-400 text-xs">—</span>
                                                @endforelse
                                                @if (count($analyse->competences_extraites ?? []) > 3)
                                                    <span class="text-xs text-slate-400">+{{ count($analyse->competences_extraites) - 3 }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-6 max-w-[200px]">
                                            @forelse (array_slice($analyse->points_forts ?? [], 0, 2) as $point)
                                                <span class="text-xs text-slate-600 block truncate">{{ $point }}</span>
                                            @empty
                                                <span class="text-slate-400 text-xs">—</span>
                                            @endforelse
                                        </td>
                                        <td class="px-6 py-6">
                                            <x-recommendation-badge :recommandation="$analyse->recommandation" />
                                        </td>
                                        <td class="px-6 py-6 text-right">
                                            <a href="{{ route('analyses.show', $analyse) }}" class="text-xs font-medium text-slate-500 hover:text-slate-900 transition-colors">
                                                Détail &rarr;
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile cards -->
                <div class="md:hidden space-y-3">
                    @foreach ($analyses as $i => $analyse)
                        @php $isTop = $i < $topCount; @endphp
                        <div class="card !p-0 overflow-hidden {{ $isTop ? 'ring-2 ring-emerald-200' : '' }}">
                            <div class="flex items-center justify-between px-6 py-6 border-b border-slate-100">
                                <div class="flex items-center gap-2">
                                    @if ($isTop)
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">{{ $i + 1 }}</span>
                                    @else
                                        <span class="text-xs font-medium text-slate-400">{{ $i + 1 }}</span>
                                    @endif
                                    <h3 class="font-display font-semibold text-slate-950 text-sm">{{ $analyse->candidat?->nom ?? 'Candidat' }}</h3>
                                </div>
                                <x-recommendation-badge :recommandation="$analyse->recommandation" />
                            </div>
                            <div class="px-6 py-6 space-y-4">
                                <x-score-bar :score="$analyse->matching_score" />
                                <div class="grid grid-cols-2 gap-3 text-xs">
                                    <div>
                                        <span class="text-slate-400">Expérience</span>
                                        <p class="font-medium text-slate-700">{{ $analyse->annees_experience ?? '—' }} ans</p>
                                    </div>
                                    <div>
                                        <span class="text-slate-400">Études</span>
                                        <p class="font-medium text-slate-700 truncate">{{ $analyse->niveau_etudes ?? '—' }}</p>
                                    </div>
                                </div>
                                @if ($analyse->competences_extraites)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach (array_slice($analyse->competences_extraites, 0, 4) as $skill)
                                            <span class="badge bg-slate-100 text-slate-600 border border-slate-200/60 text-xs">{{ $skill }}</span>
                                        @endforeach
                                        @if (count($analyse->competences_extraites) > 4)
                                            <span class="text-xs text-slate-400">+{{ count($analyse->competences_extraites) - 4 }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="px-6 py-4 border-t border-slate-100 flex justify-between">
                                <a href="{{ route('analyses.show', $analyse) }}" class="text-xs font-medium text-slate-500 hover:text-slate-900 transition-colors">
                                    Analyse complète &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Verdict IA -->
                <div x-data="comparisonVerdict({{ $analyses->pluck('id')->toJson() }})" class="card !p-0 overflow-hidden">
                    <div class="px-6 py-6 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="font-display font-semibold text-slate-950">Verdict comparatif IA</h2>
                        <button
                            @click="generate"
                            :disabled="loading"
                            class="btn-primary btn-sm"
                            x-text="loading ? 'Analyse en cours…' : 'Générer le verdict'"
                        ></button>
                    </div>
                    <div class="px-6 py-6">
                        <template x-if="!verdict && !loading && !error">
                                <div class="flex items-start gap-3 text-sm text-slate-500">
                                <svg class="w-5 h-5 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                                <p>Cliquez sur « Générer le verdict » pour obtenir une analyse comparative IA de tous les candidats.</p>
                            </div>
                        </template>
                        <template x-if="loading">
                            <div class="flex items-center gap-3 text-sm text-amber-700">
                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                <p>Génération du verdict comparatif en cours…</p>
                            </div>
                        </template>
                        <template x-if="error">
                            <div class="flex items-start gap-3 text-sm text-red-700">
                                <svg class="w-5 h-5 shrink-0 mt-0.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <p class="font-medium">Erreur</p>
                                    <p class="mt-1 text-red-600" x-text="error"></p>
                                </div>
                            </div>
                        </template>
                        <template x-if="verdict">
                            <div class="space-y-5">
                                <div class="flex flex-wrap gap-3">
                                    <template x-for="(rec, i) in verdict.rankings" :key="i">
                                        <div class="flex items-center gap-2 px-3 py-2 rounded-lg"
                                            :class="i < 4 ? 'bg-emerald-50 text-emerald-800' : 'bg-slate-50 text-slate-600'">
                                            <span class="text-xs font-bold"
                                                :class="i < 4 ? 'text-emerald-600' : 'text-slate-400'"
                                                x-text="`#${i + 1}`"></span>
                                            <span class="text-sm font-medium" x-text="rec.nom"></span>
                                            <span class="text-xs" :class="i < 4 ? 'text-emerald-600' : 'text-slate-500'"
                                                x-text="`${rec.score}%`"></span>
                                        </div>
                                    </template>
                                </div>
                                <div class="text-sm text-slate-700 leading-relaxed" x-text="verdict.analyse"></div>
                                <div x-show="verdict.recommandation">
                                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-2">Recommandation</p>
                                    <p class="text-sm text-slate-700 leading-relaxed" x-text="verdict.recommandation"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Back -->
                <div class="pt-4">
                    <a href="{{ route('offres.show', $offre) }}" class="btn-ghost btn-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Retour à l'offre
                    </a>
                </div>

            @endif

        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('comparisonVerdict', (analyseIds) => ({
                loading: false,
                verdict: null,
                error: null,
                generate() {
                    this.loading = true;
                    this.error = null;
                    this.verdict = null;

                    fetch('{{ route("offres.comparer.verdict", $offre) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({ analyse_ids: analyseIds }),
                    })
                    .then(r => {
                        if (!r.ok) return r.json().then(e => { throw new Error(e.error || 'Erreur serveur'); });
                        return r.json();
                    })
                    .then(data => {
                        this.verdict = data;
                        this.loading = false;
                    })
                    .catch(err => {
                        this.error = err.message;
                        this.loading = false;
                    });
                },
            }));
        });
    </script>
    @endpush
</x-app-layout>
