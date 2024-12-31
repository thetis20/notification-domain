<?php

namespace Notification\Domain\TestsIntegration\Adapter\Presenter;

use Notification\Domain\Presenter\CreateNotificationPresenterInterface;
use Notification\Domain\Response\CreateNotificationResponse;

class CreateNotificationPresenterTest implements CreateNotificationPresenterInterface
{
    public CreateNotificationResponse $response;

    public function present(CreateNotificationResponse $response): void
    {
        $this->response = $response;
    }
}