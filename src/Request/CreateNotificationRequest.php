<?php

namespace Notification\Domain\Request;

use Notification\Domain\Entity\User;
use Notification\Domain\Entity\Email;
use Notification\Domain\Entity\NotificationTemplate;
use Notification\Domain\Entity\Phone;

class CreateNotificationRequest
{
    /** @var array<User|Email|Phone> */
    private array $to;
    private NotificationTemplate $template;
    /** @var array<string,mixed> */
    private array $params;

    public function __construct(array $to, NotificationTemplate $template, array $params=[])
    {
        $this->to = $to;
        $this->template = $template;
        $this->params = $params;
    }

    /**
     * @return array<User|Email|Phone>
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
