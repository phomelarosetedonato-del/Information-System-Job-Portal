<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmployerVerificationRejected extends Notification implements ShouldQueue
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
            ->subject('Your employer verification was not approved')
            ->greeting('Hello ' . ($notifiable->name ?? ''))
            ->line('We reviewed your employer verification request and it was not approved at this time.');

        if ($this->reason) {
            $mail->line('Reason provided by the administrator:')->line($this->reason);
        }

    $mail->line('You may resubmit verification after addressing the issues indicated.');
    // Use the existing named route for verification requirements
    $route = Route::has('employer.verification.requirements') ? route('employer.verification.requirements') : url('/');
    $mail->action('View verification instructions', $route);
        $mail->line('If you think this is a mistake, contact support.');

        return $mail;
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'employer_verification_rejected',
            'message' => 'Your employer verification was not approved.' . ($this->reason ? ' Reason: ' . $this->reason : ''),
            'reason' => $this->reason,
            'url' => route('employer.verification.status') ?? route('profile.show'),
        ];
    }
}
