<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - AK Recrutement')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #333; border-radius: 2px; }
    </style>
</head>
<body class="bg-dark text-white" style="height:100vh;display:flex;flex-direction:column;overflow:hidden">

{{-- NAVBAR --}}
<nav class="bg-black border-b border-darkBorder px-6 h-14 flex items-center justify-between shrink-0 z-10">
    <div class="flex items-center gap-3">
        <x-burger controls="menu-lateral" class="-ml-2" />
        <a href="{{ route('home') }}" class="text-xl font-extrabold">
            AK <span class="text-brandVif">Recrutement</span>
        </a>
    </div>
    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-brand flex items-center justify-center text-sm font-bold">
                {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="hidden sm:block">
                <p class="text-sm font-semibold leading-none">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-400">Administrateur</p>
            </div>
        </div>
    </div>
</nav>

<div class="flex flex-1 overflow-hidden">

{{-- Fond sombre derrière le menu sur mobile --}}
<div data-menu-overlay="menu-lateral" class="hidden fixed inset-0 top-14 z-20 bg-black/50 md:hidden"></div>

{{-- SIDEBAR (menu coulissant sur mobile, fixe sur ordinateur) --}}
<aside id="menu-lateral" data-menu-hidden-class="-translate-x-full" class="w-56 bg-black border-r border-darkBorder flex flex-col shrink-0 sidebar-scroll overflow-y-auto fixed top-14 bottom-0 left-0 z-30 -translate-x-full transition-transform duration-200 md:static md:translate-x-0">
    <nav aria-label="Menu d'administration" class="pt-2">
        <x-lateral.section titre="Principal">
            <x-lateral.lien :href="route('admin.dashboard')" :actif="request()->routeIs('admin.dashboard')" icone="tableau-de-bord">Tableau de bord</x-lateral.lien>
            <x-lateral.lien :href="route('admin.users')" :actif="request()->routeIs('admin.users*')" icone="utilisateurs" :badge="$compteurs['utilisateurs']" badge-label="utilisateurs">Utilisateurs</x-lateral.lien>
            <x-lateral.lien :href="route('admin.offres')" :actif="request()->routeIs('admin.offres*')" icone="offres" :badge="$compteurs['offres']" badge-label="offres">Offres</x-lateral.lien>
            <x-lateral.lien :href="route('admin.candidatures')" :actif="request()->routeIs('admin.candidatures')" icone="candidatures" :badge="$compteurs['candidatures']" badge-label="candidatures">Candidatures</x-lateral.lien>
        </x-lateral.section>
        <x-lateral.section titre="Communication">
            <x-lateral.lien :href="route('admin.contacts')" :actif="request()->routeIs('admin.contacts*')" icone="enveloppe" :badge="$compteurs['contactsNonLus']" badge-label="messages non lus">Messages contact</x-lateral.lien>
        </x-lateral.section>
        <x-lateral.section titre="Analytique">
            <x-lateral.lien :href="route('admin.stats')" :actif="request()->routeIs('admin.stats')" icone="statistiques">Statistiques</x-lateral.lien>
        </x-lateral.section>
        <x-lateral.section titre="Compte">
            <x-lateral.lien :href="route('admin.parametres')" :actif="request()->routeIs('admin.parametres')" icone="parametres">Paramètres</x-lateral.lien>
        </x-lateral.section>
    </nav>

    <x-lateral.deconnexion />
</aside>

{{-- CONTENT --}}
<main class="flex-1 overflow-y-auto p-4 md:p-6">
    @if (session('success'))
        <div class="bg-orange-950 border border-brand text-orange-200 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-950 border border-red-600 text-red-200 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('error') }}</div>
    @endif
    @yield('content')
</main>

</div>
</body>
</html>
