<?php

declare(strict_types=1);

namespace Application\Repository;

use Application\Entity\User;

interface UserRepositoryInterface
{

    public function persist(User $user): void;

    public function findUserByEmail(string $email): ?User;

}
