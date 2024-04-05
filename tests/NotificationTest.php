<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Karlos3098\SimplyConnectLaravelNotifications\Interfaces\SimplyConnectNotification;
use Karlos3098\SimplyConnectLaravelNotifications\Services\SimplyConnectMessage;

beforeEach(function () {
    config()->set('simply_connect.api_key', 'test_api_key');
    config()->set('simply_connect.default_device_id', 123);
    config()->set('simply_connect.service_path', 'https://panel.simply-connect.ovh');
});

it('sends a post request to the simply-connect endpoint', function () {

    class TestNotification extends \Illuminate\Notifications\Notification implements SimplyConnectNotification
    {
        public function via(object $notifiable): array
        {
            return ['simply-connect'];
        }

        public function toSimplyConnect(object $notifiable): SimplyConnectMessage
        {
            return (new SimplyConnectMessage)
                ->text('Test message');
        }
    }

    Http::fake([
        'https://panel.simply-connect.ovh/api/messages' => Http::response(['id' => 1]),
    ]);

    Notification::route('simply-connect', '+48123456789')->notify(new TestNotification());

    Http::assertSent(function ($request) {
        return $request->url() == 'https://panel.simply-connect.ovh/api/messages' &&
            $request->method() == 'POST' &&
            $request->data() == [
                'deviceId' => 123,
                'phoneNumber' => '+48123456789',
                'text' => 'Test message',
            ];
    });

    Http::assertSentCount(1);
});
