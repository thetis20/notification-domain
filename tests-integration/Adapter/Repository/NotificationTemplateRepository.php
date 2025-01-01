<?php

namespace Notification\Domain\TestsIntegration\Adapter\Repository;

use Notification\Domain\Entity\NotificationTemplate;
use Notification\Domain\Gateway\NotificationTemplateGateway;

class NotificationTemplateRepository implements NotificationTemplateGateway
{
    /** @var array{notificationTemplates: NotificationTemplate[]}  */
    private array $data;

    /**
     * @param array{notificationTemplates: NotificationTemplate[]} $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function findOneByKey(string $key): NotificationTemplate|null
    {
        foreach ($this->data['notificationTemplates'] as $notificationTemplate) {
            if ($notificationTemplate->getKey() === $key) {
                return $notificationTemplate;
            }
        }

        return null;
    }
}
