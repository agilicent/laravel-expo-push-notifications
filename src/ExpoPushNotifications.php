<?php

namespace Relative\LaravelExpoPushNotifications;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;
use Relative\LaravelExpoPushNotifications\Models\PushToken;

class ExpoPushNotifications
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('expo-push-notifications.service_url');
    }

    public function send($notifiable, Notification $notification): void
    {
        $batchId = Uuid::uuid4();
        $message = $notification->toExpoPushNotification($notifiable);
        $message->to = $notifiable->pushTokens()->active()->get()->pluck('token')->toArray();

        $tickets = $this->dispatch($message);
        $pushNotifications = collect($message->to)->map(function ($token, $index) use ($message, $tickets, $notifiable, $batchId) {
            return $this->createPushNotificationRecord($tickets[$index], $token, $batchId, $message, $notifiable);
        });

        $receipts = collect($this->fetchReceipts($pushNotifications->pluck('ticket')->toArray()))->values();
        $this->updatePushNotificationReceiptStatuses($tickets, $pushNotifications, $receipts);
    }

    public function dispatch($pushNotification): array
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Accept-encoding' => 'gzip, deflate',
            'Content-Type' => 'application/json'
        ])->post("$this->baseUrl/send", (array)$pushNotification);
        return $response->json()['data'];
    }

    public function fetchReceipts(array $tickets): array
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Accept-encoding' => 'gzip, deflate',
            'Content-Type' => 'application/json'
        ])->post("$this->baseUrl/getReceipts", [
            'ids' => $tickets
        ]);

        return $response->json()['data'];
    }

    /**
     * @param $ticket
     * @param $token
     * @param \Ramsey\Uuid\UuidInterface $batchId
     * @param $notification
     * @param $notifiable
     * @return mixed
     */
    public function createPushNotificationRecord($ticket, $token, \Ramsey\Uuid\UuidInterface $batchId, $notification, $notifiable)
    {
        $pushToken = PushToken::findByReference($token);
        $status = $ticket['status'] === 'error' ? 'error' : 'unknown';
        if ($status === 'error' && $ticket['details']['error'] === 'DeviceNotRegistered') {
            try {
                $pushToken->expire();
            } catch (\Exception $exception) {
                Log::error("Unable to find token $token to expire");
            }
        }
        $pushToken->touch();
        return \Relative\LaravelExpoPushNotifications\Models\PushNotification::create([
            'batch_id' => $batchId,
            'notification' => (array)$notification,
            'notifiable_id' => $notifiable->id,
            'notifiable_type' => get_class($notifiable),
            'token' => $token,
            'status' => $status,
            'ticket' => $ticket['id'] ?? '',
            'error' => $status === 'error' ? $ticket['details']['error'] : null
        ]);
    }

    /**
     * @param array $tickets
     * @param Collection $pushNotifications
     * @param Collection $receipts
     */
    public function updatePushNotificationReceiptStatuses(array $tickets, Collection $pushNotifications, Collection $receipts): void
    {
        collect($tickets)->map(function ($ticket, $index) use ($pushNotifications, $receipts) {
            $pushNotification = $pushNotifications[$index];
            if (isset($ticket['id'])) {
                $pushNotification->status = $receipts[$index]['status'] ?? 'unknown';
                if ($pushNotification->status === 'error') {
                    $pushNotification->errors = $receipts[$pushNotification->ticket]['details']['error'];
                }
                $pushNotification->save();
            }
        });
    }
}
