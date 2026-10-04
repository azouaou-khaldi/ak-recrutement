<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

// RG11 : vérifié à chaque requête, donc un compte suspendu est déconnecté tout de suite,
// même s'il était déjà connecté au moment de la suspension
class CheckSuspendu
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->suspendu) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors([
                'email' => 'Votre compte a été suspendu. Contactez l\'administrateur.'
            ]);
        }

        return $next($request);
    }
}