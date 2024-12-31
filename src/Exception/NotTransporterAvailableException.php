<?php

namespace Notification\Domain\Exception;

use Notification\Domain\Entity\User;
use Notification\Domain\Entity\Email;
use Notification\Domain\Entity\Phone;

class NotTransporterAvailableException extends \Exception
{
    public function __construct(User|Email|Phone|null $receiver = null)
    {
        if($receiver === null) {
            parent::__construct("No transporter available");
            return;
        }
        parent::__construct("No transporter available for the receiver $receiver");
    }
}