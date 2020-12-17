<?php


namespace Relative\LaravelExpoPushNotifications;

/**
 * Class PushNotification
 * @package Relative\LaravelExpoPushNotifications
 * @implements https://docs.expo.io/push-notifications/sending-notifications/#message-request-format
 */
class PushNotification
{
    const PRIORITY_DEFAULT = 'default';
    const PRIORITY_NORMAL = 'normal';
    const PRIORITY_HIGH = 'high';

    const SOUND_DEFAULT = 'default';
    const SOUND_NONE = 'none';

    public $to;

    public string $sound;

    public string $title;

    public string $body;

    public array $data;

    public string $priority = self::PRIORITY_DEFAULT;

    public int $ttl;

    public int $expiration;

    public string $subtitle;

    public int $badge;

    public string $channelId;

    public function __construct(array $attributes)
    {
        foreach($attributes as $key => $value) {
            if (property_exists(static::class, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function to($value): PushNotification
    {
        $this->to = $value;
        return $this;
    }

    public function title(string $value): PushNotification
    {
        $this->title = $value;
        return $this;
    }

    public function subtitle(string $value): PushNotification
    {
        $this->subtitle = $value;
        return $this;
    }

    public function body(string $value): PushNotification
    {
        $this->body = $value;
        return $this;
    }

    public function sound(string $value): PushNotification
    {
        $this->sound = $value;
        return $this;
    }

    public function priority(string $value): PushNotification
    {
        $this->priority = $value;
        return $this;
    }

    public function data(array $value): PushNotification
    {
        $this->data = $value;
        return $this;
    }

    public function ttl(int $value): PushNotification
    {
        $this->ttl = $value;
        return $this;
    }

    public function expiration(int $value): PushNotification
    {
        $this->expiration = $value;
        return $this;
    }

    public function badge(int $value): PushNotification
    {
        $this->badge = $value;
        return $this;
    }

    public function channelId(string $value): PushNotification
    {
        $this->channelId = $value;
        return $this;
    }
}
