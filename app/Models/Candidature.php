<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    use HasFactory;

    protected $fillable = [
        'offre_id',
        'user_id',
        'message',
        'cv_path',
        'statut',
    ];

    /** Libellés affichés pour chaque statut (avec les accents, contrairement aux valeurs stockées) */
    public const STATUTS = [
        'en_attente' => 'En attente',
        'acceptee'   => 'Acceptée',
        'refusee'    => 'Refusée',
    ];

    /** $candidature->libelle_statut → « Acceptée » */
    public function getLibelleStatutAttribute(): string
    {
        return self::STATUTS[$this->statut] ?? $this->statut;
    }

    public function offre()
    {
        return $this->belongsTo(Offre::class);
    }

    public function candidat()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
