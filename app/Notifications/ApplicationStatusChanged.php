<?php

namespace App\Notifications;

use App\Models\SchemeApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApplicationStatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        public SchemeApplication $application,
        public string $newStatus   // 'approved' | 'rejected'
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $isApproved = $this->newStatus === 'approved';

        return [
            'type'           => 'application_status_changed',
            'title'          => $isApproved ? '✅ Application Approved!' : '❌ Application Rejected',
            'message'        => $isApproved
                ? "Your application for \"{$this->application->scheme?->name}\" has been approved."
                  . ($this->application->subsidy_amount
                      ? " ₹" . number_format($this->application->subsidy_amount) . " benefit has been assigned."
                      : "")
                : "Your application for \"{$this->application->scheme?->name}\" has been rejected. Reason: "
                  . ($this->application->remarks ?? 'No reason provided.'),
            'application_id' => $this->application->id,
            'scheme_name'    => $this->application->scheme?->name,
            'status'         => $this->newStatus,
            'subsidy_amount' => $this->application->subsidy_amount,
            'url'            => route('applications.show', $this->application->id),
            'icon'           => $isApproved ? '✅' : '❌',
            'color'          => $isApproved ? 'green' : 'red',
        ];
    }
}
