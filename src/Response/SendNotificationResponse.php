<?php

namespace Notification\Domain\Response;

use Notification\Domain\Entity\Mailing;
use Notification\Domain\Entity\Notification;

class SendNotificationResponse
{
    /** @var Notification[] */
    private array $notifications;
    /** @var Mailing[] */
    private array $mailings;

    /**
     * @param Notification[] $notifications
     * @param Mailing[] $mailings
     */
    public function __construct(array $notifications, array $mailings)
    {
        $this->notifications = $notifications;
        $this->mailings = $mailings;
    }

    /**
     * @return Notification[]
     */
    public function getNotifications(): array
    {
        return $this->notifications;
    }

    /**
     * @return Mailing[]
     */
    public function getMailings(): array
    {
        return $this->mailings;
    }
}
