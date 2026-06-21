<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('agent-conversations.index') }}" class="btn-ghost p-1.5 -ml-1.5 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="page-title truncate">{{ $conversation->title }}</h1>
            </div>
            <form method="POST" action="{{ route('agent-conversations.destroy', $conversation->id) }}"
                onsubmit="return confirm('Supprimer cette conversation ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger btn-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Supprimer
                </button>
            </form>
        </div>
    </x-slot>

    <div class="pb-16" x-data="{ loading: false, message: '' }"
         x-init="
             try {
                 Echo.private('conversations.{{ $conversation->id }}')
                     .listen('.ConversationMessageAdded', () => window.location.reload());
             } catch (e) {
                 console.warn('Echo non disponible', e);
             }
         ">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card !p-0 overflow-hidden flex flex-col h-[600px]">
                <div class="border-b border-slate-200/70 bg-slate-50/90 px-4 py-3 flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-700">{{ $conversation->title }}</span>
                    <span
                        class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/10 px-2.5 py-1 text-[11px] font-medium text-emerald-700">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        En ligne
                    </span>
                </div>

                <div id="messages-container"
                    class="flex-1 min-h-0 overflow-x-hidden overflow-y-auto bg-[#f1f1f1] scroll-smooth">
                    <div class="chat-container">
                        @forelse ($messages as $msg)
                            @if ($msg->role === 'assistant')
                                <div class="message received animate-slide-up opacity-0"
                                    style="animation-fill-mode: forwards; animation-duration: 0.3s">
                                    @if ($msg->tool_calls !== '[]')
                                        <div class="text-[11px] text-slate-400 mb-1">🔧 Appels d'outils effectués</div>
                                    @endif
                                    <div>{!! str($msg->content)->markdown() !!}</div>
                                    <span
                                        class="time">{{ \Carbon\Carbon::parse($msg->created_at)->format('H:i') }}</span>
                                </div>
                            @else
                                <div class="message sent animate-slide-up opacity-0"
                                    style="animation-fill-mode: forwards; animation-duration: 0.3s">
                                    <p>{{ $msg->content }}</p>
                                    <span
                                        class="time">{{ \Carbon\Carbon::parse($msg->created_at)->format('H:i') }}</span>
                                </div>
                            @endif
                        @empty
                            <div class="text-center py-12">
                                <div
                                    class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                    </svg>
                                </div>
                                <p class="text-slate-500 text-sm">Commencez la conversation avec l'assistant RH.</p>
                            </div>
                        @endforelse

                        <div x-show="loading" x-transition:enter="transition ease-golden duration-200"
                            class="message received">
                            <div class="flex items-center gap-1.5 py-1">
                                <span class="w-2 h-2 rounded-full bg-slate-300 animate-bounce"
                                    style="animation-delay: 0s"></span>
                                <span class="w-2 h-2 rounded-full bg-slate-300 animate-bounce"
                                    style="animation-delay: 0.15s"></span>
                                <span class="w-2 h-2 rounded-full bg-slate-300 animate-bounce"
                                    style="animation-delay: 0.3s"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-200/60 p-4 bg-white">
                    <form method="POST" action="{{ route('agent-conversations.message', $conversation->id) }}"
                        @submit="loading = true; $nextTick(() => { container?.scrollTo(0, container.scrollHeight); })"
                        class="flex items-center gap-3">
                        @csrf
                        <div class="relative flex-1">
                            <input type="text" name="message" x-model="message" required
                                placeholder="Posez une question à l'assistant RH..." autocomplete="off"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm
                                       placeholder:text-slate-400
                                       focus:border-slate-300 focus:ring-2 focus:ring-slate-200/50 focus:outline-none
                                       transition-all duration-200">
                            <button type="button" @click="message = ''" x-show="message.length > 0"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-medium text-sm
                                   bg-slate-900 text-white hover:bg-slate-800 active:bg-slate-950
                                   shadow-sm hover:shadow-md transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19V5m0 0l-7 7m7-7l7 7" />
                            </svg>
                            Envoyer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const container = document.getElementById('messages-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        </script>
    @endpush
</x-app-layout>
