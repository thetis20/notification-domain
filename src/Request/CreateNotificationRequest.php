<?php

namespace Notification\Domain\Request;

use Notification\Domain\Entity\User;
use Notification\Domain\Entity\Email;
use Notification\Domain\Entity\NotificationTemplate;
use Notification\Domain\Entity\Phone;
use Notification\Domain\Entity\ReceiverInterface;

class CreateNotificationRequest
{
    /** @var ReceiverInterface[] */
    private array $to;
    private NotificationTemplate $template;
    /** @var array<string,mixed> */
    private array $params;

    /**
     * @param ReceiverInterface[] $to
     * @param array<string,mixed> $params
     */
    public function __construct(array $to, NotificationTemplate $template, array $params=[])
    {
        $this->to = $to;
        $this->template = $template;
        $this->params = $params;
    }

    /**
     * @return ReceiverInterface[]
     */
    public function getTo(): array
    {
        return $this->to;
    }

    public function getTemplate(): NotificationTemplate
    {
        return $this->template;
    }

    /**
     * @return array<string,mixed>
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
