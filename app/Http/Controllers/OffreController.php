<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OffreController extends Controller
{
    // Liste publique des offres : seulement les offres actives (RG05)
    public function index(Request $request): View
    {
        $query = Offre::with('recruteur')->where('active', true)->latest();

        // Recherche par poste, entreprise ou ville. Eloquent protège contre l'injection SQL (requête préparée)
        if ($request->filled('recherche')) {
            $query->where(function ($q) use ($request) {
                $q->where('titre', 'like', '%' . $request->recherche . '%')
                  ->orWhere('entreprise', 'like', '%' . $request->recherche . '%')
                  ->orWhere('lieu', 'like', '%' . $request->recherche . '%');
            });
        }

        $offres = $query->paginate(9);

        // Recherche en direct (fetch JavaScript) : on renvoie uniquement la liste des résultats
        if ($request->ajax()) {
            return view('offres.partials.resultats', compact('offres'));
        }

        return view('offres.index', compact('offres'));
    }

    public function show(Offre $offre): View
    {
        // RG05 : une offre désactivée n'est visible que par son recruteur et par l'admin
        // (404 plutôt que 403, pour ne pas révéler que l'offre existe)
        if (!$offre->active && auth()->id() !== $offre->user_id && !auth()->user()?->isAdmin()) {
            abort(404);
        }

        return view('offres.show', compact('offre'));
    }

    public function create(): View
    {
        $this->authorizeRecruteur();
        return view('offres.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeRecruteur();

        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'entreprise' => 'required|string|max:255',
            'lieu' => 'required|string|max:255',
            'type_contrat' => 'required|in:CDI,CDD,Stage,Alternance,Freelance',
            'description' => 'required|string',
            'competences_requises' => 'nullable|string',
            'salaire' => 'nullable|string|max:255',
        ]);

        // Le propriétaire vient de la session, jamais du formulaire : impossible de publier au nom d'un autre
        $data['user_id'] = auth()->id();

        $offre = Offre::create($data);

        return redirect()->route('offres.show', $offre)->with('success', 'Offre publiée avec succès.');
    }

    public function edit(Offre $offre): View
    {
        $this->authorizeOwner($offre);
        return view('offres.edit', compact('offre'));
    }

    public function update(Request $request, Offre $offre): RedirectResponse
    {
        $this->authorizeOwner($offre);

        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'entreprise' => 'required|string|max:255',
            'lieu' => 'required|string|max:255',
            'type_contrat' => 'required|in:CDI,CDD,Stage,Alternance,Freelance',
            'description' => 'required|string',
            'competences_requises' => 'nullable|string',
            'salaire' => 'nullable|string|max:255',
            'active' => 'sometimes|boolean',
        ]);

        $offre->update($data);

        return redirect()->route('offres.show', $offre)->with('success', 'Offre mise à jour.');
    }

    public function destroy(Offre $offre): RedirectResponse
    {
        $this->authorizeOwner($offre);
        $offre->delete();

        return redirect()->route('dashboard')->with('success', 'Offre supprimée.');
    }

    // RG02 : seul un recruteur publie des offres
    private function authorizeRecruteur(): void
    {
        if (!auth()->check() || !auth()->user()->isRecruteur()) {
            abort(403, 'Seuls les recruteurs peuvent publier des offres.');
        }
    }

    // Seul l'auteur de l'offre peut la modifier ou la supprimer, même un autre recruteur ne peut pas
    private function authorizeOwner(Offre $offre): void
    {
        if (auth()->id() !== $offre->user_id) {
            abort(403);
        }
    }
}
