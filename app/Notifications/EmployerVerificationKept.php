<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmployerVerificationKept extends Notification implements ShouldQueue
{
    use Queueable;

    protected $note;

    public function __construct(?string $note = null)
    {
        $this->note = $note;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Your employer verification status has been reviewed')
            ->greeting('Hello ' . ($notifiable->name ?? ''))
            ->line('An administrator reviewed your employer verification request and decided to keep the current status.');

        if ($this->note) {
            $mail->line('Administrator note:')->line($this->note);
        }

        $mail->line('If you would like clarification, please contact support or resubmit later if applicable.');
        $mail->action('View verification status', url(route('profile.show')));

        return $mail;
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'employer_verification_kept',
            'message' => 'Your employer verification status was reviewed. Administrator note: ' . ($this->note ?? ''),
            'note' => $this->note,
            'url' => route('profile.show'),
        ];
    }
}
