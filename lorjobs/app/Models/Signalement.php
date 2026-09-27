<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Signalement extends Model
{
    protected $fillable = ['signaleur_id', 'type', 'offre_id', 'cible_id', 'raison', 'statut'];

    public function signaleur() { return $this->belongsTo(User::class, 'signaleur_id'); }
    public function offre() { return $this->belongsTo(Offre::class); }
    public function cible() { return $this->belongsTo(User::class, 'cible_id'); }
}
