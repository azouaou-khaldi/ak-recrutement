<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\GereCompte;
use App\Models\Candidature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecruteurController extends Controller
{
    use GereCompte;

    public function dashboard(): View
    {
        $stats = [
            'offres'              => auth()->user()->offres()->count(),
            'offres_actives'      => auth()->user()->offres()->where('active', true)->count(),
            'candidatures'        => Candidature::whereHas('offre', fn($q) => $q->where('user_id', auth()->id()))->count(),
            'en_attente'          => Candidature::whereHas('offre', fn($q) => $q->where('user_id', auth()->id()))->where('statut', 'en_attente')->count(),
            'candidatures_semaine' => Candidature::whereHas('offre', fn($q) => $q->where('user_id', auth()->id()))
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        $dernieres_candidatures = Candidature::whereHas('offre', fn($q) => $q->where('user_id', auth()->id()))
            ->with(['candidat', 'offre'])->latest()->take(5)->get();

        $dernieres_offres = auth()->user()->offres()->withCount('candidatures')->latest()->take(4)->get();

        return view('dashboard.recruteur', compact('stats', 'dernieres_candidatures', 'dernieres_offres'));
    }

    public function offres(): View
    {
        $offres = auth()->user()->offres()->withCount('candidatures')->latest()->paginate(10);
        return view('recruteur.offres', compact('offres'));
    }

    public function candidatures(Request $request): View
    {
        $query = Candidature::whereHas('offre', fn($q) => $q->where('user_id', auth()->id()))
            ->with(['candidat', 'offre'])->latest();

        if ($request->filled('offre')) {
            $query->where('offre_id', $request->offre);
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $candidatures = $query->paginate(15);
        // Liste des offres pour le filtre
        $mesOffres = auth()->user()->offres()->orderBy('titre')->get(['id', 'titre']);

        return view('recruteur.candidatures', compact('candidatures', 'mesOffres'));
    }

    public function voirCandidat(\App\Models\User $candidat): View
    {
        if (!$candidat->isCandidat()) {
            abort(404);
        }

        // Sécurité : le recruteur ne peut voir que les candidats ayant postulé à l'une de ses offres
        $lien = $candidat->candidatures()->whereHas('offre', fn($q) => $q->where('user_id', auth()->id()))->exists();
        if (!$lien) {
            abort(403, 'Ce candidat n\'a pas postulé à l\'une de vos offres.');
        }

        $candidatures = $candidat->candidatures()
            ->whereHas('offre', fn($q) => $q->where('user_id', auth()->id()))
            ->with('offre')->latest()->get();

        return view('recruteur.candidat_show', compact('candidat', 'candidatures'));
    }

    public function profil(): View
    {
        return view('recruteur.profil', ['nbOffres' => auth()->user()->offres()->count()]);
    }

    public function profilEdit(): View
    {
        return view('recruteur.profil_edit');
    }

    public function profilUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'name'                   => 'required|string|max:255',
            'email'                  => 'required|email|unique:users,email,' . auth()->id(),
            'telephone'              => 'nullable|string|max:20',
            'site_web'               => 'nullable|string|max:255',
            'entreprise'             => 'nullable|string|max:255',
            'secteur'                => 'nullable|string|max:100',
            'taille_entreprise'      => 'nullable|string|max:50',
            'description_entreprise' => 'nullable|string',
        ]);

        auth()->user()->update($request->only([
            'name', 'email', 'telephone', 'site_web',
            'entreprise', 'secteur', 'taille_entreprise', 'description_entreprise'
        ]));

        return redirect()->route('recruteur.profil')->with('success', 'Profil mis à jour.');
    }

    public function parametres(): View
    {
        return view('recruteur.parametres');
    }
}
