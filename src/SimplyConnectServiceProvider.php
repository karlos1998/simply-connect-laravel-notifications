<?php

namespace Karlos3098\SimplyConnectLaravelNotifications;

use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;

class SimplyConnectServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('simply', function ($app) {
                return new Channels\SimplyConnectChannel;
            });
        });
    }

    public function register()
    {
        // Register any package services here
    }
}
