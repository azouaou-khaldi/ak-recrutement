{{--
    Popup de confirmation (élément <dialog> natif : focus piégé dans la popup, touche Échap pour fermer).
    Remplie et ouverte par resources/js/confirmation.js pour les formulaires [data-confirmation-titre].
--}}
<dialog id="modale-confirmation" aria-labelledby="modale-confirmation-titre" aria-describedby="modale-confirmation-message"
        class="m-auto w-[calc(100%-2rem)] max-w-md rounded-2xl border border-darkBorder bg-darkCard p-0 text-white shadow-2xl backdrop:bg-black/75">
    <div class="p-6">
        <div class="flex items-start gap-4">
            <div class="w-11 h-11 rounded-full bg-red-950 flex items-center justify-center shrink-0">
                <x-icone nom="alerte" class="size-6 text-red-400" />
            </div>
            <div class="min-w-0">
                <h2 id="modale-confirmation-titre" class="font-bold text-lg break-words"></h2>
                <p id="modale-confirmation-message" class="text-gray-300 text-sm mt-2"></p>
                <ul id="modale-confirmation-elements" class="mt-3 space-y-1.5 text-sm text-gray-200"></ul>
            </div>
        </div>
        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6">
            <button type="button" data-confirmation-annuler autofocus
                class="min-h-11 px-5 py-2 rounded-full border border-darkBorder text-gray-200 font-semibold text-sm hover:border-gray-400 transition">
                Annuler
            </button>
            <button type="button" data-confirmation-valider
                class="min-h-11 px-5 py-2 rounded-full bg-red-700 hover:bg-red-800 text-white font-semibold text-sm transition">
                Supprimer
            </button>
        </div>
    </div>
</dialog>
