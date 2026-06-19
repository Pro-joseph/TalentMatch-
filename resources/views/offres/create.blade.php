<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('offres.index') }}" class="btn-ghost p-1.5 -ml-1.5 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <h1 class="page-title">Nouvelle offre d'emploi</h1>
        </div>
    </x-slot>

    <div class="pb-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card p-8">
                <form method="POST" action="{{ route('offres.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="titre" class="form-label">Titre</label>
                        <input id="titre" class="form-input-xl" type="text" name="titre" :value="old('titre')" required />
                        <x-input-error :messages="$errors->get('titre')" class="form-error" />
                    </div>

                    <div>
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" rows="6" required
                            class="form-input-xl resize-y">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="form-error" />
                    </div>

                    <div>
                        <label class="form-label">Compétences requises</label>
                        <div id="skills-wrapper" class="space-y-2">
                            @if (old('competences_requises'))
                                @foreach (old('competences_requises') as $skill)
                                    <div class="flex items-center gap-2">
                                        <input type="text" name="competences_requises[]" value="{{ $skill }}" required class="form-input-xl">
                                        <button type="button" class="remove-skill text-red-400 hover:text-red-600 font-bold px-2 transition-colors">&times;</button>
                                    </div>
                                @endforeach
                            @else
                                <div class="flex items-center gap-2">
                                    <input type="text" name="competences_requises[]" required class="form-input-xl">
                                    <button type="button" class="remove-skill text-red-400 hover:text-red-600 font-bold px-2 transition-colors">&times;</button>
                                </div>
                            @endif
                        </div>
                        <button type="button" id="add-skill" class="text-sm text-brand-600 hover:text-brand-700 font-medium mt-2 inline-flex items-center gap-1 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Ajouter une compétence
                        </button>
                        <x-input-error :messages="$errors->get('competences_requises')" class="form-error" />
                    </div>

                    <div>
                        <label for="experience_min" class="form-label">Expérience minimale (années)</label>
                        <input id="experience_min" class="form-input-xl" type="number" name="experience_min" :value="old('experience_min', 0)" min="0" max="50" required />
                        <x-input-error :messages="$errors->get('experience_min')" class="form-error" />
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <button type="submit" class="btn-primary">Créer</button>
                        <a href="{{ route('offres.index') }}" class="btn-ghost">Annuler</a>
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
                    class="form-input-xl">
                <button type="button" class="remove-skill text-red-400 hover:text-red-600 font-bold px-2 transition-colors">&times;</button>
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
