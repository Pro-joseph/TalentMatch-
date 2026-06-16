<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Modifier &mdash; {{ $candidat->nom }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('candidats.update', $candidat) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nom</label>
                        <input type="text" name="nom" id="nom" value="{{ old('nom', $candidat->nom) }}" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('nom')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cv_texte" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Texte du CV</label>
                        <textarea name="cv_texte" id="cv_texte" rows="15" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono text-sm">{{ old('cv_texte', $candidat->cv_texte) }}</textarea>
                        @error('cv_texte')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                            Enregistrer
                        </button>
                        <a href="{{ route('candidats.show', $candidat) }}" class="text-sm text-gray-600 dark:text-gray-400 underline underline-offset-2 hover:text-gray-900 dark:hover:text-gray-100">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
