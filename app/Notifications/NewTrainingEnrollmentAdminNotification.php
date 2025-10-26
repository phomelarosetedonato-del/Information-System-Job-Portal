<?php

namespace App\Notifications;

use App\Models\TrainingEnrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewTrainingEnrollmentAdminNotification extends Notification implements ShouldQueue
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
            ->subject('New Training Enrollment - PWD System')
            ->greeting('New Training Enrollment!')
            ->line('A new training enrollment has been submitted.')
            ->line('Participant: ' . $this->enrollment->user->name)
            ->line('Training: ' . $this->enrollment->skillTraining->title)
            ->line('Start Date: ' . $this->enrollment->skillTraining->start_date->format('F j, Y'))
            ->line('Enrolled: ' . $this->enrollment->created_at->format('F j, Y g:i A'))
            ->action('Review Enrollment', url('/admin/enrollments/' . $this->enrollment->id))
            ->line('Please review this enrollment at your earliest convenience.');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'new_training_enrollment',
            'enrollment_id' => $this->enrollment->id,
            'participant_name' => $this->enrollment->user->name,
            'training_title' => $this->enrollment->skillTraining->title,
            'message' => 'New enrollment from ' . $this->enrollment->user->name . ' for ' . $this->enrollment->skillTraining->title
        ];
    }
}
