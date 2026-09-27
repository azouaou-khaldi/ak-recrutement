@extends('layouts.app')
@section('title', "Inscription - AK Recrutement")

@section('content')
<section class="min-h-[85vh] grid md:grid-cols-2">
    <div class="hidden md:block relative">
        <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?q=80&w=1200&auto=format&fit=crop"
             alt="Rejoignez-nous" class="w-full h-full object-cover">
    </div>
    <div class="flex items-center justify-center px-6 py-16 bg-light">
        <div class="bg-white border border-lightBorder shadow-sm rounded-2xl p-8 w-full max-w-md">
            <h1 class="text-2xl font-extrabold text-center mb-1 text-gray-900">Inscription à <span class="text-brand">AK Recrutement</span></h1>
            <p class="text-gray-500 text-center mb-6 text-sm">Créez votre compte pour commencer</p>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block font-semibold mb-1 text-gray-700 text-sm">Nom complet</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-3 focus:outline-none focus:border-brand placeholder-gray-400"
                        placeholder="Jean Dupont">
                </div>
                <div>
                    <label class="block font-semibold mb-1 text-gray-700 text-sm">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-3 focus:outline-none focus:border-brand placeholder-gray-400"
                        placeholder="votre.email@exemple.com">
                </div>
                <div>
                    <label class="block font-semibold mb-1 text-gray-700 text-sm">Mot de passe</label>
                    <input type="password" name="password" required
                        class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-3 focus:outline-none focus:border-brand placeholder-gray-400"
                        placeholder="••••••••">
                    <p class="text-xs text-gray-500 mt-1">Min. 8 caractères, avec majuscule, minuscule, chiffre et symbole</p>
                </div>
                <div>
                    <label class="block font-semibold mb-1 text-gray-700 text-sm">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-3 focus:outline-none focus:border-brand placeholder-gray-400"
                        placeholder="••••••••">
                </div>
                <div>
                    <label class="block font-semibold mb-1 text-gray-700 text-sm">Type de compte</label>
                    <select name="role" required class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-3 focus:outline-none focus:border-brand">
                        <option value="">Sélectionnez un type</option>
                        <option value="candidat" {{ old('role')=='candidat'?'selected':'' }}>Candidat</option>
                        <option value="recruteur" {{ old('role')=='recruteur'?'selected':'' }}>Recruteur</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-brand hover:bg-brandDark active:scale-[0.98] text-white font-bold py-3 rounded-full transition-all duration-150 shadow-md hover:shadow-xl hover:-translate-y-0.5">
                    S'inscrire
                </button>
            </form>
            <p class="text-center text-gray-500 mt-4 text-sm">
                Déjà un compte ? <a href="{{ route('login') }}" class="text-brand font-semibold hover:underline">Se connecter</a>
            </p>
        </div>
    </div>
</section>
@endsection
