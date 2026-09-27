@extends('layouts.app')
@section('title', "Contact - AK Recrutement")

@section('content')
<section class="min-h-[85vh] px-6 py-16 bg-gradient-to-b from-orange-50/40 to-light">
    <div class="max-w-2xl mx-auto text-center mb-10">
        <h1 class="text-3xl font-extrabold mb-2 text-gray-900">Contactez <span class="text-brand">AK Recrutement</span></h1>
        <p class="text-gray-600">Nous sommes là pour répondre à toutes vos questions</p>
    </div>

    <div class="max-w-2xl mx-auto">

        

        <div class="bg-white border border-lightBorder shadow-lg rounded-2xl p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-1">Envoyez-nous un message</h2>
            <p class="text-gray-600 text-sm mb-6">Remplissez le formulaire ci-dessous et nous vous répondrons rapidement</p>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('contact.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold mb-1 text-gray-700 text-sm">Nom complet</label>
                        <input type="text" name="nom" value="{{ old('nom') }}" required
                            class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand/20 focus:border-brand focus:shadow-md transition placeholder-gray-500" placeholder="Votre nom">
                    </div>
                    <div>
                        <label class="block font-semibold mb-1 text-gray-700 text-sm">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand/20 focus:border-brand focus:shadow-md transition placeholder-gray-500" placeholder="votre.email@exemple.com">
                    </div>
                </div>
                <div>
                    <label class="block font-semibold mb-1 text-gray-700 text-sm">Sujet</label>
                    <input type="text" name="sujet" value="{{ old('sujet') }}" required
                        class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand/20 focus:border-brand focus:shadow-md transition placeholder-gray-500" placeholder="Objet de votre message">
                </div>
                <div>
                    <label class="block font-semibold mb-1 text-gray-700 text-sm">Message</label>
                    <textarea name="message" rows="5" required
                        class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand/20 focus:border-brand focus:shadow-md transition placeholder-gray-500" placeholder="Votre message...">{{ old('message') }}</textarea>
                </div>
                <button type="submit" class="w-full bg-brand hover:bg-brandDark active:scale-[0.98] text-white font-bold py-3.5 rounded-full transition-all duration-150 shadow-md hover:shadow-xl hover:-translate-y-0.5">
                    Envoyer le message
                </button>
            </form>
        </div>
    </div>
</section>
@endsection