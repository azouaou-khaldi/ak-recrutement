{{-- Groupe de liens de barre latérale avec son titre (« Mon espace », « Compte »...) --}}
@props(['titre'])

<div {{ $attributes->merge(['class' => 'p-3']) }}>
    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest px-3 mb-2">{{ $titre }}</p>
    <div class="flex flex-col gap-1">
        {{ $slot }}
    </div>
</div>
