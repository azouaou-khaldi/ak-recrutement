@extends('layouts.app')
@section('title', "Nouveau mot de passe - AK Recrutement")

@section('content')
<section class="min-h-[70vh] flex items-center justify-center px-6 py-16 bg-light">
    <div class="bg-white border border-lightBorder shadow-xs rounded-2xl p-8 w-full max-w-md">
        <h1 class="text-2xl font-extrabold text-center mb-1 text-gray-900">Nouveau <span class="text-brand">mot de passe</span></h1>
        <p class="text-gray-500 text-center mb-6 text-sm">Choisissez un nouveau mot de passe pour votre compte.</p>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm" role="alert">
                @foreach ($errors->all() as $erreur)
                    <p>{{ $erreur }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div>
                <label for="email" class="block font-semibold mb-1 text-gray-700 text-sm">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required autocomplete="email"
                    class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-3 focus:outline-hidden focus:border-brand placeholder-gray-400">
            </div>
            <div>
                <label for="password" class="block font-semibold mb-1 text-gray-700 text-sm">Nouveau mot de passe</label>
                <input type="password" id="password" name="password" required autofocus autocomplete="new-password" aria-describedby="regles-mdp"
                    class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-3 focus:outline-hidden focus:border-brand placeholder-gray-400"
                    placeholder="••••••••">
                <p id="regles-mdp" class="text-gray-500 text-xs mt-1">8 caractères minimum, avec une majuscule, une minuscule, un chiffre et un symbole.</p>
            </div>
            <div>
                <label for="password_confirmation" class="block font-semibold mb-1 text-gray-700 text-sm">Confirmer le mot de passe</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                    class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-3 focus:outline-hidden focus:border-brand placeholder-gray-400"
                    placeholder="••••••••">
            </div>
            <button type="submit" class="w-full bg-brand hover:bg-brandDark active:scale-[0.98] text-white font-bold py-3 rounded-full transition-all duration-150 shadow-md hover:shadow-xl hover:-translate-y-0.5">
                Enregistrer le nouveau mot de passe
            </button>
        </form>
    </div>
</section>
@endsection
