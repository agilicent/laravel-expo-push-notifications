<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Expo Service URL
    |--------------------------------------------------------------------------
    |
    | This is the default Expo API endpoint to send Push Notifications to.
    | You shouldn't need to change this, but it is configurable for future
    | Extensibility and testing
    |
    */
    'service_url' => env('EXPO_SERVICE_URL', 'https://exp.host/--/api/v2/push'),

];
