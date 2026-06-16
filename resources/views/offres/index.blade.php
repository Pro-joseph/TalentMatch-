@extends('layouts.app')

@section('title', 'Mes offres — ' . config('app.name'))

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Mes offres d'emploi</h1>
        <a href="{{ route('offres.create') }}" class="inline-block px-5 py-1.5 bg-[#1b1b18] dark:bg-white text-white dark:text-[#1b1b18] rounded-sm text-sm">
            + Nouvelle offre
        </a>
    </div>

    @if ($offres->isEmpty())
        <p class="text-[#706f6c] dark:text-[#A1A09A]">Aucune offre pour le moment.</p>
    @else
        <div class="grid gap-4">
            @foreach ($offres as $offre)
                <a href="{{ route('offres.show', $offre) }}" class="block p-5 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm hover:border-[#1b1b18] dark:hover:border-white transition-colors">
                    <h2 class="text-lg font-medium">{{ $offre->titre }}</h2>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-1 line-clamp-2">{{ $offre->description }}</p>
                    <div class="flex items-center gap-3 mt-3 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                        <span>{{ $offre->experience_min }} an(s) min.</span>
                        <span>{{ count($offre->competences_requises ?? []) }} compétence(s)</span>
                        <span>{{ $offre->created_at->format('d/m/Y') }}</span>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $offres->links() }}
        </div>
    @endif
@endsection
