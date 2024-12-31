<?php

namespace Notification\Domain\Response;

use Notification\Domain\Entity\Notification;

class CreateNotificationResponse
{
    private Notification $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }
    public function getNotification(): Notification
    {
        return $this->notification;
    }
}
