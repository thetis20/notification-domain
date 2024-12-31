<?php

namespace Notification\Domain\Gateway;

use Notification\Domain\Entity\Notification;

interface NotificationGateway
{
    public function insert(Notification $notification): void;
}
