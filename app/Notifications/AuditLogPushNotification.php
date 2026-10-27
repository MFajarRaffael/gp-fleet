<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class AuditLogPushNotification extends Notification
{
    public function via(object $notifiable): array
    {
        return [WebPushChannel::class];
    }

    public function toWebPush(
        object $notifiable,
        Notification $notification
    ): WebPushMessage {
        return (new WebPushMessage)
            ->title('GP Fleet Management')
            ->body('Ada aktivitas baru di sistem. Buka GP Fleet untuk melihat informasi lebih lanjut.')
            ->icon('/images/gpi_logo.png');
    }
}
