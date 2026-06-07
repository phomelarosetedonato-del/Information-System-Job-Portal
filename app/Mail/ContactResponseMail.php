<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use App\Models\Contact;

class ContactResponseMail extends Mailable
{
    public $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "✉️ Response to Your {$this->contact->inquiry_type_display} Inquiry",
            to: $this->contact->email,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-response',
            with: [
                'contact' => $this->contact,
            ],
        );
    }
}
