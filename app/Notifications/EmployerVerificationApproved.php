<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmployerVerificationApproved extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reason;

    public function __construct(?string $reason = null)
    {
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Your employer verification has been approved')
            ->greeting('Hello ' . ($notifiable->name ?? ''))
            ->line('Good news — your employer verification has been approved by the administrator.')
            ->line('Your account now has verified employer privileges.');

        if ($this->reason) {
            $mail->line('Notes from the administrator:')->line($this->reason);
        }

        $mail->action('Go to your profile', url(route('profile.show')));
        $mail->line('If you have questions, reply to this email or contact support.');

        return $mail;
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'employer_verification_approved',
            'message' => 'Your employer verification has been approved.',
            'url' => route('profile.show'),
        ];
    }
}
