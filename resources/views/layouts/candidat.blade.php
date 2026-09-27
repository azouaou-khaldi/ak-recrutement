<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AK Recrutement')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { height: 100vh; display: flex; flex-direction: column; overflow: hidden; }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #333; border-radius: 2px; }
    </style>
</head>
<body class="fond-clair bg-light text-gray-900" style="--color-light: #efefec">

{{-- NAVBAR --}}
<nav class="bg-dark border-b border-darkBorder px-6 h-14 flex items-center justify-between shrink-0 z-10">
    <div class="flex items-center gap-2">
        <x-burger controls="menu-lateral" class="-ml-2" />
        <a href="{{ route('home') }}" class="text-xl font-extrabold">
            <span class="text-white">AK</span> <span class="text-brandVif">Recrutement</span>
        </a>
    </div>
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-brand flex items-center justify-center text-sm font-bold text-white">
            {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 2)) }}
        </div>
        <div class="hidden sm:block">
            <p class="text-sm font-semibold leading-none text-white">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-300">Candidat</p>
        </div>
    </div>
</nav>

<div class="flex flex-1 overflow-hidden">

{{-- Fond sombre derrière le menu sur mobile --}}
<div data-menu-overlay="menu-lateral" class="hidden fixed inset-0 top-14 z-20 bg-black/50 md:hidden"></div>

{{-- SIDEBAR (menu coulissant sur mobile, fixe sur ordinateur) --}}
<aside id="menu-lateral" data-menu-hidden-class="-translate-x-full" class="w-56 bg-dark border-r border-darkBorder flex flex-col shrink-0 sidebar-scroll overflow-y-auto fixed top-14 bottom-0 left-0 z-30 -translate-x-full transition-transform duration-200 md:static md:translate-x-0">
    <nav aria-label="Menu de l'espace candidat" class="pt-2">
        <x-lateral.section titre="Mon espace">
            <x-lateral.lien :href="route('dashboard')" :actif="request()->routeIs('dashboard')" icone="tableau-de-bord">Tableau de bord</x-lateral.lien>
            <x-lateral.lien :href="route('candidat.candidatures')" :actif="request()->routeIs('candidat.candidatures')" icone="candidatures">Mes candidatures</x-lateral.lien>
            <x-lateral.lien :href="route('offres.index')" :actif="request()->routeIs('offres.*')" icone="offres">Offres d'emploi</x-lateral.lien>
            <x-lateral.lien :href="route('messages.index')" :actif="request()->routeIs('messages.*')" icone="messages">Messages</x-lateral.lien>
        </x-lateral.section>
        <x-lateral.section titre="Mon profil">
            <x-lateral.lien :href="route('candidat.profil')" :actif="request()->routeIs('candidat.profil*')" icone="profil">Mon profil</x-lateral.lien>
            <x-lateral.lien :href="route('candidat.parametres')" :actif="request()->routeIs('candidat.parametres')" icone="parametres">Paramètres</x-lateral.lien>
        </x-lateral.section>
    </nav>

    <x-lateral.deconnexion />
</aside>

{{-- CONTENT --}}
<main class="flex-1 overflow-y-auto p-4 md:p-6 bg-light">
    @if (session('success'))
        <div class="bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm flex items-center gap-2">
            <span class="text-lg">✓</span> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('error') }}</div>
    @endif
    @yield('content')
</main>

</div>
</body>
</html>