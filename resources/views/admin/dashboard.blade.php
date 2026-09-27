@extends('layouts.admin')
@section('title', 'Tableau de bord - Admin')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-extrabold">Tableau de <span class="text-brand">bord</span></h1>
</div>

{{-- STATS --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-darkCard border border-darkBorder rounded-xl p-5 flex items-center gap-4 hover:border-brand transition cursor-pointer">
        <div class="w-11 h-11 rounded-xl bg-orange-950 flex items-center justify-center text-xl shrink-0">👥</div>
        <div>
            <p class="text-2xl font-extrabold">{{ $stats['users'] }}</p>
            <p class="text-gray-500 text-xs mt-0.5">Utilisateurs</p>
            <p class="text-green-400 text-xs mt-1">↑ +{{ $stats['users_mois'] }} ce mois</p>
        </div>
    </div>
    <div class="bg-darkCard border border-darkBorder rounded-xl p-5 flex items-center gap-4 hover:border-brand transition cursor-pointer">
        <div class="w-11 h-11 rounded-xl bg-blue-950 flex items-center justify-center text-xl shrink-0">💼</div>
        <div>
            <p class="text-2xl font-extrabold">{{ $stats['offres'] }}</p>
            <p class="text-gray-500 text-xs mt-0.5">Offres actives</p>
            <p class="text-green-400 text-xs mt-1">↑ +{{ $stats['offres_semaine'] }} cette semaine</p>
        </div>
    </div>
    <div class="bg-darkCard border border-darkBorder rounded-xl p-5 flex items-center gap-4 hover:border-brand transition cursor-pointer">
        <div class="w-11 h-11 rounded-xl bg-green-950 flex items-center justify-center text-xl shrink-0">📄</div>
        <div>
            <p class="text-2xl font-extrabold">{{ $stats['candidatures'] }}</p>
            <p class="text-gray-500 text-xs mt-0.5">Candidatures</p>
            <p class="text-green-400 text-xs mt-1">↑ +{{ $stats['candidatures_mois'] }} ce mois</p>
        </div>
    </div>
    <div class="bg-darkCard border border-darkBorder rounded-xl p-5 flex items-center gap-4 hover:border-brand transition cursor-pointer">
        <div class="w-11 h-11 rounded-xl bg-purple-950 flex items-center justify-center text-xl shrink-0">✉️</div>
        <div>
            <p class="text-2xl font-extrabold">{{ \App\Models\Contact::where('lu', false)->count() }}</p>
            <p class="text-gray-500 text-xs mt-0.5">Messages non lus</p>
        </div>
    </div>
</div>

{{-- GRAPHIQUE + DONUT --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="col-span-2 bg-darkCard border border-darkBorder rounded-xl p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-sm">Activité mensuelle</h2>
        </div>
        <div class="flex items-end gap-3 h-28">
            @foreach($stats['activite_mois'] as $mois => $data)
            <div class="flex flex-col items-center gap-1 flex-1">
                <div class="flex items-end gap-1 h-24">
                    <div class="w-3 rounded-t-sm bg-brand" style="height: {{ max(4, $data['inscriptions'] * 3) }}px"></div>
                    <div class="w-3 rounded-t-sm bg-blue-500" style="height: {{ max(4, $data['offres'] * 3) }}px"></div>
                </div>
                <p class="text-xs text-gray-600">{{ $mois }}</p>
            </div>
            @endforeach
        </div>
        <div class="flex gap-4 mt-3">
            <div class="flex items-center gap-2 text-xs text-gray-500"><div class="w-2 h-2 bg-brand rounded-xs"></div> Inscriptions</div>
            <div class="flex items-center gap-2 text-xs text-gray-500"><div class="w-2 h-2 bg-blue-500 rounded-xs"></div> Offres publiées</div>
        </div>
    </div>
    <div class="bg-darkCard border border-darkBorder rounded-xl p-5">
        <h2 class="font-bold text-sm mb-4">Répartition utilisateurs</h2>
        <div class="flex flex-col items-center gap-4">
            <div class="relative w-28 h-28">
                @php
                    $total = $stats['users'] ?: 1;
                    $pctCandidat = round(($stats['candidats'] / $total) * 100);
                    $pctRecruteur = round(($stats['recruteurs'] / $total) * 100);
                @endphp
                <svg viewBox="0 0 36 36" class="w-28 h-28 -rotate-90">
                    <circle cx="18" cy="18" r="15.9" fill="none" stroke="#2a2a2a" stroke-width="3"/>
                    <circle cx="18" cy="18" r="15.9" fill="none" stroke="#f97316" stroke-width="3"
                        stroke-dasharray="{{ $pctCandidat }} {{ 100 - $pctCandidat }}" stroke-linecap="round"/>
                    <circle cx="18" cy="18" r="15.9" fill="none" stroke="#3b82f6" stroke-width="3"
                        stroke-dasharray="{{ $pctRecruteur }} {{ 100 - $pctRecruteur }}"
                        stroke-dashoffset="{{ -$pctCandidat }}" stroke-linecap="round"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-lg font-extrabold">{{ $stats['users'] }}</span>
                </div>
            </div>
            <div class="w-full space-y-2">
                <div class="flex items-center gap-2 text-xs text-gray-400"><div class="w-2 h-2 bg-brand rounded-full"></div> Candidats — {{ $stats['candidats'] }}</div>
                <div class="flex items-center gap-2 text-xs text-gray-400"><div class="w-2 h-2 bg-blue-500 rounded-full"></div> Recruteurs — {{ $stats['recruteurs'] }}</div>
            </div>
        </div>
    </div>
</div>

{{-- TABLEAUX --}}
<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="bg-darkCard border border-darkBorder rounded-xl p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-sm">Derniers inscrits</h2>
            <a href="{{ route('admin.users') }}" class="text-xs text-brand hover:underline bg-orange-950 border border-brand px-2 py-1 rounded-md">Voir tout</a>
        </div>
        <div class="space-y-1">
            @foreach($derniers_users as $user)
            @php $colors = ['admin'=>'bg-red-600','recruteur'=>'bg-blue-500','candidat'=>'bg-brand']; @endphp
            <div class="flex items-center justify-between py-2 border-b border-darkBorder last:border-0">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full {{ $colors[$user->role] }} flex items-center justify-center text-xs font-bold shrink-0">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="text-xs font-semibold">{{ $user->name }}</p>
                        <p class="text-xs text-gray-600">{{ $user->email }}</p>
                    </div>
                </div>
                @php $badges = ['admin'=>'bg-red-950 text-red-400 border-red-700','recruteur'=>'bg-blue-950 text-blue-400 border-blue-700','candidat'=>'bg-orange-950 text-brand border-brand']; @endphp
                <span class="text-xs px-2 py-0.5 rounded-full border font-semibold {{ $badges[$user->role] }}">{{ ucfirst($user->role) }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="bg-darkCard border border-darkBorder rounded-xl p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-sm">Offres récentes</h2>
            <a href="{{ route('admin.offres') }}" class="text-xs text-brand hover:underline bg-orange-950 border border-brand px-2 py-1 rounded-md">Voir tout</a>
        </div>
        <div class="space-y-1">
            @foreach($dernieres_offres as $offre)
            <div class="flex items-center justify-between py-2 border-b border-darkBorder last:border-0">
                <div>
                    <p class="text-xs font-semibold">{{ $offre->titre }}</p>
                    <p class="text-xs text-gray-600">{{ $offre->entreprise }} · {{ $offre->recruteur->name }}</p>
                </div>
                @if($offre->active)
                    <span class="text-xs px-2 py-0.5 rounded-full border font-semibold bg-green-950 text-green-400 border-green-700">Active</span>
                @else
                    <span class="text-xs px-2 py-0.5 rounded-full border font-semibold bg-gray-800 text-gray-500 border-gray-600">Inactive</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ACTIONS RAPIDES --}}
<div class="mb-4">
    <h2 class="font-bold text-sm mb-3">Actions rapides</h2>
    <div class="grid grid-cols-4 gap-3">
        <a href="{{ route('admin.users') }}" class="bg-darkCard border border-darkBorder rounded-xl p-4 text-center hover:border-brand hover:bg-orange-950 transition">
            <div class="text-2xl mb-2">👤</div>
            <p class="text-xs text-gray-400 font-medium">Gérer utilisateurs</p>
        </a>
        <a href="{{ route('admin.offres') }}" class="bg-darkCard border border-darkBorder rounded-xl p-4 text-center hover:border-brand hover:bg-orange-950 transition">
            <div class="text-2xl mb-2">💼</div>
            <p class="text-xs text-gray-400 font-medium">Gérer offres</p>
        </a>
        <a href="{{ route('admin.candidatures') }}" class="bg-darkCard border border-darkBorder rounded-xl p-4 text-center hover:border-brand hover:bg-orange-950 transition">
            <div class="text-2xl mb-2">📄</div>
            <p class="text-xs text-gray-400 font-medium">Candidatures</p>
        </a>
        <a href="{{ route('admin.contacts') }}" class="bg-darkCard border border-darkBorder rounded-xl p-4 text-center hover:border-brand hover:bg-orange-950 transition">
            <div class="text-2xl mb-2">✉️</div>
            <p class="text-xs text-gray-400 font-medium">Messages contact</p>
        </a>
    </div>
</div>

@endsection
