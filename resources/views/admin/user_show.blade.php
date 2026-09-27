@extends('layouts.admin')
@section('title', 'Profil - ' . $user->name)

@section('content')

<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.users') }}" class="text-gray-500 hover:text-brand text-sm">← Retour</a>
    <h1 class="text-2xl font-extrabold">Profil de <span class="text-brand">{{ $user->name }}</span></h1>
</div>

<div class="grid grid-cols-3 gap-4">
    <div class="bg-darkCard border border-darkBorder rounded-xl p-6">
        @php $avatarColors = ['admin'=>'bg-red-600','recruteur'=>'bg-blue-500','candidat'=>'bg-brand']; @endphp
        <div class="w-16 h-16 rounded-full {{ $avatarColors[$user->role] }} flex items-center justify-center text-2xl font-bold mx-auto mb-4">
            {{ strtoupper(substr($user->name, 0, 2)) }}
        </div>
        <p class="text-center font-bold text-lg">{{ $user->name }}</p>
        <p class="text-center text-gray-500 text-sm">{{ $user->email }}</p>
        <div class="mt-4 space-y-2 text-sm">
            <div class="flex justify-between"><span class="text-gray-500">Rôle</span><span class="text-brand font-semibold">{{ ucfirst($user->role) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Inscrit le</span><span>{{ $user->created_at->format('d/m/Y') }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Statut</span>
                <span class="{{ $user->suspendu ? 'text-red-400' : 'text-green-400' }} font-semibold">{{ $user->suspendu ? 'Suspendu' : 'Actif' }}</span>
            </div>
        </div>
        @if(!$user->isAdmin())
        <div class="mt-6 space-y-2">
            <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                @csrf @method('PATCH')
                <button class="w-full py-2 rounded-lg text-sm font-semibold border {{ $user->suspendu ? 'border-green-700 text-green-400 hover:bg-green-950' : 'border-yellow-700 text-yellow-400 hover:bg-yellow-950' }} transition">
                    {{ $user->suspendu ? 'Réactiver le compte' : 'Suspendre le compte' }}
                </button>
            </form>
            <form method="POST" action="{{ route('admin.users.delete', $user) }}" onsubmit="return confirm('Supprimer définitivement ?')">
                @csrf @method('DELETE')
                <button class="w-full py-2 rounded-lg text-sm font-semibold border border-red-700 text-red-400 hover:bg-red-950 transition">Supprimer le compte</button>
            </form>
        </div>
        @endif
    </div>

    <div class="col-span-2 space-y-4">
        @if($user->isRecruteur())
        <div class="bg-darkCard border border-darkBorder rounded-xl p-5">
            <h2 class="font-bold mb-4">Offres publiées ({{ $user->offres->count() }})</h2>
            @forelse($user->offres as $offre)
            <div class="flex justify-between items-center py-2 border-b border-darkBorder last:border-0">
                <div>
                    <p class="text-sm font-semibold">{{ $offre->titre }}</p>
                    <p class="text-xs text-gray-500">{{ $offre->entreprise }} · {{ $offre->candidatures->count() }} candidature(s)</p>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full border {{ $offre->active ? 'bg-green-950 text-green-400 border-green-700' : 'bg-gray-800 text-gray-500 border-gray-600' }}">{{ $offre->active ? 'Active' : 'Inactive' }}</span>
            </div>
            @empty
            <p class="text-gray-500 text-sm">Aucune offre publiée.</p>
            @endforelse
        </div>
        @endif

        @if($user->isCandidat())
        <div class="bg-darkCard border border-darkBorder rounded-xl p-5">
            <h2 class="font-bold mb-4">Candidatures ({{ $user->candidatures->count() }})</h2>
            @forelse($user->candidatures as $candidature)
            <div class="flex justify-between items-center py-2 border-b border-darkBorder last:border-0">
                <div>
                    <p class="text-sm font-semibold">{{ $candidature->offre->titre }}</p>
                    <p class="text-xs text-gray-500">{{ $candidature->offre->entreprise }}</p>
                </div>
                @php $sColors = ['en_attente'=>'bg-yellow-950 text-yellow-400 border-yellow-700','acceptee'=>'bg-green-950 text-green-400 border-green-700','refusee'=>'bg-red-950 text-red-400 border-red-700']; @endphp
                <span class="text-xs px-2 py-0.5 rounded-full border {{ $sColors[$candidature->statut] }}">{{ ucfirst(str_replace('_',' ',$candidature->statut)) }}</span>
            </div>
            @empty
            <p class="text-gray-500 text-sm">Aucune candidature.</p>
            @endforelse
        </div>
        @endif
    </div>
</div>

@endsection
