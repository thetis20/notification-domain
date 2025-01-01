<?php

namespace Notification\Domain\Entity;

use Notification\Domain\Entity\ReceiverInterface;
use Symfony\Component\Uid\Uuid;

class Mailing
{
    const STATUS_RUNNING = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_FAIL    = 3;

    private Uuid $id;
    private ReceiverInterface $receiver;
    private Notification $notification;
    private \DateTime $date;
    private int $status;

    public function __construct(Uuid $id, ReceiverInterface $receiver, Notification $notification, \DateTime $date = new \DateTime(), int $status = self::STATUS_RUNNING)
    {
        $this->id = $id;
        $this->receiver = $receiver;
        $this->notification = $notification;
        $this->date = $date;
        $this->status = $status;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getReceiver(): ReceiverInterface
    {
        return $this->receiver;
    }

    public function getNotification(): Notification
    {
        return $this->notification;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function getStatus(): int
    {
        return $this->status;
    }
}
