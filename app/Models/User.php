<?php

namespace App\Models;

use App\Mail\ReinitialisationMotDePasseMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Champs autorisés dans create() et update(). Les contrôleurs filtrent aussi avec only(),
    // donc role et suspendu ne peuvent pas être changés depuis un formulaire de profil
    protected $fillable = [
        'name', 'email', 'password', 'role', 'suspendu',
        // Candidat
        'titre_poste', 'telephone', 'ville', 'linkedin', 'portfolio',
        'disponibilite', 'experience', 'a_propos', 'competences', 'cv_path',
        // Recruteur
        'entreprise', 'secteur', 'taille_entreprise', 'description_entreprise', 'site_web',
    ];

    // Jamais affichés si l'utilisateur est transformé en tableau ou en JSON
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed', // le mot de passe est haché (bcrypt) avant d'être enregistré
            'suspendu'          => 'boolean',
        ];
    }

    // RG01 : un compte a un seul rôle, stocké dans la colonne role
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isRecruteur(): bool { return $this->role === 'recruteur'; }
    public function isCandidat(): bool { return $this->role === 'candidat'; }

    /** Pourcentage de complétion du profil candidat (tableau de bord et page profil). */
    public function pourcentageProfil(): int
    {
        $champs = ['titre_poste', 'telephone', 'ville', 'disponibilite', 'experience', 'a_propos', 'competences'];
        $remplis = collect($champs)->filter(fn ($champ) => filled($this->$champ))->count();

        return (int) round($remplis / count($champs) * 100);
    }

    /**
     * Appelée par Laravel (Password::sendResetLink) : remplace l'e-mail anglais par défaut
     * par notre e-mail en français, envoyé en file d'attente comme les autres.
     */
    public function sendPasswordResetNotification($token): void
    {
        Mail::to($this->email)->send(new ReinitialisationMotDePasseMail($this, $token));
    }

    public function offres() { return $this->hasMany(Offre::class); }
    public function candidatures() { return $this->hasMany(Candidature::class); }
    public function messagesEnvoyes() { return $this->hasMany(Message::class, 'sender_id'); }
    public function messagesRecus() { return $this->hasMany(Message::class, 'receiver_id'); }

    /** Candidatures reçues sur les offres d'un recruteur */
    public function candidaturesRecues() { return $this->hasManyThrough(Candidature::class, Offre::class); }

    protected static function booted(): void
    {
        // La base supprime en cascade offres, candidatures et messages, mais pas le fichier du CV :
        // on l'efface ici pour ne pas conserver de données personnelles après la suppression du compte (RGPD).
        static::deleting(function (User $user) {
            if ($user->cv_path) {
                Storage::disk('local')->delete($user->cv_path);
                Storage::disk('public')->delete($user->cv_path);
            }
        });
    }
}
