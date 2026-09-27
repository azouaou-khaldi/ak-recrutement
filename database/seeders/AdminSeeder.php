<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Crée le compte administrateur à partir du .env (ADMIN_EMAIL, ADMIN_PASSWORD, lus via config/app.php).
     * Aucun mot de passe n'est écrit dans le code. Si le compte existe déjà, il n'est pas modifié.
     */
    public function run(): void
    {
        $email = config('app.admin_email');

        if (User::where('email', $email)->exists()) {
            $this->command?->info("Compte admin déjà présent ($email) : inchangé.");
            return;
        }

        $motDePasse = config('app.admin_password') ?: Str::password(16);

        User::create([
            'name'     => 'Administrateur',
            'email'    => $email,
            'password' => $motDePasse,
            'role'     => 'admin',
        ]);

        $this->command?->info("Compte admin créé : $email");
        if (!config('app.admin_password')) {
            $this->command?->warn("Mot de passe généré (à noter, il ne sera plus affiché) : $motDePasse");
        }
    }
}
