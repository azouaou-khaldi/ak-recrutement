@extends('layouts.admin')
@section('title', 'Utilisateurs - Admin')

@section('content')

<div class="flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between mb-6">
    <h1 class="text-2xl font-extrabold">Gestion des <span class="text-brand">utilisateurs</span></h1>
    <div>
        <form method="GET" class="grid grid-cols-1 sm:flex sm:flex-wrap gap-2">
            <input type="text" name="recherche" value="{{ request('recherche') }}" placeholder="Rechercher..."
                class="min-h-11 sm:min-h-0 w-full sm:w-auto max-w-full min-w-0 bg-darkCard border border-darkBorder text-white rounded-lg px-3 py-2 text-sm focus:border-brand placeholder-gray-500">
            <select name="role" class="min-h-11 sm:min-h-0 w-full sm:w-auto max-w-full min-w-0 bg-darkCard border border-darkBorder text-white rounded-lg px-3 py-2 text-sm focus:border-brand">
                <option value="">Tous les rôles</option>
                <option value="candidat" {{ request('role')=='candidat'?'selected':'' }}>Candidats</option>
                <option value="recruteur" {{ request('role')=='recruteur'?'selected':'' }}>Recruteurs</option>
            </select>
            <button class="inline-flex items-center justify-center min-h-11 sm:min-h-0 bg-brand hover:bg-brandDark text-white px-4 py-2 rounded-full text-sm font-semibold">Filtrer</button>
        </form>
    </div>
</div>

<div class="bg-darkCard border border-darkBorder rounded-xl overflow-hidden">
    <x-tableau-defilant label="Liste des utilisateurs" sombre>
    <table class="w-full text-sm min-w-[680px]">
        <thead class="bg-dark border-b border-darkBorder">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase">Utilisateur</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase">Rôle</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase">Statut</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase">Inscrit le</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-darkBorder">
            @foreach($users as $user)
            @php
                $roleColors = ['admin'=>'bg-red-950 text-red-400 border-red-700','recruteur'=>'bg-blue-950 text-blue-400 border-blue-700','candidat'=>'bg-orange-950 text-brand border-brand'];
                $avatarColors = ['admin'=>'bg-red-600','recruteur'=>'bg-blue-600','candidat'=>'bg-brand'];
            @endphp
            <tr class="hover:bg-dark transition">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full {{ $avatarColors[$user->role] }} flex items-center justify-center text-xs font-bold shrink-0">
                            {{ mb_strtoupper(mb_substr($user->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-semibold">{{ $user->name }}</p>
                            <p class="text-gray-400 text-xs">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <span class="text-xs px-2 py-1 rounded-full border font-semibold whitespace-nowrap {{ $roleColors[$user->role] }}">{{ ucfirst($user->role) }}</span>
                </td>
                <td class="px-4 py-3">
                    @if($user->suspendu)
                        <span class="text-xs px-2 py-1 rounded-full border font-semibold whitespace-nowrap bg-red-950 text-red-400 border-red-700">Suspendu</span>
                    @else
                        <span class="text-xs px-2 py-1 rounded-full border font-semibold whitespace-nowrap bg-green-950 text-green-400 border-green-700">Actif</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-400 text-xs">{{ $user->created_at->format('d/m/Y') }}</td>
                <td class="px-4 py-3">
                    @if(!$user->isAdmin())
                    {{-- Boutons de 44 px de haut sur mobile (taille tactile recommandée), compacts sur ordinateur --}}
                    <div class="flex gap-2 whitespace-nowrap">
                        <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center min-h-11 sm:min-h-8 px-3 rounded-full border border-darkBorder text-brand text-xs font-semibold hover:border-brand transition">Voir</a>
                        <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                            @csrf @method('PATCH')
                            <button class="inline-flex items-center min-h-11 sm:min-h-8 px-3 rounded-full border border-darkBorder text-xs font-semibold transition {{ $user->suspendu ? 'text-green-400 hover:border-green-700' : 'text-yellow-400 hover:border-yellow-700' }}">
                                {{ $user->suspendu ? 'Réactiver' : 'Suspendre' }}
                            </button>
                        </form>
                        <x-admin.suppression-utilisateur :user="$user"
                            classe-bouton="inline-flex items-center min-h-11 sm:min-h-8 px-3 rounded-full border border-darkBorder text-red-400 text-xs font-semibold hover:border-red-700 transition" />
                    </div>
                    @else
                        <span class="text-gray-400 text-xs">—</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </x-tableau-defilant>
    <div class="px-4 py-3 border-t border-darkBorder">{{ $users->withQueryString()->links() }}</div>
</div>

@endsection
