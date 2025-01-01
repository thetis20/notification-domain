<?php

namespace Notification\Domain\UseCase;

use Notification\Domain\Entity\Mailing;
use Notification\Domain\Entity\Notification;
use Notification\Domain\Entity\Transporter;
use Notification\Domain\Gateway\MailingGateway;
use Notification\Domain\Gateway\NotificationGateway;
use Notification\Domain\Gateway\LoggerInterface;
use Notification\Domain\Gateway\TransporterGateway;
use Notification\Domain\Request\SendNotificationRequest;
use Notification\Domain\Presenter\SendNotificationPresenterInterface;
use Notification\Domain\Response\SendNotificationResponse;
use Notification\Domain\Exception\NotTransporterAvailableException;

final readonly class SendNotification
{
    /** @var Transporter[] */
    private array $transporters;

    public function __construct(
        private NotificationGateway $notificationGateway,
        private MailingGateway      $mailingGateway,
        private LoggerInterface     $logger,
        TransporterGateway          $transporterGateway)
    {
        $this->transporters = $transporterGateway->find();
    }

    public function execute(SendNotificationRequest $request, SendNotificationPresenterInterface $presenter): void
    {
        $mailings=[];
        $notifications=[];
        foreach($request->getNotifications() as $notification) {
            $notification->setStatus(Notification::STATUS_RUNNING); 
            $this->notificationGateway->update($notification);
            $fail = false;
            foreach($notification->getTo() as $receiver) {
                $index = array_search(true, array_map(function (Transporter $transporter) use ($receiver) {
                    return $transporter->isAvailableForReceiver($receiver);
                }, $this->transporters));
                if($index === false) {
                    throw new NotTransporterAvailableException($receiver);
                }
                $mailing = $this->transporters[$index]->send(
                    $notification,
                    $receiver
                );
                if($mailing->getStatus() === Mailing::STATUS_FAIL){
                    $fail = true;
                }
                $this->mailingGateway->insert($mailing);
                $mailings[] = $mailing;
            }
            $notification->setStatus($fail ? Notification::STATUS_FAIL : Notification::STATUS_SUCCESS); 
            $this->notificationGateway->update($notification);
            $notifications[] = $notification;
        }

        $presenter->present(new SendNotificationResponse($notifications, $mailings));
        $this->logger->notice('Send notification', ['notification' => $notification, $mailings]);
    }
}
