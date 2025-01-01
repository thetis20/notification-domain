<?php

namespace Notification\Domain\Exception;

use Notification\Domain\Entity\ReceiverInterface;

class NotTransporterAvailableException extends \Exception
{
    public function __construct(?ReceiverInterface $receiver = null)
    {
        if($receiver === null) {
            parent::__construct("No transporter available");
            return;
        }
        parent::__construct("No transporter available for the receiver ".$receiver::class." id:".$receiver->getId()." email:".$receiver->getEmail()." phone:".$receiver->getPhone());
    }
}