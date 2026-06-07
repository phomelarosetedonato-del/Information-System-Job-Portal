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
            ->subject('Update on Your Application for ' . $jobTitle . ' - PWD System')
            ->greeting('Dear ' . $notifiable->name . ',')
            ->line('Thank you for your interest in the **' . $jobTitle . '** position at **' . $company . '**.')
            ->line('We truly appreciate the time and effort you invested in submitting your application.')
            ->line('')
            ->line('❌ **Application Status Update:**')
            ->line('After a thorough review of your qualifications and experience, we regret to inform you that your application has not been selected for this position at this time.');

        if ($this->rejectionReason) {
            $mailMessage->line('')
                       ->line('**Feedback from the employer:**')
                       ->line('> ' . $this->rejectionReason);
        }

        $mailMessage->line('')
                   ->line('**Why This Happened:**')
                   ->line('The employer received numerous applications from highly qualified candidates. While your profile was reviewed carefully, other applicants better matched the specific requirements for this role at this time.')
                   ->line('')
                   ->line('💡 **What You Can Do:**')
                   ->line('• Don\'t be discouraged—rejection is a normal part of the job search process')
                   ->line('• Review your resume and cover letter for areas of improvement')
                   ->line('• Continue developing your skills and qualifications')
                   ->line('• Explore and apply to other job postings that match your expertise')
                   ->line('• Consider reaching out to the employer for feedback (if appropriate)')
                   ->line('')
                   ->action('Browse More Job Opportunities', url('/jobs'))
                   ->line('')
                   ->line('The right opportunity will come along, and we encourage you to keep applying and building your career. Your determination and persistence will lead to success!')
                   ->line('')
                   ->line('**Contact Us:**')
                   ->line('If you have questions about your application or need career guidance, feel free to reach out:')
                   ->line('📧 pwd.support@alaminoscity.gov.ph')
                   ->line('📞 (075) 123-4567')
                   ->salutation('Best regards,<br>**Alaminos City PWD Affairs Office**<br>PWD Information System Admin');

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
