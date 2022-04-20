<?php

namespace Relative\LaravelExpoPushNotifications\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Relative\LaravelExpoPushNotifications\Traits\HasPushTokens;

class TestUser extends Model
{
    use HasPushTokens;

    protected $guarded = [];
}
