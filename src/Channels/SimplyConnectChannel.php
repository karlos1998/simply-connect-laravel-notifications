<?php

namespace Karlos3098\SimplyConnectLaravelNotifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Karlos3098\SimplyConnectLaravelNotifications\Exceptions\CouldNotSendNotification;
use Karlos3098\SimplyConnectLaravelNotifications\Interfaces\HasDifferentPhoneNumberForSimplyConnect;
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
        $notifiableIsPhoneNumberString = isset($notifiable->routes['simply-connect']) && is_array($notifiable->routes) && is_string($notifiable->routes['simply-connect']);

        /**
         * @var SimplyConnectMessage $scNotification
         */
        $scNotification = $notification->toSimplyConnect($notifiable);

        $data = [
            'deviceId' => $scNotification->getDeviceId(),
            'text' => $scNotification->getText(),
        ];



        ///////////////////////////////////
        $phoneNumbersData = [];

        if ($scNotification->hasManyPhoneNumbers()) {
            $phoneNumbersData['phoneNumbers'] = $scNotification->getPhoneNumbers();
        } else {
            $phoneNumber = $scNotification->getPhoneNumber();

            if (!$phoneNumber) {
                if ($notifiableIsPhoneNumberString) {
                    $phoneNumber = $notifiable->routes['simply-connect'];
                } else if (in_array(HasDifferentPhoneNumberForSimplyConnect::class, class_implements($notifiable::class))) {
                    $phoneNumber = $notifiable->routeNotificationForSimplyConnect($notification);
                } else {
                    $phoneNumber = $notifiable->phone_number;
                }
            }

            if (is_array($phoneNumber)) {
                if(count($phoneNumber) > 1) {
                    $phoneNumbersData['phoneNumbers'] = $phoneNumber;
                } else if (count($phoneNumber) == 1) {
                    $phoneNumbersData['phoneNumber'] = $phoneNumber[0];
                }
            } else {
                $phoneNumbersData['phoneNumber'] = $phoneNumber;
            }
        }
        ///////////////////////////////////

        if(
            !isset($phoneNumbersData['phoneNumber']) &&
            !isset($phoneNumbersData['phoneNumbers'])
        ) return;

        if(
            isset($phoneNumbersData['phoneNumber']) &&
            $phoneNumbersData['phoneNumber'] == ''
        ) return;

        $response = Http::withToken($scNotification->getToken())
            ->baseUrl(config('simply_connect.service_path'))
            ->accept('application/json')
            ->post('/api/messages', array_merge($data, $phoneNumbersData));

        if ($response->failed()) {
            throw new CouldNotSendNotification($response->json('message'), $response->status(), $response->json('errors'));
        }

        $callback = $scNotification->getCallback();
        if ($callback) {
            $callback($response->json('id'));
        }

    }
}
