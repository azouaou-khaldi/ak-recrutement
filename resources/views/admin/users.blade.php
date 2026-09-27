@extends('layouts.admin')
@section('title', 'Utilisateurs - Admin')

@section('content')

<div class="flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between mb-6">
    <h1 class="text-2xl font-extrabold">Gestion des <span class="text-brand">utilisateurs</span></h1>
    <div>
        <form method="GET" class="grid grid-cols-1 sm:flex sm:flex-wrap gap-2">
            <input type="text" name="recherche" value="{{ request('recherche') }}" placeholder="Rechercher..."
                class="w-full sm:w-auto max-w-full min-w-0 bg-darkCard border border-darkBorder text-white rounded-lg px-3 py-2 text-sm focus:outline-hidden focus:border-brand placeholder-gray-600">
            <select name="role" class="w-full sm:w-auto max-w-full min-w-0 bg-darkCard border border-darkBorder text-white rounded-lg px-3 py-2 text-sm focus:outline-hidden focus:border-brand">
                <option value="">Tous les rôles</option>
                <option value="candidat" {{ request('role')=='candidat'?'selected':'' }}>Candidats</option>
                <option value="recruteur" {{ request('role')=='recruteur'?'selected':'' }}>Recruteurs</option>
            </select>
            <button class="bg-brand hover:bg-brandDark text-white px-4 py-2 rounded-lg text-sm font-semibold">Filtrer</button>
        </form>
    </div>
</div>

<div class="bg-darkCard border border-darkBorder rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full text-sm min-w-[680px]">
        <thead class="bg-dark border-b border-darkBorder">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Utilisateur</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Rôle</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Statut</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Inscrit le</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-darkBorder">
            @foreach($users as $user)
            @php
                $roleColors = ['admin'=>'bg-red-950 text-red-400 border-red-700','recruteur'=>'bg-blue-950 text-blue-400 border-blue-700','candidat'=>'bg-orange-950 text-brand border-brand'];
                $avatarColors = ['admin'=>'bg-red-600','recruteur'=>'bg-blue-500','candidat'=>'bg-brand'];
            @endphp
            <tr class="hover:bg-dark transition">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full {{ $avatarColors[$user->role] }} flex items-center justify-center text-xs font-bold shrink-0">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-semibold">{{ $user->name }}</p>
                            <p class="text-gray-500 text-xs">{{ $user->email }}</p>
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
                <td class="px-4 py-3 text-gray-500 text-xs">{{ $user->created_at->format('d/m/Y') }}</td>
                <td class="px-4 py-3">
                    @if(!$user->isAdmin())
                    <div class="flex gap-3">
                        <a href="{{ route('admin.users.show', $user) }}" class="text-brand text-xs font-semibold hover:underline">Voir</a>
                        <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                            @csrf @method('PATCH')
                            <button class="text-xs font-semibold hover:underline {{ $user->suspendu ? 'text-green-400' : 'text-yellow-400' }}">
                                {{ $user->suspendu ? 'Réactiver' : 'Suspendre' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.users.delete', $user) }}" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                            @csrf @method('DELETE')
                            <button class="text-red-400 text-xs font-semibold hover:underline">Supprimer</button>
                        </form>
                    </div>
                    @else
                        <span class="text-gray-600 text-xs">—</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    <div class="px-4 py-3 border-t border-darkBorder">{{ $users->withQueryString()->links() }}</div>
</div>

@endsection
