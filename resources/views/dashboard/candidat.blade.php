@extends('layouts.candidat')
@section('title', 'Tableau de bord - AK Recrutement')

@section('content')

<h1 class="text-2xl font-extrabold mb-6">Tableau de <span class="text-brand">bord</span></h1>

{{-- STATS --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    <div class="bg-white border border-lightBorder rounded-xl p-5 flex items-center gap-4 hover:border-brand transition">
        <div class="w-11 h-11 rounded-xl bg-orange-50 flex items-center justify-center text-xl shrink-0">📄</div>
        <div>
            <p class="text-2xl font-extrabold">{{ $stats['envoyees'] }}</p>
            <p class="text-gray-500 text-xs mt-0.5">Candidatures envoyées</p>
        </div>
    </div>
    <div class="bg-white border border-lightBorder rounded-xl p-5 flex items-center gap-4 hover:border-brand transition">
        <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center text-xl shrink-0">✅</div>
        <div>
            <p class="text-2xl font-extrabold">{{ $stats['acceptees'] }}</p>
            <p class="text-gray-500 text-xs mt-0.5">Candidatures acceptées</p>
        </div>
    </div>
    <div class="bg-white border border-lightBorder rounded-xl p-5 flex items-center gap-4 hover:border-brand transition">
        <div class="w-11 h-11 rounded-xl bg-yellow-50 flex items-center justify-center text-xl shrink-0">⏳</div>
        <div>
            <p class="text-2xl font-extrabold">{{ $stats['en_attente'] }}</p>
            <p class="text-gray-500 text-xs mt-0.5">En attente</p>
        </div>
    </div>
</div>

{{-- PROFIL INCOMPLET --}}
@if($pourcentageProfil < 100)
<div class="bg-white border border-brand rounded-xl p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
    <div class="flex items-center gap-3">
        <span class="text-2xl">💡</span>
        <div>
            <p class="font-semibold text-sm">Votre profil est complété à {{ $pourcentageProfil }}%</p>
            <p class="text-gray-500 text-xs">Complétez votre profil pour être visible par les recruteurs</p>
        </div>
    </div>
    <a href="{{ route('candidat.profil.edit') }}" class="bg-brand hover:bg-brandDark text-white text-xs font-semibold px-4 py-2 rounded-lg transition shrink-0 text-center">
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
    @forelse($dernieres_candidatures as $c)
    @php
        $sColors = ['en_attente'=>'bg-yellow-50 text-yellow-700 border-yellow-300','acceptee'=>'bg-green-50 text-green-700 border-green-300','refusee'=>'bg-red-50 text-red-600 border-red-300'];
        $sLabels = ['en_attente'=>'En attente','acceptee'=>'Acceptée ✓','refusee'=>'Refusée'];
    @endphp
    <div class="flex items-center justify-between gap-3 py-2 border-b border-lightBorder last:border-0">
        <div class="min-w-0">
            <p class="text-sm font-semibold break-words">{{ $c->offre->titre }}</p>
            <p class="text-gray-500 text-xs break-words">{{ $c->offre->entreprise }} — {{ $c->offre->lieu }}</p>
        </div>
        <span class="text-xs px-2 py-0.5 rounded-full border font-semibold whitespace-nowrap shrink-0 {{ $sColors[$c->statut] }}">{{ $sLabels[$c->statut] }}</span>
    </div>
    @empty
    <p class="text-gray-500 text-sm text-center py-4">Aucune candidature pour le moment.</p>
    @endforelse
</div>

@endsection
