<?php

namespace Notification\Domain\Presenter;

use Notification\Domain\Response\SendNotificationResponse;

interface SendNotificationPresenterInterface
{
    public function present(SendNotificationResponse $response): void;

}
