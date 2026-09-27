@extends('layouts.recruteur')
@section('title', 'Mon profil - AK Recrutement')

@section('content')

<div class="text-2xl font-extrabold mb-5">Mon <span class="text-brand">Profil</span></div>

{{-- HEADER --}}
<div class="bg-white border border-lightBorder rounded-xl p-6 flex flex-col sm:flex-row sm:items-center gap-5 mb-4 relative hover:border-brand transition">
    <div class="w-20 h-20 rounded-full bg-blue-500 flex items-center justify-center text-3xl font-extrabold shrink-0">
        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
    </div>
    <div class="flex-1 min-w-0 sm:pr-28">
        <h2 class="text-xl font-extrabold">{{ auth()->user()->name }}</h2>
        <p class="text-gray-500 text-sm mt-1">{{ auth()->user()->email }}</p>
        @if(auth()->user()->entreprise)
            <p class="text-brand text-sm font-semibold mt-1">🏢 {{ auth()->user()->entreprise }}</p>
        @endif
        @if(auth()->user()->secteur)
            <p class="text-gray-500 text-xs mt-1">{{ auth()->user()->secteur }}</p>
        @endif
        <div class="flex flex-wrap gap-2 mt-3">
            <span class="text-xs px-3 py-1 rounded-full border bg-blue-50 text-blue-600 border-blue-300 font-semibold">Recruteur</span>
            <span class="text-xs px-3 py-1 rounded-full border bg-orange-50 text-brand border-brand font-semibold">{{ auth()->user()->offres()->count() }} offres publiées</span>
        </div>
    </div>
    <a href="{{ route('recruteur.profil.edit') }}" class="self-start sm:absolute sm:top-5 sm:right-5 bg-brand hover:bg-brandDark text-white text-xs font-semibold px-4 py-2 rounded-lg transition">
        ✏️ Modifier
    </a>
</div>

{{-- INFOS + ENTREPRISE --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="bg-white border border-lightBorder rounded-xl p-5 hover:border-brand transition">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-sm">Informations personnelles</h3>
            <a href="{{ route('recruteur.profil.edit') }}" class="text-xs text-brand hover:underline">Modifier</a>
        </div>
        <div class="space-y-3">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-0.5">Nom complet</p>
                <p class="text-sm text-gray-800">{{ auth()->user()->name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-0.5">Email</p>
                <p class="text-sm text-gray-800">{{ auth()->user()->email }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-0.5">Téléphone</p>
                <p class="text-sm {{ auth()->user()->telephone ? 'text-gray-800' : 'text-gray-500 italic' }}">{{ auth()->user()->telephone ?? 'Non renseigné' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-0.5">Site web</p>
                <p class="text-sm {{ auth()->user()->site_web ? 'text-brand' : 'text-gray-500 italic' }}">{{ auth()->user()->site_web ?? 'Non renseigné' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-lightBorder rounded-xl p-5 hover:border-brand transition">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-sm">Mon entreprise</h3>
            <a href="{{ route('recruteur.profil.edit') }}" class="text-xs text-brand hover:underline">Modifier</a>
        </div>
        <div class="space-y-3">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-0.5">Nom de l'entreprise</p>
                <p class="text-sm {{ auth()->user()->entreprise ? 'text-gray-800' : 'text-gray-500 italic' }}">{{ auth()->user()->entreprise ?? 'Non renseigné' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-0.5">Secteur d'activité</p>
                <p class="text-sm {{ auth()->user()->secteur ? 'text-gray-800' : 'text-gray-500 italic' }}">{{ auth()->user()->secteur ?? 'Non renseigné' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-0.5">Taille</p>
                <p class="text-sm {{ auth()->user()->taille_entreprise ? 'text-gray-800' : 'text-gray-500 italic' }}">{{ auth()->user()->taille_entreprise ?? 'Non renseignée' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-0.5">Description</p>
                <p class="text-sm leading-relaxed {{ auth()->user()->description_entreprise ? 'text-gray-700' : 'text-gray-500 italic' }}">{{ auth()->user()->description_entreprise ?? 'Aucune description.' }}</p>
            </div>
        </div>
    </div>
</div>

@endsection
