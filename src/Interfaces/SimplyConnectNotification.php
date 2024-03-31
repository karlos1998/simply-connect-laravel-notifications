<?php

namespace Karlos3098\SimplyConnectLaravelNotifications\Interfaces;

use Karlos3098\SimplyConnectLaravelNotifications\Services\SimplyConnectMessage;

interface SimplyConnectNotification
{
    public function toSimplyConnect(object $notifiable): SimplyConnectMessage;
}
