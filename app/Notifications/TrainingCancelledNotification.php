<?php

namespace App\Notifications;

use App\Models\SkillTraining;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TrainingCancelledNotification extends Notification 
{
    use Queueable;

    public $training;
    public $reason;

    public function __construct(SkillTraining $training, $reason = null)
    {
        $this->training = $training;
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Training Cancelled - ' . $this->training->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We regret to inform you that the following training has been cancelled:')
            ->line('Training: ' . $this->training->title)
            ->line('Original Start Date: ' . $this->training->start_date->format('F j, Y'))
            ->line('Location: ' . $this->training->location);

        if ($this->reason) {
            $mail->line('Reason: ' . $this->reason);
        }

        $mail->line('We apologize for any inconvenience this may cause.')
            ->line('Please check our system for other available training opportunities.');

        return $mail;
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'training_cancelled',
            'training_id' => $this->training->id,
            'training_title' => $this->training->title,
            'reason' => $this->reason,
            'message' => 'Training cancelled: ' . $this->training->title
        ];
    }
}
