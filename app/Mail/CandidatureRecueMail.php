<?php

namespace App\Mail;

use App\Models\Candidature;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CandidatureRecueMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Candidature $candidature) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Nouvelle candidature reçue - ' . $this->candidature->offre->titre);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.candidature_recue');
    }
}