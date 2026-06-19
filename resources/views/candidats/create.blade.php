<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('candidats.index') }}" class="btn-ghost p-1.5 -ml-1.5 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <h1 class="page-title">Nouveau candidat</h1>
        </div>
    </x-slot>

    <div class="pb-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card p-8">
                <form method="POST" action="{{ route('candidats.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="nom" class="form-label">Nom</label>
                        <input id="nom" class="form-input-xl" type="text" name="nom" :value="old('nom')" required />
                        <x-input-error :messages="$errors->get('nom')" class="form-error" />
                    </div>

                    <div>
                        <label for="cv_texte" class="form-label">Texte du CV</label>
                        <textarea id="cv_texte" name="cv_texte" rows="15" required
                            class="form-input-xl font-mono text-sm resize-y">{{ old('cv_texte') }}</textarea>
                        <x-input-error :messages="$errors->get('cv_texte')" class="form-error" />
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <button type="submit" class="btn-primary">Enregistrer</button>
                        <a href="{{ route('candidats.index') }}" class="btn-ghost">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
