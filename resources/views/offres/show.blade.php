@extends('layouts.app')

@section('title', $offre->titre . ' — ' . config('app.name'))

@section('content')
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ $offre->titre }}</h1>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-1">Créée le {{ $offre->created_at->format('d/m/Y') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('offres.edit', $offre) }}" class="px-4 py-1.5 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm text-sm hover:border-[#1b1b18] dark:hover:border-white">Modifier</a>
            <form method="POST" action="{{ route('offres.destroy', $offre) }}" onsubmit="return confirm('Supprimer cette offre ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-1.5 border border-red-500 text-red-600 rounded-sm text-sm hover:bg-red-50 dark:hover:bg-red-900/20">Supprimer</button>
            </form>
        </div>
    </div>

    <div class="grid gap-6 max-w-3xl">
        <div>
            <h2 class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Description</h2>
            <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $offre->description }}</p>
        </div>

        <div>
            <h2 class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Expérience minimale</h2>
            <p class="text-sm">{{ $offre->experience_min }} an(s)</p>
        </div>

        <div>
            <h2 class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Compétences requises</h2>
            <div class="flex flex-wrap gap-2">
                @forelse ($offre->competences_requises ?? [] as $skill)
                    <span class="px-3 py-1 text-xs border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-full">{{ $skill }}</span>
                @empty
                    <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Aucune compétence spécifiée.</span>
                @endforelse
            </div>
        </div>
    </div>

    <div class="mt-8">
        <a href="{{ route('offres.index') }}" class="text-sm text-[#706f6c] dark:text-[#A1A09A] underline underline-offset-2">← Retour à mes offres</a>
    </div>
@endsection
