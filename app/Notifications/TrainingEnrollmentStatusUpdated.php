<?php
// app/Notifications/TrainingEnrollmentStatusUpdated.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\TrainingEnrollment;

class TrainingEnrollmentStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public $enrollment;
    public $status;

    public function __construct(TrainingEnrollment $enrollment, $status)
    {
        $this->enrollment = $enrollment;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $training = $this->enrollment->skillTraining;

        return (new MailMessage)
                    ->subject("Training Enrollment Update - {$training->title}")
                    ->greeting("Hello {$notifiable->name},")
                    ->line("Your enrollment for **{$training->title}** has been updated.")
                    ->line("**New Status:** " . ucfirst($this->status))
                    ->line("**Training:** {$training->title}")
                    ->line("**Date:** {$training->start_date} to {$training->end_date}")
                    ->line("**Location:** {$training->location}")
                    ->action('View Enrollment', route('enrollments.show', $this->enrollment))
                    ->line('Thank you for using Alaminos City PWD System!');
    }

    public function toArray($notifiable)
    {
        $training = $this->enrollment->skillTraining;

        return [
            'type' => 'training_enrollment_status',
            'enrollment_id' => $this->enrollment->id,
            'training_title' => $training->title,
            'status' => $this->status,
            'message' => "Your enrollment for {$training->title} has been {$this->status}.",
            'url' => route('enrollments.show', $this->enrollment),
        ];
    }
}
