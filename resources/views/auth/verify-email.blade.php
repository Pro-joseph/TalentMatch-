<x-guest-layout>
    <h2 class="font-display text-2xl font-semibold text-warm-900 mb-1">{{ __('Vérification email') }}</h2>
    <p class="text-sm text-warm-500 mb-6">{{ __('Un lien de vérification vous a été envoyé par email.') }}</p>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg p-3">
            {{ __('Un nouveau lien de vérification a été envoyé.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>
                {{ __('Renvoyer le lien') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-warm-500 hover:text-brand-600 underline underline-offset-2">
                {{ __('Déconnexion') }}
            </button>
        </form>
    </div>
</x-guest-layout>
