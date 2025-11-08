<?php
// app/Notifications/JobApplicationStatusUpdated.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\JobApplication;

class JobApplicationStatusUpdated extends Notification 
{
    use Queueable;

    public $application;
    public $status;

    public function __construct(JobApplication $application, $status)
    {
        $this->application = $application;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $subject = "Job Application Status Update - {$this->application->jobPosting->title}";

        return (new MailMessage)
                    ->subject($subject)
                    ->greeting("Hello {$notifiable->name},")
                    ->line("Your job application for **{$this->application->jobPosting->title}** has been updated.")
                    ->line("**New Status:** " . ucfirst($this->status))
                    ->line("**Company:** {$this->application->jobPosting->company}")
                    ->line("**Position:** {$this->application->jobPosting->title}")
                    ->action('View Application', route('applications.show', $this->application))
                    ->line('Thank you for using Alaminos City PWD System!');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'job_application_status',
            'application_id' => $this->application->id,
            'job_title' => $this->application->jobPosting->title,
            'company' => $this->application->jobPosting->company,
            'status' => $this->status,
            'message' => "Your application for {$this->application->jobPosting->title} at {$this->application->jobPosting->company} has been {$this->status}.",
            'url' => route('applications.show', $this->application),
        ];
    }
}
