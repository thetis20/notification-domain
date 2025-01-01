<?php

namespace Notification\Domain\Request;

use Notification\Domain\Entity\Notification;

class SendNotificationRequest
{
    /** @var Notification[] */
    private array $notifications;

    public function __construct(array $notifications)
    {
        $this->notifications = $notifications;
    }

    public function getNotifications(): array
    {
        return $this->notifications;
    }
}
