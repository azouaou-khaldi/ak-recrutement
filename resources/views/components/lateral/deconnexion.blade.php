{{-- Bouton de déconnexion en bas de barre latérale --}}
<div class="mt-auto p-3 border-t border-darkBorder">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:text-red-400 hover:bg-darkCard transition w-full">
            <x-icone nom="deconnexion" /> Déconnexion
        </button>
    </form>
</div>
