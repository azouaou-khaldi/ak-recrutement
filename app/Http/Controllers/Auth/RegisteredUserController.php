<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\BienvenueMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function create(): \Illuminate\View\View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            // RG09 : 8 caractères minimum, majuscule, minuscule, chiffre et symbole
            'password' => ['required', 'confirmed', Rules\Password::min(8)->mixedCase()->numbers()->symbols()],
            // RG01 : impossible de s'inscrire en admin, même en modifiant le formulaire
            'role'     => 'required|in:candidat,recruteur',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // jamais stocké en clair
            'role'     => $request->role,
        ]);

        event(new Registered($user));

        // Email de bienvenue
        Mail::to($user->email)->send(new BienvenueMail($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}