<x-app-layout>
    @php
        $pendingAnalyses = $analyses->where('status', 'pending');
        $pendingIds = $pendingAnalyses->pluck('id');
        $statusUrls = $pendingIds->mapWithKeys(fn ($id) => [$id => route('analyses.status', $id)]);
    @endphp

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('offres.index') }}" class="btn-ghost p-1.5 -ml-1.5 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="page-title truncate">{{ $offre->titre }}</h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('offres.edit', $offre) }}" class="btn-secondary btn-sm">Modifier</a>
                <form method="POST" action="{{ route('offres.destroy', $offre) }}" onsubmit="return confirm('Supprimer cette offre ? Cette action est irréversible.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger btn-sm">Supprimer</button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8 md:py-10"
         x-data="{
             statusUrls: @json($statusUrls),
             pendingIds: @json($pendingIds),
         }"
         x-init="
             if (pendingIds.length > 0) {
                 pendingIds.forEach(id => {
                     let url = statusUrls[id];
                     setInterval(async () => {
                         try {
                             let res = await fetch(url);
                             let data = await res.json();
                             if (data.status !== 'pending') window.location.reload();
                         } catch (e) {}
                     }, 4000);
                     try {
                         Echo.private('analyses.' + id)
                             .listen('.AnalysisCompleted', () => window.location.reload());
                     } catch (e) {
                         console.warn('Echo non disponible', e);
                     }
                 });
             }
         ">

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Offer criteria -->
            <div class="card !p-0 overflow-hidden">
                <div class="px-6 py-6 border-b border-slate-100">
                    <h2 class="font-display font-semibold text-slate-950">Critères</h2>
                </div>
                <div class="px-6 py-6 space-y-4">
                    <p class="text-sm text-slate-700 whitespace-pre-wrap leading-relaxed">{{ $offre->description }}</p>
                    <div class="flex flex-wrap gap-4 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="text-slate-400">Expérience min.</span>
                            <span class="font-medium text-slate-900">{{ $offre->experience_min }} an(s)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-slate-400">Compétences</span>
                            <div class="flex flex-wrap gap-1.5">
                                @forelse ($offre->competences_requises ?? [] as $skill)
                                    <span class="badge bg-slate-100 text-slate-600 border border-slate-200/60">{{ $skill }}</span>
                                @empty
                                    <span class="text-slate-400">—</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Candidates table + Submit CV -->
            <div class="card !p-0 overflow-visible">
                <div class="px-6 py-6 border-b border-slate-100 flex items-center justify-between gap-4">
                    <h2 class="font-display font-semibold text-slate-950">
                        Candidats analysés
                        @if ($analyses->isNotEmpty())
                            <span class="text-slate-400 font-normal">({{ $analyses->count() }})</span>
                        @endif
                    </h2>
                    <div class="flex items-center gap-2">
                        @if ($candidats->isNotEmpty())
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="btn-ghost btn-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Analyser
                                </button>
                                <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-lg border border-slate-200 py-2 z-20" x-cloak>
                                    <p class="px-4 py-2 text-xs font-medium text-slate-400 uppercase tracking-wider">Choisir un candidat</p>
                                    @foreach ($candidats as $c)
                                        <form method="POST" action="{{ route('analyses.store', $offre) }}">
                                            @csrf
                                            <input type="hidden" name="candidat_id" value="{{ $c->id }}">
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">{{ $c->nom }}</button>
                                        </form>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        @if ($analyses->count() >= 2)
                            <a href="{{ route('offres.comparer', $offre) }}" class="btn-ghost btn-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Comparer
                            </a>
                        @endif
                    </div>
                </div>

                @if ($analyses->isEmpty())

                    <div class="text-center py-16 px-6">
                        <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <p class="text-sm text-slate-500">Aucun candidat analysé pour cette offre.</p>
                        <p class="text-xs text-slate-400 mt-1">Les analyses apparaîtront ici une fois lancées.</p>
                    </div>

                @else

                    <!-- Desktop table -->
                    <div class="overflow-x-auto hidden sm:block">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-100">
                                    <th class="text-left text-xs font-medium text-slate-400 uppercase tracking-wider px-6 py-4">Candidat</th>
                                    <th class="text-left text-xs font-medium text-slate-400 uppercase tracking-wider px-6 py-4">Score</th>
                                    <th class="text-left text-xs font-medium text-slate-400 uppercase tracking-wider px-6 py-4">Recommandation</th>
                                    <th class="text-right px-6 py-4"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($analyses as $analyse)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-5">
                                            <a href="{{ route('analyses.show', $analyse) }}" class="font-medium text-slate-900 hover:text-slate-600 transition-colors">
                                                {{ $analyse->candidat?->nom ?? 'Candidat' }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-5 min-w-[160px]">
                                            <x-score-bar :score="$analyse->matching_score" />
                                        </td>
                                        <td class="px-6 py-5">
                                            <x-recommendation-badge :recommandation="$analyse->recommandation" />
                                        </td>
                                        <td class="px-6 py-5 text-right">
                                            <a href="{{ route('analyses.show', $analyse) }}" class="text-xs font-medium text-slate-500 hover:text-slate-900 transition-colors">
                                                Détail &rarr;
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile cards -->
                    <div class="sm:hidden divide-y divide-slate-100">
                        @foreach ($analyses as $analyse)
                            <a href="{{ route('analyses.show', $analyse) }}" class="block px-6 py-5 hover:bg-slate-50 transition-colors">
                                <div class="flex items-start justify-between mb-2">
                                    <span class="font-medium text-slate-900 text-sm">{{ $analyse->candidat?->nom ?? 'Candidat' }}</span>
                                    <x-recommendation-badge :recommandation="$analyse->recommandation" />
                                </div>
                                <x-score-bar :score="$analyse->matching_score" />
                            </a>
                        @endforeach
                    </div>

                @endif
            </div>

        </div>
    </div>
</x-app-layout>
