<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Assistant RH
            </h2>
            <a href="{{ route('agent-conversations.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                + Nouvelle conversation
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if ($conversations->isEmpty())
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-12 text-center">
                    <p class="text-gray-500 dark:text-gray-400 mb-4">Aucune conversation pour le moment.</p>
                    <a href="{{ route('agent-conversations.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                        Démarrer une conversation
                    </a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($conversations as $conv)
                        <a href="{{ route('agent-conversations.show', $conv->id) }}" class="block bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ $conv->title }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $conv->updated_at->format('d/m/Y H:i') }}</p>
                        </a>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $conversations->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
