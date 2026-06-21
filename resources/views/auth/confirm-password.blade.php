<x-guest-layout>
    <h2 class="font-display text-2xl font-semibold text-warm-900 mb-1">{{ __('Confirmer le mot de passe') }}</h2>
    <p class="text-sm text-warm-500 mb-6">{{ __('Confirmez votre mot de passe pour continuer.') }}</p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div>
            <x-input-label for="password" :value="__('Mot de passe')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-6">
            <x-primary-button>
                {{ __('Confirmer') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
