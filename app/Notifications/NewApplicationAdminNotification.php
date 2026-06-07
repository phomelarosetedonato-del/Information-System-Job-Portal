<?php

namespace App\Notifications;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewApplicationAdminNotification extends Notification
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

        $message = (new MailMessage)
            ->subject('📋 New Job Application Received - PWD System')
            ->greeting('New Job Application Submitted!')
            ->line('A new job application has been submitted for review.')
            ->line('')
            ->line('**Applicant Details:**')
            ->line('👤 **Name:** ' . $user->name)
            ->line('📧 **Email:** ' . $user->email)
            ->line('')
            ->line('**Position Details:**')
            ->line('💼 **Position:** ' . $job->title)
            ->line('🏢 **Company:** ' . $job->company)
            ->line('📍 **Location:** ' . ($job->location ?? 'Not specified'))
            ->line('')
            ->line('**Application Details:**')
            ->line('📅 **Applied On:** ' . $this->application->created_at->format('M j, Y g:i A'))
            ->line('📄 **Resume:** ' . ($this->application->resume_path ? '✅ Attached' : '❌ Not provided'))
            ->line('')
            ->action('Review Application & Download Resume', route('admin.applications.show', $this->application))
            ->line('Please review the application and resume at your earliest convenience.');

        return $message;
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
