@extends('layouts.app')

@section('title', 'Modifier — ' . $offre->titre)

@section('content')
    <h1 class="text-2xl font-semibold mb-6">Modifier l'offre</h1>

    <form method="POST" action="{{ route('offres.update', $offre) }}" class="max-w-2xl space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="titre" class="block text-sm font-medium mb-1">Titre</label>
            <input type="text" name="titre" id="titre" value="{{ old('titre', $offre->titre) }}" required
                class="w-full border border-[#e3e3e0] dark:border-[#3E3E3A] bg-transparent rounded-sm px-3 py-2 text-sm focus:outline-none focus:border-[#1b1b18] dark:focus:border-white">
            @error('titre')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium mb-1">Description</label>
            <textarea name="description" id="description" rows="6" required
                class="w-full border border-[#e3e3e0] dark:border-[#3E3E3A] bg-transparent rounded-sm px-3 py-2 text-sm focus:outline-none focus:border-[#1b1b18] dark:focus:border-white">{{ old('description', $offre->description) }}</textarea>
            @error('description')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Compétences requises</label>
            <div id="skills-wrapper">
                @foreach (old('competences_requises', $offre->competences_requises ?? []) as $skill)
                    <div class="flex items-center gap-2 mb-2">
                        <input type="text" name="competences_requises[]" value="{{ $skill }}" required
                            class="w-full border border-[#e3e3e0] dark:border-[#3E3E3A] bg-transparent rounded-sm px-3 py-2 text-sm focus:outline-none focus:border-[#1b1b18] dark:focus:border-white">
                        <button type="button" class="remove-skill text-red-600 text-sm">✕</button>
                    </div>
                @endforeach
            </div>
            <button type="button" id="add-skill" class="text-sm text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-white mt-1">+ Ajouter une compétence</button>
            @error('competences_requises')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="experience_min" class="block text-sm font-medium mb-1">Expérience minimale (années)</label>
            <input type="number" name="experience_min" id="experience_min" value="{{ old('experience_min', $offre->experience_min) }}" min="0" max="50" required
                class="w-full border border-[#e3e3e0] dark:border-[#3E3E3A] bg-transparent rounded-sm px-3 py-2 text-sm focus:outline-none focus:border-[#1b1b18] dark:focus:border-white">
            @error('experience_min')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-5 py-1.5 bg-[#1b1b18] dark:bg-white text-white dark:text-[#1b1b18] rounded-sm text-sm">Enregistrer</button>
            <a href="{{ route('offres.show', $offre) }}" class="text-sm text-[#706f6c] dark:text-[#A1A09A] underline underline-offset-2">Annuler</a>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    document.getElementById('add-skill')?.addEventListener('click', function () {
        const wrapper = document.getElementById('skills-wrapper');
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2 mb-2';
        div.innerHTML = `
            <input type="text" name="competences_requises[]" required
                class="w-full border border-[#e3e3e0] dark:border-[#3E3E3A] bg-transparent rounded-sm px-3 py-2 text-sm focus:outline-none focus:border-[#1b1b18] dark:focus:border-white">
            <button type="button" class="remove-skill text-red-600 text-sm">✕</button>
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
