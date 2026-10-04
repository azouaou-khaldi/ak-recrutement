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
    // Le candidat postule à une offre
    public function store(Request $request, Offre $offre): RedirectResponse
    {
        // RG03 : seul un candidat peut postuler
        if (!auth()->user()->isCandidat()) {
            abort(403, 'Seuls les candidats peuvent postuler.');
        }

        // RG05 : on ne postule pas à une offre désactivée
        if (!$offre->active) {
            abort(404);
        }

        $request->validate([
            'message' => 'nullable|string',
        ]);

        // RG04 : une seule candidature par offre. firstOrCreate renvoie l'existante au lieu d'en créer une 2e
        // (la base a aussi une contrainte unique sur offre_id + user_id)
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

    // Accepter ou refuser une candidature
    public function updateStatut(Request $request, Candidature $candidature): RedirectResponse
    {
        // RG06 : le recruteur propriétaire de l'offre, ou l'admin (page « Gestion des candidatures »)
        if (auth()->id() !== $candidature->offre->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        // in: refuse toute autre valeur envoyée à la main dans la requête
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