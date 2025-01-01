<?php

namespace Notification\Domain\Entity;

use Notification\Domain\Entity\Mailing;

abstract class Transporter
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function isAvailableForReceiver(ReceiverInterface $receiver): bool
    {
        return true;
    }

    abstract public function send(Notification $notification, ReceiverInterface $receiver): Mailing;
}
