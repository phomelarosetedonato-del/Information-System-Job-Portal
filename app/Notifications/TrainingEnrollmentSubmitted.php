<?php

namespace App\Notifications;

use App\Models\TrainingEnrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TrainingEnrollmentSubmitted extends Notification 
{
    use Queueable;

    public $enrollment;

    public function __construct(TrainingEnrollment $enrollment)
    {
        $this->enrollment = $enrollment;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Training Enrollment Submitted - PWD System Alaminos City')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your training enrollment has been successfully submitted.')
            ->line('Training: ' . $this->enrollment->skillTraining->title)
            ->line('Start Date: ' . $this->enrollment->skillTraining->start_date->format('F j, Y'))
            ->line('Location: ' . $this->enrollment->skillTraining->location)
            ->action('View Enrollment', url('/enrollments/' . $this->enrollment->id))
            ->line('Thank you for using our PWD system!');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'training_enrollment_submitted',
            'enrollment_id' => $this->enrollment->id,
            'training_title' => $this->enrollment->skillTraining->title,
            'message' => 'Your enrollment for ' . $this->enrollment->skillTraining->title . ' has been submitted.'
        ];
    }
}
