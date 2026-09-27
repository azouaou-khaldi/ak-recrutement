<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - AK Recrutement')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#f97316',
                        brandDark: '#ea6c0a',
                        dark: '#0d0d0d',
                        darkCard: '#1a1a1a',
                        darkBorder: '#2a2a2a',
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #333; border-radius: 2px; }
    </style>
</head>
<body class="bg-dark text-white" style="height:100vh;display:flex;flex-direction:column;overflow:hidden">

{{-- NAVBAR --}}
<nav class="bg-black border-b border-darkBorder px-6 h-14 flex items-center justify-between flex-shrink-0 z-10">
    <div class="flex items-center gap-3">
        <a href="{{ route('home') }}" class="text-xl font-extrabold">
            AK <span class="text-brand">Recrutement</span>
        </a>
    </div>
    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-brand flex items-center justify-center text-sm font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div>
                <p class="text-sm font-semibold leading-none">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500">Administrateur</p>
            </div>
        </div>
    </div>
</nav>

<div class="flex flex-1 overflow-hidden">

{{-- SIDEBAR --}}
<aside class="w-56 bg-black border-r border-darkBorder flex flex-col flex-shrink-0 sidebar-scroll overflow-y-auto">

    <div class="p-3 pt-4">
        <p class="text-xs font-bold text-gray-600 uppercase tracking-widest px-2 mb-2">Principal</p>
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('admin.dashboard') ? 'bg-orange-950 text-brand font-semibold' : 'text-gray-400 hover:text-white hover:bg-darkCard' }}">
            <span>📊</span> Tableau de bord
        </a>
        <a href="{{ route('admin.users') }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('admin.users') ? 'bg-orange-950 text-brand font-semibold' : 'text-gray-400 hover:text-white hover:bg-darkCard' }}">
            <span class="flex items-center gap-2"><span>👥</span> Utilisateurs</span>
            <span class="bg-brand text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ \App\Models\User::count() }}</span>
        </a>
        <a href="{{ route('admin.offres') }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('admin.offres') ? 'bg-orange-950 text-brand font-semibold' : 'text-gray-400 hover:text-white hover:bg-darkCard' }}">
            <span class="flex items-center gap-2"><span>💼</span> Offres</span>
            <span class="bg-brand text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ \App\Models\Offre::count() }}</span>
        </a>
        <a href="{{ route('admin.candidatures') }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('admin.candidatures') ? 'bg-orange-950 text-brand font-semibold' : 'text-gray-400 hover:text-white hover:bg-darkCard' }}">
            <span class="flex items-center gap-2"><span>📄</span> Candidatures</span>
            <span class="bg-brand text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ \App\Models\Candidature::count() }}</span>
        </a>
    </div>

    <div class="p-3">
        <p class="text-xs font-bold text-gray-600 uppercase tracking-widest px-2 mb-2">Modération</p>
        <a href="{{ route('admin.signalements') }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('admin.signalements') ? 'bg-orange-950 text-brand font-semibold' : 'text-gray-400 hover:text-white hover:bg-darkCard' }}">
            <span class="flex items-center gap-2"><span>🚨</span> Signalements</span>
            @php $signalements = \App\Models\Signalement::where('statut','en_attente')->count(); @endphp
            @if($signalements > 0)
                <span class="bg-red-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $signalements }}</span>
            @endif
        </a>
        <a href="{{ route('admin.approbations') }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('admin.approbations') ? 'bg-orange-950 text-brand font-semibold' : 'text-gray-400 hover:text-white hover:bg-darkCard' }}">
            <span class="flex items-center gap-2"><span>✅</span> Approbations</span>
            @php $approbations = \App\Models\Offre::where('approuvee', false)->where('active', true)->count(); @endphp
            @if($approbations > 0)
                <span class="bg-brand text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $approbations }}</span>
            @endif
        </a>
    </div>

    <div class="p-3">
        <p class="text-xs font-bold text-gray-600 uppercase tracking-widest px-2 mb-2">Communication</p>
        <a href="{{ route('admin.contacts') }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('admin.contacts') ? 'bg-orange-950 text-brand font-semibold' : 'text-gray-400 hover:text-white hover:bg-darkCard' }}">
            <span class="flex items-center gap-2"><span>✉️</span> Messages contact</span>
            @php $contacts = \App\Models\Contact::where('lu', false)->count(); @endphp
            @if($contacts > 0)
                <span class="bg-brand text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $contacts }}</span>
            @endif
        </a>
    </div>

    <div class="p-3">
        <p class="text-xs font-bold text-gray-600 uppercase tracking-widest px-2 mb-2">Analytique</p>
        <a href="{{ route('admin.stats') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('admin.stats') ? 'bg-orange-950 text-brand font-semibold' : 'text-gray-400 hover:text-white hover:bg-darkCard' }}">
            <span>📈</span> Statistiques
        </a>
    </div>

    <div class="p-3">
        <p class="text-xs font-bold text-gray-600 uppercase tracking-widest px-2 mb-2">Compte</p>
        <a href="{{ route('admin.parametres') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('admin.parametres') ? 'bg-orange-950 text-brand font-semibold' : 'text-gray-400 hover:text-white hover:bg-darkCard' }}">
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
<main class="flex-1 overflow-y-auto p-6">
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
