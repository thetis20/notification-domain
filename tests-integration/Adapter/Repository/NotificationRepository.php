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

    public function update(Notification $notification): void
    {
        $key = array_search($notification, $this->data['notifications'], true);
        if($key !== false) {
            $this->data['notifications'][$key] = $notification;
        }
    }

    public function find(array $filters = []): array
    {
        return array_filter($this->data['notifications'], function(Notification $notification) use ($filters) {
            if(isset($filters['status']) && $notification->getStatus() !== $filters['status']) {
                return false;
            }
            return true;
        });
    }
    
    public function findOneById(\Symfony\Component\Uid\Uuid $id): Notification|null
    {
        foreach($this->data['notifications'] as $notification) {
            if($notification->getId()->equals($id)) {
                return $notification;
            }
        }
        return null;
    }
}
