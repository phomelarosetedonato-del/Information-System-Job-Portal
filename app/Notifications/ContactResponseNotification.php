<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Contact;

class ContactResponseNotification extends Notification
{
    use Queueable;

    protected $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("✉️ Response to Your {$this->contact->inquiry_type_display} Inquiry")
            ->greeting("Hello {$this->contact->name}!")
            ->line("Thank you for reaching out to the PWD Employment Portal.")
            ->line("We're pleased to provide a response to your inquiry.")
            ->line("**Inquiry Type:** {$this->contact->inquiry_type_display}")
            ->line("**Original Message:** \"{$this->contact->subject}\"")
            ->line("**Our Response:**")
            ->line("\"{$this->contact->response_notes}\"")
            ->action('View Your Messages', route('contact-messages.index'))
            ->line("You can view all your messages and responses in your PWD dashboard anytime.")
            ->salutation("Best regards,\n**PWD Employment Support Team**");
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'contact_response',
            'message' => "Your {$this->contact->inquiry_type_display} inquiry has been responded to",
            'contact_id' => $this->contact->id,
            'inquiry_type' => $this->contact->inquiry_type,
            'url' => route('contact-messages.show', $this->contact->id),
        ];
    }
}
