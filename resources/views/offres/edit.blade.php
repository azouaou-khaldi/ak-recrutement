@extends('layouts.app')
@section('title', "Modifier l'offre - AK Recrutement")

@section('content')
<section class="px-6 py-16">
    <div class="max-w-2xl mx-auto bg-white border border-lightBorder rounded-2xl p-8">
        <h1 class="text-2xl font-extrabold mb-6">Modifier <span class="text-brand">l'offre</span></h1>
        @if ($errors->any())
            <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
                <ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <form method="POST" action="{{ route('offres.update', $offre) }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block font-semibold mb-1 text-gray-700 text-sm">Titre du poste</label>
                <input type="text" name="titre" value="{{ old('titre', $offre->titre) }}" required
                    class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-3 focus:outline-none focus:border-brand">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1 text-gray-700 text-sm">Entreprise</label>
                    <input type="text" name="entreprise" value="{{ old('entreprise', $offre->entreprise) }}" required
                        class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-3 focus:outline-none focus:border-brand">
                </div>
                <div>
                    <label class="block font-semibold mb-1 text-gray-700 text-sm">Lieu</label>
                    <input type="text" name="lieu" value="{{ old('lieu', $offre->lieu) }}" required
                        class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-3 focus:outline-none focus:border-brand">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1 text-gray-700 text-sm">Type de contrat</label>
                    <select name="type_contrat" required class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-3 focus:outline-none focus:border-brand">
                        @foreach (['CDI','CDD','Stage','Alternance','Freelance'] as $type)
                            <option value="{{ $type }}" {{ old('type_contrat',$offre->type_contrat)==$type?'selected':'' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold mb-1 text-gray-700 text-sm">Salaire (optionnel)</label>
                    <input type="text" name="salaire" value="{{ old('salaire', $offre->salaire) }}"
                        class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-3 focus:outline-none focus:border-brand">
                </div>
            </div>
            <div>
                <label class="block font-semibold mb-1 text-gray-700 text-sm">Description</label>
                <textarea name="description" rows="5" required
                    class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-3 focus:outline-none focus:border-brand">{{ old('description', $offre->description) }}</textarea>
            </div>
            <div>
                <label class="block font-semibold mb-1 text-gray-700 text-sm">Compétences requises</label>
                <textarea name="competences_requises" rows="3"
                    class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-3 focus:outline-none focus:border-brand">{{ old('competences_requises', $offre->competences_requises) }}</textarea>
            </div>
            <label class="flex items-center gap-2 text-gray-700 text-sm">
                <input type="checkbox" name="active" value="1" {{ $offre->active ? 'checked' : '' }} class="accent-brand">
                Offre active (visible publiquement)
            </label>
            <button type="submit" class="w-full bg-brand hover:bg-brandDark text-white font-bold py-3 rounded-lg transition">Enregistrer les modifications</button>
        </form>
    </div>
</section>
@endsection
