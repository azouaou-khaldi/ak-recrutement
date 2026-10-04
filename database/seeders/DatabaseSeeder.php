<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * php artisan migrate --seed : crée uniquement le compte administrateur.
     * Pour ajouter les données de démonstration : php artisan db:seed --class=DemoSeeder
     * (pas appelé ici, pour ne jamais mettre de faux comptes sur le vrai site par erreur)
     */
    public function run(): void
    {
        $this->call(AdminSeeder::class);
    }
}
