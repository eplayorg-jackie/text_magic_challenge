<?php

declare(strict_types=1);

namespace Application\Repository;

use Application\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{

    private string $alias = 'user';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function persist(User $user): void
    {
        $this->getEntityManager()->persist($user);
    }

    public function findUserByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

}
