{{--
    Lien de barre latérale, identique dans les espaces candidat, recruteur et admin.
    <x-lateral.lien :href="route('dashboard')" :actif="request()->routeIs('dashboard')" icone="tableau-de-bord" :badge="3">Tableau de bord</x-lateral.lien>
--}}
@props(['href', 'icone', 'actif' => false, 'badge' => null, 'badgeLabel' => null])

<a href="{{ $href }}" @if($actif) aria-current="page" @endif
   class="flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ $actif ? 'bg-orange-950 text-brandVif' : 'text-gray-300 hover:text-white hover:bg-darkCard' }}">
    <span class="flex items-center gap-3"><x-icone :nom="$icone" /> {{ $slot }}</span>
    @if($badge)
        <span class="bg-orange-700 text-white text-xs font-bold px-2 py-0.5 rounded-full">
            {{ $badge }}@if($badgeLabel)<span class="sr-only"> {{ $badgeLabel }}</span>@endif
        </span>
    @endif
</a>
