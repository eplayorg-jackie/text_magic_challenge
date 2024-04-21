<?php

declare(strict_types=1);

namespace Application\Repository;

use Application\Entity\Challenge;
use Application\Entity\ChallengeInterface;
use Application\Entity\Embedded\ChallengeStatus;
use Application\Entity\Embedded\Decision;
use Application\Entity\UserInterface;
use Application\InternalProtocol\Embedded\SelectedChallengeItemInterface;

interface ChallengeRepositoryInterface
{

    public function persist(Challenge $challenge): void;

    public function findActiveChallengeByUser(UserInterface $user): ?ChallengeInterface;

    /**
     * @param UserInterface $user
     * @return \Generator<Challenge>
     */
    public function findNotPassedChallengesByUser(UserInterface $user): \Generator;

    /**
     * @param UserInterface $user
     * @param int $limit
     * @param int $offset
     * @return Challenge[]
     */
    public function findChallengesByUser(UserInterface $user, int $limit = 0, int $offset = 0): array;

}
