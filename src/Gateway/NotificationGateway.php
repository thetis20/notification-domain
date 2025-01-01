<?php

namespace Notification\Domain\Gateway;

use Notification\Domain\Entity\Notification;
use Symfony\Component\Uid\Uuid;

interface NotificationGateway
{
    public function insert(Notification $notification): void;
    public function update(Notification $notification): void;

    /**
     * @param array{"status"?: int} $filters
     * @return Notification[]
     */
    public function find(array $filters): array;
    public function findOneById(Uuid $id): ?Notification;
}
