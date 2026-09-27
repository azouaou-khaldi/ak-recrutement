{{-- Bouton du menu mobile : ouvre/ferme l'élément dont l'id est passé dans "controls" (voir resources/js/menu.js) --}}
@props(['controls'])

<button type="button" data-menu-toggle aria-controls="{{ $controls }}" aria-expanded="false" aria-label="Ouvrir le menu"
    {{ $attributes->merge(['class' => 'md:hidden p-2 rounded-lg text-gray-200 hover:text-brand focus:outline-hidden focus-visible:ring-2 focus-visible:ring-brand']) }}>
    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <path data-icone-ouvrir stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
        <path data-icone-fermer class="hidden" stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/>
    </svg>
</button>
