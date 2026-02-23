<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewLeadSubmitted extends Notification
{
    use Queueable;

    public $lead;

    /**
     * Create a new notification instance.
     */
    public function __construct($lead)
    {
        $this->lead = $lead;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'lead_id' => $this->lead->id,
            'student_name' => $this->lead->student_name,
            'phone' => $this->lead->phone,
            'created_by' => $this->lead->creator->name ?? 'Marketing',
            'message' => 'New lead submitted by ' . ($this->lead->creator->name ?? 'Marketing'),
            'link' => route('admin.marketing.leads.show', $this->lead->id),
        ];
    }
}
