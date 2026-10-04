<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create(): \Illuminate\View\View
    {
        return view('auth.login');
    }

    // Connexion. RG13 : 5 tentatives par minute maximum (throttle dans les routes) contre la force brute
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Même message si l'e-mail ou le mot de passe est faux : on ne dit pas lequel des deux
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
            ]);
        }

        // Nouvel identifiant de session après connexion (protège contre la fixation de session)
        $request->session()->regenerate();

        // Retour à la page demandée avant la connexion, sinon le tableau de bord du rôle
        return redirect()->intended(route('dashboard'));
    }

    // Déconnexion : on vide la session et on change le jeton CSRF
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}