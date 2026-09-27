@extends('layouts.recruteur')
@section('title', 'Tableau de bord - AK Recrutement')

@section('content')

<h1 class="text-2xl font-extrabold mb-5">Tableau de <span class="text-brand">bord</span></h1>

{{-- STATS --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    <div class="bg-white border border-lightBorder rounded-xl p-5 flex items-center gap-4 hover:border-brand transition">
        <div class="w-11 h-11 rounded-xl bg-orange-50 flex items-center justify-center shrink-0"><x-icone nom="offres" class="size-6 text-orange-700" /></div>
        <div>
            <p class="text-2xl font-extrabold">{{ $stats['offres'] }}</p>
            <p class="text-gray-600 text-xs mt-0.5">Offres publiées</p>
            <p class="text-brand text-xs mt-1">{{ $stats['offres_actives'] }} actives</p>
        </div>
    </div>
    <div class="bg-white border border-lightBorder rounded-xl p-5 flex items-center gap-4 hover:border-brand transition">
        <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center shrink-0"><x-icone nom="utilisateurs" class="size-6 text-blue-700" /></div>
        <div>
            <p class="text-2xl font-extrabold">{{ $stats['candidatures'] }}</p>
            <p class="text-gray-600 text-xs mt-0.5">Candidatures reçues</p>
            <p class="text-blue-600 text-xs mt-1">↑ +{{ $stats['candidatures_semaine'] }} cette semaine</p>
        </div>
    </div>
    <div class="bg-white border border-lightBorder rounded-xl p-5 flex items-center gap-4 hover:border-brand transition">
        <div class="w-11 h-11 rounded-xl bg-yellow-50 flex items-center justify-center shrink-0"><x-icone nom="horloge" class="size-6 text-yellow-700" /></div>
        <div>
            <p class="text-2xl font-extrabold">{{ $stats['en_attente'] }}</p>
            <p class="text-gray-600 text-xs mt-0.5">En attente</p>
            @if($stats['en_attente'] > 0)
                <p class="text-yellow-700 text-xs mt-1 flex items-center gap-1"><x-icone nom="alerte" class="size-3.5" /> À traiter</p>
            @else
                <p class="text-green-700 text-xs mt-1">✓ Tout traité</p>
            @endif
        </div>
    </div>
</div>

{{-- CANDIDATURES + OFFRES --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

    {{-- DERNIÈRES CANDIDATURES --}}
    <div class="bg-white border border-lightBorder rounded-xl p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-sm">Dernières candidatures</h2>
            <a href="{{ route('recruteur.candidatures') }}" class="text-xs text-brand bg-orange-50 border border-brand px-2 py-1 rounded-full hover:underline">Voir tout</a>
        </div>
        @forelse($dernieres_candidatures as $c)
        @php
            $avatarColors = ['A'=>'bg-brand','B'=>'bg-purple-700','C'=>'bg-green-700','D'=>'bg-blue-700','E'=>'bg-red-700','F'=>'bg-yellow-700','G'=>'bg-pink-700','H'=>'bg-indigo-700'];
            $initial = mb_strtoupper(mb_substr($c->candidat->name, 0, 1));
            $color = $avatarColors[$initial] ?? 'bg-brand';
        @endphp
        <div class="flex items-center justify-between gap-3 py-2.5 border-b border-lightBorder last:border-0">
            <div class="flex items-center gap-2 min-w-0">
                <div class="w-8 h-8 rounded-full {{ $color }} flex items-center justify-center text-xs font-bold text-white shrink-0">
                    {{ mb_strtoupper(mb_substr($c->candidat->name, 0, 2)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold break-words">{{ $c->candidat->name }}</p>
                    <p class="text-xs text-gray-600 break-words">{{ $c->offre->titre }}</p>
                </div>
            </div>
            <div class="flex gap-1.5 shrink-0">
                @if($c->statut == 'en_attente')
                    <form method="POST" action="{{ route('candidatures.statut', $c) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="statut" value="acceptee">
                        <button class="text-xs bg-green-50 border border-green-300 text-green-700 px-2 py-1 rounded-full font-semibold hover:bg-green-50 transition" aria-label="Accepter la candidature de {{ $c->candidat->name }}">✓</button>
                    </form>
                    <form method="POST" action="{{ route('candidatures.statut', $c) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="statut" value="refusee">
                        <button class="text-xs bg-red-50 border border-red-300 text-red-700 px-2 py-1 rounded-full font-semibold hover:bg-red-50 transition" aria-label="Refuser la candidature de {{ $c->candidat->name }}">✗</button>
                    </form>
                @elseif($c->statut == 'acceptee')
                    <span class="text-xs bg-green-50 border border-green-300 text-green-700 px-2 py-1 rounded-sm font-semibold">Accepté</span>
                @else
                    <span class="text-xs bg-red-50 border border-red-300 text-red-700 px-2 py-1 rounded-sm font-semibold">Refusé</span>
                @endif
                <a href="{{ route('messages.show', $c->candidat) }}" class="text-xs bg-blue-50 border border-blue-300 text-blue-600 px-2 py-1 rounded-sm font-semibold hover:bg-blue-50 transition inline-flex items-center" aria-label="Envoyer un message à {{ $c->candidat->name }}"><x-icone nom="messages" class="size-4" /></a>
            </div>
        </div>
        @empty
        <p class="text-gray-600 text-sm text-center py-4">Aucune candidature reçue.</p>
        @endforelse
    </div>

    {{-- MES OFFRES --}}
    <div class="bg-white border border-lightBorder rounded-xl p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-sm">Mes offres</h2>
            <a href="{{ route('offres.create') }}" class="text-xs bg-brand hover:bg-brandDark text-white px-3 py-1.5 rounded-full font-semibold transition">+ Nouvelle offre</a>
        </div>
        @forelse($dernieres_offres as $offre)
        <div class="flex items-center justify-between gap-3 py-2.5 border-b border-lightBorder last:border-0">
            <div class="min-w-0">
                <p class="text-sm font-semibold break-words">{{ $offre->titre }}</p>
                <p class="text-xs text-gray-600">{{ $offre->lieu }} · {{ $offre->type_contrat }}</p>
                <p class="text-xs text-brand font-semibold mt-0.5">{{ $offre->candidatures_count }} candidature(s)</p>
            </div>
            <div class="flex flex-col sm:flex-row items-end sm:items-center gap-2 shrink-0">
                <span class="text-xs px-2 py-0.5 rounded-full border font-semibold {{ $offre->active ? 'bg-green-50 text-green-700 border-green-300' : 'bg-gray-100 text-gray-600 border-gray-300' }}">
                    {{ $offre->active ? 'Active' : 'Inactive' }}
                </span>
                <a href="{{ route('offres.edit', $offre) }}" class="text-xs text-gray-600 hover:text-brand">Modifier</a>
            </div>
        </div>
        @empty
        <p class="text-gray-600 text-sm text-center py-4">Aucune offre publiée.</p>
        @endforelse
    </div>

</div>

@endsection
