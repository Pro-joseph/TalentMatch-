<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $conversation->title }}
            </h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('agent-conversations.index') }}" class="text-sm text-gray-600 dark:text-gray-400 underline underline-offset-2 hover:text-gray-900 dark:hover:text-gray-100">Toutes les conversations</a>
                <form method="POST" action="{{ route('agent-conversations.destroy', $conversation->id) }}" onsubmit="return confirm('Supprimer cette conversation ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div id="messages-container" class="p-6 space-y-4 max-h-[60vh] overflow-y-auto">
                    @forelse ($messages as $msg)
                        <div class="flex {{ $msg->role === 'user' ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[75%] {{ $msg->role === 'user' ? 'bg-indigo-100 dark:bg-indigo-900/40 text-gray-900 dark:text-gray-100' : 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100' }} rounded-lg px-4 py-3">
                                @if ($msg->role === 'assistant' && $msg->tool_calls !== '[]')
                                    <div class="mb-2 text-xs text-gray-500 dark:text-gray-400">
                                        🔧 Appels d'outils effectués
                                    </div>
                                @endif
                                <div class="text-sm whitespace-pre-wrap">{{ $msg->content }}</div>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ \Carbon\Carbon::parse($msg->created_at)->format('H:i') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">Aucun message. Commencez la conversation.</p>
                    @endforelse
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 p-4">
                    <form method="POST" action="{{ route('agent-conversations.message', $conversation->id) }}" class="flex items-center gap-3">
                        @csrf
                        <input type="text" name="message" required placeholder="Posez une question à l'assistant RH..." autocomplete="off"
                            class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
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
