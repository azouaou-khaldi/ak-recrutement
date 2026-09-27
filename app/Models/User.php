<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'suspendu',
        // Candidat
        'titre_poste', 'telephone', 'ville', 'linkedin', 'portfolio',
        'disponibilite', 'experience', 'a_propos', 'competences', 'cv_path',
        // Recruteur
        'entreprise', 'secteur', 'taille_entreprise', 'description_entreprise', 'site_web',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'suspendu'          => 'boolean',
        ];
    }

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isRecruteur(): bool { return $this->role === 'recruteur'; }
    public function isCandidat(): bool { return $this->role === 'candidat'; }

    public function offres() { return $this->hasMany(Offre::class); }
    public function candidatures() { return $this->hasMany(Candidature::class); }
    public function messagesEnvoyes() { return $this->hasMany(Message::class, 'sender_id'); }
    public function messagesRecus() { return $this->hasMany(Message::class, 'receiver_id'); }
}
