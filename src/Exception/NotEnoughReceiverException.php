<?php

namespace Notification\Domain\Exception;

class NotEnoughReceiverException extends \Exception
{
    public function __construct()
    {
        parent::__construct("The notification must have at least one receiver");
    }
}