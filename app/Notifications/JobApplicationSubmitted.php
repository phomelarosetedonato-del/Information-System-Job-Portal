<?php

namespace App\Notifications;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobApplicationSubmitted extends Notification implements ShouldQueue
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
        return (new MailMessage)
            ->subject('Job Application Submitted - PWD System Alaminos City')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your job application has been successfully submitted.')
            ->line('Job Position: ' . $this->application->jobPosting->title)
            ->line('Company: ' . $this->application->jobPosting->company)
            ->line('Application Date: ' . $this->application->created_at->format('F j, Y'))
            ->action('View Application', url('/applications/' . $this->application->id))
            ->line('Thank you for using our PWD system!');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'job_application_submitted',
            'application_id' => $this->application->id,
            'job_title' => $this->application->jobPosting->title,
            'message' => 'Your application for ' . $this->application->jobPosting->title . ' has been submitted.'
        ];
    }
}
