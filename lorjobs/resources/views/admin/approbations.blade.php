@extends('layouts.admin')
@section('title', 'Approbations - Admin')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-extrabold">Offres en attente d'<span class="text-brand">approbation</span></h1>
</div>

@if($offres->isEmpty())
    <div class="bg-darkCard border border-darkBorder rounded-xl p-10 text-center text-gray-500">
        <div class="text-4xl mb-3">✅</div>
        <p>Aucune offre en attente d'approbation.</p>
    </div>
@else
<div class="space-y-4">
    @foreach($offres as $offre)
    <div class="bg-darkCard border border-darkBorder rounded-xl p-5 hover:border-brand transition">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs px-2 py-0.5 rounded-full border bg-orange-950 text-brand border-brand font-semibold">{{ $offre->type_contrat }}</span>
                    <span class="text-xs text-gray-500">Publié par {{ $offre->recruteur->name }} · {{ $offre->created_at->format('d/m/Y') }}</span>
                </div>
                <p class="font-bold text-lg">{{ $offre->titre }}</p>
                <p class="text-gray-400 text-sm">{{ $offre->entreprise }} · {{ $offre->lieu }}</p>
                <p class="text-gray-500 text-sm mt-2">{{ Str::limit($offre->description, 150) }}</p>
            </div>
            <div class="flex gap-2 ml-4">
                <form method="POST" action="{{ route('admin.offres.approuver', $offre) }}">
                    @csrf @method('PATCH')
                    <button class="bg-green-950 border border-green-700 text-green-400 hover:bg-green-900 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                        ✓ Approuver
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.offres.delete', $offre) }}" onsubmit="return confirm('Refuser et supprimer cette offre ?')">
                    @csrf @method('DELETE')
                    <button class="bg-red-950 border border-red-700 text-red-400 hover:bg-red-900 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                        ✗ Refuser
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection
