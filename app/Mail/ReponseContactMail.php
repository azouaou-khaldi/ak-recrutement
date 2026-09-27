<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReponseContactMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Contact $contact, public string $reponse) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Re : ' . $this->contact->sujet);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.reponse_contact');
    }
}
