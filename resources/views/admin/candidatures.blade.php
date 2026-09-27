@extends('layouts.admin')
@section('title', 'Candidatures - Admin')

@section('content')

<div class="flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between mb-6">
    <h1 class="text-2xl font-extrabold">Gestion des <span class="text-brand">candidatures</span></h1>
    <form method="GET" class="grid grid-cols-1 sm:flex sm:flex-wrap gap-2">
        <select name="statut" class="w-full sm:w-auto max-w-full min-w-0 bg-darkCard border border-darkBorder text-white rounded-lg px-3 py-2 text-sm focus:outline-hidden focus:border-brand">
            <option value="">Tous les statuts</option>
            <option value="en_attente" {{ request('statut')=='en_attente'?'selected':'' }}>En attente</option>
            <option value="acceptee" {{ request('statut')=='acceptee'?'selected':'' }}>Acceptées</option>
            <option value="refusee" {{ request('statut')=='refusee'?'selected':'' }}>Refusées</option>
        </select>
        <button class="bg-brand hover:bg-brandDark text-white px-4 py-2 rounded-lg text-sm font-semibold">Filtrer</button>
    </form>
</div>

<div class="bg-darkCard border border-darkBorder rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full text-sm min-w-[680px]">
        <thead class="bg-dark border-b border-darkBorder">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Candidat</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Offre</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Date</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Statut</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Changer statut</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-darkBorder">
            @foreach($candidatures as $c)
            @php $sColors = ['en_attente'=>'bg-yellow-950 text-yellow-400 border-yellow-700','acceptee'=>'bg-green-950 text-green-400 border-green-700','refusee'=>'bg-red-950 text-red-400 border-red-700']; @endphp
            <tr class="hover:bg-dark transition">
                <td class="px-4 py-3">
                    <p class="font-semibold">{{ $c->candidat->name }}</p>
                    <p class="text-gray-500 text-xs">{{ $c->candidat->email }}</p>
                </td>
                <td class="px-4 py-3">
                    <p class="font-semibold">{{ $c->offre->titre }}</p>
                    <p class="text-gray-500 text-xs">{{ $c->offre->entreprise }}</p>
                </td>
                <td class="px-4 py-3 text-gray-500 text-xs">{{ $c->created_at->format('d/m/Y') }}</td>
                <td class="px-4 py-3">
                    <span class="text-xs px-2 py-1 rounded-full border font-semibold whitespace-nowrap {{ $sColors[$c->statut] }}">
                        {{ $c->statut == 'en_attente' ? 'En attente' : ($c->statut == 'acceptee' ? 'Acceptée' : 'Refusée') }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <form method="POST" action="{{ route('candidatures.statut', $c) }}" class="flex gap-2">
                        @csrf @method('PATCH')
                        <select name="statut" class="w-full sm:w-auto max-w-full min-w-0 bg-dark border border-darkBorder text-white rounded-lg px-2 py-1 text-xs focus:outline-hidden focus:border-brand">
                            <option value="en_attente" {{ $c->statut=='en_attente'?'selected':'' }}>En attente</option>
                            <option value="acceptee" {{ $c->statut=='acceptee'?'selected':'' }}>Acceptée</option>
                            <option value="refusee" {{ $c->statut=='refusee'?'selected':'' }}>Refusée</option>
                        </select>
                        <button class="bg-brand hover:bg-brandDark text-white px-2 py-1 rounded-sm text-xs font-semibold">OK</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    <div class="px-4 py-3 border-t border-darkBorder">{{ $candidatures->withQueryString()->links() }}</div>
</div>

@endsection
