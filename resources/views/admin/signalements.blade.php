@extends('layouts.admin')
@section('title', 'Signalements - Admin')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-extrabold">Gestion des <span class="text-brand">signalements</span></h1>
</div>

@if($signalements->isEmpty())
    <div class="bg-darkCard border border-darkBorder rounded-xl p-10 text-center text-gray-500">
        <div class="text-4xl mb-3">✅</div>
        <p>Aucun signalement en attente.</p>
    </div>
@else
<div class="space-y-4">
    @foreach($signalements as $s)
    <div class="bg-darkCard border border-darkBorder rounded-xl p-5 hover:border-brand transition">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs px-2 py-0.5 rounded-full border font-semibold {{ $s->type == 'offre' ? 'bg-blue-950 text-blue-400 border-blue-700' : 'bg-orange-950 text-brand border-brand' }}">
                        {{ $s->type == 'offre' ? '💼 Offre' : '👤 Utilisateur' }}
                    </span>
                    <span class="text-xs text-gray-500">Signalé par {{ $s->signaleur->name }} · {{ $s->created_at->format('d/m/Y') }}</span>
                </div>
                <p class="font-semibold">{{ $s->type == 'offre' ? $s->offre->titre : $s->cible->name }}</p>
                <p class="text-gray-400 text-sm mt-1">{{ $s->raison }}</p>
            </div>
            <div class="flex gap-2 ml-4">
                <form method="POST" action="{{ route('admin.signalements.traiter', $s) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="action" value="valider">
                    <button class="bg-red-950 border border-red-700 text-red-400 hover:bg-red-900 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                        Agir (supprimer)
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.signalements.traiter', $s) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="action" value="ignorer">
                    <button class="bg-darkCard border border-darkBorder text-gray-400 hover:border-brand px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                        Ignorer
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection
