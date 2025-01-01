<?php

namespace Notification\Domain\UseCase;

use Notification\Domain\Entity\Notification;
use Notification\Domain\Gateway\UserGateway;
use Notification\Domain\Gateway\NotificationGateway;
use Notification\Domain\Gateway\LoggerInterface;
use Notification\Domain\Request\CreateNotificationRequest;
use Notification\Domain\Presenter\CreateNotificationPresenterInterface;
use Notification\Domain\Response\CreateNotificationResponse;
use Symfony\Component\Uid\Uuid;

final readonly class CreateNotification
{

    public function __construct(
        private NotificationGateway $notificationGateway,
        private LoggerInterface     $logger)
    {
    }

    public function execute(CreateNotificationRequest $request, CreateNotificationPresenterInterface $presenter): void
    {
        $notification = new Notification(Uuid::v4(),$request->getTo(), $request->getTemplate(), $request->getParams());
        
        $this->notificationGateway->insert($notification);

        $presenter->present(new CreateNotificationResponse($notification));
        $this->logger->notice('Create notification', ['notification' => $notification]);
    }
}
