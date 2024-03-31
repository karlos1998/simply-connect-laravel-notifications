<?php

namespace Karlos3098\SimplyConnectLaravelNotifications\Facades;

use Illuminate\Support\Facades\Facade;
use Karlos3098\SimplyConnectLaravelNotifications\Services\MessageService;

/**
 * @method static array getMessageById(int $messageId)
 * @method static MessageService setBearerToken(string $token): MessageService
 *
 * @see \Karlos3098\SimplyConnectLaravelNotifications\Services\MessageService
 */
class MessageDetails extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return MessageService::class;
    }
}
