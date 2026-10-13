<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class MarketplaceNotice extends Notification
{
    public function __construct(public string $title, public string $message, public string $path) {}

    public function via(object $notifiable): array { return ['database']; }

    public function toArray(object $notifiable): array
    {
        return ['title' => $this->title, 'message' => $this->message, 'path' => $this->path];
    }
}
