<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl font-semibold text-warm-900">Nouvelle conversation</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <form method="POST" action="{{ route('agent-conversations.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="title" :value="__('Sujet')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required placeholder="ex: Analyse du profil de Jean Dupont" />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="message" :value="__('Premier message')" />
                        <textarea id="message" name="message" rows="6" required placeholder="Ex: Quels sont les candidats recommandés pour le poste de Développeur Laravel ?"
                            class="block mt-1 w-full border-warm-300 focus:border-brand-500 focus:ring-brand-500 rounded-md shadow-sm">{{ old('message') }}</textarea>
                        <x-input-error :messages="$errors->get('message')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Démarrer') }}</x-primary-button>
                        <a href="{{ route('agent-conversations.index') }}" class="text-sm text-warm-500 hover:text-brand-600 underline underline-offset-2">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
