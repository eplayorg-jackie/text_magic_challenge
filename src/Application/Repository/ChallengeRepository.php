<?php

declare(strict_types=1);

namespace Application\Repository;

use Application\Entity\Challenge;
use Application\Entity\ChallengeInterface;
use Application\Entity\Embedded\ChallengeStatus;
use Application\Entity\UserInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ChallengeRepository extends ServiceEntityRepository implements ChallengeRepositoryInterface
{

    private string $alias = 'challenge';

    public function __construct(
        ManagerRegistry $managerRegistry
    ) {
        parent::__construct($managerRegistry, Challenge::class);
    }

    public function persist(Challenge $challenge): void
    {
        $this->getEntityManager()->persist($challenge);
    }

    public function findActiveChallengeByUser(UserInterface $user): ?ChallengeInterface
    {
        return $this
            ->createQueryBuilder($this->alias)
            ->addSelect('user')
            ->addSelect('challengeItems')
            ->join("$this->alias.user", 'user')
            ->join("$this->alias.challengeItems", 'challengeItems')
            ->where('user.id = :userId')
            ->andWhere("$this->alias.actualStatus = :actualStatus")
            ->setParameter('userId', $user->getId())
            ->setParameter('actualStatus', ChallengeStatus::INITIATED->value)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findNotPassedChallengesByUser(UserInterface $user): \Generator
    {
        return $this
            ->createQueryBuilder($this->alias)
            ->distinct()
            ->join("$this->alias.user", 'user')
            ->join("$this->alias.challengeItems", 'challengeItems')
            ->where('user.id = :userId')
            ->andWhere("$this->alias.actualStatus = :actualStatus")
            ->setParameter('userId', $user->getId())
            ->setParameter('actualStatus', ChallengeStatus::INITIATED->value)
            ->getQuery()
            ->toIterable();
    }

    public function findChallengesByUser(UserInterface $user, int $limit = 0, int $offset = 0): array
    {
        return $this
            ->createQueryBuilder($this->alias)
            ->distinct()
            ->join("$this->alias.user", 'user')
            ->join("$this->alias.challengeItems", 'challengeItems')
            ->where('user.id = :userId')
            ->setParameter('userId', $user->getId())
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

}
