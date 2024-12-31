<?php

namespace Notification\Domain\Request;

use Notification\Domain\Entity\User;
use Notification\Domain\Entity\Email;
use Notification\Domain\Entity\Phone;

class CreateNotificationRequest
{
    /** @var array<User|Email|Phone> */
    private array $to;
    private string $key;
    /** @var array<string,mixed> */
    private array $params;

    public function __construct(array $to, string $key, array $params=[])
    {
        $this->to = $to;
        $this->key = $key;
        $this->params = $params;
    }

    /**
     * @return array<User|Email|Phone>
     */
    public function getTo(): array
    {
        return $this->to;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return array<string,mixed>
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
