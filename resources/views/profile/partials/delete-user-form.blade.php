<section class="space-y-6">
    <header>
        <h2 class="font-display text-lg font-semibold text-red-700">
            Supprimer le compte
        </h2>
        <p class="mt-1 text-sm text-warm-500">
            Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées.
        </p>
    </header>

    <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
        Supprimer le compte
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="font-display text-lg font-semibold text-warm-900">
                Êtes-vous sûr de vouloir supprimer votre compte ?
            </h2>
            <p class="mt-1 text-sm text-warm-500 mb-6">
                Veuillez entrer votre mot de passe pour confirmer.
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-3/4" placeholder="{{ __('Password') }}" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Annuler</x-secondary-button>
                <x-danger-button>Supprimer</x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
