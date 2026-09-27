@extends('layouts.candidat')
@section('title', 'Tableau de bord - AK Recrutement')

@section('content')

<h1 class="text-2xl font-extrabold mb-6">Tableau de <span class="text-brand">bord</span></h1>

{{-- STATS --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white border border-lightBorder rounded-xl p-5 flex items-center gap-4 hover:border-brand transition">
        <div class="w-11 h-11 rounded-xl bg-orange-50 flex items-center justify-center text-xl shrink-0">📄</div>
        <div>
            <p class="text-2xl font-extrabold">{{ auth()->user()->candidatures()->count() }}</p>
            <p class="text-gray-500 text-xs mt-0.5">Candidatures envoyées</p>
        </div>
    </div>
    <div class="bg-white border border-lightBorder rounded-xl p-5 flex items-center gap-4 hover:border-brand transition">
        <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center text-xl shrink-0">✅</div>
        <div>
            <p class="text-2xl font-extrabold">{{ auth()->user()->candidatures()->where('statut','acceptee')->count() }}</p>
            <p class="text-gray-500 text-xs mt-0.5">Candidatures acceptées</p>
        </div>
    </div>
    <div class="bg-white border border-lightBorder rounded-xl p-5 flex items-center gap-4 hover:border-brand transition">
        <div class="w-11 h-11 rounded-xl bg-yellow-50 flex items-center justify-center text-xl shrink-0">⏳</div>
        <div>
            <p class="text-2xl font-extrabold">{{ auth()->user()->candidatures()->where('statut','en_attente')->count() }}</p>
            <p class="text-gray-500 text-xs mt-0.5">En attente</p>
        </div>
    </div>
</div>

{{-- PROFIL INCOMPLET --}}
@php
    $champs = ['titre_poste', 'telephone', 'ville', 'disponibilite', 'experience', 'a_propos', 'competences'];
    $remplis = collect($champs)->filter(fn($c) => auth()->user()->$c)->count();
    $pct = round(($remplis / count($champs)) * 100);
@endphp
@if($pct < 100)
<div class="bg-white border border-brand rounded-xl p-4 flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <span class="text-2xl">💡</span>
        <div>
            <p class="font-semibold text-sm">Votre profil est complété à {{ $pct }}%</p>
            <p class="text-gray-500 text-xs">Complétez votre profil pour être visible par les recruteurs</p>
        </div>
    </div>
    <a href="{{ route('candidat.profil.edit') }}" class="bg-brand hover:bg-brandDark text-white text-xs font-semibold px-4 py-2 rounded-lg transition shrink-0">
        Compléter →
    </a>
</div>
@endif

{{-- DERNIÈRES CANDIDATURES --}}
<div class="bg-white border border-lightBorder rounded-xl p-5">
    <div class="flex justify-between items-center mb-4">
        <h2 class="font-bold text-sm">Dernières candidatures</h2>
        <a href="{{ route('candidat.candidatures') }}" class="text-xs text-brand hover:underline bg-orange-50 border border-brand px-2 py-1 rounded-md">Voir tout</a>
    </div>
    @forelse(auth()->user()->candidatures()->with('offre')->latest()->take(5)->get() as $c)
    @php
        $sColors = ['en_attente'=>'bg-yellow-50 text-yellow-700 border-yellow-300','acceptee'=>'bg-green-50 text-green-700 border-green-300','refusee'=>'bg-red-50 text-red-600 border-red-300'];
        $sLabels = ['en_attente'=>'En attente','acceptee'=>'Acceptée ✓','refusee'=>'Refusée'];
    @endphp
    <div class="flex items-center justify-between py-2 border-b border-lightBorder last:border-0">
        <div>
            <p class="text-sm font-semibold">{{ $c->offre->titre }}</p>
            <p class="text-gray-500 text-xs">{{ $c->offre->entreprise }} — {{ $c->offre->lieu }}</p>
        </div>
        <span class="text-xs px-2 py-0.5 rounded-full border font-semibold {{ $sColors[$c->statut] }}">{{ $sLabels[$c->statut] }}</span>
    </div>
    @empty
    <p class="text-gray-500 text-sm text-center py-4">Aucune candidature pour le moment.</p>
    @endforelse
</div>

@endsection
