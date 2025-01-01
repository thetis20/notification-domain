<?php

namespace Notification\Domain\Exception;

class NotUuidException extends \Exception
{
    public function __construct(string $uuid)
    {
        parent::__construct("The uuid $uuid is not valid");
    }
}