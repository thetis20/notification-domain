<?php

namespace Notification\Domain\RequestFactory;

use Notification\Domain\Entity\Notification;
use Notification\Domain\Request\SendNotificationRequest;
use Notification\Domain\Gateway\NotificationGateway;
use Notification\Domain\Exception\NotUuidException;
use Symfony\Component\Uid\Uuid;

final readonly class SendNotificationRequestFactory
{

    public function __construct(private NotificationGateway $notificationGateway)
    {
    }

    /**
     * @param mixed $id
     * @throws NotUuidException
     * @return SendNotificationRequest
     */
    public function create(mixed $id = null): SendNotificationRequest
    {
        if($id !== null && !$id instanceof Uuid && !Uuid::isValid($id)) {
            throw new NotUuidException($id);
        }
        $notifications = [];
        if($id === null) {
            $notifications = $this->notificationGateway->find([
                "status" => Notification::STATUS_PENDING
            ]);
        }else {
            $notifications = [$this->notificationGateway->findOneById($id instanceof Uuid ? $id : Uuid::fromString($id))];
        }
        return new SendNotificationRequest($notifications);
    }

}