<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl font-semibold text-warm-900">Nouvelle offre d'emploi</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <form method="POST" action="{{ route('offres.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="titre" :value="__('Titre')" />
                        <x-text-input id="titre" class="block mt-1 w-full" type="text" name="titre" :value="old('titre')" required />
                        <x-input-error :messages="$errors->get('titre')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" rows="6" required
                            class="block mt-1 w-full border-warm-300 focus:border-brand-500 focus:ring-brand-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label :value="__('Compétences requises')" />
                        <div id="skills-wrapper" class="mt-1 space-y-2">
                            @if (old('competences_requises'))
                                @foreach (old('competences_requises') as $skill)
                                    <div class="flex items-center gap-2">
                                        <input type="text" name="competences_requises[]" value="{{ $skill }}" required
                                            class="w-full border-warm-300 focus:border-brand-500 focus:ring-brand-500 rounded-md shadow-sm">
                                        <button type="button" class="remove-skill text-red-500 hover:text-red-700 font-bold px-2">&times;</button>
                                    </div>
                                @endforeach
                            @else
                                <div class="flex items-center gap-2">
                                    <input type="text" name="competences_requises[]" required
                                        class="w-full border-warm-300 focus:border-brand-500 focus:ring-brand-500 rounded-md shadow-sm">
                                    <button type="button" class="remove-skill text-red-500 hover:text-red-700 font-bold px-2">&times;</button>
                                </div>
                            @endif
                        </div>
                        <button type="button" id="add-skill" class="text-sm text-brand-600 hover:text-brand-700 underline underline-offset-2 mt-2">+ Ajouter une compétence</button>
                        <x-input-error :messages="$errors->get('competences_requises')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="experience_min" :value="__('Expérience minimale (années)')" />
                        <x-text-input id="experience_min" class="block mt-1 w-full" type="number" name="experience_min" :value="old('experience_min', 0)" min="0" max="50" required />
                        <x-input-error :messages="$errors->get('experience_min')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Créer') }}</x-primary-button>
                        <a href="{{ route('offres.index') }}" class="text-sm text-warm-500 hover:text-brand-600 underline underline-offset-2">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('add-skill')?.addEventListener('click', function () {
            const wrapper = document.getElementById('skills-wrapper');
            const div = document.createElement('div');
            div.className = 'flex items-center gap-2';
            div.innerHTML = `
                <input type="text" name="competences_requises[]" required
                    class="w-full border-warm-300 focus:border-brand-500 focus:ring-brand-500 rounded-md shadow-sm">
                <button type="button" class="remove-skill text-red-500 hover:text-red-700 font-bold px-2">&times;</button>
            `;
            wrapper.appendChild(div);
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-skill')) {
                const row = e.target.closest('.flex');
                if (document.querySelectorAll('#skills-wrapper .flex').length > 1) {
                    row.remove();
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
