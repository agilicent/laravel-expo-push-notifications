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
}
