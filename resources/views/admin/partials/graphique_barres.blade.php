{{--
    Graphique en barres par mois.
    Paramètres : $donnees (['Jan' => 12, ...]), $couleurBarre (ex. 'bg-brand'), $couleurTexte (ex. 'text-brand')
    Les hauteurs sont proportionnelles à la plus grande valeur : aucune barre ne dépasse du cadre.
--}}
@php $max = max(1, collect($donnees)->max()); @endphp

<div class="flex items-end gap-2 h-32">
    @foreach($donnees as $mois => $nb)
    <div class="flex flex-col items-center gap-1 flex-1 h-full">
        <p class="text-xs {{ $couleurTexte }} font-bold">{{ $nb }}</p>
        <div class="flex-1 w-full flex items-end">
            <div class="w-full rounded-t-sm {{ $couleurBarre }}" style="height: max(4px, {{ round($nb / $max * 100) }}%)"></div>
        </div>
        <p class="text-xs text-gray-600">{{ $mois }}</p>
    </div>
    @endforeach
</div>
