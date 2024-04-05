<?php

use Karlos3098\SimplyConnectLaravelNotifications\Services\MessageService;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config()->set('simply_connect.api_key', 'test_api_key');
    config()->set('simply_connect.service_path', 'https://panel.simply-connect.ovh');
});

it('can fetch message details from service by id', function () {
    Http::fake([
        'https://panel.simply-connect.ovh/api/messages/*' => Http::response(['id' => 1]),
    ]);

    $service = new MessageService();
    $messageDetails = $service->getMessageById(1);

    expect($messageDetails['id'])->toBe(1);
});
