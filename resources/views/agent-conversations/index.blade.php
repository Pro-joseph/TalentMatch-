<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="page-title">Agent conversationnel</h1>
                <p class="page-subtitle">Posez des questions sur vos offres et candidats</p>
            </div>
            <a href="{{ route('agent-conversations.create') }}" class="btn-primary shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle conversation
            </a>
        </div>
    </x-slot>

    <div class="pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($conversations->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <h3 class="empty-state-title">Aucune conversation</h3>
                    <p class="empty-state-text">Commencez une nouvelle conversation avec l'assistant RH.</p>
                    <a href="{{ route('agent-conversations.create') }}" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nouvelle conversation
                    </a>
                </div>
            @else
                <div class="grid gap-3 max-w-3xl">
                    @foreach ($conversations as $i => $conv)
                        <a href="{{ route('agent-conversations.show', $conv) }}"
                            class="card-hover w-full flex items-start gap-4 animate-slide-up opacity-0"
                            style="animation-fill-mode: forwards; animation-delay: {{ $i * 0.04 }}s">
                            <div
                                class="hidden sm:flex w-11 h-11 rounded-xl bg-slate-100 text-slate-700 items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-4">
                                    <h3 class="font-display font-semibold text-slate-950 line-clamp-2">
                                        {{ $conv->title }}
                                    </h3>
                                    <span
                                        class="shrink-0 text-xs text-slate-400 whitespace-nowrap">{{ $conv->messages_count }}
                                        msg · {{ $conv->updated_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-slate-500 mt-1 line-clamp-2">
                                    {{ $conv->messages->first()->content ?? 'Aucun message' }}</p>
                            </div>
                            <svg class="w-5 h-5 text-slate-300 shrink-0 mt-1 hidden sm:block" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
