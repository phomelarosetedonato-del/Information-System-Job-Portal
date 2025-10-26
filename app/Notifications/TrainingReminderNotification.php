<?php

namespace App\Notifications;

use App\Models\TrainingEnrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TrainingReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $enrollment;
    public $daysUntil;

    public function __construct(TrainingEnrollment $enrollment, $daysUntil)
    {
        $this->enrollment = $enrollment;
        $this->daysUntil = $daysUntil;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Training Reminder - ' . $this->enrollment->skillTraining->title . ' starts in ' . $this->daysUntil . ' days')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('This is a friendly reminder about your upcoming training.')
            ->line('Training: ' . $this->enrollment->skillTraining->title)
            ->line('Start Date: ' . $this->enrollment->skillTraining->start_date->format('F j, Y'))
            ->line('Location: ' . $this->enrollment->skillTraining->location)
            ->line('The training will start in ' . $this->daysUntil . ' days.')
            ->action('View Training Details', url('/enrollments/' . $this->enrollment->id))
            ->line('We look forward to seeing you there!');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'training_reminder',
            'enrollment_id' => $this->enrollment->id,
            'training_title' => $this->enrollment->skillTraining->title,
            'days_until' => $this->daysUntil,
            'message' => 'Reminder: ' . $this->enrollment->skillTraining->title . ' starts in ' . $this->daysUntil . ' days'
        ];
    }
}
