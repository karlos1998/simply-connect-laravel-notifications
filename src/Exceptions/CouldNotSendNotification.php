<?php

namespace Karlos3098\SimplyConnectLaravelNotifications\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public function __construct(string $message, int $code, protected array $errors)
    {
        parent::__construct($message, $code);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
