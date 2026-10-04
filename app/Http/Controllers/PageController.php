<?php

namespace App\Http\Controllers;

use App\Mail\ContactAdminMail;
use App\Models\Contact;
use App\Models\Offre;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PageController extends Controller
{
    // Page d'accueil : les chiffres affichés viennent de la base, pas de valeurs inventées
    public function home(): View
    {
        $stats = [
            'offres'      => Offre::where('active', true)->count(),
            // Entreprises qui recrutent actuellement (noms distincts sur les offres actives)
            'entreprises' => Offre::where('active', true)->distinct()->count('entreprise'),
            'candidats'   => User::where('role', 'candidat')->count(),
        ];

        return view('home', compact('stats'));
    }

    public function contact(): View
    {
        return view('contact');
    }

    // Formulaire de contact, ouvert aux visiteurs. RG13 : limité à 5 envois par minute (throttle dans les routes)
    public function contactStore(Request $request): RedirectResponse
    {
        $request->validate([
            'nom'     => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'sujet'   => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $contact = Contact::create($request->only('nom', 'email', 'sujet', 'message'));

        // Email à l'admin (adresse lue dans le .env, pas écrite dans le code)
        Mail::to(config('app.admin_email'))->send(new ContactAdminMail($contact));

        return back()->with('success', 'Votre message a bien été envoyé.');
    }

    // Une seule adresse /tableau-de-bord : on envoie chaque utilisateur vers le tableau de bord de son rôle
    public function dashboard(): RedirectResponse|View
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isRecruteur()) {
            return app(RecruteurController::class)->dashboard();
        }

        return app(CandidatController::class)->dashboard();
    }
}