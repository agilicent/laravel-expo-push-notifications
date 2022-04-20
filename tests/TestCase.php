<?php
namespace Relative\LaravelExpoPushNotifications\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Relative\LaravelExpoPushNotifications\ExpoPushNotificationsServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

abstract class TestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->artisan('migrate');
        $this->setUpDatabase();
    }

    protected function setUpDatabase()
    {
        Schema::create('test_users', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            ExpoPushNotificationsServiceProvider::class,
        ];
    }
}
