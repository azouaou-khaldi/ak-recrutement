@extends(auth()->check() && auth()->user()->isCandidat() ? 'layouts.candidat' : 'layouts.app')
@section('title', "Offres - AK Recrutement")

@section('content')
<section class="px-6 py-16 min-h-screen bg-gradient-to-b from-orange-50/40 to-light">
    <h1 class="text-3xl font-extrabold text-center mb-8">Offres <span class="text-brand">disponibles</span></h1>

    {{-- Sans JavaScript : recherche classique. Avec JavaScript : résultats en direct (resources/js/recherche.js) --}}
    <form method="GET" action="{{ route('offres.index') }}" role="search" data-recherche-offres="resultats-offres"
          class="max-w-lg mx-auto mb-10 flex gap-2">
        <label for="recherche" class="sr-only">Rechercher une offre</label>
        <input type="search" id="recherche" name="recherche" value="{{ request('recherche') }}" autocomplete="off"
            placeholder="Rechercher un poste, une entreprise..."
            class="flex-1 bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-3 focus:border-brand placeholder-gray-500 shadow-xs">
        <button class="bg-brand hover:bg-brandDark text-white px-5 py-3 rounded-full font-semibold transition">Rechercher</button>
    </form>

    <div id="resultats-offres" aria-live="polite" class="transition-opacity duration-150">
        @include('offres.partials.resultats')
    </div>
</section>
@endsection
