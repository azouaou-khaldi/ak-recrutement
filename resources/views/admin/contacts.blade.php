@extends('layouts.admin')
@section('title', 'Messages contact - Admin')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-extrabold">Messages de <span class="text-brand">contact</span></h1>
</div>

@if($contacts->isEmpty())
    <div class="bg-darkCard border border-darkBorder rounded-xl p-10 text-center text-gray-500">
        <div class="text-4xl mb-3">✉️</div>
        <p>Aucun message de contact.</p>
    </div>
@else
<div class="space-y-3">
    @foreach($contacts as $contact)
    <div class="bg-darkCard border {{ $contact->lu ? 'border-darkBorder' : 'border-brand' }} rounded-xl p-5 hover:border-brand transition">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    @if(!$contact->lu)
                        <span class="w-2 h-2 bg-brand rounded-full flex-shrink-0"></span>
                    @endif
                    <p class="font-bold">{{ $contact->sujet }}</p>
                </div>
                <p class="text-gray-400 text-sm">De : <span class="text-white">{{ $contact->nom }}</span> — {{ $contact->email }}</p>
                <p class="text-gray-500 text-sm mt-2">{{ $contact->message }}</p>
                <p class="text-gray-600 text-xs mt-2">{{ $contact->created_at->format('d/m/Y à H:i') }}</p>
            </div>
            <div class="flex gap-2 ml-4">
                @if(!$contact->lu)
                <form method="POST" action="{{ route('admin.contacts.lu', $contact) }}">
                    @csrf @method('PATCH')
                    <button class="border border-darkBorder text-gray-400 hover:border-brand hover:text-brand px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                        Marquer lu
                    </button>
                </form>
                @endif
                <form method="POST" action="{{ route('admin.contacts.delete', $contact) }}" onsubmit="return confirm('Supprimer ?')">
                    @csrf @method('DELETE')
                    <button class="bg-red-950 border border-red-700 text-red-400 hover:bg-red-900 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="mt-4">{{ $contacts->links() }}</div>
@endif

@endsection
