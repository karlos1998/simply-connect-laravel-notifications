<?php

namespace Karlos3098\SimplyConnectLaravelNotifications\Interfaces;

use Karlos3098\SimplyConnectLaravelNotifications\Messages\SimplyConnectMessage;

interface SimplyConnectNotification
{
    public function toSimplyConnect($notifiable): SimplyConnectMessage;
}
