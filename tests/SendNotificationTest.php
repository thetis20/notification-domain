<?php

namespace Notification\Domain\Tests;

use Notification\Domain\Entity\Email;
use Notification\Domain\Entity\Notification;
use Notification\Domain\Entity\Phone;
use Notification\Domain\Entity\Transporter;
use Notification\Domain\Entity\User;
use Notification\Domain\Entity\Mailing;
use Notification\Domain\Entity\ReceiverInterface;
use Notification\Domain\Entity\NotificationTemplate;
use Notification\Domain\Exception\NotTransporterAvailableException;
use Notification\Domain\Exception\NotUuidException;
use Notification\Domain\RequestFactory\SendNotificationRequestFactory;
use Notification\Domain\Response\SendNotificationResponse;
use Notification\Domain\UseCase\SendNotification;
use PHPUnit\Framework\TestCase;
use Notification\Domain\TestsIntegration\Adapter\Repository\NotificationRepository;
use Notification\Domain\TestsIntegration\Adapter\Repository\UserRepository;
use Notification\Domain\TestsIntegration\Adapter\Repository\TransporterRepository;
use Notification\Domain\TestsIntegration\Adapter\Repository\NotificationTemplateRepository;
use Notification\Domain\TestsIntegration\Adapter\Repository\MailingRepository;
use Notification\Domain\TestsIntegration\Adapter\Presenter\SendNotificationPresenterTest;
use Notification\Domain\TestsIntegration\Adapter\Logger;
use Symfony\Component\Uid\Uuid;

class SendNotificationTest extends TestCase
{
    private SendNotificationPresenterTest $presenter;
    private NotificationRepository $notificationGateway;
    private SendNotification $useCase;
    private SendNotificationRequestFactory $requestFactory;

    protected function setUp(): void
    {
        $data = [
            'users' => [
                new User( 'username', 'User Name', 'username@email.com'),
                new User( 'username2', 'User Name 2', 'username2@email.com'),
                new User( 'username3', 'User Name 3', 'username3@email.com', '+33612345678'),
            ],
            'notifications' => [],
            'mailings'=>[],
            'notificationTemplates' => [
                new NotificationTemplate('notification-key')
            ],
            'transporters' => [
                new class extends Transporter{
                    public function __construct()
                    {
                        parent::__construct('default');
                    }

                    public function isAvailableForReceiver(ReceiverInterface $receiver): bool
                    {
                        return $receiver->getEmail() !== null;
                    }
                    
                    public function send(Notification $notification, ReceiverInterface $receiver): Mailing
                    {
                        return new Mailing(Uuid::v4(), $receiver, $notification, new \DateTime(), 
                        $receiver->getId() === 'username3' ? Mailing::STATUS_FAIL : Mailing::STATUS_SUCCESS);
                    }
                }
            ]
        ];
        $data['notifications'][] = new Notification(
            Uuid::fromString('17d732dd-2047-4c06-979a-ed51c6faf45d'), 
            [$data['users'][0], $data['users'][1]],
        $data['notificationTemplates'][0], 
        ['param1' => 'value1', 'param2' => 'value2']
        );

        $data['notifications'][] = new Notification(
            Uuid::fromString('012d6afb-a67c-4eb2-b82a-aceecb3ad843'), 
            [$data['users'][2]],
        $data['notificationTemplates'][0], 
        ['param1' => 'value1', 'param2' => 'value2'],
        new \DateTime(),
        Notification::STATUS_FAIL
        );

        $data['notifications'][] = new Notification(
            Uuid::fromString('0f2e24a4-459f-4230-b224-987ae6902813'), 
            [new Phone('+33612345679')],
        $data['notificationTemplates'][0], 
        ['param1' => 'value1', 'param2' => 'value2'],
        new \DateTime(),
        Notification::STATUS_FAIL
        );
        $this->presenter = new SendNotificationPresenterTest();
        $this->notificationGateway = new NotificationRepository($data);
        $this->useCase = new SendNotification(
            $this->notificationGateway,
            new MailingRepository($data),
             new Logger(),
            new TransporterRepository($data));
        $this->requestFactory = new SendNotificationRequestFactory($this->notificationGateway);
    }

    public function testSuccessful(): void
    {
        $request = $this->requestFactory->create();

        $this->useCase->execute($request, $this->presenter);

        $this->assertInstanceOf(SendNotificationResponse::class, $this->presenter->response);

        $this->assertCount(1, $this->presenter->response->getNotifications());
        $this->assertCount(2, $this->presenter->response->getMailings());
        $this->assertInstanceOf(Uuid::class, $this->presenter->response->getMailings()[0]->getId());
        $this->assertInstanceOf(\DateTime::class, $this->presenter->response->getMailings()[0]->getDate());
        $this->assertInstanceOf(User::class, $this->presenter->response->getMailings()[0]->getReceiver());
        $this->assertEquals('17d732dd-2047-4c06-979a-ed51c6faf45d',$this->presenter->response->getMailings()[0]->getNotification()->getId()->__tostring());
    }

    public function testOneIdSuccessful(): void
    {
        $request = $this->requestFactory->create('17d732dd-2047-4c06-979a-ed51c6faf45d');

        $this->useCase->execute($request, $this->presenter);

        $this->assertInstanceOf(SendNotificationResponse::class, $this->presenter->response);

        $this->assertCount(1, $this->presenter->response->getNotifications());
        $this->assertCount(2, $this->presenter->response->getMailings());
        $this->assertInstanceOf(Uuid::class, $this->presenter->response->getMailings()[0]->getId());
        $this->assertInstanceOf(\DateTime::class, $this->presenter->response->getMailings()[0]->getDate());
        $this->assertInstanceOf(User::class, $this->presenter->response->getMailings()[0]->getReceiver());
        $this->assertEquals($this->presenter->response->getNotifications()[0]->getId()->__tostring(), 
        $this->presenter->response->getMailings()[0]->getNotification()->getId()->__tostring());
    }

    public function testFail(): void
    {
        $this->expectException(NotUuidException::class);
        $request = $this->requestFactory->create('not-found');
    }

    public function testTransporterAvailableFail(): void
    {
        $request = $this->requestFactory->create('0f2e24a4-459f-4230-b224-987ae6902813');
        $this->expectException(NotTransporterAvailableException::class);

        $this->useCase->execute($request, $this->presenter);
    }


    public function testTransporterFail(): void
    {
        $request = $this->requestFactory->create('012d6afb-a67c-4eb2-b82a-aceecb3ad843');

        $this->useCase->execute($request, $this->presenter);
        $this->assertCount(1, $this->presenter->response->getNotifications());
        $this->assertCount(1, $this->presenter->response->getMailings());
        $this->assertEquals(Mailing::STATUS_FAIL, $this->presenter->response->getMailings()[0]->getStatus());
        $this->assertEquals(Notification::STATUS_FAIL, $this->presenter->response->getNotifications()[0]->getStatus());
    }
}
