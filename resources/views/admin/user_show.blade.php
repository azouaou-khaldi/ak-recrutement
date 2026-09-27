@extends('layouts.admin')
@section('title', 'Profil - ' . $user->name)

@section('content')

<div class="flex flex-wrap items-baseline gap-x-3 gap-y-1 mb-6">
    <a href="{{ route('admin.users') }}" class="inline-flex items-center min-h-11 sm:min-h-0 text-gray-400 hover:text-brand text-sm whitespace-nowrap">← Retour</a>
    <h1 class="text-2xl font-extrabold">Profil de <span class="text-brand">{{ $user->name }}</span></h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <div class="bg-darkCard border border-darkBorder rounded-xl p-6">
        @php $avatarColors = ['admin'=>'bg-red-600','recruteur'=>'bg-blue-600','candidat'=>'bg-brand']; @endphp
        <div class="w-16 h-16 rounded-full {{ $avatarColors[$user->role] }} flex items-center justify-center text-2xl font-bold mx-auto mb-4">
            {{ mb_strtoupper(mb_substr($user->name, 0, 2)) }}
        </div>
        <p class="text-center font-bold text-lg">{{ $user->name }}</p>
        <p class="text-center text-gray-400 text-sm">{{ $user->email }}</p>
        <div class="mt-4 space-y-2 text-sm">
            <div class="flex justify-between gap-3"><span class="text-gray-400">Rôle</span><span class="text-brand font-semibold">{{ ucfirst($user->role) }}</span></div>
            <div class="flex justify-between gap-3"><span class="text-gray-400">Inscrit le</span><span>{{ $user->created_at->format('d/m/Y') }}</span></div>
            <div class="flex justify-between gap-3"><span class="text-gray-400">Statut</span>
                <span class="{{ $user->suspendu ? 'text-red-400' : 'text-green-400' }} font-semibold">{{ $user->suspendu ? 'Suspendu' : 'Actif' }}</span>
            </div>
        </div>
        @if(!$user->isAdmin())
        <div class="mt-6 space-y-2">
            <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                @csrf @method('PATCH')
                <button class="w-full min-h-11 py-2 rounded-full text-sm font-semibold border {{ $user->suspendu ? 'border-green-700 text-green-400 hover:bg-green-950' : 'border-yellow-700 text-yellow-400 hover:bg-yellow-950' }} transition">
                    {{ $user->suspendu ? 'Réactiver le compte' : 'Suspendre le compte' }}
                </button>
            </form>
            <x-admin.suppression-utilisateur :user="$user" libelle="Supprimer le compte"
                classe-bouton="w-full min-h-11 py-2 rounded-full text-sm font-semibold border border-red-700 text-red-400 hover:bg-red-950 transition" />
        </div>
        @endif
    </div>

    <div class="lg:col-span-2 space-y-4 min-w-0">
        @if($user->isRecruteur())
        <div class="bg-darkCard border border-darkBorder rounded-xl p-5">
            <h2 class="font-bold mb-4">Offres publiées ({{ $user->offres->count() }})</h2>
            @forelse($user->offres as $offre)
            <div class="flex justify-between items-center gap-3 py-2 border-b border-darkBorder last:border-0">
                <div class="min-w-0">
                    <p class="text-sm font-semibold">{{ $offre->titre }}</p>
                    <p class="text-xs text-gray-400">{{ $offre->entreprise }} · {{ $offre->candidatures->count() }} candidature(s)</p>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full border shrink-0 whitespace-nowrap {{ $offre->active ? 'bg-green-950 text-green-400 border-green-700' : 'bg-gray-800 text-gray-400 border-gray-600' }}">{{ $offre->active ? 'Active' : 'Inactive' }}</span>
            </div>
            @empty
            <p class="text-gray-400 text-sm">Aucune offre publiée.</p>
            @endforelse
        </div>
        @endif

        @if($user->isCandidat())
        <div class="bg-darkCard border border-darkBorder rounded-xl p-5">
            <h2 class="font-bold mb-4">Candidatures ({{ $user->candidatures->count() }})</h2>
            @forelse($user->candidatures as $candidature)
            <div class="flex justify-between items-center gap-3 py-2 border-b border-darkBorder last:border-0">
                <div class="min-w-0">
                    <p class="text-sm font-semibold">{{ $candidature->offre->titre }}</p>
                    <p class="text-xs text-gray-400">{{ $candidature->offre->entreprise }}</p>
                </div>
                @php $sColors = ['en_attente'=>'bg-yellow-950 text-yellow-400 border-yellow-700','acceptee'=>'bg-green-950 text-green-400 border-green-700','refusee'=>'bg-red-950 text-red-400 border-red-700']; @endphp
                <span class="text-xs px-2 py-0.5 rounded-full border shrink-0 whitespace-nowrap {{ $sColors[$candidature->statut] }}">{{ $candidature->libelle_statut }}</span>
            </div>
            @empty
            <p class="text-gray-400 text-sm">Aucune candidature.</p>
            @endforelse
        </div>
        @endif
    </div>
</div>

@endsection
