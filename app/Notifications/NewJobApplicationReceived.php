<?php
// app/Notifications/NewJobApplicationReceived.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\JobApplication;

class NewJobApplicationReceived extends Notification
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
        $user = $this->application->user;
        $job = $this->application->jobPosting;

        return (new MailMessage)
                    ->subject("📋 New Job Application Received - {$job->title}")
                    ->greeting("Hello {$notifiable->name},")
                    ->line("A new job application has been submitted for the position you posted.")
                    ->line('')
                    ->line('**Applicant Information:**')
                    ->line("👤 **Name:** {$user->name}")
                    ->line("📧 **Email:** {$user->email}")
                    ->line('')
                    ->line('**Position Details:**')
                    ->line("💼 **Position:** {$job->title}")
                    ->line("🏢 **Company:** {$job->company}")
                    ->line("📍 **Location:** " . ($job->location ?? 'Not specified'))
                    ->line('')
                    ->line('**Application Submission:**')
                    ->line("📅 **Applied On:** {$this->application->created_at->format('M j, Y g:i A')}")
                    ->line("📄 **Resume:** " . ($this->application->resume_path ? '✅ Attached' : '❌ Not provided'))
                    ->line('')
                    ->action('Review Application & Download Resume', route('admin.applications.show', $this->application))
                    ->line('The applicant\'s resume and full application details are available for download on the application details page.')
                    ->line('Please review the application at your earliest convenience.');
    }

    public function toArray($notifiable)
    {
        $user = $this->application->user;
        $job = $this->application->jobPosting;

        return [
            'type' => 'new_job_application',
            'application_id' => $this->application->id,
            'applicant_name' => $user->name,
            'job_title' => $job->title,
            'company' => $job->company,
            'message' => "New application from {$user->name} for {$job->title}",
            'url' => route('admin.applications.index'),
        ];
    }
}
