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
                        light: '#f7f7f5',
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
    </style>
</head>
<body class="bg-light text-gray-900">

{{-- NAVBAR --}}
<nav class="bg-dark border-b border-darkBorder px-6 h-14 flex items-center justify-between flex-shrink-0 z-10">
    <a href="{{ route('home') }}" class="text-xl font-extrabold">
        <span class="text-white">AK</span> <span class="text-brand">Recrutement</span>
    </a>
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-sm font-bold text-white">
            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
        </div>
        <div>
            <p class="text-sm font-semibold leading-none text-white">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-400">Recruteur</p>
        </div>
    </div>
</nav>

<div class="flex flex-1 overflow-hidden">

{{-- SIDEBAR --}}
<aside class="w-56 bg-dark border-r border-darkBorder flex flex-col flex-shrink-0 sidebar-scroll overflow-y-auto">
    <div class="p-3 pt-4">
        <p class="text-xs font-bold text-gray-600 uppercase tracking-widest px-2 mb-2">Mon espace</p>
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('dashboard') ? 'bg-orange-950 text-brand font-semibold' : 'text-gray-400 hover:text-white hover:bg-darkCard' }}">
            <span>📊</span> Tableau de bord
        </a>
        <a href="{{ route('recruteur.offres') }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('recruteur.offres') ? 'bg-orange-950 text-brand font-semibold' : 'text-gray-400 hover:text-white hover:bg-darkCard' }}">
            <span class="flex items-center gap-2"><span>💼</span> Mes offres</span>
            @php $nbOffres = auth()->user()->offres()->count(); @endphp
            @if($nbOffres > 0)
                <span class="bg-brand text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $nbOffres }}</span>
            @endif
        </a>
        <a href="{{ route('recruteur.candidatures') }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('recruteur.candidatures') ? 'bg-orange-950 text-brand font-semibold' : 'text-gray-400 hover:text-white hover:bg-darkCard' }}">
            <span class="flex items-center gap-2"><span>👥</span> Candidatures</span>
            @php $nbCandidatures = \App\Models\Candidature::whereHas('offre', fn($q) => $q->where('user_id', auth()->id()))->where('statut','en_attente')->count(); @endphp
            @if($nbCandidatures > 0)
                <span class="bg-brand text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $nbCandidatures }}</span>
            @endif
        </a>
        <a href="{{ route('messages.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('messages.*') ? 'bg-orange-950 text-brand font-semibold' : 'text-gray-400 hover:text-white hover:bg-darkCard' }}">
            <span>💬</span> Messages
        </a>
    </div>
    <div class="p-3">
        <p class="text-xs font-bold text-gray-600 uppercase tracking-widest px-2 mb-2">Mon profil</p>
        <a href="{{ route('recruteur.profil') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('recruteur.profil*') ? 'bg-orange-950 text-brand font-semibold' : 'text-gray-400 hover:text-white hover:bg-darkCard' }}">
            <span>👤</span> Mon profil
        </a>
        <a href="{{ route('recruteur.parametres') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('recruteur.parametres') ? 'bg-orange-950 text-brand font-semibold' : 'text-gray-400 hover:text-white hover:bg-darkCard' }}">
            <span>⚙️</span> Paramètres
        </a>
    </div>
    <div class="mt-auto p-3 border-t border-darkBorder">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-500 hover:text-red-400 w-full">
                <span>🚪</span> Déconnexion
            </button>
        </form>
    </div>
</aside>

{{-- CONTENT --}}
<main class="flex-1 overflow-y-auto p-6 bg-light">
    @if (session('success'))
        <div class="bg-orange-50 border border-brand text-orange-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('error') }}</div>
    @endif
    @yield('content')
</main>

</div>
</body>
</html>
