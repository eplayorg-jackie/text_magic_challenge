<?php

declare(strict_types=1);

namespace Application\Service;

use Application\Entity\UserInterface;

interface UserServiceInterface
{

    public function createIfNotExists(string $email): UserInterface;

}
