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
            AK <span class="text-brand">Recrutement</span>
        </a>
    </div>
    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-brand flex items-center justify-center text-sm font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="hidden sm:block">
                <p class="text-sm font-semibold leading-none">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500">Administrateur</p>
            </div>
        </div>
    </div>
</nav>

<div class="flex flex-1 overflow-hidden">

{{-- Fond sombre derrière le menu sur mobile --}}
<div data-menu-overlay="menu-lateral" class="hidden fixed inset-0 top-14 z-20 bg-black/50 md:hidden"></div>

{{-- SIDEBAR (menu coulissant sur mobile, fixe sur ordinateur) --}}
<aside id="menu-lateral" data-menu-hidden-class="-translate-x-full" class="w-56 bg-black border-r border-darkBorder flex flex-col shrink-0 sidebar-scroll overflow-y-auto fixed top-14 bottom-0 left-0 z-30 -translate-x-full transition-transform duration-200 md:static md:translate-x-0">

    <div class="p-3 pt-4">
        <p class="text-xs font-bold text-gray-600 uppercase tracking-widest px-2 mb-2">Principal</p>
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('admin.dashboard') ? 'bg-orange-950 text-brand font-semibold' : 'text-gray-400 hover:text-white hover:bg-darkCard' }}">
            <x-icone nom="tableau-de-bord" /> Tableau de bord
        </a>
        <a href="{{ route('admin.users') }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('admin.users') ? 'bg-orange-950 text-brand font-semibold' : 'text-gray-400 hover:text-white hover:bg-darkCard' }}">
            <span class="flex items-center gap-3"><x-icone nom="utilisateurs" /> Utilisateurs</span>
            <span class="bg-brand text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ \App\Models\User::count() }}</span>
        </a>
        <a href="{{ route('admin.offres') }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('admin.offres') ? 'bg-orange-950 text-brand font-semibold' : 'text-gray-400 hover:text-white hover:bg-darkCard' }}">
            <span class="flex items-center gap-3"><x-icone nom="offres" /> Offres</span>
            <span class="bg-brand text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ \App\Models\Offre::count() }}</span>
        </a>
        <a href="{{ route('admin.candidatures') }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('admin.candidatures') ? 'bg-orange-950 text-brand font-semibold' : 'text-gray-400 hover:text-white hover:bg-darkCard' }}">
            <span class="flex items-center gap-3"><x-icone nom="candidatures" /> Candidatures</span>
            <span class="bg-brand text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ \App\Models\Candidature::count() }}</span>
        </a>
    </div>

    <div class="p-3">
        <p class="text-xs font-bold text-gray-600 uppercase tracking-widest px-2 mb-2">Communication</p>
        <a href="{{ route('admin.contacts') }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('admin.contacts*') ? 'bg-orange-950 text-brand font-semibold' : 'text-gray-400 hover:text-white hover:bg-darkCard' }}">
            <span class="flex items-center gap-3"><x-icone nom="enveloppe" /> Messages contact</span>
            @php $contacts = \App\Models\Contact::where('lu', false)->count(); @endphp
            @if($contacts > 0)
                <span class="bg-brand text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $contacts }}</span>
            @endif
        </a>
    </div>

    <div class="p-3">
        <p class="text-xs font-bold text-gray-600 uppercase tracking-widest px-2 mb-2">Analytique</p>
        <a href="{{ route('admin.stats') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('admin.stats') ? 'bg-orange-950 text-brand font-semibold' : 'text-gray-400 hover:text-white hover:bg-darkCard' }}">
            <x-icone nom="statistiques" /> Statistiques
        </a>
    </div>

    <div class="p-3">
        <p class="text-xs font-bold text-gray-600 uppercase tracking-widest px-2 mb-2">Compte</p>
        <a href="{{ route('admin.parametres') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('admin.parametres') ? 'bg-orange-950 text-brand font-semibold' : 'text-gray-400 hover:text-white hover:bg-darkCard' }}">
            <x-icone nom="parametres" /> Paramètres
        </a>
    </div>

    <div class="mt-auto p-3 border-t border-darkBorder">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-500 hover:text-red-400 w-full">
                <x-icone nom="deconnexion" /> Déconnexion
            </button>
        </form>
    </div>
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
