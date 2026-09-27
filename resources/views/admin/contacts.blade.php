@extends('layouts.admin')
@section('title', 'Messages contact - Admin')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-extrabold">Messages de <span class="text-brand">contact</span></h1>
</div>

@if($contacts->isEmpty())
    <div class="bg-darkCard border border-darkBorder rounded-xl p-10 text-center text-gray-400">
        <x-icone nom="enveloppe" class="size-10 mx-auto mb-3 text-gray-400" />
        <p>Aucun message de contact.</p>
    </div>
@else
<div class="space-y-3">
    @foreach($contacts as $contact)
    <div class="bg-darkCard border {{ $contact->lu ? 'border-darkBorder' : 'border-brand' }} rounded-xl hover:border-brand transition flex items-center gap-3 pr-4">
        {{-- Toute la zone est cliquable et ouvre le message complet --}}
        <a href="{{ route('admin.contacts.show', $contact) }}" class="flex-1 min-w-0 p-5">
            <div class="flex items-center gap-2 mb-1">
                @if(!$contact->lu)
                    <span class="w-2 h-2 bg-brand rounded-full shrink-0" aria-hidden="true"></span>
                    <span class="sr-only">Non lu :</span>
                @endif
                <p class="font-bold truncate">{{ $contact->sujet }}</p>
            </div>
            <p class="text-gray-400 text-sm truncate">De : <span class="text-white">{{ $contact->nom }}</span></p>
            <p class="text-gray-400 text-xs mt-2">
                {{ $contact->created_at->format('d/m/Y à H:i') }}
                @if($contact->repondu_le)
                    · <span class="text-green-400">Répondu</span>
                @endif
            </p>
        </a>
        <form method="POST" action="{{ route('admin.contacts.delete', $contact) }}" onsubmit="return confirm('Supprimer ?')" class="shrink-0">
            @csrf @method('DELETE')
            <button class="inline-flex items-center justify-center min-h-11 sm:min-h-0 bg-red-950 border border-red-700 text-red-400 hover:bg-red-900 px-3 py-1.5 rounded-full text-xs font-semibold transition">
                Supprimer
            </button>
        </form>
    </div>
    @endforeach
</div>
<div class="mt-4">{{ $contacts->links() }}</div>
@endif

@endsection
