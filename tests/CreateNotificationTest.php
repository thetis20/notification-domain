<?php

namespace Notification\Domain\Tests;

use Notification\Domain\Entity\Email;
use Notification\Domain\Entity\Notification;
use Notification\Domain\Entity\Phone;
use Notification\Domain\Entity\User;
use Notification\Domain\Entity\NotificationTemplate;
use Notification\Domain\Exception\NotEnoughReceiverException;
use Notification\Domain\Exception\NotUserIdentifierException;
use Notification\Domain\Exception\NotificationNotFoundException;
use Notification\Domain\RequestFactory\CreateNotificationRequestFactory;
use Notification\Domain\Response\CreateNotificationResponse;
use Notification\Domain\UseCase\CreateNotification;
use PHPUnit\Framework\TestCase;
use Notification\Domain\TestsIntegration\Adapter\Repository\NotificationRepository;
use Notification\Domain\TestsIntegration\Adapter\Repository\UserRepository;
use Notification\Domain\TestsIntegration\Adapter\Repository\NotificationTemplateRepository;
use Notification\Domain\TestsIntegration\Adapter\Presenter\CreateNotificationPresenterTest;
use Notification\Domain\TestsIntegration\Adapter\Logger;
use Symfony\Component\Uid\Uuid;

class CreateNotificationTest extends TestCase
{
    private CreateNotificationPresenterTest $presenter;
    private UserRepository $userGateway;
    private NotificationRepository $notificationGateway;
    private NotificationTemplateRepository $notificationTemplateGateway;
    private CreateNotification $useCase;
    private CreateNotificationRequestFactory $requestFactory;

    protected function setUp(): void
    {
        $data = [
            'users' => [
                new User( 'username', 'User Name', new Email('username@email.com')),
                new User( 'username2', 'User Name 2', new Email('username2@email.com')),
                new User( 'username3', 'User Name 3', new Email('username3@email.com'), new Phone('+33612345678')),
            ],
            'notifications' => [],
            'notificationTemplates' => [
                new NotificationTemplate('notification-key')
            ]
        ];
        $this->presenter = new CreateNotificationPresenterTest();
        $this->userGateway = new UserRepository($data);
        $this->notificationGateway = new NotificationRepository($data);
        $this->notificationTemplateGateway = new NotificationTemplateRepository($data);
        $this->useCase = new CreateNotification($this->userGateway, $this->notificationGateway, new Logger());
        $this->requestFactory = new CreateNotificationRequestFactory($this->userGateway, $this->notificationTemplateGateway);
    }

    public function testSuccessful(): void
    {
        $to = ['username@email.com','username2', '+33612345678', 'username-unknown@email.com', '+33612345679'];
        $key = 'notification-key';
        $params = ['param1' => 'value1', 'param2' => 'value2'];

        $request = $this->requestFactory->create($to, $key, $params);

        $this->useCase->execute($request, $this->presenter);

        $this->assertInstanceOf(CreateNotificationResponse::class, $this->presenter->response);

        $this->assertInstanceOf(Notification::class, $this->presenter->response->getNotification());
        $this->assertInstanceOf(User::class, $this->presenter->response->getNotification()->getTo()[0]);
        $this->assertEquals('username', $this->presenter->response->getNotification()->getTo()[0]->getId());
        $this->assertEquals('User Name', $this->presenter->response->getNotification()->getTo()[0]->getUsualName());
        $this->assertInstanceOf(User::class, $this->presenter->response->getNotification()->getTo()[1]);
        $this->assertEquals('username2', $this->presenter->response->getNotification()->getTo()[1]->getId());
        $this->assertInstanceOf(User::class, $this->presenter->response->getNotification()->getTo()[2]);
        $this->assertEquals('username3', $this->presenter->response->getNotification()->getTo()[2]->getId());
        $this->assertInstanceOf(Email::class, $this->presenter->response->getNotification()->getTo()[3]);
        $this->assertEquals('username-unknown@email.com', $this->presenter->response->getNotification()->getTo()[3]->__toString());
        $this->assertInstanceOf(Phone::class, $this->presenter->response->getNotification()->getTo()[4]);
        $this->assertEquals('+33612345679', $this->presenter->response->getNotification()->getTo()[4]->__toString());

        $this->assertInstanceOf(Uuid::class, $this->presenter->response->getNotification()->getId());
        $this->assertInstanceOf(\DateTime::class, $this->presenter->response->getNotification()->getDate());
        $this->assertEquals(Notification::STATUS_PENDING, $this->presenter->response->getNotification()->getStatus());
        $this->assertEquals('notification-key', $this->presenter->response->getNotification()->getKey());
        $this->assertEquals('value1', $this->presenter->response->getNotification()->getParams()['param1']);
        $this->assertEquals('value2', $this->presenter->response->getNotification()->getParams()['param2']);
    }


    /**
     * @dataProvider provideFailedValidationRequestsData
     * @param string[] $to
     * @param string $key
     * @param array<string,mixed> $params
     */
    public function testFailedValidation(array $to, string $key, array $params, string $exception): void
    {
        $this->expectException($exception);

        $request = $this->requestFactory->create($to, $key, $params);

    }

    public function provideFailedValidationRequestsData(): \Generator
    {
        yield [[], "notification-key", [], NotEnoughReceiverException::class];
        yield [['username@email.com'], "notification-key-unknown", [], NotificationNotFoundException::class];
        yield [['not-identifier'], "notification-key", [], NotUserIdentifierException::class];
    }
}
