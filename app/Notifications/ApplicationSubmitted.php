<?php

namespace App\Notifications;

use App\Models\SchemeApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApplicationSubmitted extends Notification
{
    use Queueable;

    public function __construct(public SchemeApplication $application) {}

    /**
     * Store in DB only (no email needed)
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'           => 'application_submitted',
            'title'          => 'New Scheme Application',
            'message'        => "{$this->application->farmer?->user?->name} applied for " .
                                "{$this->application->scheme?->name}.",
            'application_id' => $this->application->id,
            'farmer_name'    => $this->application->farmer?->user?->name,
            'scheme_name'    => $this->application->scheme?->name,
            'url'            => route('applications.show', $this->application->id),
            'icon'           => '📝',
            'color'          => 'blue',
        ];
    }
}
