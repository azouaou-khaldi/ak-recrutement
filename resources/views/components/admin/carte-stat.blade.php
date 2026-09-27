{{--
    Carte de statistique du tableau de bord admin, cliquable vers la page correspondante.
    - evolution : nombre de nouveaux éléments sur la période (null = pas d'évolution affichée)
    - Si l'évolution vaut 0, on affiche un message neutre (texteVide) au lieu d'une flèche verte « ↑ +0 ».
--}}
@props(['href', 'icone', 'fond', 'couleur', 'valeur', 'libelle', 'evolution' => null, 'periode' => null, 'texteVide' => null])

<a href="{{ $href }}" class="group bg-darkCard border border-darkBorder rounded-xl p-5 flex items-center gap-4 hover:border-brand transition">
    <div class="w-11 h-11 rounded-xl {{ $fond }} flex items-center justify-center shrink-0">
        <x-icone :nom="$icone" class="size-6 {{ $couleur }}" />
    </div>
    <div class="min-w-0">
        <p class="text-2xl font-extrabold">{{ $valeur }}</p>
        <p class="text-gray-400 text-xs mt-0.5 group-hover:text-white transition">{{ $libelle }}</p>
        @if(!is_null($evolution))
            @if($evolution > 0)
                <p class="text-green-400 text-xs mt-1"><span aria-hidden="true">↑</span> +{{ $evolution }} {{ $periode }}</p>
            @else
                <p class="text-gray-400 text-xs mt-1">{{ $texteVide }}</p>
            @endif
        @endif
    </div>
</a>
