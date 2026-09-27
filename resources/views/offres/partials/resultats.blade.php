{{-- Liste des offres : affichée dans la page, ou renvoyée seule à la recherche en direct (resources/js/recherche.js) --}}
<p class="sr-only">{{ $offres->total() }} {{ $offres->total() > 1 ? 'offres trouvées' : 'offre trouvée' }}</p>

@if ($offres->isEmpty())
    <div class="max-w-md mx-auto bg-white border border-lightBorder rounded-2xl p-10 text-center shadow-xs">
        <x-icone nom="offres" class="size-14 mx-auto mb-4 text-gray-400" />
        @if (request()->filled('recherche'))
            <p class="text-gray-600">Aucune offre ne correspond à « {{ request('recherche') }} ».<br>Essayez un autre mot-clé.</p>
        @else
            <p class="text-gray-600">Aucune offre disponible pour le moment.<br>Revenez bientôt !</p>
        @endif
    </div>
@else
    <div class="max-w-5xl mx-auto grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($offres as $offre)
            <a href="{{ route('offres.show', $offre) }}"
               class="group relative bg-gradient-to-br from-orange-50 to-white border border-lightBorder rounded-2xl overflow-hidden shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-200 flex flex-col h-full">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-brandVif" aria-hidden="true"></div>
                <div class="p-6 pl-7 flex flex-col h-full">
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full bg-green-100 text-green-800">
                            {{ $offre->type_contrat }}
                        </span>
                        <span aria-hidden="true" class="text-gray-300 group-hover:text-brand group-hover:translate-x-1 transition-all text-lg">→</span>
                    </div>
                    <h2 class="text-lg font-bold mb-1 text-gray-900 group-hover:text-brand transition">{{ $offre->titre }}</h2>
                    <p class="text-gray-600 text-sm mb-3">{{ $offre->entreprise }} · {{ $offre->lieu }}</p>
                    <p class="text-gray-600 text-sm flex-1">{{ Str::limit($offre->description, 90) }}</p>
                    @if($offre->salaire)
                        <p class="text-green-800 font-semibold text-sm mt-4 pt-4 border-t border-lightBorder/70">{{ $offre->salaire }}</p>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
    <div class="max-w-5xl mx-auto mt-8">{{ $offres->withQueryString()->links() }}</div>
@endif
