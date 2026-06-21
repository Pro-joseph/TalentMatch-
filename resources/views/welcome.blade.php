<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TalentMatch &mdash; Recrutement Intelligent par IA</title>

    @fonts

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="bg-slate-50 text-slate-950 font-sans antialiased min-h-screen flex flex-col">

    {{-- Header --}}
    <header class="w-full border-b border-slate-200 bg-white/80 backdrop-blur-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-sm bg-slate-900"></span>
                    <span class="font-display text-xl font-semibold text-slate-950 tracking-tight">TalentMatch</span>
                </a>

                <nav class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('offres.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-slate-950 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-900 transition">
                            Mes offres
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-sm text-slate-600 hover:text-slate-900 font-medium transition">
                            Connexion
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center px-4 py-2 bg-slate-950 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-800 transition">
                                Inscription
                            </a>
                        @endif
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    {{-- Hero --}}
    <section class="flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
            <div class="max-w-3xl mx-auto text-center">
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-900 mb-6">
                    Propulsé par l'IA générative
                </span>
                <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold text-slate-950 leading-tight">
                    Le recrutement qui<br>
                    <span class="text-slate-900">voit plus loin</span>
                </h1>
                <p class="mt-6 text-lg text-slate-600 leading-relaxed max-w-xl mx-auto">
                    Analysez les CV, mesurez la compatibilité avec vos offres, et recevez des recommandations
                    précises — le tout piloté par l'intelligence artificielle.
                </p>
                <div class="mt-10 flex items-center justify-center gap-4">
                    @auth
                        <a href="{{ route('offres.index') }}"
                            class="inline-flex items-center px-6 py-3 bg-slate-950 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-slate-800 transition">
                            Accéder à mes offres
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center px-6 py-3 bg-slate-950 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-slate-800 transition">
                            Commencer gratuitement
                        </a>
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center px-6 py-3 bg-white border border-slate-300 rounded-md font-semibold text-sm text-slate-700 uppercase tracking-widest hover:bg-slate-50 transition">
                            Se connecter
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="bg-white border-t border-slate-200 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-display text-3xl font-semibold text-slate-950">
                    Tout ce dont vous avez besoin pour recruter mieux
                </h2>
                <p class="mt-3 text-slate-500">
                    Une plateforme complète, du dépôt d'offre à la décision finale.
                </p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="card p-6">
                    <div
                        class="w-10 h-10 rounded-lg bg-slate-100 text-slate-900 flex items-center justify-center text-lg font-bold mb-4">
                        01</div>
                    <h3 class="font-display text-lg font-semibold text-slate-950 mb-2">Analyse intelligente</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Notre IA extrait et évalue les compétences de chaque candidat, puis calcule un score de
                        matching précis avec vos offres d'emploi.
                    </p>
                </div>
                <div class="card p-6">
                    <div
                        class="w-10 h-10 rounded-lg bg-slate-100 text-slate-900 flex items-center justify-center text-lg font-bold mb-4">
                        02</div>
                    <h3 class="font-display text-lg font-semibold text-slate-950 mb-2">Recommandations claires</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Chaque analyse produit une recommandation — recommandé, réservé ou non retenu — avec les
                        points forts et les lacunes identifiés.
                    </p>
                </div>
                <div class="card p-6">
                    <div
                        class="w-10 h-10 rounded-lg bg-slate-100 text-slate-900 flex items-center justify-center text-lg font-bold mb-4">
                        03</div>
                    <h3 class="font-display text-lg font-semibold text-slate-950 mb-2">Assistant RH</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Dialoguez avec un assistant IA qui connaît vos offres et candidats. Posez-lui vos
                        questions en langage naturel.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-slate-950 text-slate-100 py-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-display text-3xl font-semibold text-slate-100 mb-4">
                Prêt à transformer votre recrutement ?
            </h2>
            <p class="text-slate-300 mb-8">
                Rejoignez TalentMatch et laissez l'IA vous aider à trouver les meilleurs talents.
            </p>
            @auth
                <a href="{{ route('offres.index') }}"
                    class="inline-flex items-center px-6 py-3 bg-slate-100 border border-transparent rounded-md font-semibold text-sm text-slate-950 uppercase tracking-widest hover:bg-slate-200 transition">
                    Mes offres
                </a>
            @else
                <a href="{{ route('register') }}"
                    class="inline-flex items-center px-6 py-3 bg-slate-100 border border-transparent rounded-md font-semibold text-sm text-slate-950 uppercase tracking-widest hover:bg-slate-200 transition">
                @endauth
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-white border-t border-slate-200 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between text-sm text-slate-500">
            <span>&copy; {{ date('Y') }} TalentMatch. Tous droits réservés.</span>
            <span>v{{ app()->version() }}</span>
        </div>
    </footer>
</body>

</html>
