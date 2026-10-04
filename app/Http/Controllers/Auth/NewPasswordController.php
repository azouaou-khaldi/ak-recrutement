<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as RegleMotDePasse;
use Illuminate\View\View;

/**
 * Page ouverte depuis le lien de l'e-mail : choix d'un nouveau mot de passe.
 */
class NewPasswordController extends Controller
{
    public function create(Request $request, string $token): View
    {
        return view('auth.reinitialiser-mot-de-passe', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            // RG09 : mot de passe fort, même règle qu'à l'inscription
            'password' => ['required', 'confirmed', RegleMotDePasse::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        // Le broker vérifie que le jeton existe, correspond à l'e-mail et n'a pas expiré (60 min),
        // puis le supprime : un lien ne peut servir qu'une seule fois.
        $statut = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $motDePasse) {
                $user->forceFill([
                    'password'       => $motDePasse, // haché automatiquement (cast "hashed" du modèle User)
                    'remember_token' => Str::random(60), // déconnecte les sessions « se souvenir de moi »
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($statut !== Password::PASSWORD_RESET) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Ce lien de réinitialisation est invalide ou a expiré. Faites une nouvelle demande.']);
        }

        return redirect()->route('login')->with('status', 'Votre mot de passe a été réinitialisé. Vous pouvez vous connecter.');
    }
}
