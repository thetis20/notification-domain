<?php

namespace Notification\Domain\Exception;

class NotUserIdentifierException extends \Exception
{
    public function __construct(string $identifier)
    {
        parent::__construct("The user identifier $identifier is not valid. It must be an id, a phone or an email");
    }
}