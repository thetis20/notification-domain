<?php

namespace Notification\Domain\TestsIntegration\Adapter\Repository;

use Notification\Domain\Entity\Mailing;
use Notification\Domain\Gateway\MailingGateway;

class MailingRepository implements MailingGateway
{
    /** @var array{mailings: Mailing[]}  */
    private array $data;

    /**
     * @param array{mailings: Mailing[]} $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function insert(Mailing $mailing): void
    {
        $this->data['mailings'][] = $mailing;
    }

    /**
     * @return Mailing[]
     */
    public function find(): array
    {
        return $this->data['mailings'];
    }
}
