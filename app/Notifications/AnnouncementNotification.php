<?php

namespace App\Notifications;

use App\Models\Announcement;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class AnnouncementNotification extends Notification
{

    protected $announcementId;
    protected $announcement;

    public function __construct(Announcement $announcement)
    {
        $this->announcementId = $announcement->id;
        $this->announcement = $announcement;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        Log::info('AnnouncementNotification@toMail - Building email', [
            'user_id' => $notifiable->id,
            'user_email' => $notifiable->email,
            'announcement_id' => $this->announcement->id,
            'announcement_title' => $this->announcement->title,
        ]);

        try {
            $message = (new MailMessage)
                ->subject('📢 New Announcement: ' . $this->announcement->title)
                ->greeting('Hello ' . $notifiable->name . ',')
                ->line('A new announcement has been posted on the PWD System:')
                ->line('**' . $this->announcement->title . '**')
                ->line($this->announcement->content)
                ->action('View Announcement', route('announcements.show', $this->announcement->id))
                ->line('Thank you for using the PWD System!')
                ->line('This is an automated notification. Please do not reply to this email.');

            Log::info('AnnouncementNotification@toMail - Email built successfully', [
                'user_id' => $notifiable->id,
            ]);

            return $message;
        } catch (\Throwable $e) {
            Log::error('AnnouncementNotification@toMail - Error building email', [
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
     *
     * @return DatabaseMessage
     */
    public function toDatabase($notifiable)
    {
        Log::info('AnnouncementNotification@toDatabase - Creating database notification', [
            'user_id' => $notifiable->id,
            'announcement_id' => $this->announcement->id,
        ]);

        try {
            $message = new DatabaseMessage([
                'announcement_id' => $this->announcement->id,
                'title' => $this->announcement->title,
                'content' => $this->announcement->content,
                'created_by' => $this->announcement->created_by,
                'message' => 'New announcement: ' . $this->announcement->title,
                'type' => 'announcement',
                'action_url' => route('announcements.show', $this->announcement->id),
            ]);

            Log::info('AnnouncementNotification@toDatabase - Database notification created', [
                'user_id' => $notifiable->id,
            ]);

            return $message;
        } catch (\Throwable $e) {
            Log::error('AnnouncementNotification@toDatabase - Error creating database notification', [
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
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'announcement_id' => $this->announcement->id,
            'title' => $this->announcement->title,
            'content' => $this->announcement->content,
            'created_by' => $this->announcement->created_by,
            'message' => 'New announcement: ' . $this->announcement->title,
        ];
    }
}
