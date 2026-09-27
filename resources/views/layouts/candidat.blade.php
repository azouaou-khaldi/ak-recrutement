<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AK Recrutement')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#f97316',
                        brandDark: '#ea6c0a',
                        light: '#efefec',
                        lightBorder: '#ececE6',
                        dark: '#0d0d0d',
                        darkCard: '#1a1a1a',
                        darkBorder: '#2a2a2a',
                    }
                }
            }
        }
    </script>
    <style>
        body { height: 100vh; display: flex; flex-direction: column; overflow: hidden; }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #333; border-radius: 2px; }
        .nav-icon { width: 19px; height: 19px; flex-shrink: 0; }
    </style>
</head>
<body class="bg-light text-gray-900">

{{-- NAVBAR --}}
<nav class="bg-dark border-b border-darkBorder px-6 h-14 flex items-center justify-between flex-shrink-0 z-10">
    <a href="{{ route('home') }}" class="text-xl font-extrabold">
        <span class="text-white">AK</span> <span class="text-brand">Recrutement</span>
    </a>
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-brand flex items-center justify-center text-sm font-bold text-white">
            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
        </div>
        <div>
            <p class="text-sm font-semibold leading-none text-white">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-300">Candidat</p>
        </div>
    </div>
</nav>

<div class="flex flex-1 overflow-hidden">

{{-- SIDEBAR --}}
<aside class="w-56 bg-dark border-r border-darkBorder flex flex-col flex-shrink-0 sidebar-scroll overflow-y-auto">
    <div class="p-3 pt-5 flex flex-col gap-1">

        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-orange-950 text-brand' : 'text-gray-200 hover:text-white hover:bg-darkCard' }}">
            <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13h4v8H3v-8Zm7-9h4v17h-4V4Zm7 5h4v12h-4V9Z"/>
            </svg>
            Tableau de bord
        </a>

        <a href="{{ route('candidat.profil') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('candidat.profil') ? 'bg-orange-950 text-brand' : 'text-gray-200 hover:text-white hover:bg-darkCard' }}">
            <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0"/>
            </svg>
            Mon profil
        </a>

        <a href="{{ route('candidat.candidatures') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('candidat.candidatures') ? 'bg-orange-950 text-brand' : 'text-gray-200 hover:text-white hover:bg-darkCard' }}">
            <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l4.414 4.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z"/>
            </svg>
            Mes candidatures
        </a>

        <a href="{{ route('offres.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('offres.index') ? 'bg-orange-950 text-brand' : 'text-gray-200 hover:text-white hover:bg-darkCard' }}">
            <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.1a2 2 0 0 1-2 2H5.75a2 2 0 0 1-2-2v-4.1M3.75 12h16.5M16.5 6.75V5.5A2.5 2.5 0 0 0 14 3h-4a2.5 2.5 0 0 0-2.5 2.5v1.25M8 12v3m8-3v3"/>
            </svg>
            Offres d'emploi
        </a>

        <a href="{{ route('messages.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('messages.*') ? 'bg-orange-950 text-brand' : 'text-gray-200 hover:text-white hover:bg-darkCard' }}">
            <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8m-8 4h5M21 12c0 4.556-4.03 8.25-9 8.25a9.76 9.76 0 0 1-3.253-.555L3 21l1.395-3.72C3.512 16.078 3 14.594 3 13c0-4.556 4.03-8.25 9-8.25s9 3.694 9 7.25Z"/>
            </svg>
            Messages
        </a>

        <div class="h-px bg-darkBorder my-2"></div>

        <a href="{{ route('candidat.parametres') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('candidat.parametres') ? 'bg-orange-950 text-brand' : 'text-gray-200 hover:text-white hover:bg-darkCard' }}">
            <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 0 0-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 0 0-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 0 0-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 0 0-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 0 0 1.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065Z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
            </svg>
            Paramètres
        </a>
    </div>

    <div class="mt-auto p-3 border-t border-darkBorder">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:text-red-400 hover:bg-darkCard transition w-full">
                <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H3"/>
                </svg>
                Déconnexion
            </button>
        </form>
    </div>
</aside>

{{-- CONTENT --}}
<main class="flex-1 overflow-y-auto p-6 bg-light">
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