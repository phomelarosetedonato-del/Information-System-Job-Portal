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
            ->subject('🌟 Your Application Has Been Shortlisted! - PWD System')
            ->greeting('Great news ' . $notifiable->name . '!')
            ->line('Your job application has been **shortlisted** by the employer.')
            ->line('**Job Position:** ' . $jobTitle)
            ->line('**Company:** ' . $company)
            ->line('This is an important step in the hiring process. The employer may contact you soon for further steps such as an interview or assessment.')
            ->action('View Application Details', url('/applications/' . $this->application->id))
            ->line('We wish you the best of luck in the next stages!')
            ->salutation('Best Regards,<br>PWD System Team');
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
