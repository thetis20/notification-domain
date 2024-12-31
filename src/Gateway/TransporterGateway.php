<?php

namespace Notification\Domain\Gateway;

use Notification\Domain\Entity\Transporter;

interface TransporterGateway
{
    /**
     * @return Transporter[]
     */
    public function find():array;
}
