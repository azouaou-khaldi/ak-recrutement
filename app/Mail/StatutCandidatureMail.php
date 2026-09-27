<?php

namespace App\Mail;

use App\Models\Candidature;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StatutCandidatureMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Candidature $candidature) {}

    public function envelope(): Envelope
    {
        $statut = $this->candidature->statut === 'acceptee' ? 'Acceptée ✓' : 'Refusée';
        return new Envelope(subject: 'Votre candidature a été ' . $statut . ' - AK Recrutement');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.statut_candidature');
    }
}