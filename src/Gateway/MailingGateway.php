<?php

namespace Notification\Domain\Gateway;

use Notification\Domain\Entity\Mailing;

interface MailingGateway
{
    public function insert(Mailing $mailing): void;
}
