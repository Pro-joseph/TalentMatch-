<nav x-data="{ open: false }" class="bg-white border-b border-warm-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <x-application-logo />

                <div class="hidden sm:flex sm:items-center sm:gap-1">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Tableau de bord') }}
                    </x-nav-link>
                    <x-nav-link :href="route('offres.index')" :active="request()->routeIs('offres.*')">
                        {{ __('Offres') }}
                    </x-nav-link>
                    <x-nav-link :href="route('candidats.index')" :active="request()->routeIs('candidats.*')">
                        {{ __('Candidats') }}
                    </x-nav-link>
                    <x-nav-link :href="route('agent-conversations.index')" :active="request()->routeIs('agent-conversations.*')">
                        {{ __('Assistant RH') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-warm-700 hover:text-brand-600 transition-colors">
                            <span class="w-7 h-7 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center text-xs font-semibold">
                                {{ substr(Auth::user()->name, 0, 2) }}
                            </span>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profil') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Déconnexion') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2 text-warm-600 hover:text-brand-600 transition-colors">
                    <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': ! open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-warm-200">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Tableau de bord') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('offres.index')" :active="request()->routeIs('offres.*')">
                {{ __('Offres') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('candidats.index')" :active="request()->routeIs('candidats.*')">
                {{ __('Candidats') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('agent-conversations.index')" :active="request()->routeIs('agent-conversations.*')">
                {{ __('Assistant RH') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-2 border-t border-warm-200 px-4">
            <div class="flex items-center gap-3 mb-3">
                <span class="w-8 h-8 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center text-sm font-semibold">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </span>
                <div>
                    <div class="font-medium text-sm text-warm-900">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-warm-500">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profil') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Déconnexion') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
