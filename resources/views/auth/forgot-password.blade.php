<x-guest-layout>
    <h2 class="font-display text-2xl font-semibold text-warm-900 mb-1">Mot de passe oublié</h2>
    <p class="text-sm text-warm-500 mb-6">{{ __('Saisissez votre email et nous vous enverrons un lien de réinitialisation.') }}</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button>
                {{ __('Envoyer le lien') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
