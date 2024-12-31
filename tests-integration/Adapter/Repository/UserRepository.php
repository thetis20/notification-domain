<?php

namespace Notification\Domain\TestsIntegration\Adapter\Repository;

use Notification\Domain\Gateway\UserGateway;
use Notification\Domain\Entity\User;

class UserRepository implements UserGateway
{
    /** @var array{users: User[]}  */
    private array $data;

    /**
     * @param array{users: User[]} $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function findOneByEmail(string $email): ?User
    {
        $index = array_search($email, array_map(function ($user) {
            return $user->getEmail();
        }, $this->data['users']));
        return $index === false ? null : $this->data['users'][$index];
    }

    public function findOneByPhone(string $phone): ?User
    {
        $index = array_search($phone, array_map(function ($user) {
            return $user->getPhone();
        }, $this->data['users']));
        return $index === false ? null : $this->data['users'][$index];
    }

    public function findOneById(string $id): ?User
    {
        $index = array_search($id, array_map(function ($user) {
            return $user->getId();
        }, $this->data['users']));
        return $index === false ? null : $this->data['users'][$index];
    }
}
