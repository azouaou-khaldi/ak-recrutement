<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', "AK Recrutement")</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light text-gray-900 min-h-screen">

    <nav class="flex items-center justify-between px-6 py-4 border-b border-darkBorder bg-dark">
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-2xl font-bold">
            <span class="text-white">AK</span><span class="text-brand"> Recrutement</span>
        </a>
        <div class="hidden md:flex items-center gap-8 font-medium text-gray-300">
            <a href="{{ route('home') }}" class="hover:text-brand transition">Accueil</a>
            <a href="{{ route('offres.index') }}" class="hover:text-brand transition">Offres</a>
            <a href="{{ route('contact') }}" class="hover:text-brand transition">Contact</a>
        </div>
        <x-burger controls="menu-mobile" />
        <div class="hidden md:flex items-center gap-3">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700">Admin</a>
                @endif
                <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg bg-brand text-white font-semibold hover:bg-brandDark">Tableau de bord</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="px-4 py-2 rounded-lg border border-gray-600 text-gray-200 font-semibold hover:border-brand hover:text-brand">Déconnexion</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="px-5 py-2 rounded-lg border border-gray-600 text-gray-200 font-semibold hover:border-brand hover:text-brand">Connexion</a>
                <a href="{{ route('register') }}" class="px-5 py-2 rounded-lg bg-brand text-white font-semibold hover:bg-brandDark">S'inscrire</a>
            @endauth
        </div>
    </nav>

    {{-- Menu mobile (affiché par le bouton burger, voir resources/js/menu.js) --}}
    <div id="menu-mobile" class="hidden md:hidden bg-dark border-b border-darkBorder px-6 pb-6 pt-2">
        <div class="flex flex-col gap-1 font-medium text-gray-300">
            <a href="{{ route('home') }}" class="py-2 hover:text-brand transition">Accueil</a>
            <a href="{{ route('offres.index') }}" class="py-2 hover:text-brand transition">Offres</a>
            <a href="{{ route('contact') }}" class="py-2 hover:text-brand transition">Contact</a>
        </div>
        <div class="flex flex-col gap-3 mt-4">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-lg bg-red-600 text-white font-semibold text-center hover:bg-red-700">Admin</a>
                @endif
                <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg bg-brand text-white font-semibold text-center hover:bg-brandDark">Tableau de bord</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full px-4 py-2 rounded-lg border border-gray-600 text-gray-200 font-semibold hover:border-brand hover:text-brand">Déconnexion</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="px-5 py-2 rounded-lg border border-gray-600 text-gray-200 font-semibold text-center hover:border-brand hover:text-brand">Connexion</a>
                <a href="{{ route('register') }}" class="px-5 py-2 rounded-lg bg-brand text-white font-semibold text-center hover:bg-brandDark">S'inscrire</a>
            @endauth
        </div>
    </div>

    @if (session('success'))
    <div class="max-w-3xl mx-auto mt-4 px-4">
        <div class="bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <span class="text-lg">✓</span> {{ session('success') }}
        </div>
    </div>
@endif

    @if (session('error'))
        <div class="max-w-3xl mx-auto mt-4 px-4">
            <div class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <footer class="bg-dark border-t border-darkBorder text-white px-6 py-12">
        <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-8">
            <div>
                <p class="text-xl font-bold">AK <span class="text-brand">Recrutement</span></p>
                <p class="mt-2 text-gray-200">Votre partenaire de confiance pour le recrutement et l'emploi.</p>
            </div>
            <div>
                <p class="font-bold text-brand mb-2">Contact</p>
                <p class="text-gray-200">📧 azouaoukhaldi07@gmail.com</p>
                <p class="text-gray-200">📞 +33 7 73 45 10 85</p>
                <p class="text-gray-200">📍 11-13 rue d'Estrées, 75007 Paris</p>
            </div>
        </div>
        <p class="text-center text-gray-200 mt-8 text-sm">&copy; {{ date('Y') }} AK Recrutement. Tous droits réservés.</p>
    </footer>
</body>
</html>
