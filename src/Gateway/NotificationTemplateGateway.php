<?php

namespace Notification\Domain\Gateway;

interface NotificationTemplateGateway
{
    public function isExist(string $key): bool;
}
