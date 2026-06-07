<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Contact;

class NewContactMessage extends Notification
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
            ->subject("🔔 New Contact Message from {$this->contact->name}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("A new contact message has been received from the PWD Job Portal.")
            ->line("**From:** {$this->contact->name}")
            ->line("**Email:** {$this->contact->email}")
            ->line("**Inquiry Type:** {$this->contact->inquiry_type_display}")
            ->line("**Message:**")
            ->line("\"{$this->contact->message}\"")
            ->action('View Message', route('admin.contacts.show', $this->contact->id))
            ->line("You can respond to this message in the admin panel.")
            ->salutation("Best regards,\n**PWD Employment System**");
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'new_contact_message',
            'message' => "New contact message from {$this->contact->name}",
            'contact_id' => $this->contact->id,
            'contact_name' => $this->contact->name,
            'contact_email' => $this->contact->email,
            'inquiry_type' => $this->contact->inquiry_type,
            'url' => route('admin.contacts.show', $this->contact->id),
        ];
    }
}
