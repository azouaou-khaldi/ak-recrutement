@extends('layouts.recruteur')
@section('title', 'Mes offres - AK Recrutement')

@section('content')

<div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-5">
    <h1 class="text-2xl font-extrabold">Mes <span class="text-brand">offres</span></h1>
    <a href="{{ route('offres.create') }}" class="bg-brand hover:bg-brandDark text-white px-4 py-2 rounded-full text-sm font-semibold transition text-center">+ Publier une offre</a>
</div>

@if($offres->isEmpty())
    <div class="bg-white border border-lightBorder rounded-xl p-10 text-center">
        <x-icone nom="offres" class="size-10 mx-auto mb-3 text-gray-400" />
        <p class="text-gray-600 mb-4">Vous n'avez publié aucune offre pour le moment.</p>
        <a href="{{ route('offres.create') }}" class="bg-brand hover:bg-brandDark text-white px-5 py-2 rounded-full text-sm font-semibold transition">Publier ma première offre</a>
    </div>
@else
    <div class="space-y-3">
        @foreach($offres as $offre)
        <div class="bg-white border border-lightBorder rounded-xl p-5 hover:border-brand transition">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <span class="text-xs px-2 py-0.5 rounded-full border bg-orange-50 text-brand border-brand font-semibold">{{ $offre->type_contrat }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full border font-semibold {{ $offre->active ? 'bg-green-50 text-green-700 border-green-300' : 'bg-gray-100 text-gray-600 border-gray-300' }}">
                            {{ $offre->active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <h3 class="font-bold text-lg break-words">{{ $offre->titre }}</h3>
                    <p class="text-gray-600 text-sm">{{ $offre->entreprise }} — {{ $offre->lieu }} @if($offre->salaire) · <span class="text-brand">{{ $offre->salaire }}</span> @endif</p>
                    <p class="text-brand text-sm font-semibold mt-1">{{ $offre->candidatures_count }} candidature(s)</p>
                </div>
                <div class="flex flex-wrap gap-2 md:shrink-0">
                    <a href="{{ route('recruteur.candidatures', ['offre' => $offre->id]) }}" class="border border-lightBorder text-gray-700 hover:border-brand hover:text-brand px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                        Voir candidatures
                    </a>
                    <a href="{{ route('offres.edit', $offre) }}" class="border border-lightBorder text-gray-700 hover:border-brand hover:text-brand px-3 py-1.5 rounded-full text-xs font-semibold transition">
                        Modifier
                    </a>
                    <form method="POST" action="{{ route('offres.destroy', $offre) }}" onsubmit="return confirm('Supprimer cette offre ?')">
                        @csrf @method('DELETE')
                        <button class="border border-red-300 text-red-700 hover:bg-red-50 px-3 py-1.5 rounded-full text-xs font-semibold transition">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $offres->links() }}</div>
@endif

@endsection
