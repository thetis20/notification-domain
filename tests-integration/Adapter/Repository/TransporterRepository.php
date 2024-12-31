<?php

namespace Notification\Domain\TestsIntegration\Adapter\Repository;

use Notification\Domain\Entity\Transporter;
use Notification\Domain\Gateway\TransporterGateway;

class TransporterRepository implements TransporterGateway
{
    /** @var array{transporters: Transporter[]}  */
    private array $data;

    /**
     * @param array{transporters: Transporter[]} $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function find(): array
    {
        return $this->data['transporters'];
    }
}
