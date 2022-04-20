<?php
namespace Relative\LaravelExpoPushNotifications\Tests\Models;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Relative\LaravelExpoPushNotifications\ExpoPushNotifications;
use Relative\LaravelExpoPushNotifications\PushNotification;

class TestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable)
    {
        return [ExpoPushNotifications::class];
    }

    public function toExpoPushNotification($notifiable)
    {
        return (new PushNotification)
            ->title('Some title')
            ->body('Some body...')
            ->data([
                'content-available' => 1,
            ])
            ->badge(123);
    }
}
