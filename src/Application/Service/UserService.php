<?php

declare(strict_types=1);

namespace Application\Service;

use Application\Entity\User;
use Application\Entity\UserInterface;
use Application\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\Uid\Factory\UuidFactory;

class UserService implements UserServiceInterface
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UuidFactory $idFactory,
        private readonly ClockInterface $clock,
        private readonly UserRepositoryInterface $userRepository,
    ) {

    }

    public function createIfNotExists(string $email): UserInterface
    {
        $existedUser = $this->userRepository->findUserByEmail($email);
        if ($existedUser) {
            return $existedUser;
        }

        $user = new User($this->idFactory->create(), $email, $this->clock->now());

        $this->userRepository->persist($user);
        $this->entityManager->flush();

        return $user;
    }

}
