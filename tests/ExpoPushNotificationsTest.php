<?php

namespace Relative\LaravelExpoPushNotifications\Tests;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Relative\LaravelExpoPushNotifications\ExpoPushNotifications;
use Relative\LaravelExpoPushNotifications\Models\PushNotification;
use Relative\LaravelExpoPushNotifications\Tests\Models\TestNotification;
use Relative\LaravelExpoPushNotifications\Tests\Models\TestUser;

class ExpoPushNotificationsTest extends TestCase
{
    private ExpoPushNotifications $channel;

    public function setUp(): void
    {
        parent::setUp();
        $this->channel = new ExpoPushNotifications;
    }

    /** @test It can send a notification */
    public function it_can_send_a_notification()
    {
        $now = Carbon::parse('2022-01-01 12:00:00');
        $this->travelTo($now);
        Http::fake(['*' => Http::response($this->okResponse())]);
        $user = TestUser::create();
        $token = $user->pushTokens()->create(['token' => 'some-token', 'last_used_at' => '2021-06-15 12:00:00']);

        $this->channel->send($user, new TestNotification);

        $pushNotification = PushNotification::first();
        $this->assertEquals(1, PushNotification::count());
        $this->assertIsString($pushNotification->batch_id);
        $this->assertEquals($user->id, $pushNotification->notifiable_id);
        $this->assertEquals(TestUser::class, $pushNotification->notifiable_type);
        $this->assertEquals('some-token', $pushNotification->token);
        $this->assertEquals('ok', $pushNotification->status);
        $this->assertEquals('5078bd5a-de49-4ca2-91a3-ce15573febaa', $pushNotification->ticket);
        $this->assertEquals(null, $pushNotification->error);
        $this->assertNull($token->fresh()->expired_at);
        $this->assertEquals($now->timestamp, $token->fresh()->last_used_at);
    }

    /** @test It can handle errors */
    public function it_can_handle_errors()
    {
        Http::fake(['*' => Http::response($this->errorResponse())]);
        $user = TestUser::create();
        $token = $user->pushTokens()->create(['token' => 'some-token']);

        $this->channel->send($user, new TestNotification);

        $pushNotification = PushNotification::first();
        $this->assertEquals(1, PushNotification::count());
        $this->assertEquals('error', $pushNotification->status);
        $this->assertEquals('InvalidCredentials', $pushNotification->error);
        $this->assertNotNull($token->fresh()->expired_at);
    }

    private function okResponse()
    {
        return '{"data":[{"id":"5078bd5a-de49-4ca2-91a3-ce15573febaa","status":"ok"}]}';
    }

    private function errorResponse()
    {
        return '{"data":[{"id":"91fd968a-e585-46c9-84cd-88fbf220ac9f","status":"error","message":"Unable to retrieve the FCM server key for the recipient\'s app. Make sure you have provided a server key as directed by the Expo FCM documentation.","details":{"error":"InvalidCredentials","fault":"developer"}}]}';
    }
}
