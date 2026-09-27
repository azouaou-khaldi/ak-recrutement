@extends('layouts.recruteur')
@section('title', 'Modifier mon profil - AK Recrutement')

@section('content')

<div class="flex items-center gap-3 mb-5">
    <a href="{{ route('recruteur.profil') }}" class="text-gray-600 hover:text-brand text-sm">← Retour</a>
    <h1 class="text-2xl font-extrabold">Modifier mon <span class="text-brand">profil</span></h1>
</div>

@if ($errors->any())
    <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
        @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
    </div>
@endif

<form method="POST" action="{{ route('recruteur.profil.update') }}" class="space-y-4">
    @csrf @method('PUT')

    <div class="bg-white border border-lightBorder rounded-xl p-6">
        <h2 class="font-bold mb-4">Informations personnelles</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs text-gray-600 font-semibold mb-1 uppercase tracking-wider">Nom complet</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                    class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-2.5 text-sm focus:border-brand">
            </div>
            <div>
                <label class="block text-xs text-gray-600 font-semibold mb-1 uppercase tracking-wider">Email</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                    class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-2.5 text-sm focus:border-brand">
            </div>
            <div>
                <label class="block text-xs text-gray-600 font-semibold mb-1 uppercase tracking-wider">Téléphone</label>
                <input type="text" name="telephone" value="{{ old('telephone', auth()->user()->telephone) }}"
                    placeholder="+33 6 00 00 00 00"
                    class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-2.5 text-sm focus:border-brand placeholder-gray-400">
            </div>
            <div>
                <label class="block text-xs text-gray-600 font-semibold mb-1 uppercase tracking-wider">Site web</label>
                <input type="text" name="site_web" value="{{ old('site_web', auth()->user()->site_web) }}"
                    placeholder="www.monentreprise.com"
                    class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-2.5 text-sm focus:border-brand placeholder-gray-400">
            </div>
        </div>
    </div>

    <div class="bg-white border border-lightBorder rounded-xl p-6">
        <h2 class="font-bold mb-4">Mon entreprise</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-xs text-gray-600 font-semibold mb-1 uppercase tracking-wider">Nom de l'entreprise</label>
                <input type="text" name="entreprise" value="{{ old('entreprise', auth()->user()->entreprise) }}"
                    placeholder="Ex: TechVision SAS"
                    class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-2.5 text-sm focus:border-brand placeholder-gray-400">
            </div>
            <div>
                <label class="block text-xs text-gray-600 font-semibold mb-1 uppercase tracking-wider">Secteur d'activité</label>
                <select name="secteur" class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-2.5 text-sm focus:border-brand">
                    <option value="">Sélectionner</option>
                    @foreach(['Technologie','Finance','Santé','Commerce','Industrie','Construction','Transport','Éducation','Droit','Autre'] as $s)
                        <option value="{{ $s }}" {{ old('secteur', auth()->user()->secteur) == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-600 font-semibold mb-1 uppercase tracking-wider">Taille de l'entreprise</label>
                <select name="taille_entreprise" class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-2.5 text-sm focus:border-brand">
                    <option value="">Sélectionner</option>
                    @foreach(['1-10 employés','11-50 employés','51-200 employés','201-500 employés','500+ employés'] as $t)
                        <option value="{{ $t }}" {{ old('taille_entreprise', auth()->user()->taille_entreprise) == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div>
            <label class="block text-xs text-gray-600 font-semibold mb-1 uppercase tracking-wider">Description de l'entreprise</label>
            <textarea name="description_entreprise" rows="4" placeholder="Décrivez votre entreprise..."
                class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-2.5 text-sm focus:border-brand placeholder-gray-500">{{ old('description_entreprise', auth()->user()->description_entreprise) }}</textarea>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="bg-brand hover:bg-brandDark text-white font-bold px-6 py-2.5 rounded-full text-sm transition">
            Enregistrer
        </button>
        <a href="{{ route('recruteur.profil') }}" class="border border-lightBorder text-gray-600 hover:border-brand hover:text-brand px-6 py-2.5 rounded-full text-sm transition">
            Annuler
        </a>
    </div>
</form>

@endsection
