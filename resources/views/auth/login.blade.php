@extends('layouts.app')
@section('title', "Connexion - AK Recrutement")

@section('content')
<section class="min-h-[85vh] grid md:grid-cols-2">
    <div class="hidden md:block relative">
        <img src="https://images.unsplash.com/photo-1573497491208-6b1acb260507?q=80&w=1200&auto=format&fit=crop"
             alt="Espace de travail" class="w-full h-full object-cover">
    </div>
    <div class="flex items-center justify-center px-6 py-16 bg-light">
        <div class="bg-white border border-lightBorder shadow-xs rounded-2xl p-8 w-full max-w-md">
            <h1 class="text-2xl font-extrabold text-center mb-1 text-gray-900">Connexion à <span class="text-brand">AK Recrutement</span></h1>
            <p class="text-gray-600 text-center mb-6 text-sm">Entrez vos identifiants pour accéder à votre compte</p>

            @if (session('status'))
                <div class="bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm" role="status">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block font-semibold mb-1 text-gray-700 text-sm">Email</label>
                    <input type="email" id="email" name="email" autocomplete="email" value="{{ old('email') }}" required
                        class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-3 focus:border-brand placeholder-gray-500"
                        placeholder="votre.email@exemple.com">
                </div>
                <div>
                    <div class="flex items-baseline justify-between gap-3 mb-1">
                        <label for="password" class="block font-semibold text-gray-700 text-sm">Mot de passe</label>
                        <a href="{{ route('password.request') }}" class="text-xs text-brand font-semibold hover:underline">Mot de passe oublié ?</a>
                    </div>
                    <input type="password" id="password" name="password" autocomplete="current-password" required
                        class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-3 focus:border-brand placeholder-gray-500"
                        placeholder="••••••••">
                </div>
                <button  type="submit" class="w-full bg-brand hover:bg-brandDark active:scale-[0.98] text-white font-bold py-3 rounded-full transition-all duration-150 shadow-md hover:shadow-xl hover:-translate-y-0.5">
                    Se connecter
                </button>
            </form>
            <p class="text-center text-gray-600 mt-4 text-sm">
                Pas encore de compte ? <a href="{{ route('register') }}" class="text-brand font-semibold hover:underline">S'inscrire</a>
            </p>
        </div>
    </div>
</section>
@endsection
