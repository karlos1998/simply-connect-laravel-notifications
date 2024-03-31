<?php

namespace Karlos3098\SimplyConnectLaravelNotifications\Channels;

use Illuminate\Notifications\Notification;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Karlos3098\SimplyConnectLaravelNotifications\Exceptions\CouldNotSendNotification;
use Karlos3098\SimplyConnectLaravelNotifications\Services\SimplyConnectMessage;

class SimplyConnectChannel
{
    public function __construct()
    {
    }

    /**
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        /**
         * @var SimplyConnectMessage $scNotification
         */
        $scNotification = $notification->toSimplyConnect($notifiable);

        $data = [
            'deviceId' => $scNotification->getDeviceId(),
            'text' => $scNotification->getText(),
        ];

        $phoneNumberColumn = 'phone_number';

        $phoneNumbersData = $scNotification->hasManyPhoneNumbers() ? [
            'phoneNumbers' => $scNotification->getPhoneNumbers(),
        ] : [
            'phoneNumber' => $scNotification->getPhoneNumber() ?? $notifiable->$phoneNumberColumn,
        ];

        $response = Http::withToken($scNotification->getToken())
            ->accept('application/json')
            ->post("https://panel.simply-connect.ovh/api/messages", array_merge($data, $phoneNumbersData));

        if($response->failed()) {
            throw new CouldNotSendNotification($response->json('message'), $response->status(), $response->json('errors'));
        }

        dd($response->json());
    }
}
