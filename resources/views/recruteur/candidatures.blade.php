@extends('layouts.recruteur')
@section('title', 'Candidatures - AK Recrutement')

@section('content')

<div class="flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between mb-5">
    <h1 class="text-2xl font-extrabold">Candidatures <span class="text-brand">reçues</span></h1>
    <form method="GET" class="grid grid-cols-1 sm:flex sm:flex-wrap gap-2">
        <select name="offre" class="w-full sm:w-auto max-w-full min-w-0 bg-white border border-lightBorder text-gray-900 rounded-lg px-3 py-2 text-sm focus:border-brand">
            <option value="">Toutes les offres</option>
            @foreach($mesOffres as $o)
                <option value="{{ $o->id }}" {{ request('offre') == $o->id ? 'selected' : '' }}>{{ $o->titre }}</option>
            @endforeach
        </select>
        <select name="statut" class="w-full sm:w-auto max-w-full min-w-0 bg-white border border-lightBorder text-gray-900 rounded-lg px-3 py-2 text-sm focus:border-brand">
            <option value="">Tous les statuts</option>
            <option value="en_attente" {{ request('statut')=='en_attente'?'selected':'' }}>En attente</option>
            <option value="acceptee" {{ request('statut')=='acceptee'?'selected':'' }}>Acceptées</option>
            <option value="refusee" {{ request('statut')=='refusee'?'selected':'' }}>Refusées</option>
        </select>
        <button class="bg-brand hover:bg-brandDark text-white px-4 py-2 rounded-full text-sm font-semibold">Filtrer</button>
    </form>
</div>

@if($candidatures->isEmpty())
    <div class="bg-white border border-lightBorder rounded-xl p-10 text-center">
        <x-icone nom="utilisateurs" class="size-10 mx-auto mb-3 text-gray-400" />
        <p class="text-gray-600">Aucune candidature reçue pour le moment.</p>
    </div>
@else
    <div class="bg-white border border-lightBorder rounded-xl overflow-hidden">
        <x-tableau-defilant label="Candidatures reçues">
        <table class="w-full text-sm min-w-[760px]">
            <thead class="bg-light border-b border-lightBorder">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Candidat</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Offre</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Statut</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-darkBorder">
                @foreach($candidatures as $c)
                @php
                    $sColors = ['en_attente'=>'bg-yellow-50 text-yellow-700 border-yellow-300','acceptee'=>'bg-green-50 text-green-700 border-green-300','refusee'=>'bg-red-50 text-red-700 border-red-300'];
                    $sLabels = ['en_attente'=>'En attente','acceptee'=>'Acceptée','refusee'=>'Refusée'];
                @endphp
                <tr class="hover:bg-orange-50 transition">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-brand flex items-center justify-center text-xs font-bold text-white shrink-0">
                                {{ mb_strtoupper(mb_substr($c->candidat->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-semibold">{{ $c->candidat->name }}</p>
                                <p class="text-gray-600 text-xs">{{ $c->candidat->email }}</p>
                                @if($c->candidat->titre_poste)
                                    <p class="text-brand text-xs">{{ $c->candidat->titre_poste }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <p class="font-semibold text-sm">{{ $c->offre->titre }}</p>
                        <p class="text-gray-600 text-xs">{{ $c->offre->type_contrat }}</p>
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs">{{ $c->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2 py-1 rounded-full border font-semibold whitespace-nowrap {{ $sColors[$c->statut] }}">{{ $sLabels[$c->statut] }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2 whitespace-nowrap">
                            @if($c->statut == 'en_attente')
                                <form method="POST" action="{{ route('candidatures.statut', $c) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="statut" value="acceptee">
                                    <button class="text-xs bg-green-50 border border-green-300 text-green-700 px-2 py-1 rounded-full font-semibold hover:bg-green-50 transition">Accepter</button>
                                </form>
                                <form method="POST" action="{{ route('candidatures.statut', $c) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="statut" value="refusee">
                                    <button class="text-xs bg-red-50 border border-red-300 text-red-700 px-2 py-1 rounded-full font-semibold hover:bg-red-50 transition">Refuser</button>
                                </form>
                            @endif
                            <a href="{{ route('recruteur.candidat.show', $c->candidat) }}" class="text-xs bg-white border border-brand text-brand px-2 py-1 rounded-sm font-semibold hover:bg-orange-50 transition inline-flex items-center gap-1"><x-icone nom="profil" class="size-3.5" /> Profil</a>
                            <a href="{{ route('messages.show', $c->candidat) }}" class="text-xs bg-blue-50 border border-blue-300 text-blue-600 px-2 py-1 rounded-sm font-semibold hover:bg-blue-50 transition inline-flex items-center gap-1"><x-icone nom="messages" class="size-3.5" /> Contacter</a>
                            @if($c->candidat->cv_path)
                                <a href="{{ route('cv.telecharger', $c->candidat) }}" target="_blank" class="text-xs bg-white border border-lightBorder text-gray-500 px-2 py-1 rounded-sm font-semibold hover:border-brand hover:text-brand transition inline-flex items-center gap-1"><x-icone nom="candidatures" class="size-3.5" /> CV</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @if($c->message)
                <tr class="bg-gray-50">
                    <td colspan="5" class="px-4 py-2 text-xs text-gray-600 italic"><span class="inline-flex items-center gap-1.5"><x-icone nom="messages" class="size-3.5" /> "{{ $c->message }}"</span></td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
        </x-tableau-defilant>
        <div class="px-4 py-3 border-t border-lightBorder">{{ $candidatures->withQueryString()->links() }}</div>
    </div>
@endif

@endsection
