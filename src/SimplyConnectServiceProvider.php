<?php

namespace Karlos3098\SimplyConnectLaravelNotifications;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class SimplyConnectServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Notification::resolved(
            fn (ChannelManager $service) => $service->extend(
                'simply-connect',
                fn ($app) => new Channels\SimplyConnectChannel
            )
        );

        $this->publishes([
            __DIR__.'/../config/simply_connect.php' => config_path('simply_connect.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Karlos3098\SimplyConnectLaravelNotifications\Commands\SimplyConnectNotificationMakeCommand::class,
            ]);
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/simply_connect.php', 'simply-connect'
        );
    }
}
