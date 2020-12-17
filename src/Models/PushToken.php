<?php

namespace Relative\LaravelExpoPushNotifications\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PushToken
 * @package Relative\LaravelExpoPushNotifications\Models
 *
 * @property int $id
 * @property string $token
 * @property array $meta
 * @property Carbon $created_at
 * @property Carbon $last_used_at
 * @property Carbon|null $expired_at
 */
class PushToken extends Model
{
    protected $casts = [
        'meta' => 'array',
        'created_at' => 'timestamp',
        'last_used_at' => 'timestamp',
        'expired_at' => 'timestamp',
    ];

    protected $fillable = [
        'token',
        'meta'
    ];

    protected $table = 'expo_push_tokens';

    public $timestamps = false;

    public function notifiable()
    {
        return $this->morphTo();
    }

    public function scopeActive($query)
    {
        return $query->whereNull('expired_at');
    }

    public static function findByReference($tokenReference)
    {
        return static::where('token', $tokenReference)->first();
    }

    public function expire()
    {
        $this->update(['expired_at' => Carbon::now()]);
    }
}
