<?php

namespace Notification\Domain\RequestFactory;

use Notification\Domain\Entity\Phone;
use Notification\Domain\Entity\Email;
use Notification\Domain\Exception\NotUserIdentifierException;
use Notification\Domain\Request\CreateNotificationRequest;
use Notification\Domain\Gateway\NotificationTemplateGateway;
use Notification\Domain\Gateway\UserGateway;
use Notification\Domain\Exception\NotEnoughReceiverException;
use Notification\Domain\Exception\NotificationNotFoundException;

final readonly class CreateNotificationRequestFactory
{
    public function __construct(
        private UserGateway $userGateway,
        private NotificationTemplateGateway $notificationTemplateGateway)
    {
    }

    /**
     * @param string[] $to
     * @param string $key
     * @param array<string,mixed> $params
     * @throws NotEnoughReceiverException
     * @throws NotUserIdentifierException
     * @return CreateNotificationRequest
     * @return NotificationNotFoundException
     */
    public function create(array $to, string $key, array $params=[]): CreateNotificationRequest
    {
        if(count($to) === 0) {
            throw new NotEnoughReceiverException();
        }
        if(!$this->notificationTemplateGateway->isExist($key)){
            throw new NotificationNotFoundException($key);
        }
        $items = [];
        foreach ($to as $value) {
            if(Phone::isValid($value)) {
                $user = $this->userGateway->findOneByPhone($value);
                $items[] = $user ?: new Phone($value);
            }elseif(Email::isValid($value)) {
                $user = $this->userGateway->findOneByEmail($value);
                $items[] = $user ?: new Email($value);
            }else{
                $user = $this->userGateway->findOneById($value);
                if($user === null) {
                    throw new NotUserIdentifierException($value);
                }
                $items[] = $user;
            }
        }
        return new CreateNotificationRequest($items, $key, $params);
    }

}