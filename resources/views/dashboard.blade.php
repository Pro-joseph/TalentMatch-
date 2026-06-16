<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl font-semibold text-warm-900">Tableau de bord</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <p class="text-warm-600">Bienvenue, <strong class="text-warm-900">{{ auth()->user()->name }}</strong>.</p>
                <p class="text-sm text-warm-500 mt-2">Utilisez la navigation pour gérer vos offres, candidats et analyses.</p>
            </div>
        </div>
    </div>
</x-app-layout>
