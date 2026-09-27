@extends('layouts.admin')
@section('title', 'Tableau de bord - Admin')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-extrabold">Tableau de <span class="text-brand">bord</span></h1>
</div>

{{-- STATS --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <x-admin.carte-stat :href="route('admin.users')" icone="utilisateurs" fond="bg-orange-950" couleur="text-orange-400"
        :valeur="$stats['users']" libelle="Utilisateurs"
        :evolution="$stats['users_mois']" periode="ce mois" texte-vide="Aucune inscription ce mois" />
    <x-admin.carte-stat :href="route('admin.offres')" icone="offres" fond="bg-blue-950" couleur="text-blue-400"
        :valeur="$stats['offres']" libelle="Offres actives"
        :evolution="$stats['offres_semaine']" periode="cette semaine" texte-vide="Aucune nouvelle offre cette semaine" />
    <x-admin.carte-stat :href="route('admin.candidatures')" icone="candidatures" fond="bg-green-950" couleur="text-green-400"
        :valeur="$stats['candidatures']" libelle="Candidatures"
        :evolution="$stats['candidatures_mois']" periode="ce mois" texte-vide="Aucune candidature ce mois" />
    <x-admin.carte-stat :href="route('admin.contacts')" icone="enveloppe" fond="bg-purple-950" couleur="text-purple-400"
        :valeur="$stats['contacts_non_lus']" libelle="Messages non lus" />
</div>

{{-- GRAPHIQUE + DONUT --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    <div class="lg:col-span-2 bg-darkCard border border-darkBorder rounded-xl p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-sm">Activité mensuelle</h2>
        </div>
        {{-- Hauteurs proportionnelles au mois le plus actif : la plus haute barre fait 100 % du cadre --}}
        @php $maxActivite = max(1, collect($stats['activite_mois'])->flatten()->max()); @endphp
        {{-- Chaque barre affiche sa valeur au-dessus (lisible sans survol, y compris sur mobile et au lecteur d'écran) --}}
        <div class="flex items-end gap-1 sm:gap-3 h-40">
            @foreach($stats['activite_mois'] as $mois => $data)
            <div class="flex flex-col items-center gap-1 flex-1 h-full min-w-0">
                <div class="flex items-end justify-center gap-1 flex-1 w-full">
                    @foreach([['inscriptions', 'bg-brand', 'Inscriptions'], ['offres', 'bg-blue-500', 'Offres publiées']] as [$cle, $couleur, $serie])
                    <div class="flex flex-col items-center justify-end h-full w-5">
                        <span class="text-[11px] font-semibold text-gray-200 leading-none mb-1" data-valeur-barre>
                            <span class="sr-only">{{ $serie }} en {{ $mois }} : </span>{{ $data[$cle] }}
                        </span>
                        <div class="w-3 rounded-t-sm {{ $couleur }}" style="height: max(4px, calc((100% - 16px) * {{ round($data[$cle] / $maxActivite, 3) }}))" aria-hidden="true"></div>
                    </div>
                    @endforeach
                </div>
                <p class="text-xs text-gray-400">{{ $mois }}</p>
            </div>
            @endforeach
        </div>
        <div class="flex gap-4 mt-3">
            <div class="flex items-center gap-2 text-xs text-gray-400"><div class="w-2 h-2 bg-brand rounded-xs"></div> Inscriptions</div>
            <div class="flex items-center gap-2 text-xs text-gray-400"><div class="w-2 h-2 bg-blue-500 rounded-xs"></div> Offres publiées</div>
        </div>
    </div>
    <div class="bg-darkCard border border-darkBorder rounded-xl p-5">
        <h2 class="font-bold text-sm mb-4">Répartition utilisateurs</h2>
        <div class="flex flex-col items-center gap-4">
            <div class="relative w-28 h-28">
                @php
                    // Les trois rôles : la somme des parts correspond bien au total affiché au centre
                    $total = $stats['users'] ?: 1;
                    $parts = [
                        ['Candidats', $stats['candidats'], '#f97316', 'bg-brand'],
                        ['Recruteurs', $stats['recruteurs'], '#3b82f6', 'bg-blue-500'],
                        ['Administrateurs', $stats['admins'], '#dc2626', 'bg-red-600'],
                    ];
                    $debut = 0;
                @endphp
                <svg viewBox="0 0 36 36" class="w-28 h-28 -rotate-90" aria-hidden="true">
                    <circle cx="18" cy="18" r="15.9" fill="none" stroke="#2a2a2a" stroke-width="3"/>
                    @foreach($parts as [$libelle, $nombre, $couleurTrait])
                        @php $pct = $nombre / $total * 100; @endphp
                        @if($nombre > 0)
                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="{{ $couleurTrait }}" stroke-width="3"
                                stroke-dasharray="{{ round($pct, 2) }} {{ round(100 - $pct, 2) }}" stroke-dashoffset="{{ round(-$debut, 2) }}"/>
                        @endif
                        @php $debut += $pct; @endphp
                    @endforeach
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-lg font-extrabold">{{ $stats['users'] }}</span>
                </div>
            </div>
            <ul class="w-full space-y-2">
                @foreach($parts as [$libelle, $nombre, $couleurTrait, $pastille])
                    <li class="flex items-center gap-2 text-xs text-gray-300"><span class="w-2 h-2 {{ $pastille }} rounded-full" aria-hidden="true"></span> {{ $libelle }} — {{ $nombre }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

{{-- TABLEAUX --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
    <div class="bg-darkCard border border-darkBorder rounded-xl p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-sm">Derniers inscrits</h2>
            <a href="{{ route('admin.users') }}" class="inline-flex items-center justify-center min-h-11 sm:min-h-0 text-xs text-brand hover:underline bg-orange-950 border border-brand px-2 py-1 rounded-full">Voir tout</a>
        </div>
        <div class="space-y-1">
            @foreach($derniers_users as $user)
            @php $colors = ['admin'=>'bg-red-600','recruteur'=>'bg-blue-600','candidat'=>'bg-brand']; @endphp
            <div class="flex items-center justify-between gap-3 py-2 border-b border-darkBorder last:border-0">
                <div class="flex items-center gap-2 min-w-0">
                    <div class="w-7 h-7 rounded-full {{ $colors[$user->role] }} flex items-center justify-center text-xs font-bold shrink-0">
                        {{ mb_strtoupper(mb_substr($user->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold truncate">{{ $user->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                    </div>
                </div>
                @php $badges = ['admin'=>'bg-red-950 text-red-400 border-red-700','recruteur'=>'bg-blue-950 text-blue-400 border-blue-700','candidat'=>'bg-orange-950 text-brand border-brand']; @endphp
                <span class="text-xs px-2 py-0.5 rounded-full border font-semibold shrink-0 whitespace-nowrap {{ $badges[$user->role] }}">{{ ucfirst($user->role) }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="bg-darkCard border border-darkBorder rounded-xl p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-sm">Offres récentes</h2>
            <a href="{{ route('admin.offres') }}" class="inline-flex items-center justify-center min-h-11 sm:min-h-0 text-xs text-brand hover:underline bg-orange-950 border border-brand px-2 py-1 rounded-full">Voir tout</a>
        </div>
        <div class="space-y-1">
            @foreach($dernieres_offres as $offre)
            <div class="flex items-center justify-between gap-3 py-2 border-b border-darkBorder last:border-0">
                <div class="min-w-0">
                    <p class="text-xs font-semibold break-words">{{ $offre->titre }}</p>
                    <p class="text-xs text-gray-400">{{ $offre->entreprise }} · {{ $offre->recruteur->name }}</p>
                </div>
                @if($offre->active)
                    <span class="text-xs px-2 py-0.5 rounded-full border font-semibold shrink-0 whitespace-nowrap bg-green-950 text-green-400 border-green-700">Active</span>
                @else
                    <span class="text-xs px-2 py-0.5 rounded-full border font-semibold shrink-0 whitespace-nowrap bg-gray-800 text-gray-400 border-gray-600">Inactive</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ACTIONS RAPIDES --}}
<div class="mb-4">
    <h2 class="font-bold text-sm mb-3">Actions rapides</h2>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <a href="{{ route('admin.users') }}" class="bg-darkCard border border-darkBorder rounded-xl p-4 text-center hover:border-brand hover:bg-orange-950 transition">
            <x-icone nom="utilisateurs" class="size-7 mx-auto mb-2 text-brandVif" />
            <p class="text-xs text-gray-400 font-medium">Gérer utilisateurs</p>
        </a>
        <a href="{{ route('admin.offres') }}" class="bg-darkCard border border-darkBorder rounded-xl p-4 text-center hover:border-brand hover:bg-orange-950 transition">
            <x-icone nom="offres" class="size-7 mx-auto mb-2 text-brandVif" />
            <p class="text-xs text-gray-400 font-medium">Gérer offres</p>
        </a>
        <a href="{{ route('admin.candidatures') }}" class="bg-darkCard border border-darkBorder rounded-xl p-4 text-center hover:border-brand hover:bg-orange-950 transition">
            <x-icone nom="candidatures" class="size-7 mx-auto mb-2 text-brandVif" />
            <p class="text-xs text-gray-400 font-medium">Candidatures</p>
        </a>
        <a href="{{ route('admin.contacts') }}" class="bg-darkCard border border-darkBorder rounded-xl p-4 text-center hover:border-brand hover:bg-orange-950 transition">
            <x-icone nom="enveloppe" class="size-7 mx-auto mb-2 text-brandVif" />
            <p class="text-xs text-gray-400 font-medium">Messages contact</p>
        </a>
    </div>
</div>

@endsection
