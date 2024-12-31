<?php

namespace Notification\Domain\Gateway;

use Notification\Domain\Entity\User;

interface UserGateway
{
    public function findOneByPhone(string $phone): ?User;
    public function findOneByEmail(string $email): ?User;
    public function findOneById(string $id): ?User;
}
