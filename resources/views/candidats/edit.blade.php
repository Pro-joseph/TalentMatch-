<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl font-semibold text-warm-900">Modifier &mdash; {{ $candidat->nom }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <form method="POST" action="{{ route('candidats.update', $candidat) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="nom" :value="__('Nom')" />
                        <x-text-input id="nom" class="block mt-1 w-full" type="text" name="nom" :value="old('nom', $candidat->nom)" required />
                        <x-input-error :messages="$errors->get('nom')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="cv_texte" :value="__('Texte du CV')" />
                        <textarea id="cv_texte" name="cv_texte" rows="15" required
                            class="block mt-1 w-full border-warm-300 focus:border-brand-500 focus:ring-brand-500 rounded-md shadow-sm font-mono text-sm">{{ old('cv_texte', $candidat->cv_texte) }}</textarea>
                        <x-input-error :messages="$errors->get('cv_texte')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Enregistrer') }}</x-primary-button>
                        <a href="{{ route('candidats.show', $candidat) }}" class="text-sm text-warm-500 hover:text-brand-600 underline underline-offset-2">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
