<?php

namespace Notification\Domain\RequestFactory;

use Notification\Domain\Entity\Phone;
use Notification\Domain\Entity\Email;
use Notification\Domain\Entity\Transporter;
use Notification\Domain\Exception\NotUserIdentifierException;
use Notification\Domain\Request\CreateNotificationRequest;
use Notification\Domain\Gateway\NotificationTemplateGateway;
use Notification\Domain\Gateway\UserGateway;
use Notification\Domain\Gateway\TransporterGateway;
use Notification\Domain\Exception\NotEnoughReceiverException;
use Notification\Domain\Exception\NotificationNotFoundException;
use Notification\Domain\Exception\NotTransporterAvailableException;

final readonly class CreateNotificationRequestFactory
{
    /** @var Transporter[] */
    private array $transporters;

    public function __construct(
        private UserGateway $userGateway,
        private NotificationTemplateGateway $notificationTemplateGateway,
        TransporterGateway  $transporterGateway)
    {
        $this->transporters = $transporterGateway->find();
    }

    /**
     * @param string[] $to
     * @param string $key
     * @param array<string,mixed> $params
     * @throws NotEnoughReceiverException
     * @throws NotUserIdentifierException
     * @throws NotTransporterAvailableException
     * @return CreateNotificationRequest
     */
    public function create(array $to, string $key, array $params=[]): CreateNotificationRequest
    {
        if(count($this->transporters) === 0) {
            throw new NotTransporterAvailableException();
        }
        if(count($to) === 0) {
            throw new NotEnoughReceiverException();
        }
        $template = $this->notificationTemplateGateway->findOneByKey($key);
        if(!$template){
            throw new NotificationNotFoundException($key);
        }
        $items = [];
        foreach ($to as $value) {
            $receiver = null;
            if(Phone::isValid($value)) {
                $receiver = $this->userGateway->findOneByPhone($value);
                $receiver = $receiver ?: new Phone($value);
            }elseif(Email::isValid($value)) {
                $receiver = $this->userGateway->findOneByEmail($value);
                $receiver = $receiver ?: new Email($value);
            }else{
                $receiver = $this->userGateway->findOneById($value);
            }

            if($receiver === null) {
                throw new NotUserIdentifierException($value);
            }
            $index = array_search(true, array_map(function (Transporter $transporter) use ($receiver) {
                return $transporter->isAvailableForReceiver($receiver);
            }, $this->transporters));
            if($index === false) {
                throw new NotTransporterAvailableException($receiver);
            }

            $items[] = $receiver;
        }
        return new CreateNotificationRequest($items, $template, $params);
    }

}