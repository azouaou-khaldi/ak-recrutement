@extends('layouts.admin')
@section('title', 'Message de ' . $contact->nom . ' - Admin')

@section('content')

<div class="flex flex-wrap items-baseline gap-x-3 gap-y-1 mb-6">
    <a href="{{ route('admin.contacts') }}" class="inline-flex items-center min-h-11 sm:min-h-0 text-gray-400 hover:text-brand text-sm whitespace-nowrap">← Retour</a>
    <h1 class="text-2xl font-extrabold break-words min-w-0">{{ $contact->sujet }}</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    {{-- MESSAGE COMPLET --}}
    <div class="lg:col-span-2 space-y-4 min-w-0">
        <div class="bg-darkCard border border-darkBorder rounded-xl p-5">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-4 pb-4 border-b border-darkBorder">
                <div class="min-w-0">
                    <p class="font-bold">{{ $contact->nom }}</p>
                    <a href="mailto:{{ $contact->email }}" class="text-brand text-sm hover:underline">{{ $contact->email }}</a>
                </div>
                <p class="text-gray-400 text-xs shrink-0">{{ $contact->created_at->format('d/m/Y à H:i') }}</p>
            </div>
            <p class="text-gray-300 text-sm leading-relaxed whitespace-pre-line">{{ $contact->message }}</p>
        </div>

        {{-- RÉPONSE DÉJÀ ENVOYÉE --}}
        @if($contact->reponse)
        <div class="bg-darkCard border border-green-800 rounded-xl p-5">
            <p class="text-green-400 text-xs font-semibold mb-2">✓ Réponse envoyée le {{ $contact->repondu_le->format('d/m/Y à H:i') }}</p>
            <p class="text-gray-300 text-sm leading-relaxed whitespace-pre-line">{{ $contact->reponse }}</p>
        </div>
        @endif

        {{-- FORMULAIRE DE RÉPONSE --}}
        <form method="POST" action="{{ route('admin.contacts.repondre', $contact) }}" class="bg-darkCard border border-darkBorder rounded-xl p-5">
            @csrf
            <label for="reponse" class="block font-bold text-sm mb-1">
                {{ $contact->reponse ? 'Envoyer une nouvelle réponse' : 'Répondre' }}
            </label>
            <p class="text-gray-400 text-xs mb-3">La réponse sera envoyée par e-mail à {{ $contact->email }}.</p>
            <textarea id="reponse" name="reponse" rows="6" required maxlength="5000"
                class="w-full bg-dark border {{ $errors->has('reponse') ? 'border-red-600' : 'border-darkBorder' }} text-white rounded-lg px-3 py-2 text-sm focus:border-brand placeholder-gray-500"
                placeholder="Bonjour {{ $contact->nom }}, ...">{{ old('reponse') }}</textarea>
            @error('reponse')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
            <div class="flex justify-end mt-3">
                <button class="min-h-11 sm:min-h-0 bg-brand hover:bg-brandDark text-white px-5 py-2 rounded-full text-sm font-semibold transition w-full sm:w-auto">
                    Envoyer
                </button>
            </div>
        </form>
    </div>

    {{-- INFORMATIONS --}}
    <div class="bg-darkCard border border-darkBorder rounded-xl p-5 h-fit">
        <h2 class="font-bold text-sm mb-4">Informations</h2>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between gap-3"><span class="text-gray-400">Statut</span><span class="text-green-400 font-semibold">Lu</span></div>
            <div class="flex justify-between gap-3"><span class="text-gray-400">Réponse</span>
                <span class="{{ $contact->repondu_le ? 'text-green-400' : 'text-yellow-400' }} font-semibold">{{ $contact->repondu_le ? 'Envoyée' : 'En attente' }}</span>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.contacts.delete', $contact) }}" onsubmit="return confirm('Supprimer ce message ?')" class="mt-6">
            @csrf @method('DELETE')
            <button class="w-full py-2 rounded-lg text-sm font-semibold border border-red-700 text-red-400 hover:bg-red-950 transition">Supprimer le message</button>
        </form>
    </div>

</div>

@endsection
