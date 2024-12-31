<?php

namespace Notification\Domain\Entity;

use Notification\Domain\Entity\Email;
use Notification\Domain\Entity\Phone;

class User implements \JsonSerializable
{
    private string $id;
    private string $usualName;
    private Email $email;
    private ?Phone $phone;

    public function __construct(string $id, string $usualName, Email $email, ?Phone $phone = null)
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

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPhone(): ?Phone
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
