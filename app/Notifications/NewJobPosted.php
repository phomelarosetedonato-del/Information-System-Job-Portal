<?php
// app/Notifications/NewJobPosted.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\JobPosting;

class NewJobPosted extends Notification 
{
    use Queueable;

    public $jobPosting;

    public function __construct(JobPosting $jobPosting)
    {
        $this->jobPosting = $jobPosting;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject("New Job Opportunity: {$this->jobPosting->title}")
                    ->greeting("Hello {$notifiable->name},")
                    ->line("A new job opportunity that matches your profile has been posted!")
                    ->line("**Position:** {$this->jobPosting->title}")
                    ->line("**Company:** {$this->jobPosting->company}")
                    ->line("**Location:** {$this->jobPosting->location}")
                    ->line("**Salary:** {$this->jobPosting->salary}")
                    ->line("**Type:** {$this->jobPosting->employment_type}")
                    ->action('View Job Details', route('public.job-postings.show', $this->jobPosting))
                    ->line('Apply now to be considered for this position!');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'new_job_posted',
            'job_posting_id' => $this->jobPosting->id,
            'job_title' => $this->jobPosting->title,
            'company' => $this->jobPosting->company,
            'message' => "New job posted: {$this->jobPosting->title} at {$this->jobPosting->company}",
            'url' => route('public.job-postings.show', $this->jobPosting),
        ];
    }
}
