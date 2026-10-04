<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * Actions de compte communes aux espaces candidat, recruteur et admin.
 *
 * Utilisé par CandidatController, RecruteurController et AdminController
 * (use GereCompte;) pour ne pas dupliquer ce code dans chaque contrôleur.
 * Chaque espace garde ses propres routes : seul le code est partagé.
 */
trait GereCompte
{
    // Changer son mot de passe. RG09 : le nouveau doit être fort
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        // On redemande l'ancien mot de passe : quelqu'un devant une session restée ouverte ne peut pas le changer
        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
        }

        auth()->user()->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Mot de passe mis à jour.');
    }

    // RGPD : l'utilisateur peut supprimer son compte et toutes ses données (le CV est effacé par le modèle User)
    public function deleteCompte(Request $request): RedirectResponse
    {
        // On garde l'utilisateur de côté, car après logout() auth()->user() ne le renvoie plus
        $user = auth()->user();

        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete();

        return redirect()->route('home')->with('success', 'Votre compte a été supprimé.');
    }
}
