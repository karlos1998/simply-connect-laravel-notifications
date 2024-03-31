<?php

namespace Karlos3098\SimplyConnectLaravelNotifications\Channels;

use Illuminate\Notifications\Notification;
use GuzzleHttp\Client;

class SimplyConnectChannel
{
    public function __construct()
    {
    }

    public function send($notifiable, Notification $notification)
    {
        $notification = $notification->toSimplyConnect($notifiable);
        dd($notification);
        // Your code to send notification goes here
    }
}
