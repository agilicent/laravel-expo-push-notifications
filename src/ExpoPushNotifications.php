<?php

namespace Relative\LaravelExpoPushNotifications;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class ExpoPushNotifications
{
    public function send($notifiable, Notification $notification): void
    {
        $message = $notification->toExpoPushNotification($notifiable);
        $message->to = $notifiable->pushTokens()->active()->get()->pluck('token')->toArray();

        $this->dispatch($message);
    }

    public function dispatch($pushNotification)
    {
        Http::withHeaders([
            'Accept' => 'application/json',
            'Accept-encoding' => 'gzip, deflate',
            'Content-Type' => 'application/json'
        ])->post(config('expo-push-notifications.service_url'), (array) $pushNotification);
    }
}
