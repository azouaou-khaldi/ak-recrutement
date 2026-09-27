{{--
    Tableau à défilement horizontal (mobile). Une indication et une ombre sur le bord droit
    s'affichent seulement si le tableau dépasse de l'écran (voir resources/js/tableaux.js).
    <x-tableau-defilant label="Liste des utilisateurs" sombre> <table>...</table> </x-tableau-defilant>
--}}
@props(['label' => 'Tableau', 'sombre' => false])

<div data-tableau-defilant>
    <p data-indice-defilement hidden
       class="flex items-center gap-1.5 px-4 py-2 text-xs border-b {{ $sombre ? 'text-gray-300 border-darkBorder bg-dark' : 'text-gray-600 border-lightBorder bg-light' }}">
        <x-icone nom="defiler" class="size-4" /> Faites glisser le tableau vers la gauche pour tout voir
    </p>
    <div class="relative">
        {{-- tabindex + role="region" : la zone peut aussi défiler au clavier (flèches) --}}
        <div class="overflow-x-auto" data-zone-defilement tabindex="0" role="region" aria-label="{{ $label }}">
            {{ $slot }}
        </div>
        <div data-ombre-defilement hidden aria-hidden="true"
             class="pointer-events-none absolute inset-y-0 right-0 w-10 bg-gradient-to-l {{ $sombre ? 'from-black/80' : 'from-gray-900/20' }} to-transparent"></div>
    </div>
</div>
