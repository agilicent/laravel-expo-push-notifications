<?php

namespace Relative\LaravelExpoPushNotifications\Facades;

use Illuminate\Support\Facades\Facade;

class ExpoPushNotifications extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'expo-push-notifications';
    }
}
