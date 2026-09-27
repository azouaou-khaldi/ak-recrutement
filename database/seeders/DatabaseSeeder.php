<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * php artisan migrate --seed : crée uniquement le compte administrateur.
     * Pour ajouter les données de démonstration : php artisan db:seed --class=DemoSeeder
     */
    public function run(): void
    {
        $this->call(AdminSeeder::class);
    }
}
