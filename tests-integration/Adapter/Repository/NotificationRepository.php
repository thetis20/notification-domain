<?php

namespace Notification\Domain\TestsIntegration\Adapter\Repository;

use Notification\Domain\Entity\Notification;
use Notification\Domain\Gateway\NotificationGateway;

class NotificationRepository implements NotificationGateway
{
    /** @var array{notifications: Notification[]}  */
    private array $data;

    /**
     * @param array{notifications: Notification[]} $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function insert(Notification $notification): void
    {
        $this->data['notifications'][] = $notification;
    }
}
