<?php

use Karlos3098\SimplyConnectLaravelNotifications\Facades\MessageDetails;
use Karlos3098\SimplyConnectLaravelNotifications\Services\MessageService;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config()->set('simply_connect.api_key', 'test_api_key');
    config()->set('simply_connect.service_path', 'https://panel.simply-connect.ovh');
});

it('can fetch message details from facade by id', function () {
    Http::fake([
        'https://panel.simply-connect.ovh/api/messages/*' => Http::response(['id' => 1]),
    ]);

    $messageDetails = MessageDetails::getMessageById(1);

    expect($messageDetails['id'])->toBe(1);
});

it('the server responds to requests', function(){
    $messageDetails = MessageDetails::getMessageById(1);
    expect($messageDetails['message'])->toBe('Unauthenticated.');
});

it('bearer from the configuration was used', function(){
    Http::fake(function ($request) {
        expect($request->headers()['Authorization'][0])->toBe('Bearer test_api_key');
    });

    MessageDetails::getMessageById(1);
});

it('the base link from the configuration has been used', function(){
    Http::fake(function ($request) {
        expect($request->url())->toStartWith('https://panel.simply-connect.ovh');
    });

    MessageDetails::getMessageById(1);
});


it('the link to download the message was correct', function(){
    Http::fake(function ($request) {
        expect($request->url())->toBe('https://panel.simply-connect.ovh/api/messages/1');
    });

    MessageDetails::getMessageById(1);
});

