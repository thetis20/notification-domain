<?php

namespace Notification\Domain\Entity;

use Symfony\Component\Uid\Uuid;

class Notification implements \JsonSerializable
{
    const STATUS_PENDING = 1;
    const STATUS_RUNNING = 2;
    const STATUS_SUCCESS = 3;
    const STATUS_FAIL    = 4;

    private Uuid $id;
    /** @var ReceiverInterface[] */
    private array $to;
    private NotificationTemplate $template;
    private \DateTime $date;
    /** @var array<string,mixed> */
    private array $params;
    private int $status;

    /**
     * @param ReceiverInterface[] $to
     * @param array<string,mixed> $params
     */
    public function __construct(Uuid $id, array $to, NotificationTemplate $template, array $params,\DateTime $date = new \DateTime(), int $status = self::STATUS_PENDING)
    {
        $this->id = $id;
        $this->to = $to;
        $this->template = $template;
        $this->date = $date;
        $this->params = $params;
        $this->status = $status;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return ReceiverInterface[]
     */
    public function getTo(): array
    {
        return $this->to;
    }

    public function getTemplate(): NotificationTemplate
    {
        return $this->template;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @return array<string,mixed>
     */
    public function getParams(): array
    {
        return $this->params;
    }

    public function getStatus(): int
    {
        return $this->status;
    }
    
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'to' => $this->to,
            'template' => $this->template->getKey(),
            'date' => $this->date->format('Y-m-d H:i:s'),
            'params' => $this->params,
            'status' => $this->status,
        ];
    }
}
