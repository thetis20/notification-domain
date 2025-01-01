<?php

namespace Notification\Domain\TestsIntegration\Adapter\Presenter;

use Notification\Domain\Presenter\SendNotificationPresenterInterface;
use Notification\Domain\Response\SendNotificationResponse;

class SendNotificationPresenterTest implements SendNotificationPresenterInterface
{
    public SendNotificationResponse $response;

    public function present(SendNotificationResponse $response): void
    {
        $this->response = $response;
    }
}