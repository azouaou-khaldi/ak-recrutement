<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CandidatureController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CandidatController;
use App\Http\Controllers\RecruteurController;
use Illuminate\Support\Facades\Route;

// Pages publiques
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactStore'])->middleware('throttle:5,1')->name('contact.store');

// Offres — liste publique
Route::get('/offres', [OffreController::class, 'index'])->name('offres.index');

// Authentification
Route::middleware('guest')->group(function () {
    Route::get('/inscription', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/inscription', [RegisteredUserController::class, 'store'])->middleware('throttle:5,1');
    Route::get('/connexion', [AuthenticatedSessionController::class, 'create'])->name('login');
    // Limite à 5 tentatives par minute (protection contre la force brute)
    Route::post('/connexion', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/deconnexion', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/tableau-de-bord', [PageController::class, 'dashboard'])->name('dashboard');

    // Offres — création/modif réservée aux recruteurs (AVANT offres/{offre} !)
    Route::middleware('role:recruteur')->group(function () {
        Route::get('/offres/creer', [OffreController::class, 'create'])->name('offres.create');
        Route::post('/offres', [OffreController::class, 'store'])->name('offres.store');
        Route::get('/offres/{offre}/modifier', [OffreController::class, 'edit'])->name('offres.edit');
        Route::put('/offres/{offre}', [OffreController::class, 'update'])->name('offres.update');
        Route::delete('/offres/{offre}', [OffreController::class, 'destroy'])->name('offres.destroy');
    });

    // Candidatures — postuler réservé aux candidats
    Route::middleware('role:candidat')->group(function () {
        Route::post('/offres/{offre}/postuler', [CandidatureController::class, 'store'])->name('candidatures.store');
    });
    Route::patch('/candidatures/{candidature}/statut', [CandidatureController::class, 'updateStatut'])->name('candidatures.statut');

    // CV — accès contrôlé (candidat, admin, ou recruteur concerné)
    Route::get('/cv/{user}', [CandidatController::class, 'cvTelecharger'])->name('cv.telecharger');

    // Messagerie
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');

    // Recruteur — réservé au rôle recruteur
    Route::middleware('role:recruteur')->group(function () {
        Route::get('/recruteur/offres', [RecruteurController::class, 'offres'])->name('recruteur.offres');
        Route::get('/recruteur/candidatures', [RecruteurController::class, 'candidatures'])->name('recruteur.candidatures');
        Route::get('/recruteur/candidats/{candidat}', [RecruteurController::class, 'voirCandidat'])->name('recruteur.candidat.show');
        Route::get('/recruteur/profil', [RecruteurController::class, 'profil'])->name('recruteur.profil');
        Route::get('/recruteur/profil/modifier', [RecruteurController::class, 'profilEdit'])->name('recruteur.profil.edit');
        Route::put('/recruteur/profil', [RecruteurController::class, 'profilUpdate'])->name('recruteur.profil.update');
        Route::get('/recruteur/parametres', [RecruteurController::class, 'parametres'])->name('recruteur.parametres');
        Route::put('/recruteur/parametres/password', [RecruteurController::class, 'updatePassword'])->name('recruteur.parametres.password');
        Route::delete('/recruteur/compte', [RecruteurController::class, 'deleteCompte'])->name('recruteur.compte.delete');
    });

    // Candidat — réservé au rôle candidat
    Route::middleware('role:candidat')->group(function () {
        Route::get('/mon-profil', [CandidatController::class, 'profil'])->name('candidat.profil');
        Route::get('/mon-profil/modifier', [CandidatController::class, 'profilEdit'])->name('candidat.profil.edit');
        Route::put('/mon-profil', [CandidatController::class, 'profilUpdate'])->name('candidat.profil.update');
        Route::post('/mon-profil/cv', [CandidatController::class, 'cvUpload'])->name('candidat.cv.upload');
        Route::delete('/mon-profil/cv', [CandidatController::class, 'cvDelete'])->name('candidat.cv.delete');
        Route::get('/mes-candidatures', [CandidatController::class, 'candidatures'])->name('candidat.candidatures');
        Route::get('/parametres', [CandidatController::class, 'parametres'])->name('candidat.parametres');
        Route::put('/parametres/password', [CandidatController::class, 'updatePassword'])->name('candidat.parametres.password');
        Route::delete('/mon-compte', [CandidatController::class, 'deleteCompte'])->name('candidat.compte.delete');
    });

    // Administration — réservé au rôle admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/utilisateurs', [AdminController::class, 'users'])->name('users');
        Route::get('/utilisateurs/{user}', [AdminController::class, 'showUser'])->name('users.show');
        Route::patch('/utilisateurs/{user}/toggle', [AdminController::class, 'toggleUser'])->name('users.toggle');
        Route::delete('/utilisateurs/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
        Route::get('/offres', [AdminController::class, 'offres'])->name('offres');
        Route::patch('/offres/{offre}/toggle', [AdminController::class, 'toggleOffre'])->name('offres.toggle');
        Route::delete('/offres/{offre}', [AdminController::class, 'deleteOffre'])->name('offres.delete');
        Route::get('/candidatures', [AdminController::class, 'candidatures'])->name('candidatures');
        Route::get('/contacts', [AdminController::class, 'contacts'])->name('contacts');
        Route::patch('/contacts/{contact}/lu', [AdminController::class, 'marquerContactLu'])->name('contacts.lu');
        Route::delete('/contacts/{contact}', [AdminController::class, 'deleteContact'])->name('contacts.delete');
        Route::get('/statistiques', [AdminController::class, 'stats'])->name('stats');
        Route::get('/parametres', [AdminController::class, 'parametres'])->name('parametres');
        Route::put('/parametres', [AdminController::class, 'updateParametres'])->name('parametres.update');
        Route::put('/parametres/password', [AdminController::class, 'updatePassword'])->name('parametres.password');
    });
});

// Offre publique par ID — TOUJOURS EN DERNIER (sinon capte "creer" comme un ID)
Route::get('/offres/{offre}', [OffreController::class, 'show'])->name('offres.show');