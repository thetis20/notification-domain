<?php

namespace Notification\Domain\Exception;

class NotPhoneException extends \Exception
{
    public function __construct(string $phone)
    {
        parent::__construct("The phone $phone is not valid");
    }
}