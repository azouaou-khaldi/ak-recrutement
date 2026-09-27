<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReinitialisationMotDePasseMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public string $token) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Réinitialisation de votre mot de passe - AK Recrutement');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reinitialisation_mot_de_passe',
            with: [
                'lien'    => route('password.reset', ['token' => $this->token, 'email' => $this->user->email]),
                'minutes' => config('auth.passwords.users.expire'),
            ],
        );
    }
}
