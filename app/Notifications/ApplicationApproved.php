<?php

namespace App\Notifications;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationApproved extends Notification 
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
            ->subject('🎉 Your Job Application Has Been Approved! - PWD System')
            ->greeting('Congratulations ' . $notifiable->name . '!')
            ->line('We are pleased to inform you that your job application has been **approved**.')
            ->line('**Job Position:** ' . $jobTitle)
            ->line('**Company:** ' . $company)
            ->line('**Application Date:** ' . $this->application->created_at->format('F j, Y'))
            ->line('The employer may contact you directly for the next steps in the hiring process.')
            ->action('View Application Details', url('/applications/' . $this->application->id))
            ->line('Thank you for using our PWD Job Portal!')
            ->salutation('Best Regards,<br>PWD System Team');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'application_approved',
            'application_id' => $this->application->id,
            'job_title' => $this->application->jobPosting->title ?? 'Unknown Job',
            'company' => $this->application->jobPosting->company ?? 'Unknown Company',
            'message' => 'Your application has been approved.',
        ];
    }
}
