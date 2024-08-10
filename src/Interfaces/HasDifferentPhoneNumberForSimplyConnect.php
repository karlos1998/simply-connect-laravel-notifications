<?php

namespace Karlos3098\SimplyConnectLaravelNotifications\Interfaces;

use Illuminate\Notifications\Notification;

interface HasDifferentPhoneNumberForSimplyConnect
{
    /**
     * Route notifications for the simply-connect channel.
     *
     * @return  array<string>|string|null
     */
    public function routeNotificationForSimplyConnect(Notification $notification): array|string|null;
}
