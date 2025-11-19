<?php

namespace App\Notifications;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationInterviewScheduled extends Notification implements ShouldQueue
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
        $interviewDate = $this->application->interview_scheduled_at 
            ? \Carbon\Carbon::parse($this->application->interview_scheduled_at)->format('F j, Y g:i A')
            : 'To be confirmed';

        return (new MailMessage)
            ->subject('📅 Interview Scheduled - ' . $jobTitle . ' - PWD System')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your interview has been scheduled for the following position:')
            ->line('**Job Position:** ' . $jobTitle)
            ->line('**Company:** ' . $company)
            ->line('**Interview Date & Time:** ' . $interviewDate)
            ->line('**Location/Method:** ' . ($this->application->interview_location ?? 'To be confirmed'))
            ->lineIf(!empty($this->application->interview_notes), '**Additional Notes:**')
            ->lineIf(!empty($this->application->interview_notes), $this->application->interview_notes)
            ->line('Please arrive 10 minutes early and bring any required documents.')
            ->action('View Application Details', url('/applications/' . $this->application->id))
            ->line('Good luck with your interview!')
            ->salutation('Best Regards,<br>PWD System Team');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'interview_scheduled',
            'application_id' => $this->application->id,
            'job_title' => $this->application->jobPosting->title ?? 'Unknown Job',
            'company' => $this->application->jobPosting->company ?? 'Unknown Company',
            'interview_date' => $this->application->interview_scheduled_at,
            'interview_location' => $this->application->interview_location,
            'message' => 'Your interview has been scheduled.',
        ];
    }
}
