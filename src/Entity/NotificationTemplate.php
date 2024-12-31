<?php

namespace Notification\Domain\Entity;

class NotificationTemplate
{
    private string $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }
    
    public function getKey(): string
    {
        return $this->key;
    }
}
