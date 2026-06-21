<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('offres.index') }}" class="btn-ghost p-1.5 -ml-1.5 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="page-title">Nouvelle offre d'emploi</h1>
        </div>
    </x-slot>

    <div class="pb-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card p-6 sm:p-8">
                <form method="POST" action="{{ route('offres.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="titre" value="Titre" />
                        <input id="titre" class="form-input-xl mt-1.5" type="text" name="titre" :value="old('titre')" required autofocus />
                        <x-input-error :messages="$errors->get('titre')" class="mt-1.5" />
                    </div>

                    <div>
                        <x-input-label for="description" value="Description" />
                        <textarea id="description" name="description" rows="6" required
                            class="form-input-xl mt-1.5 resize-y">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-1.5" />
                    </div>

                    <div>
                        <x-input-label value="Compétences requises" />
                        <div class="mt-1.5">
                            <x-skill-input name="competences_requises" :existing="old('competences_requises', [])" />
                        </div>
                        <x-input-error :messages="$errors->get('competences_requises')" class="mt-1.5" />
                    </div>

                    <div>
                        <x-input-label for="experience_min" value="Expérience minimale" />
                        <select id="experience_min" name="experience_min" required
                            class="form-select mt-1.5">
                            @for ($i = 0; $i <= 15; $i++)
                                <option value="{{ $i }}" @selected(old('experience_min', 0) == $i)>
                                    {{ $i === 0 ? 'Aucune' : ($i === 1 ? '1 an' : $i . ' ans') }}
                                </option>
                            @endfor
                        </select>
                        <x-input-error :messages="$errors->get('experience_min')" class="mt-1.5" />
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="btn-primary">Créer l'offre</button>
                        <a href="{{ route('offres.index') }}" class="btn-ghost">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
