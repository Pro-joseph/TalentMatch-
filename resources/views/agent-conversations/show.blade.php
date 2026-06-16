<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-display text-2xl font-semibold text-warm-900">{{ $conversation->title }}</h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('agent-conversations.index') }}" class="text-sm text-warm-500 hover:text-brand-600 underline underline-offset-2">Toutes les conversations</a>
                <form method="POST" action="{{ route('agent-conversations.destroy', $conversation->id) }}" onsubmit="return confirm('Supprimer cette conversation ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm border border-warm-200">
                <div id="messages-container" class="p-6 space-y-4 max-h-[60vh] overflow-y-auto">
                    @forelse ($messages as $msg)
                        <div class="flex {{ $msg->role === 'user' ? 'justify-end' : 'justify-start' }} animate-fade-in">
                            <div class="max-w-[75%] {{ $msg->role === 'user' ? 'bg-brand-100 text-warm-900' : 'bg-warm-100 text-warm-900' }} rounded-lg px-4 py-3">
                                @if ($msg->role === 'assistant' && $msg->tool_calls !== '[]')
                                    <div class="mb-2 text-xs text-warm-500">
                                        Appels d'outils effectués
                                    </div>
                                @endif
                                <div class="text-sm whitespace-pre-wrap">{{ $msg->content }}</div>
                                <p class="text-xs text-warm-400 mt-1">{{ \Carbon\Carbon::parse($msg->created_at)->format('H:i') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-warm-500 py-8">Aucun message. Commencez la conversation.</p>
                    @endforelse
                </div>

                <div class="border-t border-warm-200 p-4">
                    <form method="POST" action="{{ route('agent-conversations.message', $conversation->id) }}" class="flex items-center gap-3">
                        @csrf
                        <input type="text" name="message" required placeholder="Posez une question à l'assistant RH..." autocomplete="off"
                            class="flex-1 border-warm-300 focus:border-brand-500 focus:ring-brand-500 rounded-md shadow-sm text-sm">
                        <x-primary-button>Envoyer</x-primary-button>
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
