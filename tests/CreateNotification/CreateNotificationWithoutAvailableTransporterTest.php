<?php

namespace Notification\Domain\Tests;

use Notification\Domain\Entity\Email;
use Notification\Domain\Entity\Phone;
use Notification\Domain\Entity\Transporter;
use Notification\Domain\Entity\User;
use Notification\Domain\Entity\NotificationTemplate;
use Notification\Domain\Exception\NotTransporterAvailableException;
use Notification\Domain\RequestFactory\CreateNotificationRequestFactory;
use Notification\Domain\TestsIntegration\Adapter\Repository\UserRepository;
use Notification\Domain\TestsIntegration\Adapter\Repository\TransporterRepository;
use Notification\Domain\TestsIntegration\Adapter\Repository\NotificationTemplateRepository;
use PHPUnit\Framework\TestCase;

class CreateNotificationWithoutAvailableTransporterTest extends TestCase
{
    private CreateNotificationRequestFactory $requestFactory;

    protected function setUp(): void
    {
        $data = [
            'users' => [
                new User( 'username', 'User Name', 'username@email.com'),
                new User( 'username2', 'User Name 2', 'username2@email.com'),
                new User( 'username3', 'User Name 3', 'username3@email.com', '+33612345678'),
            ],
            'notifications' => [],
            'notificationTemplates' => [
                new NotificationTemplate('notification-key')
            ],
            'transporters' => []
        ];
        $this->requestFactory = new CreateNotificationRequestFactory(new UserRepository($data),
         new NotificationTemplateRepository($data),
         new TransporterRepository($data));
    }

    public function testFail(): void
    {
        $to = ['username@email.com','username2', '+33612345678', 'username-unknown@email.com', '+33612345679'];
        $key = 'notification-key';
        $params = ['param1' => 'value1', 'param2' => 'value2'];

        $this->expectException(NotTransporterAvailableException::class);

        $request = $this->requestFactory->create($to, $key, $params);
    }
}
