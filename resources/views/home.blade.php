@extends('layouts.app')
@section('title', "Accueil - AK Recrutement")

@section('content')

{{-- Hero avec image --}}
<section class="relative px-6 py-24 overflow-hidden">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=2000&auto=format&fit=crop"
             alt="Équipe au travail" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent via-90% to-light/60"></div>
    </div>
    <div class="relative max-w-4xl mx-auto text-center">
        <h1 class="text-5xl font-extrabold mb-4 leading-tight text-white drop-shadow-lg">
            Trouvez l'emploi qui <span class="text-brand">vous correspond</span>
        </h1>
        <p class="text-white text-lg mb-10 drop-shadow-md">Des offres dans tous les secteurs vous attendent.</p>
        <form action="{{ route('offres.index') }}" method="GET" class="flex flex-wrap gap-3 justify-center max-w-2xl mx-auto">
            <label for="recherche-accueil" class="sr-only">Rechercher une offre</label>
            <div class="relative flex-1 min-w-0">
                <x-icone nom="loupe" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none" />
                <input type="search" id="recherche-accueil" name="recherche" placeholder="Poste, compétence ou entreprise"
                    class="w-full bg-white border border-lightBorder text-gray-900 rounded-lg pl-10 pr-4 py-3 focus:border-brand placeholder-gray-500 shadow-xs">
            </div>
            <button type="submit" class="bg-brand hover:bg-brandDark text-white font-semibold px-6 py-3 rounded-full transition">Rechercher</button>
        </form>
        <div class="flex justify-center gap-10 mt-10">
            {{-- Chiffres réels calculés dans PageController@home --}}
            <div class="bg-black/40 rounded-lg px-4 py-2"><p class="text-3xl font-bold text-brandVif">{{ number_format($stats['offres'], 0, ',', ' ') }}</p><p class="text-white text-sm">{{ $stats['offres'] > 1 ? 'Offres actives' : 'Offre active' }}</p></div>
            <div class="bg-black/40 rounded-lg px-4 py-2"><p class="text-3xl font-bold text-brandVif">{{ number_format($stats['entreprises'], 0, ',', ' ') }}</p><p class="text-white text-sm">{{ $stats['entreprises'] > 1 ? 'Entreprises' : 'Entreprise' }}</p></div>
            <div class="bg-black/40 rounded-lg px-4 py-2"><p class="text-3xl font-bold text-brandVif">{{ number_format($stats['candidats'], 0, ',', ' ') }}</p><p class="text-white text-sm">{{ $stats['candidats'] > 1 ? 'Candidats inscrits' : 'Candidat inscrit' }}</p></div>
        </div>
    </div>
</section>

{{-- À propos avec image --}}
<section class="px-6 py-20 bg-white">
    <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-10 items-center">
        <div>
            <h2 class="text-3xl font-extrabold mb-6 text-gray-900">À propos de <span class="text-brand">AK Recrutement</span></h2>
            <p class="text-gray-600 leading-relaxed text-lg">
                AK Recrutement est une plateforme de recrutement nouvelle génération, conçue pour
                <span class="text-gray-900 font-semibold">simplifier et accélérer</span> la mise en relation entre
                les entreprises ambitieuses et les talents d'exception. Que vous soyez à la recherche de votre
                prochain défi professionnel ou du candidat idéal, AK Recrutement vous accompagne à chaque étape
                avec des outils <span class="text-gray-900 font-semibold">modernes, intuitifs et efficaces</span>.
            </p>
        </div>
        <div class="rounded-2xl overflow-hidden shadow-lg border border-lightBorder">
            <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=1200&auto=format&fit=crop"
                 alt="Entretien d'embauche" class="w-full h-80 object-cover">
        </div>
    </div>
</section>

{{-- Mission et Valeurs --}}
<section class="px-6 py-16">
    <h2 class="text-3xl font-extrabold text-center mb-10 text-gray-900">Notre Mission et <span class="text-brand">Nos Valeurs</span></h2>
    <div class="max-w-5xl mx-auto grid md:grid-cols-3 gap-6">
        <div class="bg-white border border-lightBorder rounded-xl p-6 hover:border-brand hover:shadow-md transition text-center">
            <h3 class="text-xl font-bold text-brand mb-3">Notre Mission</h3>
            <p class="text-gray-600 leading-relaxed">Connecter les meilleurs talents avec les entreprises qui recherchent l'excellence, en créant des opportunités de carrière enrichissantes et durables.</p>
        </div>
        <div class="bg-white border border-lightBorder rounded-xl p-6 hover:border-brand hover:shadow-md transition text-center">
            <h3 class="text-xl font-bold text-brand mb-3">Excellence</h3>
            <p class="text-gray-600 leading-relaxed">Nous nous engageons à fournir un service de qualité supérieure, avec une attention particulière aux détails et aux besoins spécifiques de chacun.</p>
        </div>
        <div class="bg-white border border-lightBorder rounded-xl p-6 hover:border-brand hover:shadow-md transition text-center">
            <h3 class="text-xl font-bold text-brand mb-3">Innovation</h3>
            <p class="text-gray-600 leading-relaxed">Nous utilisons les dernières technologies pour améliorer constamment l'expérience de recrutement et faciliter les connexions professionnelles.</p>
        </div>
    </div>
