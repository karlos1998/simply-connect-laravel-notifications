<?php

namespace Karlos3098\SimplyConnectLaravelNotifications\Interfaces;

use Illuminate\Notifications\Notification;

interface HasDifferentPhoneNumberForSimplyConnect
{
    function routeNotificationForSimplyConnect(Notification $notification): string;
}
