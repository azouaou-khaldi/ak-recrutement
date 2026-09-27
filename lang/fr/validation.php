<?php

/*
|--------------------------------------------------------------------------
| Messages de validation en français
|--------------------------------------------------------------------------
|
| Sans ce fichier, APP_LOCALE=fr affichait les clés brutes
| (« validation.required », « validation.confirmed »...) dans les formulaires.
|
*/

return [
    'accepted'       => 'Le champ :attribute doit être accepté.',
    'active_url'     => 'Le champ :attribute doit être une URL valide.',
    'after'          => 'Le champ :attribute doit être une date postérieure au :date.',
    'alpha'          => 'Le champ :attribute ne doit contenir que des lettres.',
    'alpha_dash'     => 'Le champ :attribute ne doit contenir que des lettres, chiffres, tirets et underscores.',
    'alpha_num'      => 'Le champ :attribute ne doit contenir que des lettres et des chiffres.',
    'array'          => 'Le champ :attribute doit être une liste.',
    'before'         => 'Le champ :attribute doit être une date antérieure au :date.',
    'between'        => [
        'numeric' => 'Le champ :attribute doit être compris entre :min et :max.',
        'file'    => 'Le fichier :attribute doit peser entre :min et :max ko.',
        'string'  => 'Le champ :attribute doit contenir entre :min et :max caractères.',
        'array'   => 'Le champ :attribute doit contenir entre :min et :max éléments.',
    ],
    'boolean'        => 'Le champ :attribute doit être vrai ou faux.',
    'confirmed'      => 'La confirmation du champ :attribute ne correspond pas.',
    'current_password' => 'Le mot de passe est incorrect.',
    'date'           => 'Le champ :attribute doit être une date valide.',
    'different'      => 'Les champs :attribute et :other doivent être différents.',
    'digits'         => 'Le champ :attribute doit contenir :digits chiffres.',
    'email'          => 'Le champ :attribute doit être une adresse e-mail valide.',
    'exists'         => 'La valeur sélectionnée pour :attribute est invalide.',
    'file'           => 'Le champ :attribute doit être un fichier.',
    'filled'         => 'Le champ :attribute doit avoir une valeur.',
    'image'          => 'Le champ :attribute doit être une image.',
    'in'             => 'La valeur sélectionnée pour :attribute est invalide.',
    'integer'        => 'Le champ :attribute doit être un nombre entier.',
    'max'            => [
        'numeric' => 'Le champ :attribute ne doit pas dépasser :max.',
        'file'    => 'Le fichier :attribute ne doit pas dépasser :max ko.',
        'string'  => 'Le champ :attribute ne doit pas dépasser :max caractères.',
        'array'   => 'Le champ :attribute ne doit pas contenir plus de :max éléments.',
    ],
    'mimes'          => 'Le fichier :attribute doit être de type : :values.',
    'mimetypes'      => 'Le fichier :attribute doit être de type : :values.',
    'min'            => [
        'numeric' => 'Le champ :attribute doit être au moins égal à :min.',
        'file'    => 'Le fichier :attribute doit peser au moins :min ko.',
        'string'  => 'Le champ :attribute doit contenir au moins :min caractères.',
        'array'   => 'Le champ :attribute doit contenir au moins :min éléments.',
    ],
    'not_in'         => 'La valeur sélectionnée pour :attribute est invalide.',
    'numeric'        => 'Le champ :attribute doit être un nombre.',
    'password'       => [
        'letters'       => 'Le champ :attribute doit contenir au moins une lettre.',
        'mixed'         => 'Le champ :attribute doit contenir au moins une majuscule et une minuscule.',
        'numbers'       => 'Le champ :attribute doit contenir au moins un chiffre.',
        'symbols'       => 'Le champ :attribute doit contenir au moins un symbole.',
        'uncompromised' => 'Ce :attribute est apparu dans une fuite de données. Veuillez en choisir un autre.',
    ],
    'present'        => 'Le champ :attribute doit être présent.',
    'regex'          => 'Le format du champ :attribute est invalide.',
    'required'       => 'Le champ :attribute est obligatoire.',
    'required_if'    => 'Le champ :attribute est obligatoire quand :other vaut :value.',
    'required_with'  => 'Le champ :attribute est obligatoire quand :values est renseigné.',
    'same'           => 'Les champs :attribute et :other doivent correspondre.',
    'size'           => [
        'numeric' => 'Le champ :attribute doit valoir :size.',
        'file'    => 'Le fichier :attribute doit peser :size ko.',
        'string'  => 'Le champ :attribute doit contenir :size caractères.',
        'array'   => 'Le champ :attribute doit contenir :size éléments.',
    ],
    'string'         => 'Le champ :attribute doit être une chaîne de caractères.',
    'unique'         => 'Cette valeur de :attribute est déjà utilisée.',
    'uploaded'       => 'Le fichier :attribute n\'a pas pu être envoyé.',
    'url'            => 'Le champ :attribute doit être une URL valide.',

    /*
    | Noms des champs affichés dans les messages (« Le champ e-mail est obligatoire. »)
    */
    'attributes' => [
        'name'                   => 'nom',
        'nom'                    => 'nom',
        'email'                  => 'e-mail',
        'password'               => 'mot de passe',
        'password_confirmation'  => 'confirmation du mot de passe',
        'current_password'       => 'mot de passe actuel',
        'role'                   => 'type de compte',
        'sujet'                  => 'sujet',
        'message'                => 'message',
        'reponse'                => 'réponse',
        'contenu'                => 'message',
        'titre'                  => 'titre',
        'entreprise'             => 'entreprise',
        'lieu'                   => 'lieu',
        'type_contrat'           => 'type de contrat',
        'description'            => 'description',
        'competences_requises'   => 'compétences requises',
        'salaire'                => 'salaire',
        'telephone'              => 'téléphone',
        'ville'                  => 'ville',
        'linkedin'               => 'LinkedIn',
        'portfolio'              => 'portfolio',
        'titre_poste'            => 'poste recherché',
        'disponibilite'          => 'disponibilité',
        'experience'             => 'expérience',
        'a_propos'               => 'à propos',
        'competences'            => 'compétences',
        'cv'                     => 'CV',
        'site_web'               => 'site web',
        'secteur'                => 'secteur',
        'taille_entreprise'      => 'taille de l\'entreprise',
        'description_entreprise' => 'description de l\'entreprise',
        'statut'                 => 'statut',
    ],
];
