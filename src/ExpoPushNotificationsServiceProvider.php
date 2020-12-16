<?php

namespace Relative\LaravelExpoPushNotifications;

use Illuminate\Support\ServiceProvider;

class ExpoPushNotificationsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'relative');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'relative');
         $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/expo-push-notifications.php', 'expo-push-notifications');

        // Register the service the package provides.
        $this->app->singleton('expo-push-notifications', function ($app) {
            return new ExpoPushNotifications();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['expo-push-notifications'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__ . '/../config/expo-push-notifications.php' => config_path('expo-push-notifications.php'),
        ], 'expo-push-notifications.config');

        // Publishing the views.
        $this->publishes([
            __DIR__.'/../database/migrations' => base_path('database/migrations'),
        ], 'expo-push-notifications.migrations');
    }
}
