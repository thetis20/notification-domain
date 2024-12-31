<?php

namespace Notification\Domain\Exception;

class NotificationNotFoundException extends \Exception
{
    public function __construct(string $key)
    {
        parent::__construct("The notification with key $key was not found");
    }
}