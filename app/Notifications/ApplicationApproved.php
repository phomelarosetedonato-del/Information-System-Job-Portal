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
            ->subject('🎉 Application Approved – Congratulations! - PWD System')
            ->greeting('Dear ' . $notifiable->name . ',')
            ->line('**Congratulations!** Your application for the position of **' . $jobTitle . '** has been approved by the employer.')
            ->line('After reviewing your qualifications, they are impressed with your skills and experience.')
            ->line('')
            ->line('✅ **Next Steps:**')
            ->line('You are now invited to proceed to the **interview stage**. The employer will reach out with the schedule, instructions, and interview details soon.')
            ->line('Please keep your phone and email active to ensure smooth communication.')
            ->line('')
            ->line('📋 **Application Details:**')
            ->line('• **Position:** ' . $jobTitle)
            ->line('• **Company:** ' . $company)
            ->line('• **Application Date:** ' . $this->application->created_at->format('F j, Y'))
            ->line('')
            ->action('View Application Details', url('/applications/' . $this->application->id))
            ->line('')
            ->line('We wish you the best of luck in the next step of the hiring process. Prepare well, stay confident, and showcase your abilities—you are one step closer to achieving your career goals!')
            ->salutation('Best regards,<br>**Alaminos City PWD Affairs Office**<br>PWD Information System Admin');
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
