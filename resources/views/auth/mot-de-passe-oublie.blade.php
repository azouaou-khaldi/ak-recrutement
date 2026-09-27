@extends('layouts.app')
@section('title', "Mot de passe oublié - AK Recrutement")

@section('content')
<section class="min-h-[70vh] flex items-center justify-center px-6 py-16 bg-light">
    <div class="bg-white border border-lightBorder shadow-xs rounded-2xl p-8 w-full max-w-md">
        <h1 class="text-2xl font-extrabold text-center mb-1 text-gray-900">Mot de passe <span class="text-brand">oublié</span></h1>
        <p class="text-gray-600 text-center mb-6 text-sm">Indiquez votre adresse e-mail : nous vous enverrons un lien pour choisir un nouveau mot de passe.</p>

        @if (session('status'))
            <div class="bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm" role="status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm" role="alert">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block font-semibold mb-1 text-gray-700 text-sm">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                    class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-3 focus:border-brand placeholder-gray-500"
                    placeholder="votre.email@exemple.com">
            </div>
            <button type="submit" class="w-full bg-brand hover:bg-brandDark active:scale-[0.98] text-white font-bold py-3 rounded-full transition-all duration-150 shadow-md hover:shadow-xl hover:-translate-y-0.5">
                Envoyer le lien
            </button>
        </form>
        <p class="text-center text-gray-600 mt-4 text-sm">
            <a href="{{ route('login') }}" class="text-brand font-semibold hover:underline">← Retour à la connexion</a>
        </p>
    </div>
</section>
@endsection
