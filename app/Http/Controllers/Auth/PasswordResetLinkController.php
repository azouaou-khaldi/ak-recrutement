<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

/**
 * « Mot de passe oublié » : envoie un lien de réinitialisation par e-mail.
 */
class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.mot-de-passe-oublie');
    }

    // RG13 : limité à 5 demandes par minute (throttle dans les routes) pour éviter d'inonder une boîte mail
    public function store(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        // Le broker de Laravel crée un jeton (table password_reset_tokens) et appelle
        // User::sendPasswordResetNotification(), qui envoie notre e-mail en français.
        Password::sendResetLink($request->only('email'));

        // Même message que le compte existe ou non : on ne révèle pas qui est inscrit (énumération de comptes)
        return back()->with('status', 'Si un compte existe avec cette adresse, un e-mail contenant un lien de réinitialisation vient de vous être envoyé.');
    }
}
