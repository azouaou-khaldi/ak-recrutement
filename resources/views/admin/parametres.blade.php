@extends('layouts.admin')
@section('title', 'Paramètres - Admin')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-extrabold"><span class="text-brand">Paramètres</span> du compte</h1>
</div>

<div class="max-w-xl space-y-4">
    <div class="bg-darkCard border border-darkBorder rounded-xl p-6">
        <h2 class="font-bold mb-4">Informations personnelles</h2>
        @if ($errors->any())
            <div class="bg-red-950 border border-red-600 text-red-200 px-4 py-3 rounded-lg mb-4 text-sm">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif
        <form method="POST" action="{{ route('admin.parametres.update') }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-1">Nom complet</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                    class="w-full bg-dark border border-darkBorder text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-brand">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                    class="w-full bg-dark border border-darkBorder text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-brand">
            </div>
            <button type="submit" class="bg-brand hover:bg-brandDark text-white font-semibold px-5 py-2.5 rounded-lg text-sm transition">
                Enregistrer
            </button>
        </form>
    </div>

    <div class="bg-darkCard border border-darkBorder rounded-xl p-6">
        <h2 class="font-bold mb-4">Changer le mot de passe</h2>
        <form method="POST" action="{{ route('admin.parametres.password') }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-1">Mot de passe actuel</label>
                <input type="password" name="current_password" required
                    class="w-full bg-dark border border-darkBorder text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-brand">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-1">Nouveau mot de passe</label>
                <input type="password" name="password" required
                    class="w-full bg-dark border border-darkBorder text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-brand">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-1">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" required
                    class="w-full bg-dark border border-darkBorder text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-brand">
            </div>
            <button type="submit" class="bg-brand hover:bg-brandDark text-white font-semibold px-5 py-2.5 rounded-lg text-sm transition">
                Changer le mot de passe
            </button>
        </form>
    </div>
</div>

@endsection
