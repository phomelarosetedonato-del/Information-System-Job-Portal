<?php

namespace App\Notifications;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationRejected extends Notification 
{
    use Queueable;

    public $application;
    public $rejectionReason;

    /**
     * Create a new notification instance.
     */
    public function __construct(JobApplication $application, $rejectionReason = null)
    {
        $this->application = $application;
        $this->rejectionReason = $rejectionReason;
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

        $mailMessage = (new MailMessage)
            ->subject('Update on Your Job Application - PWD System')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Thank you for your interest in the **' . $jobTitle . '** position at **' . $company . '**.')
            ->line('After careful consideration, we regret to inform you that your application has not been selected for this position.');

        if ($this->rejectionReason) {
            $mailMessage->line('')
                       ->line('**Reason provided by employer:**')
                       ->line('"' . $this->rejectionReason . '"');
        }

        $mailMessage->line('')
                   ->line('We encourage you to apply for other positions that match your skills and experience.')
                   ->action('Browse More Jobs', url('/jobs'))
                   ->line('We appreciate your interest and wish you the best in your job search.')
                   ->salutation('Best Regards,<br>PWD System Team');

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'application_rejected',
            'application_id' => $this->application->id,
            'job_title' => $this->application->jobPosting->title ?? 'Unknown Job',
            'company' => $this->application->jobPosting->company ?? 'Unknown Company',
            'rejection_reason' => $this->rejectionReason,
            'message' => 'Your application has been rejected.',
        ];
    }
}
