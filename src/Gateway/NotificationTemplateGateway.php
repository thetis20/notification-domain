<?php

namespace Notification\Domain\Gateway;

use Notification\Domain\Entity\NotificationTemplate;

interface NotificationTemplateGateway
{
    public function findOneByKey(string $key): ?NotificationTemplate;
}
