<?php

namespace Karlos3098\SimplyConnectLaravelNotifications\Exceptions;


class CouldNotSendNotification extends \Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param array $errors
     */
    public function __construct(string $message, int $code, protected array $errors)
    {
        parent::__construct($message, $code);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

}
