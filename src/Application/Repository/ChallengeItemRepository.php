<?php

declare(strict_types=1);

namespace Application\Repository;

use Application\Entity\ChallengeInterface;
use Application\Entity\ChallengeItem;
use Application\Entity\Embedded\ChallengeStatus;
use Application\Entity\Embedded\Decision;
use Application\Entity\UserInterface;
use Application\InternalProtocol\Embedded\SelectedChallengeItemInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ChallengeItemRepository extends ServiceEntityRepository implements ChallengeItemRepositoryInterface
{

    private string $alias = 'challengeItem';

    public function __construct(
        ManagerRegistry $managerRegistry
    ) {
        parent::__construct($managerRegistry, ChallengeItem::class);
    }

    public function persist(ChallengeItem $challengeItem): void
    {
        $this->getEntityManager()->persist($challengeItem);
    }

    public function identifyChallengeItem(
        UserInterface $user,
        ChallengeInterface $challenge,
        SelectedChallengeItemInterface $selectedChallengeItem,
        ChallengeStatus $challengeStatus = ChallengeStatus::INITIATED,
        Decision $challengeItemDecision = Decision::INITIATED,
    ): ?ChallengeItem {
        return $this
            ->createQueryBuilder($this->alias)
            ->addSelect('user')
            ->addSelect('challenge')
            ->join("$this->alias.challenge", 'challenge')
            ->join("challenge.user", 'user')
            ->where('challenge.id = :challengeId')
            ->andWhere('challenge.actualStatus = :actualStatus')
            ->andWhere('user.id = :userId')
            ->andWhere("$this->alias.id = :selectedChallengeItemId")
            ->andWhere("$this->alias.decision = :decision")
            ->setParameter('challengeId', $challenge->getId())
            ->setParameter('actualStatus', $challengeStatus)
            ->setParameter('userId', $user->getId())
            ->setParameter('selectedChallengeItemId', $selectedChallengeItem->getId()->toRfc4122())
            ->setParameter('decision', $challengeItemDecision)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
