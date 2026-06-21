<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('agent-conversations.index') }}" class="btn-ghost p-1.5 -ml-1.5 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <h1 class="page-title">Nouvelle conversation</h1>
        </div>
    </x-slot>

    <div class="pb-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card p-8">
                <form method="POST" action="{{ route('agent-conversations.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="title" class="form-label">Sujet</label>
                        <input id="title" class="form-input-xl" type="text" name="title" :value="old('title')" required placeholder="ex: Analyse du profil de Jean Dupont" />
                        <x-input-error :messages="$errors->get('title')" class="form-error" />
                    </div>

                    <div>
                        <label for="message" class="form-label">Premier message</label>
                        <textarea id="message" name="message" rows="6" required placeholder="Ex: Quels sont les candidats recommandés pour le poste de Développeur Laravel ?"
                            class="form-input-xl resize-y">{{ old('message') }}</textarea>
                        <x-input-error :messages="$errors->get('message')" class="form-error" />
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            Démarrer
                        </button>
                        <a href="{{ route('agent-conversations.index') }}" class="btn-ghost">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
