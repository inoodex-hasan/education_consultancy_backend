<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewApplicationNotification extends Notification
{
    use Queueable;

    public $application;

    /**
     * Create a new notification instance.
     */
    public function __construct($application)
    {
        $this->application = $application;
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
            'application_id' => $this->application->id,
            'application_number' => $this->application->application_id,
            'student_name' => ($this->application->student->first_name ?? '') . ' ' . ($this->application->student->last_name ?? ''),
            'created_by' => auth()->user()->name ?? 'System',
            'message' => 'New application ' . $this->application->application_id . ' created by ' . (auth()->user()->name ?? 'System'),
            'link' => route('admin.applications.edit', $this->application->id),
        ];
    }
}
