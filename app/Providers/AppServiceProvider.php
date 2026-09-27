<?php

namespace App\Providers;

use App\View\Composers\BarreLateraleAdminComposer;
use App\View\Composers\BarreLateraleRecruteurComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Données des barres latérales, injectées dans les layouts (aucune requête SQL dans les vues)
        View::composer('layouts.admin', BarreLateraleAdminComposer::class);
        View::composer('layouts.recruteur', BarreLateraleRecruteurComposer::class);
    }
}