</section>

{{-- Nos Services --}}
<section class="px-6 py-16 bg-white">
    <h2 class="text-3xl font-extrabold text-center mb-10 text-gray-900">Nos <span class="text-brand">Services</span></h2>
    <div class="max-w-5xl mx-auto grid md:grid-cols-3 gap-6">
        <div class="bg-light border border-lightBorder rounded-xl p-6 hover:border-brand hover:shadow-md transition text-center">
            <h3 class="text-xl font-bold text-brand mb-3">Gestion des Offres</h3>
            <p class="text-gray-600 leading-relaxed">Les recruteurs peuvent créer, modifier et gérer leurs offres d'emploi facilement grâce à notre interface intuitive et puissante.</p>
        </div>
        <div class="bg-light border border-lightBorder rounded-xl p-6 hover:border-brand hover:shadow-md transition text-center">
            <h3 class="text-xl font-bold text-brand mb-3">Recherche de Talents</h3>
            <p class="text-gray-600 leading-relaxed">Les candidats peuvent parcourir les offres, postuler en ligne et suivre l'état de leurs candidatures en temps réel depuis leur espace personnel.</p>
        </div>
        <div class="bg-light border border-lightBorder rounded-xl p-6 hover:border-brand hover:shadow-md transition text-center">
            <h3 class="text-xl font-bold text-brand mb-3">Messagerie Intégrée</h3>
            <p class="text-gray-600 leading-relaxed">Communiquez directement avec les recruteurs ou candidats grâce à notre système de messagerie sécurisé, rapide et efficace.</p>
        </div>
    </div>
</section>

{{-- Comment ça marche --}}
<section class="px-6 py-16">
    <h2 class="text-3xl font-extrabold text-center mb-10 text-gray-900">Comment ça <span class="text-brand">marche ?</span></h2>
    <div class="max-w-4xl mx-auto grid md:grid-cols-2 gap-6">
        <div class="bg-white border border-lightBorder rounded-xl p-6 hover:border-brand hover:shadow-md transition">
            <h3 class="text-xl font-bold text-brand mb-4">Pour les Recruteurs</h3>
            <ol class="space-y-3 text-gray-600">
                <li class="flex items-start gap-3"><span class="text-brand font-bold">1.</span> Créez votre compte recruteur</li>
                <li class="flex items-start gap-3"><span class="text-brand font-bold">2.</span> Publiez vos offres d'emploi avec tous les détails nécessaires</li>
                <li class="flex items-start gap-3"><span class="text-brand font-bold">3.</span> Recevez et consultez les candidatures</li>
                <li class="flex items-start gap-3"><span class="text-brand font-bold">4.</span> Communiquez avec les candidats via la messagerie</li>
                <li class="flex items-start gap-3"><span class="text-brand font-bold">5.</span> Gérez vos recrutements de manière efficace</li>
            </ol>
        </div>
        <div class="bg-white border border-lightBorder rounded-xl p-6 hover:border-brand hover:shadow-md transition">
            <h3 class="text-xl font-bold text-brand mb-4">Pour les Candidats</h3>
            <ol class="space-y-3 text-gray-600">
                <li class="flex items-start gap-3"><span class="text-brand font-bold">1.</span> Inscrivez-vous en tant que candidat</li>
                <li class="flex items-start gap-3"><span class="text-brand font-bold">2.</span> Parcourez les offres d'emploi disponibles</li>
                <li class="flex items-start gap-3"><span class="text-brand font-bold">3.</span> Postulez aux offres qui vous intéressent</li>
                <li class="flex items-start gap-3"><span class="text-brand font-bold">4.</span> Suivez l'état de vos candidatures</li>
                <li class="flex items-start gap-3"><span class="text-brand font-bold">5.</span> Échangez avec les recruteurs pour préparer vos entretiens</li>
            </ol>
        </div>
    </div>
</section>

{{-- CTA avec image --}}
<section class="relative px-6 py-20 text-center overflow-hidden">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?q=80&w=2000&auto=format&fit=crop"
             alt="" class="w-full h-full object-cover">
        {{-- Filtre orange très foncé : le texte blanc reste lisible quelle que soit la photo --}}
        <div class="absolute inset-0 bg-orange-950/85"></div>
    </div>
    <div class="relative">
        <h2 class="text-3xl font-extrabold text-white mb-3">Prêt à commencer avec <span class="text-brandVif">AK Recrutement</span> ?</h2>
        <p class="text-orange-50 mb-8">Rejoignez notre communauté et découvrez de nouvelles opportunités</p>
        <a href="{{ route('register') }}" class="inline-block bg-dark text-brandVif font-bold px-8 py-3 rounded-full hover:bg-darkCard transition">S'inscrire gratuitement</a>
    </div>
</section>

@endsection