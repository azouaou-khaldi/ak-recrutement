@extends('layouts.admin')
@section('title', 'Offres - Admin')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-extrabold">Gestion des <span class="text-brand">offres</span></h1>
    <form method="GET" class="flex gap-2">
        <input type="text" name="recherche" value="{{ request('recherche') }}" placeholder="Rechercher..."
            class="bg-darkCard border border-darkBorder text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-brand placeholder-gray-600">
        <select name="statut" class="bg-darkCard border border-darkBorder text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-brand">
            <option value="">Tous les statuts</option>
            <option value="active" {{ request('statut')=='active'?'selected':'' }}>Actives</option>
            <option value="inactive" {{ request('statut')=='inactive'?'selected':'' }}>Inactives</option>
        </select>
        <button class="bg-brand hover:bg-brandDark text-white px-4 py-2 rounded-lg text-sm font-semibold">Filtrer</button>
    </form>
</div>

<div class="bg-darkCard border border-darkBorder rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-dark border-b border-darkBorder">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Offre</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Recruteur</th>
                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase">Candidatures</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Statut</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-darkBorder">
            @foreach($offres as $offre)
            <tr class="hover:bg-dark transition">
                <td class="px-4 py-3">
                    <p class="font-semibold">{{ $offre->titre }}</p>
                    <p class="text-gray-500 text-xs">{{ $offre->entreprise }} · {{ $offre->lieu }} · {{ $offre->type_contrat }}</p>
                </td>
                <td class="px-4 py-3 text-gray-400 text-xs">{{ $offre->recruteur->name }}</td>
                <td class="px-4 py-3 text-center font-bold text-brand">{{ $offre->candidatures_count }}</td>
                <td class="px-4 py-3">
                    @if($offre->active)
                        <span class="text-xs px-2 py-1 rounded-full border bg-green-950 text-green-400 border-green-700">Active</span>
                    @else
                        <span class="text-xs px-2 py-1 rounded-full border bg-gray-800 text-gray-500 border-gray-600">Inactive</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-3">
                        <form method="POST" action="{{ route('admin.offres.toggle', $offre) }}">
                            @csrf @method('PATCH')
                            <button class="text-xs font-semibold hover:underline {{ $offre->active ? 'text-yellow-400' : 'text-green-400' }}">
                                {{ $offre->active ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.offres.delete', $offre) }}" onsubmit="return confirm('Supprimer ?')">
                            @csrf @method('DELETE')
                            <button class="text-red-400 text-xs font-semibold hover:underline">Supprimer</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-darkBorder">{{ $offres->withQueryString()->links() }}</div>
</div>

@endsection
