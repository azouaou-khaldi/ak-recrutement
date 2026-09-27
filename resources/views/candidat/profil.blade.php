@extends('layouts.candidat')
@section('title', 'Mon Profil - AK Recrutement')

@section('content')

<div class="text-2xl font-extrabold mb-5">Mon <span class="text-brand">Profil</span></div>

{{-- HEADER PROFIL --}}
<div class="bg-white border border-lightBorder rounded-xl p-6 flex items-center gap-5 mb-4 relative hover:border-brand transition">
    <div class="w-20 h-20 rounded-full bg-brand flex items-center justify-center text-3xl font-extrabold flex-shrink-0">
        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
    </div>
    <div class="flex-1">
        <h2 class="text-xl font-extrabold">{{ auth()->user()->name }}</h2>
        <p class="text-gray-500 text-sm mt-1">{{ auth()->user()->email }}
            @if(auth()->user()->ville) · {{ auth()->user()->ville }} @endif
        </p>
        @if(auth()->user()->titre_poste)
            <p class="text-brand text-sm font-semibold mt-1">💼 {{ auth()->user()->titre_poste }}</p>
        @endif
        <div class="flex gap-2 mt-3 flex-wrap">
            <span class="text-xs px-3 py-1 rounded-full border bg-orange-50 text-brand border-brand font-semibold">Candidat</span>
            @if(auth()->user()->disponibilite)
                <span class="text-xs px-3 py-1 rounded-full border bg-green-50 text-green-700 border-green-300 font-semibold">
                    {{ auth()->user()->disponibilite }}
                </span>
            @endif
            @if(auth()->user()->experience)
                <span class="text-xs px-3 py-1 rounded-full border bg-blue-50 text-blue-600 border-blue-300 font-semibold">
                    {{ auth()->user()->experience }} d'expérience
                </span>
            @endif
        </div>
    </div>
    <a href="{{ route('candidat.profil.edit') }}" class="absolute top-5 right-5 bg-brand hover:bg-brandDark text-white text-xs font-semibold px-4 py-2 rounded-lg transition">
        ✏️ Modifier
    </a>
</div>

{{-- BARRE DE COMPLÉTION --}}
@php
    $champs = ['titre_poste', 'telephone', 'ville', 'disponibilite', 'experience', 'a_propos', 'competences'];
    $remplis = collect($champs)->filter(fn($c) => auth()->user()->$c)->count();
    $pct = round(($remplis / count($champs)) * 100);
@endphp
<div class="bg-white border border-lightBorder rounded-xl p-4 flex items-center gap-4 mb-4">
    <p class="text-sm text-gray-500 flex-shrink-0">Profil complété à <span class="text-brand font-bold">{{ $pct }}%</span></p>
    <div class="flex-1 bg-gray-200 rounded-full h-1.5">
        <div class="bg-brand h-1.5 rounded-full transition-all" style="width: {{ $pct }}%"></div>
    </div>
    @if($pct < 100)
        <p class="text-xs text-gray-600 flex-shrink-0">💡 Complétez votre profil pour être visible</p>
    @else
        <p class="text-xs text-green-700 flex-shrink-0">✓ Profil complet !</p>
    @endif
</div>

