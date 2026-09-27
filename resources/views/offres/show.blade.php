@extends('layouts.app')
@section('title', $offre->titre . " - AK Recrutement")

@section('content')
<section class="px-6 py-16">
    <div class="max-w-2xl mx-auto bg-white border border-lightBorder rounded-2xl p-8">
        <span class="inline-block text-xs font-semibold bg-white border border-lightBorder text-brand px-3 py-1 rounded-full mb-4">{{ $offre->type_contrat }}</span>
        <h1 class="text-2xl font-extrabold mb-1">{{ $offre->titre }}</h1>
        <p class="text-gray-500 mb-6">{{ $offre->entreprise }} — {{ $offre->lieu }} @if($offre->salaire) · <span class="text-brand font-semibold">{{ $offre->salaire }}</span> @endif</p>
        <h2 class="font-bold text-brand mb-2">Description</h2>
        <p class="text-gray-700 whitespace-pre-line mb-6">{{ $offre->description }}</p>
        @if ($offre->competences_requises)
            <h2 class="font-bold text-brand mb-2">Compétences requises</h2>
            <p class="text-gray-700 whitespace-pre-line mb-6">{{ $offre->competences_requises }}</p>
        @endif
        @auth
            @if (auth()->user()->isCandidat())
                @if ($offre->aPostule(auth()->user()))
                    <p class="bg-orange-50 border border-brand text-orange-700 font-semibold px-4 py-3 rounded-lg text-center">Vous avez déjà postulé à cette offre.</p>
                @else
                    <form method="POST" action="{{ route('candidatures.store', $offre) }}" class="space-y-3">
                        @csrf
                        <textarea name="message" rows="3" placeholder="Message au recruteur (optionnel)"
                            class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-3 focus:outline-none focus:border-brand placeholder-gray-400"></textarea>
                        <button class="w-full bg-brand hover:bg-brandDark text-white font-bold py-3 rounded-lg transition">Postuler à cette offre</button>
                    </form>
                @endif
            @elseif (auth()->id() === $offre->user_id)
                <div class="flex gap-3">
                    <a href="{{ route('offres.edit', $offre) }}" class="flex-1 text-center border border-lightBorder text-gray-700 font-semibold py-3 rounded-lg hover:border-brand hover:text-brand transition">Modifier</a>
                    <form method="POST" action="{{ route('offres.destroy', $offre) }}" class="flex-1" onsubmit="return confirm('Supprimer cette offre ?')">
                        @csrf @method('DELETE')
                        <button class="w-full border border-red-300 text-red-600 font-semibold py-3 rounded-lg hover:bg-red-50 transition">Supprimer</button>
                    </form>
                </div>
            @endif
        @else
            <a href="{{ route('login') }}" class="block text-center bg-brand hover:bg-brandDark text-white font-bold py-3 rounded-lg transition">Connectez-vous pour postuler</a>
        @endauth
    </div>
</section>
@endsection
