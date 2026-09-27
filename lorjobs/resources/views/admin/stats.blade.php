@extends('layouts.admin')
@section('title', 'Statistiques - Admin')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-extrabold"><span class="text-brand">Statistiques</span> de la plateforme</h1>
</div>

<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="bg-darkCard border border-darkBorder rounded-xl p-5">
        <h2 class="font-bold text-sm mb-4">Inscriptions par mois</h2>
        <div class="flex items-end gap-2 h-32">
            @foreach($stats['inscriptions_mois'] as $mois => $nb)
            <div class="flex flex-col items-center gap-1 flex-1">
                <p class="text-xs text-brand font-bold">{{ $nb }}</p>
                <div class="w-full rounded-t-sm bg-brand" style="height: {{ max(4, $nb * 4) }}px"></div>
                <p class="text-xs text-gray-600">{{ $mois }}</p>
            </div>
            @endforeach
        </div>
    </div>
    <div class="bg-darkCard border border-darkBorder rounded-xl p-5">
        <h2 class="font-bold text-sm mb-4">Offres publiées par mois</h2>
        <div class="flex items-end gap-2 h-32">
            @foreach($stats['offres_mois'] as $mois => $nb)
            <div class="flex flex-col items-center gap-1 flex-1">
                <p class="text-xs text-blue-400 font-bold">{{ $nb }}</p>
                <div class="w-full rounded-t-sm bg-blue-500" style="height: {{ max(4, $nb * 4) }}px"></div>
                <p class="text-xs text-gray-600">{{ $mois }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="grid grid-cols-4 gap-4">
    <div class="bg-darkCard border border-darkBorder rounded-xl p-5 text-center">
        <p class="text-3xl font-extrabold text-brand">{{ $stats['total_users'] }}</p>
        <p class="text-gray-500 text-sm mt-1">Total utilisateurs</p>
    </div>
    <div class="bg-darkCard border border-darkBorder rounded-xl p-5 text-center">
        <p class="text-3xl font-extrabold text-brand">{{ $stats['total_offres'] }}</p>
        <p class="text-gray-500 text-sm mt-1">Total offres</p>
    </div>
    <div class="bg-darkCard border border-darkBorder rounded-xl p-5 text-center">
        <p class="text-3xl font-extrabold text-brand">{{ $stats['total_candidatures'] }}</p>
        <p class="text-gray-500 text-sm mt-1">Total candidatures</p>
    </div>
    <div class="bg-darkCard border border-darkBorder rounded-xl p-5 text-center">
        <p class="text-3xl font-extrabold text-brand">{{ $stats['taux_acceptation'] }}%</p>
        <p class="text-gray-500 text-sm mt-1">Taux d'acceptation</p>
    </div>
</div>

@endsection
