@extends('layouts.candidat')
@section('title', 'Mes candidatures - AK Recrutement')

@section('content')

<div class="flex items-center justify-between mb-5">
    <h1 class="text-2xl font-extrabold">Mes <span class="text-brand">candidatures</span></h1>
    <a href="{{ route('offres.index') }}" class="bg-brand hover:bg-brandDark text-white px-4 py-2 rounded-full text-sm font-semibold transition">
        Parcourir les offres
    </a>
</div>

@if($candidatures->isEmpty())
    <div class="bg-white border border-lightBorder rounded-xl p-10 text-center">
        <x-icone nom="candidatures" class="size-10 mx-auto mb-3 text-gray-400" />
        <p class="text-gray-600">Vous n'avez postulé à aucune offre pour le moment.</p>
        <a href="{{ route('offres.index') }}" class="inline-block mt-4 text-brand hover:underline text-sm font-semibold">Parcourir les offres →</a>
    </div>
@else
    <div class="space-y-3">
        @foreach($candidatures as $c)
        @php
            $sColors = [
                'en_attente' => 'bg-yellow-50 text-yellow-700 border-yellow-300',
                'acceptee'   => 'bg-green-50 text-green-700 border-green-300',
                'refusee'    => 'bg-red-50 text-red-700 border-red-300',
            ];
            $sLabels = ['en_attente' => 'En attente', 'acceptee' => 'Acceptée ✓', 'refusee' => 'Refusée'];
        @endphp
        <div class="bg-white border border-lightBorder rounded-xl p-5 flex items-center justify-between hover:border-brand transition">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs px-2 py-0.5 rounded-full border bg-orange-50 text-brand border-brand font-semibold">{{ $c->offre->type_contrat }}</span>
                </div>
                <h3 class="font-bold">{{ $c->offre->titre }}</h3>
                <p class="text-gray-600 text-sm">{{ $c->offre->entreprise }} — {{ $c->offre->lieu }}</p>
                @if($c->message)
                    <p class="text-gray-600 text-xs mt-1 italic">"{{ Str::limit($c->message, 80) }}"</p>
                @endif
                <p class="text-gray-700 text-xs mt-1">Postulé le {{ $c->created_at->format('d/m/Y') }}</p>
            </div>
            <div class="flex items-center gap-3 ml-4">
                <span class="text-xs px-3 py-1 rounded-full border font-semibold {{ $sColors[$c->statut] }}">
                    {{ $sLabels[$c->statut] }}
                </span>
                <a href="{{ route('offres.show', $c->offre) }}" class="text-xs text-gray-500 hover:text-brand transition">Voir l'offre →</a>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $candidatures->links() }}</div>
@endif

@endsection
