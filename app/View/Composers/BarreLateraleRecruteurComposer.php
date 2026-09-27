<?php

namespace App\View\Composers;

use App\Models\Candidature;
use Illuminate\View\View;

/**
 * Fournit les compteurs de la barre latérale recruteur à layouts/recruteur.blade.php
 * (voir BarreLateraleAdminComposer pour le principe).
 */
class BarreLateraleRecruteurComposer
{
    public function compose(View $view): void
    {
        $recruteur = auth()->user();

        $view->with('compteurs', [
            'offres'                 => $recruteur->offres()->count(),
            'candidaturesEnAttente'  => Candidature::whereHas('offre', fn ($q) => $q->where('user_id', $recruteur->id))
                                            ->where('statut', 'en_attente')
                                            ->count(),
        ]);
    }
}
