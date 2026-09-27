@extends(auth()->check() && auth()->user()->isCandidat() ? 'layouts.candidat' : 'layouts.app')
@section('title', "Offres - AK Recrutement")

@section('content')
<section class="px-6 py-16 min-h-screen bg-gradient-to-b from-orange-50/40 to-light">
    <h1 class="text-3xl font-extrabold text-center mb-8">Offres <span class="text-brand">disponibles</span></h1>
    <form method="GET" class="max-w-lg mx-auto mb-10 flex gap-2">
        <input type="text" name="recherche" value="{{ request('recherche') }}"
            placeholder="Rechercher un poste, une entreprise..."
            class="flex-1 bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-3 focus:outline-none focus:border-brand placeholder-gray-400 shadow-sm">
        <button class="bg-brand hover:bg-brandDark text-white px-5 py-3 rounded-lg font-semibold transition">Rechercher</button>
    </form>
    @if ($offres->isEmpty())
        <div class="max-w-md mx-auto bg-white border border-lightBorder rounded-2xl p-10 text-center shadow-sm">
            <div class="text-6xl mb-4">💼</div>
            <p class="text-gray-500">Aucune offre disponible pour le moment.<br>Revenez bientôt !</p>
        </div>
    @else
        <div class="max-w-5xl mx-auto grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($offres as $offre)
                <a href="{{ route('offres.show', $offre) }}"
                   class="group relative bg-gradient-to-br from-orange-50 to-white border border-lightBorder rounded-2xl overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-200 flex flex-col h-full">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-brand"></div>
                    <div class="p-6 pl-7 flex flex-col h-full">
                        <div class="flex items-center justify-between mb-4">
                            <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full bg-green-100 text-green-700">
                                {{ $offre->type_contrat }}
                            </span>
                            <span class="text-gray-300 group-hover:text-brand group-hover:translate-x-1 transition-all text-lg">→</span>
                        </div>
                        <h2 class="text-lg font-bold mb-1 text-gray-900 group-hover:text-brand transition">{{ $offre->titre }}</h2>
                        <p class="text-gray-500 text-sm mb-3">{{ $offre->entreprise }} · {{ $offre->lieu }}</p>
                        <p class="text-gray-500 text-sm flex-1">{{ Str::limit($offre->description, 90) }}</p>
                        @if($offre->salaire)
                            <p class="text-green-500 font-semibold text-sm mt-4 pt-4 border-t border-lightBorder/70">{{ $offre->salaire }}</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
        <div class="max-w-5xl mx-auto mt-8">{{ $offres->withQueryString()->links() }}</div>
    @endif
</section>
@endsection