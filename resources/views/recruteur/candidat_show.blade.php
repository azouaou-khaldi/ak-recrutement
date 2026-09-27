@extends('layouts.recruteur')
@section('title', 'Profil de ' . $candidat->name)

@section('content')

<div class="flex flex-wrap items-baseline gap-x-3 gap-y-1 mb-5">
    <a href="{{ url()->previous() }}" class="text-gray-500 hover:text-brand text-sm whitespace-nowrap">← Retour</a>
    <h1 class="text-2xl font-extrabold">Profil de <span class="text-brand">{{ $candidat->name }}</span></h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <div class="bg-white border border-lightBorder rounded-xl p-6">
        <div class="w-16 h-16 rounded-full bg-brand flex items-center justify-center text-2xl font-bold text-white mx-auto mb-4">
            {{ mb_strtoupper(mb_substr($candidat->name, 0, 2)) }}
        </div>
        <p class="text-center font-bold text-lg">{{ $candidat->name }}</p>
        <p class="text-center text-gray-500 text-sm">{{ $candidat->email }}</p>
        @if($candidat->telephone)
            <p class="text-center text-gray-500 text-sm">{{ $candidat->telephone }}</p>
        @endif

        <div class="mt-5 space-y-3">
            @if($candidat->cv_path)
                <a href="{{ route('cv.telecharger', $candidat) }}" target="_blank"
                   class="block text-center bg-brand hover:bg-brandDark text-white font-semibold py-2.5 rounded-lg text-sm transition">
                    📄 Télécharger le CV
                </a>
            @else
                <p class="text-center text-gray-400 text-xs italic">Aucun CV fourni</p>
            @endif
            <a href="{{ route('messages.show', $candidat) }}"
               class="block text-center border border-lightBorder text-gray-700 hover:border-brand hover:text-brand font-semibold py-2.5 rounded-lg text-sm transition">
                💬 Envoyer un message
            </a>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-4 min-w-0">
        <div class="bg-white border border-lightBorder rounded-xl p-5">
            <h2 class="font-bold mb-4 text-sm">Présentation</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-0.5">Poste recherché</p>
                    <p class="text-sm {{ $candidat->titre_poste ? 'text-gray-800' : 'text-gray-400 italic' }}">{{ $candidat->titre_poste ?? 'Non renseigné' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-0.5">Ville</p>
                    <p class="text-sm {{ $candidat->ville ? 'text-gray-800' : 'text-gray-400 italic' }}">{{ $candidat->ville ?? 'Non renseignée' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-0.5">Disponibilité</p>
                    <p class="text-sm {{ $candidat->disponibilite ? 'text-green-700' : 'text-gray-400 italic' }}">{{ $candidat->disponibilite ?? 'Non renseignée' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-0.5">Expérience</p>
                    <p class="text-sm {{ $candidat->experience ? 'text-gray-800' : 'text-gray-400 italic' }}">{{ $candidat->experience ?? 'Non renseignée' }}</p>
                </div>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-0.5">À propos</p>
                <p class="text-sm leading-relaxed {{ $candidat->a_propos ? 'text-gray-700' : 'text-gray-400 italic' }}">{{ $candidat->a_propos ?? 'Aucune description.' }}</p>
            </div>
        </div>

        <div class="bg-white border border-lightBorder rounded-xl p-5">
            <h2 class="font-bold mb-4 text-sm">Compétences</h2>
            @if($candidat->competences)
                <div class="flex flex-wrap gap-2">
                    @foreach(explode(',', $candidat->competences) as $comp)
                        <span class="text-xs px-3 py-1 rounded-full border bg-orange-50 text-brand border-brand font-medium">{{ trim($comp) }}</span>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-400 italic">Aucune compétence renseignée.</p>
            @endif
        </div>

        <div class="bg-white border border-lightBorder rounded-xl p-5">
            <h2 class="font-bold mb-4 text-sm">Candidatures chez vous ({{ $candidatures->count() }})</h2>
            @php
                $sColors = ['en_attente'=>'bg-yellow-50 text-yellow-700 border-yellow-300','acceptee'=>'bg-green-50 text-green-700 border-green-300','refusee'=>'bg-red-50 text-red-600 border-red-300'];
                $sLabels = ['en_attente'=>'En attente','acceptee'=>'Acceptée','refusee'=>'Refusée'];
            @endphp
            @foreach($candidatures as $c)
            <div class="flex justify-between items-center gap-3 py-2 border-b border-lightBorder last:border-0">
                <div class="min-w-0">
                    <p class="text-sm font-semibold">{{ $c->offre->titre }}</p>
                    <p class="text-xs text-gray-500">{{ $c->created_at->format('d/m/Y') }}</p>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full border shrink-0 whitespace-nowrap font-semibold {{ $sColors[$c->statut] }}">{{ $sLabels[$c->statut] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
