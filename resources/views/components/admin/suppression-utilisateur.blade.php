{{--
    Formulaire de suppression d'un utilisateur par l'admin.
    Le détail de ce qui sera supprimé (selon le rôle) s'affiche dans la popup <x-modale-confirmation>
    (resources/js/confirmation.js). Sans JavaScript, la confirmation native du navigateur prend le relais.
--}}
@props(['user', 'libelle' => 'Supprimer', 'classeBouton' => ''])

@php
    $pluriel = fn (int $n, string $singulier, string $pluriel) => $n . ' ' . ($n > 1 ? $pluriel : $singulier);
    $nbMessages = ($user->messages_envoyes_count ?? $user->messagesEnvoyes()->count())
                + ($user->messages_recus_count ?? $user->messagesRecus()->count());

    $elements = [];
    if ($user->isRecruteur()) {
        $nbOffres = $user->offres_count ?? $user->offres()->count();
        $nbRecues = $user->candidatures_recues_count ?? $user->candidaturesRecues()->count();
        $elements[] = $pluriel($nbOffres, 'offre publiée', 'offres publiées');
        $elements[] = $pluriel($nbRecues, 'candidature reçue', 'candidatures reçues') . ' sur ces offres';
    } else {
        $elements[] = $pluriel($user->candidatures_count ?? $user->candidatures()->count(), 'candidature', 'candidatures');
        if ($user->cv_path) {
            $elements[] = 'son CV (fichier supprimé du serveur)';
        }
    }
    $elements[] = $pluriel($nbMessages, 'message', 'messages') . ' de messagerie (envoyés et reçus)';
@endphp

<form method="POST" action="{{ route('admin.users.delete', $user) }}"
      onsubmit="return confirm('Supprimer définitivement le compte de {{ addslashes($user->name) }} ?')"
      data-confirmation-titre="Supprimer le compte de {{ $user->name }} ?"
      data-confirmation-message="Cette action est définitive. Seront également supprimés :"
      data-confirmation-elements="{{ json_encode($elements, JSON_UNESCAPED_UNICODE) }}"
      data-confirmation-bouton="Supprimer définitivement"
      {{ $attributes }}>
    @csrf @method('DELETE')
    <button class="{{ $classeBouton }}">{{ $libelle }}</button>
</form>
