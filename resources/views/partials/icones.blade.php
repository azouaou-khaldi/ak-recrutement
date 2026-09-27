{{--
    Icônes du site (onglet du navigateur, écran d'accueil iPhone).
    Le paramètre ?v= force le navigateur à recharger l'icône : sans lui, il peut garder en cache
    celle d'un autre projet servi à la même adresse (par exemple l'icône Laravel sur 127.0.0.1:8000).
    Changer la version ici après toute modification du logo.
--}}
@php $versionIcones = 2; @endphp
<link rel="icon" href="{{ asset('favicon.ico') }}?v={{ $versionIcones }}" sizes="16x16 32x32 48x48">
<link rel="icon" href="{{ asset('favicon.svg') }}?v={{ $versionIcones }}" type="image/svg+xml">
<link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}?v={{ $versionIcones }}">
<meta name="theme-color" content="#0d0d0d">
