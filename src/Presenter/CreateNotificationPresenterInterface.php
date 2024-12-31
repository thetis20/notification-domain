<?php

namespace Notification\Domain\Presenter;

use Notification\Domain\Response\CreateNotificationResponse;

interface CreateNotificationPresenterInterface
{
    public function present(CreateNotificationResponse $response): void;

}
