<?php

namespace App\Http\Controllers;

use App\Mail\CandidatureRecueMail;
use App\Mail\StatutCandidatureMail;
use App\Models\Candidature;
use App\Models\Offre;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CandidatureController extends Controller
{
    public function store(Request $request, Offre $offre): RedirectResponse
    {
        if (!auth()->user()->isCandidat()) {
            abort(403, 'Seuls les candidats peuvent postuler.');
        }

        if (!$offre->active) {
            abort(404);
        }

        $request->validate([
            'message' => 'nullable|string',
        ]);

        $candidature = Candidature::firstOrCreate(
            ['offre_id' => $offre->id, 'user_id' => auth()->id()],
            ['message' => $request->message]
        );

        // Déjà postulé : pas de nouvel e-mail au recruteur
        if (!$candidature->wasRecentlyCreated) {
            return back()->with('error', 'Vous avez déjà postulé à cette offre.');
        }

        // Email au recruteur
        Mail::to($offre->recruteur->email)->send(new CandidatureRecueMail($candidature));

        return back()->with('success', 'Votre candidature a bien été envoyée.');
    }

    public function updateStatut(Request $request, Candidature $candidature): RedirectResponse
    {
        // Le recruteur propriétaire de l'offre, ou l'admin (page « Gestion des candidatures »)
        if (auth()->id() !== $candidature->offre->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'statut' => 'required|in:en_attente,acceptee,refusee',
        ]);

        $candidature->update(['statut' => $request->statut]);

        // Email au candidat si statut change
        if (in_array($request->statut, ['acceptee', 'refusee'])) {
            Mail::to($candidature->candidat->email)->send(new StatutCandidatureMail($candidature));
        }

        return back()->with('success', 'Statut de la candidature mis à jour.');
    }
}