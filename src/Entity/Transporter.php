<?php

namespace Notification\Domain\Entity;


abstract class Transporter
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @param User|Phone|Email $receiver 
     * @return bool
     */
    public function isAvailableForReceiver(User|Phone|Email $receiver): bool
    {
        return true;
    }

}
