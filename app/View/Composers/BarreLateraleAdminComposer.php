<?php

namespace App\View\Composers;

use App\Models\Candidature;
use App\Models\Contact;
use App\Models\Offre;
use App\Models\User;
use Illuminate\View\View;

/**
 * Fournit les compteurs de la barre latérale admin à layouts/admin.blade.php.
 *
 * Le layout est partagé par toutes les pages admin : un View Composer évite
 * de répéter ces requêtes dans chaque méthode de contrôleur, tout en gardant
 * la vue sans requête SQL (respect du modèle MVC).
 */
class BarreLateraleAdminComposer
{
    public function compose(View $view): void
    {
        $view->with('compteurs', [
            'utilisateurs'   => User::count(),
            'offres'         => Offre::count(),
            'candidatures'   => Candidature::count(),
            'contactsNonLus' => Contact::where('lu', false)->count(),
        ]);
    }
}
