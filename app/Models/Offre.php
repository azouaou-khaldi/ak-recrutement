<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offre extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titre',
        'entreprise',
        'lieu',
        'type_contrat',
        'description',
        'competences_requises',
        'salaire',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    // L'auteur de l'offre (colonne user_id)
    public function recruteur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function candidatures()
    {
        return $this->hasMany(Candidature::class);
    }

    // Sert à afficher « Déjà postulé » au lieu du bouton (RG04). Visiteur non connecté : false
    public function aPostule(?User $user): bool
    {
        if (!$user) {
            return false;
        }
        return $this->candidatures()->where('user_id', $user->id)->exists();
    }
}
