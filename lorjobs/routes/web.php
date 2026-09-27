<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CandidatureController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// Pages publiques
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactStore'])->name('contact.store');

// Offres publiques
Route::get('/offres', [OffreController::class, 'index'])->name('offres.index');
Route::get('/offres/{offre}', [OffreController::class, 'show'])->name('offres.show');

// Authentification
Route::middleware('guest')->group(function () {
    Route::get('/inscription', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/inscription', [RegisteredUserController::class, 'store']);
    Route::get('/connexion', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/connexion', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/deconnexion', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/tableau-de-bord', [PageController::class, 'dashboard'])->name('dashboard');

    // Offres
    Route::get('/offres/creer', [OffreController::class, 'create'])->name('offres.create');
    Route::post('/offres', [OffreController::class, 'store'])->name('offres.store');
    Route::get('/offres/{offre}/modifier', [OffreController::class, 'edit'])->name('offres.edit');
    Route::put('/offres/{offre}', [OffreController::class, 'update'])->name('offres.update');
    Route::delete('/offres/{offre}', [OffreController::class, 'destroy'])->name('offres.destroy');

    // Candidatures
    Route::post('/offres/{offre}/postuler', [CandidatureController::class, 'store'])->name('candidatures.store');
    Route::patch('/candidatures/{candidature}/statut', [CandidatureController::class, 'updateStatut'])->name('candidatures.statut');

    // Messagerie
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');

    // Administration
    Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        // Utilisateurs
        Route::get('/utilisateurs', [AdminController::class, 'users'])->name('users');
        Route::get('/utilisateurs/{user}', [AdminController::class, 'showUser'])->name('users.show');
        Route::patch('/utilisateurs/{user}/toggle', [AdminController::class, 'toggleUser'])->name('users.toggle');
        Route::delete('/utilisateurs/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');

        // Offres
        Route::get('/offres', [AdminController::class, 'offres'])->name('offres');
        Route::patch('/offres/{offre}/approuver', [AdminController::class, 'approuverOffre'])->name('offres.approuver');
        Route::patch('/offres/{offre}/toggle', [AdminController::class, 'toggleOffre'])->name('offres.toggle');
        Route::delete('/offres/{offre}', [AdminController::class, 'deleteOffre'])->name('offres.delete');

        // Candidatures
        Route::get('/candidatures', [AdminController::class, 'candidatures'])->name('candidatures');

        // Modération
        Route::get('/signalements', [AdminController::class, 'signalements'])->name('signalements');
        Route::patch('/signalements/{signalement}/traiter', [AdminController::class, 'traiterSignalement'])->name('signalements.traiter');
        Route::get('/approbations', [AdminController::class, 'approbations'])->name('approbations');

        // Contacts
        Route::get('/contacts', [AdminController::class, 'contacts'])->name('contacts');
        Route::patch('/contacts/{contact}/lu', [AdminController::class, 'marquerContactLu'])->name('contacts.lu');
        Route::delete('/contacts/{contact}', [AdminController::class, 'deleteContact'])->name('contacts.delete');

        // Stats et paramètres
        Route::get('/statistiques', [AdminController::class, 'stats'])->name('stats');
        Route::get('/parametres', [AdminController::class, 'parametres'])->name('parametres');
        Route::put('/parametres', [AdminController::class, 'updateParametres'])->name('parametres.update');
        Route::put('/parametres/password', [AdminController::class, 'updatePassword'])->name('parametres.password');
    });
});
