# Laravel Expo Push Notifications

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

An Expo Push Notifications driver for Laravel Notifications.

Automatically expires PushTokens if they fail due to `DeviceNotRegistered` error, and won't use them again.

Stores data about Push Notification delivery status.

## Installation

Via Composer

``` bash
$ composer require relative/laravel-expo-push-notifications
```

Run migrations

``` bash
$ php artisan migrate
```

Optional: Publish migrations & configuration

``` bash
$ php artisan vendor:publish --provider="Relative\LaravelExpoPushNotifications\ExpoPushNotificationsServiceProvider"
```
If you use UUIDs for your model `id` fields, publish the migrations and follow the instructions in the file to switch to string `id` columns.

## Usage

### Setup your notifiable users

To get started, add the `HasPushTokens` trait to your notifiable class(es), e.g. your `App\User` model

```PHP
<?php

use Relative\LaravelExpoPushNotifications\Traits\HasPushTokens;

class User {
    use Notifiable, HasPushTokens;
    
    //
}
```

### Register Push Tokens to your users

Your Expo app will be able to generate a Push Token and POST it to a controller method in  your Laravel application,
which can then register the token to that user, for example:

```PHP
<?php

class PushNotificationController extends \Illuminate\Routing\Controller {

    public function register(Request $request)
    {
        $token = $request->input('token');
        $request->user()->pushTokens()->firstOrCreate(
            ['token' => $token],
            ['token' => $token],
        );
        return response()->status(200);
    }

}
```
 
### Notify a user about something

Add `ExpoPushNotifications` to your `Notifiable` object
```PHP
<?php

use Illuminate\Bus\Queueable;
use Relative\LaravelExpoPushNotifications\ExpoPushNotifications;
use Relative\LaravelExpoPushNotifications\PushNotification;

class NewOrder extends \Illuminate\Notifications\Notification {

    use Queueable;
    
    public $order;

    /**
     * Create a new notification instance.
     *
     * @param $order
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [ExpoPushNotifications::class];
    }

    public function toExpoPushNotification($notifiable)
    {
        return (new PushNotification)
            ->title('New order received')
            ->body("Order #{$this->order->id} is ready for processing");
    }

}
```
The constructor of the `PushNotification` class accepts an array of parameters matching the schema defined here:
https://docs.expo.io/push-notifications/sending-notifications/#message-request-format

Alternatively you can use the expressive API, in Laravel style as shown above.

The `PushNotification` class has constants for the `priority` and `sound` parameters:
```
PushNotification::PRIORITY_HIGH;
PushNotification::PRIORITY_NORMAL;
PushNotification::PRIORITY_DEFAULT;

PushNotification::SOUND_DEFAULT;
PushNotification::SOUND_NONE;
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## Credits

- [Nick Cousins](https://github.com/NickCousins)
- [Relative](https://github.com/relativelimited)
- [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/relative/expo-push-notifications.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/relative/expo-push-notifications.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/relative/expo-push-notifications/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/relative/expo-push-notifications
[link-downloads]: https://packagist.org/packages/relative/expo-push-notifications
[link-travis]: https://travis-ci.org/relative/expo-push-notifications
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/relative
[link-contributors]: ../../contributors
