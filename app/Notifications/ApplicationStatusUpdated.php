<?php

namespace App\Notifications;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusUpdated extends Notification 
{
    use Queueable;

    public $application;
    public $oldStatus;
    public $newStatus;

    public function __construct(JobApplication $application, $oldStatus, $newStatus)
    {
        $this->application = $application;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $statusColors = [
            'pending' => '#6c757d',
            'reviewed' => '#17a2b8',
            'shortlisted' => '#ffc107',
            'interview' => '#fd7e14',
            'accepted' => '#28a745',
            'rejected' => '#dc3545'
        ];

        return (new MailMessage)
            ->subject('Application Status Updated - ' . $this->application->jobPosting->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your job application status has been updated.')
            ->line('Position: ' . $this->application->jobPosting->title)
            ->line('Company: ' . $this->application->jobPosting->company)
            ->line('Previous Status: ' . ucfirst($this->oldStatus))
            ->line('New Status: **' . ucfirst($this->newStatus) . '**')
            ->line('Update Date: ' . now()->format('F j, Y'))
            ->action('View Application Details', url('/applications/' . $this->application->id))
            ->line('Thank you for your interest in this position.');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'application_status_updated',
            'application_id' => $this->application->id,
            'job_title' => $this->application->jobPosting->title,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'message' => 'Your application for ' . $this->application->jobPosting->title . ' has been updated to ' . $this->newStatus
        ];
    }
}
