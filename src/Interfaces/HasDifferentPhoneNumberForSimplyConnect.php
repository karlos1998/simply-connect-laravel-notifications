<?php

namespace Karlos3098\SimplyConnectLaravelNotifications\Interfaces;

use Illuminate\Notifications\Notification;

interface HasDifferentPhoneNumberForSimplyConnect
{
    public function routeNotificationForSimplyConnect(Notification $notification): string;
}
