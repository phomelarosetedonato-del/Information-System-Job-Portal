<?php

namespace App\Notifications;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationShortlisted extends Notification
{
    use Queueable;

    public $application;

    /**
     * Create a new notification instance.
     */
    public function __construct(JobApplication $application)
    {
        $this->application = $application;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $jobTitle = $this->application->jobPosting->title ?? 'Unknown Job';
        $company = $this->application->jobPosting->company ?? 'Unknown Company';

        return (new MailMessage)
            ->subject('🌟 Congratulations! Your Application Has Been Shortlisted - PWD System')
            ->greeting('Dear ' . $notifiable->name . ',')
            ->line('**Excellent news!** Your job application for the position of **' . $jobTitle . '** at **' . $company . '** has been **shortlisted**.')
            ->line('Out of all the applications received, your qualifications stood out and impressed the employer. You are now moving to the next phase of our selection process!')
            ->line('')
            ->line('✅ **Application Details:**')
            ->line('📋 **Position:** ' . $jobTitle)
            ->line('🏢 **Company:** ' . $company)
            ->line('📅 **Applied On:** ' . $this->application->created_at->format('M j, Y'))
            ->line('')
            ->line('📌 **Next Steps:**')
            ->line('This is a critical milestone in the hiring process! The employer will now contact you directly to discuss the following:')
            ->line('• Interview scheduling and format (phone, video, or in-person)')
            ->line('• Any additional assessments or evaluations needed')
            ->line('• Expected timeline for the next phase')
            ->line('')
            ->line('⚠️ **Important Reminders:**')
            ->line('• Keep your phone and email active and check frequently')
            ->line('• Respond promptly to any communication from the employer')
            ->line('• Prepare well and research the company and role thoroughly')
            ->line('• Dress professionally and maintain a positive attitude')
            ->line('')
            ->action('View Application Details', url('/applications/' . $this->application->id))
            ->line('')
            ->line('We are proud that you have been selected to move forward. Your hard work and dedication are paying off. Give your best effort in the next stage—we believe in you!')
            ->salutation('Best regards,<br>**Alaminos City PWD Affairs Office**<br>PWD Information System Admin');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'application_shortlisted',
            'application_id' => $this->application->id,
            'job_title' => $this->application->jobPosting->title ?? 'Unknown Job',
            'company' => $this->application->jobPosting->company ?? 'Unknown Company',
            'message' => 'Your application has been shortlisted.',
        ];
    }
}
