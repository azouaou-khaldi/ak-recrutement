@extends('layouts.candidat')
@section('title', 'Modifier mon profil - AK Recrutement')

@section('content')

<div class="flex items-center gap-3 mb-5">
    <a href="{{ route('candidat.profil') }}" class="text-gray-500 hover:text-brand text-sm">← Retour</a>
    <h1 class="text-2xl font-extrabold">Modifier mon <span class="text-brand">profil</span></h1>
</div>

@if ($errors->any())
    <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
        @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
    </div>
@endif

<form method="POST" action="{{ route('candidat.profil.update') }}" class="space-y-4">
    @csrf @method('PUT')

    <div class="bg-white border border-lightBorder rounded-xl p-6">
        <h2 class="font-bold mb-4">Informations personnelles</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs text-gray-500 font-semibold mb-1 uppercase tracking-wider">Nom complet</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                    class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-2.5 text-sm focus:outline-hidden focus:border-brand">
            </div>
            <div>
                <label class="block text-xs text-gray-500 font-semibold mb-1 uppercase tracking-wider">Email</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                    class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-2.5 text-sm focus:outline-hidden focus:border-brand">
            </div>
            <div>
                <label class="block text-xs text-gray-500 font-semibold mb-1 uppercase tracking-wider">Téléphone</label>
                <input type="text" name="telephone" value="{{ old('telephone', auth()->user()->telephone) }}"
                    placeholder="+33 6 00 00 00 00"
                    class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-2.5 text-sm focus:outline-hidden focus:border-brand placeholder-gray-400">
            </div>
            <div>
                <label class="block text-xs text-gray-500 font-semibold mb-1 uppercase tracking-wider">Ville</label>
                <input type="text" name="ville" value="{{ old('ville', auth()->user()->ville) }}"
                    placeholder="Paris, France"
                    class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-2.5 text-sm focus:outline-hidden focus:border-brand placeholder-gray-400">
            </div>
            <div>
                <label class="block text-xs text-gray-500 font-semibold mb-1 uppercase tracking-wider">LinkedIn</label>
                <input type="text" name="linkedin" value="{{ old('linkedin', auth()->user()->linkedin) }}"
                    placeholder="linkedin.com/in/votre-profil"
                    class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-2.5 text-sm focus:outline-hidden focus:border-brand placeholder-gray-400">
            </div>
            <div>
                <label class="block text-xs text-gray-500 font-semibold mb-1 uppercase tracking-wider">Portfolio</label>
                <input type="text" name="portfolio" value="{{ old('portfolio', auth()->user()->portfolio) }}"
                    placeholder="monportfolio.com"
                    class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-2.5 text-sm focus:outline-hidden focus:border-brand placeholder-gray-400">
            </div>
        </div>
    </div>

    <div class="bg-white border border-lightBorder rounded-xl p-6">
        <h2 class="font-bold mb-4">Présentation professionnelle</h2>
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-xs text-gray-500 font-semibold mb-1 uppercase tracking-wider">Poste recherché</label>
                <input type="text" name="titre_poste" value="{{ old('titre_poste', auth()->user()->titre_poste) }}"
                    placeholder="Développeur Full Stack"
                    class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-2.5 text-sm focus:outline-hidden focus:border-brand placeholder-gray-400">
            </div>
            <div>
                <label class="block text-xs text-gray-500 font-semibold mb-1 uppercase tracking-wider">Disponibilité</label>
                <select name="disponibilite" class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-2.5 text-sm focus:outline-hidden focus:border-brand">
                    <option value="">Sélectionner</option>
                    @foreach(['Immédiate', '1 mois', '2 mois', '3 mois', 'Plus de 3 mois'] as $d)
                        <option value="{{ $d }}" {{ old('disponibilite', auth()->user()->disponibilite) == $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 font-semibold mb-1 uppercase tracking-wider">Expérience</label>
                <select name="experience" class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-2.5 text-sm focus:outline-hidden focus:border-brand">
                    <option value="">Sélectionner</option>
                    @foreach(['Moins d\'1 an', '1 an', '2 ans', '3 ans', '4 ans', '5 ans', 'Plus de 5 ans'] as $e)
                        <option value="{{ $e }}" {{ old('experience', auth()->user()->experience) == $e ? 'selected' : '' }}>{{ $e }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div>
            <label class="block text-xs text-gray-500 font-semibold mb-1 uppercase tracking-wider">À propos</label>
            <textarea name="a_propos" rows="4" placeholder="Décrivez-vous en quelques lignes..."
                class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-2.5 text-sm focus:outline-hidden focus:border-brand placeholder-gray-400">{{ old('a_propos', auth()->user()->a_propos) }}</textarea>
        </div>
    </div>

    <div class="bg-white border border-lightBorder rounded-xl p-6">
        <h2 class="font-bold mb-4">Compétences</h2>
        <label class="block text-xs text-gray-500 font-semibold mb-1 uppercase tracking-wider">Compétences (séparées par des virgules)</label>
        <input type="text" name="competences" value="{{ old('competences', auth()->user()->competences) }}"
            placeholder="React, Node.js, PHP, Laravel, MySQL..."
            class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-2.5 text-sm focus:outline-hidden focus:border-brand placeholder-gray-400">
        <p class="text-xs text-gray-600 mt-1">Exemple : React, Node.js, PHP, MySQL, Git</p>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="bg-brand hover:bg-brandDark text-white font-bold px-6 py-2.5 rounded-lg text-sm transition">
            Enregistrer les modifications
        </button>
        <a href="{{ route('candidat.profil') }}" class="border border-lightBorder text-gray-500 hover:border-brand hover:text-brand px-6 py-2.5 rounded-lg text-sm transition">
            Annuler
        </a>
    </div>
</form>

@endsection
