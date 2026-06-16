<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-display text-2xl font-semibold text-warm-900">Assistant RH</h2>
            <a href="{{ route('agent-conversations.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition ease-in-out duration-150">
                + Nouvelle conversation
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if ($conversations->isEmpty())
                <div class="card-plain text-center py-12">
                    <p class="text-warm-500 mb-4">Aucune conversation pour le moment.</p>
                    <a href="{{ route('agent-conversations.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-700">
                        Démarrer une conversation
                    </a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($conversations as $conv)
                        <a href="{{ route('agent-conversations.show', $conv->id) }}" class="card hover:border-brand-300 transition-all block">
                            <h3 class="font-display font-semibold text-warm-900">{{ $conv->title }}</h3>
                            <p class="text-sm text-warm-500 mt-1">{{ $conv->updated_at->format('d/m/Y H:i') }}</p>
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
