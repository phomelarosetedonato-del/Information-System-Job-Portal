<?php
// app/Notifications/JobApplicationSubmittedConfirmation.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Log;

class JobApplicationSubmittedConfirmation extends Notification
{
    // Do NOT use Queueable - send synchronously

    public $application;

    public function __construct(JobApplication $application)
    {
        $this->application = $application;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        Log::info('JobApplicationSubmittedConfirmation@toMail - Building email', [
            'user_id' => $notifiable->id,
            'application_id' => $this->application->id,
            'job_title' => $this->application->jobPosting->title,
        ]);

        try {
            $job = $this->application->jobPosting;
            $company = $job->company ?? 'the hiring company';

            $message = (new MailMessage)
                ->subject("✅ Application Submitted - {$job->title}")
                ->greeting("Dear {$notifiable->name},")
                ->line("**Thank you for applying!**")
                ->line("")
                ->line("Your application for the position of **{$job->title}** at **{$company}** has been successfully submitted.")
                ->line("We have received your application and have saved all the information you provided.")
                ->line("")
                ->line("**Application Details:**")
                ->line("📌 **Position:** {$job->title}")
                ->line("🏢 **Company:** {$company}")
                ->line("📅 **Applied On:** {$this->application->created_at->format('M j, Y g:i A')}")
                ->line("")
                ->line("**What Happens Next:**")
                ->line("Our recruitment team will carefully review your application and compare your qualifications with the job requirements. If your profile matches what the employer is looking for, they will reach out to you directly for further discussion.")
                ->line("")
                ->line("This review process typically takes several days to a few weeks, depending on the volume of applications received. We appreciate your patience.")
                ->line("")
                ->line("**In the Meantime:**")
                ->line("• Keep your phone and email active for any communication from the employer")
                ->line("• Track your application status in your PWD Portal dashboard")
                ->line("• Continue exploring and applying to other job postings that match your skills")
                ->line("• Update your profile information to increase your visibility to employers")
                ->line("")
                ->action('View Application Status', route('applications.index'))
                ->line("")
                ->line("**Contact Us:**")
                ->line("If you have any questions or concerns about your application, our support team is here to help:")
                ->line("📧 pwd.support@alaminoscity.gov.ph")
                ->line("📞 (075) 123-4567")
                ->line("")
                ->line("We wish you the best of luck with your application. Stay confident and prepared—you might be just the candidate they're looking for!")
                ->salutation('Best regards,<br>**Alaminos City PWD Affairs Office**<br>PWD Information System Admin');

            Log::info('JobApplicationSubmittedConfirmation@toMail - Email built successfully', [
                'user_id' => $notifiable->id,
            ]);

            return $message;
        } catch (\Throwable $e) {
            Log::error('JobApplicationSubmittedConfirmation@toMail - Error building email', [
                'user_id' => $notifiable->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        Log::info('JobApplicationSubmittedConfirmation@toDatabase - Creating database notification', [
            'user_id' => $notifiable->id,
            'application_id' => $this->application->id,
        ]);

        try {
            $job = $this->application->jobPosting;

            $data = [
                'type' => 'job_application_submitted',
                'application_id' => $this->application->id,
                'job_title' => $job->title,
                'company' => $job->company ?? 'Unknown Company',
                'message' => "Your application for {$job->title} has been submitted successfully.",
                'url' => route('applications.show', $this->application->id),
            ];

            Log::info('JobApplicationSubmittedConfirmation@toDatabase - Database notification created', [
                'user_id' => $notifiable->id,
            ]);

            return $data;
        } catch (\Throwable $e) {
            Log::error('JobApplicationSubmittedConfirmation@toDatabase - Error creating database notification', [
                'user_id' => $notifiable->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        $job = $this->application->jobPosting;

        return [
            'type' => 'job_application_submitted',
            'application_id' => $this->application->id,
            'job_title' => $job->title,
            'company' => $job->company ?? 'Unknown Company',
            'message' => "Your application for {$job->title} has been submitted successfully.",
        ];
    }
}
