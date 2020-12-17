<?php


namespace Relative\LaravelExpoPushNotifications\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class PushNotification
 * @package Relative\LaravelExpoPushNotifications\Models
 *
 * @property array $notification
 * @property string $ticket
 * @property int $notifiable_id
 * @property string $notifiable_type
 * @property string $token
 * @property string $error
 */
class PushNotification extends Model
{
    protected $table = 'expo_push_notifications';

    protected $fillable = [
        'notification','ticket','notifiable_id','notifiable_type','token','error', 'status'
    ];

    protected $casts = [
        'notification' => 'array',
    ];
}
