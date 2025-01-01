<?php

namespace Notification\Domain\Entity;

use Notification\Domain\Entity\ReceiverInterface;

class User implements \JsonSerializable, ReceiverInterface
{
    private string $id;
    private string $email;
    private ?string $phone;

    public function __construct(string $id, string $email, ?string $phone = null)
    {
        $this->id = $id;
        $this->email = $email;
        $this->phone = $phone;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }
    public function getSlug(): string
    {
        return self::class.'-'.$this->id;
    }
}
