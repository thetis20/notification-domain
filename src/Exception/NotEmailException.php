<?php

namespace Notification\Domain\Exception;

class NotEmailException extends \Exception
{
    public function __construct(string $email)
    {
        parent::__construct("The email $email is not valid");
    }
}