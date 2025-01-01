<?php

namespace Notification\Domain\Entity;

use Notification\Domain\Entity\Email;
use Notification\Domain\Entity\Phone;
use Notification\Domain\Entity\ReceiverInterface;

class User implements \JsonSerializable, ReceiverInterface
{
    private string $id;
    private string $usualName;
    private string $email;
    private ?string $phone;

    public function __construct(string $id, string $usualName, string $email, ?string $phone = null)
    {
        $this->id = $id;
        $this->usualName = $usualName;
        $this->email = $email;
        $this->phone = $phone;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUsualName(): string
    {
        return $this->usualName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'usualName' => $this->usualName,
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }
}