{{-- INFOS + PRÉSENTATION --}}
<div class="grid grid-cols-2 gap-4 mb-4">
    <div class="bg-white border border-lightBorder rounded-xl p-5 hover:border-brand transition">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-sm">Informations personnelles</h3>
            <a href="{{ route('candidat.profil.edit') }}" class="text-xs text-brand hover:underline">Modifier</a>
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
                <p class="text-sm {{ auth()->user()->telephone ? 'text-gray-800' : 'text-gray-500 italic' }}">
                    {{ auth()->user()->telephone ?? 'Non renseigné' }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-0.5">Ville</p>
                <p class="text-sm {{ auth()->user()->ville ? 'text-gray-800' : 'text-gray-500 italic' }}">
                    {{ auth()->user()->ville ?? 'Non renseignée' }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-0.5">LinkedIn</p>
                <p class="text-sm {{ auth()->user()->linkedin ? 'text-brand' : 'text-gray-500 italic' }}">
                    {{ auth()->user()->linkedin ?? 'Non renseigné' }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-0.5">Portfolio</p>
                <p class="text-sm {{ auth()->user()->portfolio ? 'text-brand' : 'text-gray-500 italic' }}">
                    {{ auth()->user()->portfolio ?? 'Non renseigné' }}
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-lightBorder rounded-xl p-5 hover:border-brand transition">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-sm">Présentation</h3>
            <a href="{{ route('candidat.profil.edit') }}" class="text-xs text-brand hover:underline">Modifier</a>
        </div>
        <div class="space-y-3">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-0.5">Poste recherché</p>
                <p class="text-sm {{ auth()->user()->titre_poste ? 'text-gray-800' : 'text-gray-500 italic' }}">
                    {{ auth()->user()->titre_poste ?? 'Non renseigné' }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-0.5">Disponibilité</p>
                <p class="text-sm {{ auth()->user()->disponibilite ? 'text-green-700' : 'text-gray-500 italic' }}">
                    {{ auth()->user()->disponibilite ?? 'Non renseignée' }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-0.5">Expérience</p>
                <p class="text-sm {{ auth()->user()->experience ? 'text-gray-800' : 'text-gray-500 italic' }}">
                    {{ auth()->user()->experience ?? 'Non renseignée' }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-0.5">À propos</p>
                <p class="text-sm leading-relaxed {{ auth()->user()->a_propos ? 'text-gray-700' : 'text-gray-500 italic' }}">
                    {{ auth()->user()->a_propos ?? 'Aucune description renseignée.' }}
                </p>
            </div>
        </div>
    </div>
</div>

{{-- COMPÉTENCES + CV --}}
<div class="grid grid-cols-2 gap-4">
    <div class="bg-white border border-lightBorder rounded-xl p-5 hover:border-brand transition">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-sm">Compétences</h3>
            <a href="{{ route('candidat.profil.edit') }}" class="text-xs text-brand hover:underline">Modifier</a>
        </div>
        @if(auth()->user()->competences)
            <div class="flex flex-wrap gap-2">
                @foreach(explode(',', auth()->user()->competences) as $comp)
                    <span class="text-xs px-3 py-1 rounded-full border bg-orange-50 text-brand border-brand font-medium">
                        {{ trim($comp) }}
                    </span>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 italic">Aucune compétence renseignée.</p>
            <a href="{{ route('candidat.profil.edit') }}" class="inline-block mt-3 text-xs text-brand hover:underline">+ Ajouter des compétences</a>
        @endif
    </div>

    <div class="bg-white border border-lightBorder rounded-xl p-5 hover:border-brand transition">
        <h3 class="font-bold text-sm mb-4">Mon CV</h3>
        @if ($errors->any())
            <div class="bg-red-50 border border-red-300 text-red-700 px-3 py-2 rounded-lg mb-3 text-xs">{{ $errors->first() }}</div>
        @endif
        @if(auth()->user()->cv_path)
            <div class="flex items-center gap-3 bg-white border border-lightBorder rounded-lg p-3">
                <span class="text-2xl">📄</span>
                <div class="flex-1">
                    <p class="text-sm font-semibold">CV uploadé</p>
                    <p class="text-xs text-gray-500">Visible par les recruteurs</p>
                </div>
                <a href="{{ route('cv.telecharger', auth()->user()) }}" target="_blank"
                   class="text-xs text-brand hover:underline">Voir</a>
            </div>
            <form method="POST" action="{{ route('candidat.cv.delete') }}" class="mt-2">
                @csrf @method('DELETE')
                <button class="text-xs text-red-600 hover:underline">Supprimer le CV</button>
            </form>
        @else
            <form method="POST" action="{{ route('candidat.cv.upload') }}" enctype="multipart/form-data">
                @csrf
                <label class="block border-2 border-dashed border-lightBorder rounded-xl p-5 text-center cursor-pointer hover:border-brand transition">
                    <div class="text-3xl mb-2">📄</div>
                    <p class="text-xs text-gray-500"><span class="text-brand">Cliquez pour uploader</span> votre CV</p>
                    <p class="text-xs text-gray-600 mt-1">PDF, DOC — max 5MB</p>
                    <input type="file" name="cv" accept=".pdf,.doc,.docx" class="hidden" onchange="this.form.submit()">
                </label>
            </form>
        @endif
    </div>
</div>

@endsection