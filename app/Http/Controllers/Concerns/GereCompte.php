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
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
        }

        auth()->user()->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Mot de passe mis à jour.');
    }

    public function deleteCompte(Request $request): RedirectResponse
    {
        $user = auth()->user();

        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete();

        return redirect()->route('home')->with('success', 'Votre compte a été supprimé.');
    }
}
