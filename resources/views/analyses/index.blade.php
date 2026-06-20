<x-app-layout>
    @php
        $pendingAnalyses = $analyses->where('status', 'pending');
        $pendingIds = $pendingAnalyses->pluck('id');
        $statusUrls = $pendingIds->mapWithKeys(fn ($id) => [$id => route('analyses.status', $id)]);
    @endphp

    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('offres.show', $offre) }}" class="btn-ghost p-1.5 -ml-1.5 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="page-title">Analyses</h1>
                <p class="page-subtitle">{{ $offre->titre }}</p>
            </div>
        </div>
    </x-slot>

    <div class="pb-16"
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if ($analyses->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg class="w-7 h-7 text-warm-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3 class="empty-state-title">Aucune analyse pour cette offre</h3>
                    <p class="empty-state-text">Lancez une analyse depuis la page de l'offre.</p>
                    <a href="{{ route('offres.show', $offre) }}" class="btn-primary">Voir l'offre</a>
                </div>
            @else
                <div class="grid gap-3">
                    @foreach ($analyses as $i => $analyse)
                        <a href="{{ route('analyses.show', $analyse) }}"
                           class="card-hover flex items-center gap-4 animate-slide-up opacity-0"
                           style="animation-fill-mode: forwards; animation-delay: {{ $i * 0.04 }}s">
                            @if ($analyse->status === 'pending')
                                <div class="w-14 h-14 rounded-xl flex items-center justify-center text-base font-bold shrink-0 bg-slate-100 text-slate-400 animate-pulse">
                                    <span class="text-xs">...</span>
                                </div>

                                <div class="flex-1 min-w-0 animate-pulse">
                                    <div class="flex items-center gap-3">
                                        <div class="h-5 bg-slate-200 rounded w-40"></div>
                                    </div>
                                    <div class="flex items-center gap-4 mt-1.5">
                                        <div class="h-4 bg-slate-200 rounded w-24"></div>
                                        <div class="h-4 bg-slate-200 rounded w-20"></div>
                                    </div>
                                </div>
                            @else
                                <div class="w-14 h-14 rounded-xl flex items-center justify-center text-base font-bold shrink-0
                                    {{ $analyse->matching_score >= 70 ? 'bg-emerald-50 text-emerald-700' : ($analyse->matching_score >= 40 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700') }}">
                                    {{ $analyse->matching_score ?? '—' }}
                                    <span class="text-xs font-normal ml-0.5">/100</span>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3">
                                        <h3 class="font-display font-semibold text-warm-900">{{ $analyse->candidat->nom }}</h3>
                                        @if ($analyse->recommandation)
                                            <span class="badge-{{ $analyse->recommandation->value === 'recommandé' ? 'emerald' : ($analyse->recommandation->value === 'réservé' ? 'amber' : 'red') }}">
                                                {{ $analyse->recommandation->label() }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-4 mt-1.5 text-sm text-warm-500">
                                        <span>{{ $analyse->annees_experience }} an(s) exp.</span>
                                        <span>{{ $analyse->niveau_etudes }}</span>
                                        @if ($analyse->status === 'pending')
                                            <span class="flex items-center gap-1.5 text-amber-600">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse-soft"></span>
                                                En cours
                                            </span>
                                        @elseif ($analyse->status === 'failed')
                                            <span class="text-red-600">Échec</span>
                                        @else
                                            <span class="text-emerald-600">Terminé</span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <svg class="w-5 h-5 text-warm-300 shrink-0 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $analyses->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
