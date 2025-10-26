<?php

namespace App\Notifications;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewApplicationAdminNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $application;

    public function __construct(JobApplication $application)
    {
        $this->application = $application;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Job Application Received - PWD System')
            ->greeting('New Job Application!')
            ->line('A new job application has been submitted.')
            ->line('Applicant: ' . $this->application->user->name)
            ->line('Position: ' . $this->application->jobPosting->title)
            ->line('Company: ' . $this->application->jobPosting->company)
            ->line('Applied: ' . $this->application->created_at->format('F j, Y g:i A'))
            ->action('Review Application', url('/admin/applications/' . $this->application->id))
            ->line('Please review this application at your earliest convenience.');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'new_job_application',
            'application_id' => $this->application->id,
            'applicant_name' => $this->application->user->name,
            'job_title' => $this->application->jobPosting->title,
            'message' => 'New application from ' . $this->application->user->name . ' for ' . $this->application->jobPosting->title
        ];
    }
}
