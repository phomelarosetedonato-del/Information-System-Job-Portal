<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'inquiry_type',
        'is_read',
        'responded_at',
        'response_notes',
        'ip_address',
        'user_id',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'responded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the inquiry type display name
     */
    public function getInquiryTypeDisplayAttribute()
    {
        return match($this->inquiry_type) {
            'job_application_support' => 'Job Application Support',
            'employer_partnership' => 'Employer Partnership',
            'training_programs' => 'Training Programs',
            'technical_support' => 'Technical Support',
            'accessibility_concerns' => 'Accessibility Concerns',
            'account_issues' => 'Account Issues',
            'feedback' => 'Feedback & Suggestions',
            'other' => 'Other',
            default => ucfirst(str_replace('_', ' ', $this->inquiry_type))
        };
    }

    /**
     * Get the badge color for inquiry type
     */
    public function getInquiryTypeBadgeColorAttribute()
    {
        return match($this->inquiry_type) {
            'job_application_support' => 'primary',
            'employer_partnership' => 'info',
            'training_programs' => 'success',
            'technical_support' => 'warning',
            'accessibility_concerns' => 'danger',
            'account_issues' => 'warning',
            'feedback' => 'light',
            'other' => 'secondary',
            default => 'secondary'
        };
    }

    /**
     * Mark as read
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
        return $this;
    }

    /**
     * Mark as unread
     */
    public function markAsUnread()
    {
        $this->update(['is_read' => false]);
        return $this;
    }

    /**
     * Mark as responded
     */
    public function markAsResponded($notes = null)
    {
        $this->update([
            'responded_at' => now(),
            'response_notes' => $notes,
            'is_read' => true,
        ]);
        return $this;
    }

    /**
     * Relationship: Get the user (if authenticated user submitted this)
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Send response notification to user
     */
    public function sendResponseNotification()
    {
        // If user is associated, send notification via their user account
        if ($this->user_id && $this->user) {
            $this->user->notify(new \App\Notifications\ContactResponseNotification($this));
        }
        // Also send email to the contact email address
        \Illuminate\Support\Facades\Mail::send(new \App\Mail\ContactResponseMail($this));
    }

    /**
     * Get unread count
     */
    public static function getUnreadCount()
    {
        return self::where('is_read', false)->count();
    }

    /**
     * Get unresponded count
     */
    public static function getUnrespondedCount()
    {
        return self::whereNull('responded_at')->count();
    }

    /**
     * Scope: unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope: responded messages
     */
    public function scopeResponded($query)
    {
        return $query->whereNotNull('responded_at');
    }

    /**
     * Scope: unresponded messages
     */
    public function scopeUnresponded($query)
    {
        return $query->whereNull('responded_at');
    }
}
