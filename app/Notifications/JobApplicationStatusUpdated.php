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
        $jobTitle = $this->application->jobPosting->title ?? 'Unknown Position';
        $company = $this->application->jobPosting->company ?? 'Unknown Company';
        $statusDisplay = ucfirst(str_replace('_', ' ', $this->status));

        // Determine emoji based on status
        $statusEmoji = match($this->status) {
            'approved' => '🎉',
            'rejected' => '❌',
            'shortlisted' => '🌟',
            'pending' => '⏳',
            'interview_scheduled' => '📅',
            default => '📋',
        };

        return (new MailMessage)
                    ->subject("{$statusEmoji} Job Application Status Update - {$jobTitle}")
                    ->greeting("Dear {$notifiable->name},")
                    ->line("This is to inform you that your job application status has been updated.")
                    ->line('')
                    ->line("**Status Update Details:**")
                    ->line("{$statusEmoji} **Current Status:** {$statusDisplay}")
                    ->line("📋 **Position:** {$jobTitle}")
                    ->line("🏢 **Company:** {$company}")
                    ->line("📅 **Last Updated:** " . now()->format('M j, Y g:i A'))
                    ->line('')
                    ->line("Your application progress is being monitored closely. For the latest details and to track any further updates, please visit your application dashboard.")
                    ->action('View Application Status', route('applications.show', $this->application))
                    ->line('')
                    ->line("If you have any questions or concerns regarding your application, please don't hesitate to contact our support team.")
                    ->line('')
                    ->line('**Contact Information:**')
                    ->line('📧 pwd.support@alaminoscity.gov.ph')
                    ->line('📞 (075) 123-4567')
                    ->line('')
                    ->line('Thank you for using Alaminos City PWD Information System!')
                    ->salutation('Best regards,<br>**Alaminos City PWD Affairs Office**<br>PWD Information System Admin');
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
