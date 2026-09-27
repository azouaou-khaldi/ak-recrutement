@extends('layouts.recruteur')
@section('title', 'Paramètres - AK Recrutement')

@section('content')

<h1 class="text-2xl font-extrabold mb-5"><span class="text-brand">Paramètres</span> du compte</h1>

<div class="max-w-xl space-y-4">
    <div class="bg-white border border-lightBorder rounded-xl p-6">
        <h2 class="font-bold mb-4">Changer le mot de passe</h2>
        @if ($errors->any())
            <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif
        <form method="POST" action="{{ route('recruteur.parametres.password') }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs text-gray-600 font-semibold mb-1 uppercase tracking-wider">Mot de passe actuel</label>
                <input type="password" name="current_password" required
                    class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-2.5 text-sm focus:border-brand">
            </div>
            <div>
                <label class="block text-xs text-gray-600 font-semibold mb-1 uppercase tracking-wider">Nouveau mot de passe</label>
                <input type="password" name="password" required
                    class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-2.5 text-sm focus:border-brand">
            </div>
            <div>
                <label class="block text-xs text-gray-600 font-semibold mb-1 uppercase tracking-wider">Confirmer</label>
                <input type="password" name="password_confirmation" required
                    class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-2.5 text-sm focus:border-brand">
            </div>
            <button type="submit" class="bg-brand hover:bg-brandDark text-white font-semibold px-5 py-2.5 rounded-full text-sm transition">
                Changer le mot de passe
            </button>
        </form>
    </div>

    <div class="bg-white border border-red-900 rounded-xl p-6">
        <h2 class="font-bold mb-2 text-red-700">Zone dangereuse</h2>
        <p class="text-gray-600 text-sm mb-4">La suppression de votre compte supprimera également toutes vos offres et données.</p>
        <form method="POST" action="{{ route('recruteur.compte.delete') }}" onsubmit="return confirm('Supprimer votre compte ? Cette action est irréversible.')">
            @csrf @method('DELETE')
            <button class="border border-red-300 text-red-700 hover:bg-red-50 px-5 py-2 rounded-full text-sm font-semibold transition">
                Supprimer mon compte
            </button>
        </form>
    </div>
</div>

@endsection
